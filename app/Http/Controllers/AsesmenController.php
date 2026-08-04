<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AsesmenImport;
use App\Models\Asesmen;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Narkotika;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Carbon\Carbon;
use App\Exports\AsesmenExport;

class AsesmenController extends Controller
{
    /**
     * Menampilkan halaman utama Data Asesmen TAT (Tabel Index)
     */
    public function index(Request $request)
    {
        // 1. Inisiasi Query Builder beserta relasinya
        $query = Asesmen::with(['narkotika', 'pendidikan', 'pekerjaan', 'rekomendasi']);

        // 2. Logika Pencarian Kata Kunci (Teks)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_register', 'like', "%{$search}%")
                    ->orWhere('no_lkn', 'like', "%{$search}%");
            });
        }

        // 3. Logika Filter Bulan (Berdasarkan tgl_pelaksanaan)
        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_pelaksanaan', $request->bulan);
        }

        // 4. Logika Filter Tahun (Berdasarkan tgl_pelaksanaan)
        if ($request->filled('tahun')) {
            $query->whereYear('tgl_pelaksanaan', $request->tahun);
        }

        // 5. Logika Filter Jenis Narkotika
        if ($request->filled('narkotika')) {
            $query->where('narkotika_id', $request->narkotika);
        }

        // 6. Logika Filter Status Pelaksanaan (Sudah/Belum di-Rehab)
        if ($request->filled('status')) {
            $query->where('pelaksanaan', strtoupper($request->status));
        }

        // 7. Logika Sorting (Pengurutan)
        $sort = $request->get('sort', 'terbaru'); // Default 'terbaru'
        if ($sort === 'terlama') {
            $query->oldest('tgl_pelaksanaan');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->latest('tgl_pelaksanaan');
        }

        // 8. Eksekusi query dengan pagination
        $asesmens = $query->paginate(10)->withQueryString();

        // 9. Ambil data master untuk Dropdown Filter di View
        $masterNarkotika = Narkotika::orderBy('jenis_narkotika', 'asc')->get();

        $tahunTerkecil = Asesmen::min(\Illuminate\Support\Facades\DB::raw('YEAR(tgl_pelaksanaan)')) ?? date('Y');
        $tahunTerbesar = max(date('Y'), Asesmen::max(\Illuminate\Support\Facades\DB::raw('YEAR(tgl_pelaksanaan)')));
        $daftarTahun = range($tahunTerbesar, $tahunTerkecil);

        return view('asesmen.index', compact('asesmens', 'masterNarkotika', 'daftarTahun'));
    }

    /**
     * Menampilkan form tambah data manual (Create)
     */
    public function create()
    {
        $masterPendidikan = Pendidikan::orderBy('nama_pendidikan', 'asc')->get();
        $masterPekerjaan = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $masterNarkotika = Narkotika::orderBy('jenis_narkotika', 'asc')->get();
        $masterRekomendasi = Rekomendasi::orderBy('tempat_rehabilitasi', 'asc')->get();

        return view('asesmen.create', compact(
            'masterPendidikan',
            'masterPekerjaan',
            'masterNarkotika',
            'masterRekomendasi'
        ));
    }

    /**
     * Menyimpan data baru ke database (Store)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Administrasi Surat & Registrasi
            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string|max:255',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',

            // Identitas Klien
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'kewarganegaraan' => 'nullable|string|max:100',
            'agama' => 'nullable|string|max:100',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'pendidikan_id' => 'nullable',
            'pekerjaan_id' => 'nullable',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',

            // Perkara Hukum & Barang Bukti
            'narkotika_id' => 'nullable',
            'berat_bb' => 'nullable|numeric',
            'deskripsi_bb' => 'nullable|string',
            'pasal_sangkaan' => 'nullable|string',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'tes_urine' => 'nullable|string|max:50',

            // Hasil Asesmen Awal (Rekap TAT)
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'rekomendasi_id' => 'nullable',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'keterangan_tambahan' => 'nullable|string',

            // Analisis Case Conference
            'aspek_hukum' => 'nullable|string',
            'aspek_medis' => 'nullable|string',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'saran_case_conference' => 'nullable|string',
        ]);

        Asesmen::create($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen & Case Conference berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman Detail Data (Show)
     */
    public function show(string $id)
    {
        $asesmen = Asesmen::with(['narkotika', 'pendidikan', 'pekerjaan', 'rekomendasi'])->findOrFail($id);
        return view('asesmen.show', compact('asesmen'));
    }

    /**
     * Menampilkan form edit data (Edit)
     */
    public function edit(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);

        $masterPendidikan = Pendidikan::orderBy('nama_pendidikan', 'asc')->get();
        $masterPekerjaan = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $masterNarkotika = Narkotika::orderBy('jenis_narkotika', 'asc')->get();
        $masterRekomendasi = Rekomendasi::orderBy('tempat_rehabilitasi', 'asc')->get();

        return view('asesmen.edit', compact(
            'asesmen',
            'masterPendidikan',
            'masterPekerjaan',
            'masterNarkotika',
            'masterRekomendasi'
        ));
    }

    /**
     * Menyimpan perubahan data ke database (Update)
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            // Administrasi Surat & Registrasi
            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string|max:255',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',

            // Identitas Klien
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'kewarganegaraan' => 'nullable|string|max:100',
            'agama' => 'nullable|string|max:100',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'pendidikan_id' => 'nullable',
            'pekerjaan_id' => 'nullable',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',

            // Perkara Hukum & Barang Bukti
            'narkotika_id' => 'nullable',
            'berat_bb' => 'nullable|numeric',
            'deskripsi_bb' => 'nullable|string',
            'pasal_sangkaan' => 'nullable|string',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'tes_urine' => 'nullable|string|max:50',

            // Hasil Asesmen Awal (Rekap TAT)
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'rekomendasi_id' => 'nullable',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'keterangan_tambahan' => 'nullable|string',

            // Analisis Case Conference
            'aspek_hukum' => 'nullable|string',
            'aspek_medis' => 'nullable|string',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'saran_case_conference' => 'nullable|string',
        ]);

        $asesmen = Asesmen::findOrFail($id);
        $asesmen->update($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen & Case Conference berhasil diperbarui!');
    }

    /**
     * Menghapus data dari database (Destroy)
     */
    public function destroy(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);
        $asesmen->delete();

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil dihapus!');
    }

    /**
     * Memproses upload dan import file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file_excel.required' => 'Anda belum memilih file Excel.',
            'file_excel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv'
        ]);

        try {
            Excel::import(new AsesmenImport, $request->file('file_excel'));
            return redirect()->route('asesmen.index')->with('success', 'Data Excel Asesmen berhasil diimpor ke database!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh Template Excel Kosong beserta petunjuk pengisian
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => 'NO.',
            'B' => 'NAMA LENGKAP',
            'C' => 'NIK',
            'D' => 'KEWARGANEGARAAN',
            'E' => 'TEMPAT LAHIR',
            'F' => 'TANGGAL LAHIR',
            'G' => 'JENIS KELAMIN (L/P)',
            'H' => 'NO HP',
            'I' => 'PENDIDIKAN',
            'J' => 'PEKERJAAN',
            'K' => 'PENGHASILAN RATA-RATA',
            'L' => 'ALAMAT KTP',
            'M' => 'ALAMAT DOMISILI',
            'N' => 'NO REGISTRASI',
            'O' => 'NO/BLN',
            'P' => 'ASAL PENGAJUAN',
            'Q' => 'NO SURAT PENGAJUAN',
            'R' => 'NO LKN',
            'S' => 'TGL SURAT',
            'T' => 'TGL BERKAS',
            'U' => 'TGL PELAKSANAAN',
            'V' => 'TGL TANGKAP',
            'W' => 'JENIS NARKOTIKA',
            'X' => 'BERAT BUKTI (gr)',
            'Y' => 'BARANG BUKTI (DESKRIPSI)',
            'Z' => 'PASAL YANG DISANGKAKAN',
            'AA' => 'STATUS HUKUM',
            'AB' => 'KETERLIBATAN JARINGAN',
            'AC' => 'CARA MENDAPATKAN',
            'AD' => 'DAPAT DARI SIAPA',
            'AE' => 'KESEHATAN FISIK',
            'AF' => 'PSIKOLOGI',
            'AG' => 'HASIL TES URINE',
            'AH' => 'TINGKAT KETERGANTUNGAN',
            'AI' => 'POLA PEMAKAIAN',
            'AJ' => 'ALASAN PENGGUNAAN',
            'AK' => 'KONDISI KELUARGA',
            'AL' => 'KONDISI LINGKUNGAN',
            'AM' => 'HASIL ASESMEN HUKUM',
            'AN' => 'HASIL ASESMEN MEDIS',
            'AO' => 'REKOMENDASI TAT',
            'AP' => 'PELAKSANAAN REKOMENDASI (YA/TIDAK)',
            'AQ' => 'KETERANGAN TAMBAHAN',
            'AR' => 'SARAN CASE CONFERENCE'
        ];

        foreach ($headers as $col => $val) {
            $sheet->setCellValue($col . '2', $val);
            $sheet->getColumnDimension($col)->setAutoSize(true);

            $sheet->getStyle($col . '2')->getFont()->setBold(true);
            $sheet->getStyle($col . '2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFD3D3D3');
        }

        $sheet->mergeCells('A1:AR1');
        $sheet->setCellValue('A1', 'PETUNJUK PENGISIAN: Isi data mulai dari baris ke-4. Kolom tanggal mohon diisi dengan format YYYY-MM-DD (contoh: 2026-08-17). Kosongi sel jika data tidak ada.');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Template_Import_Asesmen_Case_Conference.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
        exit;
    }

    /**
     * Mencetak Berita Acara TAT menjadi PDF
     */
    public function cetakPdf(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);
        $pdf = Pdf::loadView('asesmen.pdf', compact('asesmen'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita_Acara_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.pdf');
    }

    public function beritaAcara(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);
        return view('asesmen.berita-acara', compact('asesmen'));
    }

    /**
     * Memproses form Berita Acara dan mencetak ke dokumen Word (.docx)
     */
    public function generateBeritaAcara(Request $request, string $id)
    {
        $asesmen = Asesmen::with(['pendidikan', 'pekerjaan'])->findOrFail($id);

        $templatePath = storage_path('app/templates/template_berita_acara.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template Word Berita Acara tidak ditemukan di storage.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('no_register', $asesmen->no_register ?? '-');

        $jk = $asesmen->jenis_kelamin == 'L' ? 'laki-laki' : ($asesmen->jenis_kelamin == 'P' ? 'perempuan' : '-');
        $templateProcessor->setValue('jenis_kelamin', $jk);

        $umur = '-';
        if ($asesmen->tgl_lahir) {
            $umur = Carbon::parse($asesmen->tgl_lahir)->age;
        }
        $templateProcessor->setValue('umur', $umur);

        $templateProcessor->setValue('pendidikan', $asesmen->pendidikan->nama_pendidikan ?? '-');
        $templateProcessor->setValue('pekerjaan', $asesmen->pekerjaan->nama_pekerjaan ?? '-');
        $templateProcessor->setValue('kewarganegaraan', $asesmen->kewarganegaraan ?? 'Indonesia');
        $templateProcessor->setValue('tempat_lahir', $asesmen->tempat_lahir ?? '-');
        $templateProcessor->setValue('tgl_lahir', $asesmen->tgl_lahir ? Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('nik', $asesmen->nik ?? '-');
        $templateProcessor->setValue('agama', $asesmen->agama ?? '-');
        $templateProcessor->setValue('penghasilan', $asesmen->penghasilan_rata_rata ?? '-');
        $templateProcessor->setValue('alamat_domisili', $asesmen->alamat_domisili ?? '-');

        // Mapping Request Form Berita Acara
        $templateProcessor->setValue('no_ba', $request->no_ba ?? '-');
        $templateProcessor->setValue('hari_ba', $request->hari_ba ?? '-');
        $templateProcessor->setValue('tgl_sk_tim', $request->tgl_sk_tim ?? '-');
        $templateProcessor->setValue('tgl_pelaksanaan_surat', $request->tgl_pelaksanaan_surat ?? '-');
        $templateProcessor->setValue('tgl_surat_narkoba', $request->tgl_surat_narkoba ?? '-');

        if ($request->tgl_ba) {
            $tanggalBa = Carbon::parse($request->tgl_ba);
            $templateProcessor->setValue('tgl_ba', $tanggalBa->translatedFormat('d F Y'));

            $bulanIndo = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $templateProcessor->setValue('bln_ba', $bulanIndo[$tanggalBa->month]);
            $templateProcessor->setValue('thn_ba', $tanggalBa->year);

            if (class_exists('NumberFormatter')) {
                $formatter = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
                $thnHuruf = ucwords($formatter->format($tanggalBa->year));
            } else {
                $thnHuruf = $tanggalBa->year;
            }
            $templateProcessor->setValue('thn_ba_huruf', $thnHuruf);
        } else {
            $templateProcessor->setValue('tgl_ba', '-');
            $templateProcessor->setValue('bln_ba', '-');
            $templateProcessor->setValue('thn_ba', '-');
            $templateProcessor->setValue('thn_ba_huruf', '-');
        }

        // Tim Medis & Hukum
        $templateProcessor->setValue('med_1_nama', $request->med_1_nama ?? '-');
        $templateProcessor->setValue('med_1_nip', $request->med_1_nip ?? '-');
        $templateProcessor->setValue('med_1_jabatan', $request->med_1_jabatan ?? '-');

        $templateProcessor->setValue('med_2_nama', $request->med_2_nama ?? '-');
        $templateProcessor->setValue('med_2_sip', $request->med_2_sip ?? '-');
        $templateProcessor->setValue('med_2_jabatan', $request->med_2_jabatan ?? '-');

        $templateProcessor->setValue('huk_1_nama', $request->huk_1_nama ?? '-');
        $templateProcessor->setValue('huk_1_pangkat', $request->huk_1_pangkat ?? '-');
        $templateProcessor->setValue('huk_1_nip', $request->huk_1_nip ?? '-');
        $templateProcessor->setValue('huk_1_jabatan', $request->huk_1_jabatan ?? '-');

        $templateProcessor->setValue('huk_2_nama', $request->huk_2_nama ?? '-');
        $templateProcessor->setValue('huk_2_pangkat', $request->huk_2_pangkat ?? '-');
        $templateProcessor->setValue('huk_2_nip', $request->huk_2_nip ?? '-');
        $templateProcessor->setValue('huk_2_jabatan', $request->huk_2_jabatan ?? '-');

        $templateProcessor->setValue('huk_3_nama', $request->huk_3_nama ?? '-');
        $templateProcessor->setValue('huk_3_pangkat', $request->huk_3_pangkat ?? '-');
        $templateProcessor->setValue('huk_3_nip', $request->huk_3_nip ?? '-');
        $templateProcessor->setValue('huk_3_jabatan', $request->huk_3_jabatan ?? '-');

        // Narasi
        $templateProcessor->setValue('hasil_medis', $request->hasil_medis ?? '-');
        $templateProcessor->setValue('pasal_sangkaan', $request->pasal_sangkaan ?? '-');
        $templateProcessor->setValue('hasil_hukum', $request->hasil_hukum ?? '-');
        $templateProcessor->setValue('dokter_penandatangan', $request->dokter_penandatangan ?? '-');
        $templateProcessor->setValue('hasil_urine', $request->hasil_urine ?? '-');
        $templateProcessor->setValue('hasil_lab_for', $request->hasil_lab_for ?: '-');
        $templateProcessor->setValue('jenis_narkotika', $request->jenis_narkotika ?? '-');
        $templateProcessor->setValue('pola_pemakaian', $request->pola_pemakaian ?? '-');
        $templateProcessor->setValue('kategori_ketergantungan', $request->kategori_ketergantungan ?? '-');
        $templateProcessor->setValue('diagnosis_medis', $request->diagnosis_medis ?? '-');
        $templateProcessor->setValue('kesimpulan_keterlibatan', $request->kesimpulan_keterlibatan ?? '-');
        $templateProcessor->setValue('jenis_rehabilitasi', $request->jenis_rehabilitasi ?? '-');
        $templateProcessor->setValue('tempat_rehabilitasi', $request->tempat_rehabilitasi ?? '-');
        $templateProcessor->setValue('lama_rehabilitasi', $request->lama_rehabilitasi ?? '-');

        $fileName = 'Berita_Acara_TAT_' . preg_replace('/[^A-Za-z0-9]/', '_', $asesmen->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp_' . $fileName);

        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function rekomendasi(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);

        $riwayat_kepada = collect();
        $riwayat_no_keputusan = collect();
        $riwayat_tentang = collect();
        $riwayat_narkotika = collect();
        $riwayat_perawatan = collect();
        $riwayat_diagnosis = collect();

        return view('asesmen.rekomendasi', compact(
            'asesmen',
            'riwayat_kepada',
            'riwayat_no_keputusan',
            'riwayat_tentang',
            'riwayat_narkotika',
            'riwayat_perawatan',
            'riwayat_diagnosis'
        ));
    }

    public function unduhRekomendasi(Request $request, string $id)
    {
        $asesmen = Asesmen::with(['rekomendasi', 'narkotika'])->findOrFail($id);

        $templatePath = storage_path('app/templates/template_rekomendasi.docx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template Word tidak ditemukan.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $templateProcessor->setValue('no_surat_rekomendasi', $request->no_surat_rekomendasi ?? '-');

        try {
            $tgl_rek = Carbon::parse($request->tgl_rekomendasi)->translatedFormat('d F Y');
        } catch (Exception $e) {
            $tgl_rek = $request->tgl_rekomendasi ?? '-';
        }
        $templateProcessor->setValue('tgl_rekomendasi', $tgl_rek);

        $templateProcessor->setValue('kepada_yth', $request->kepada_yth ?? '-');
        $templateProcessor->setValue('no_keputusan', $request->no_keputusan ?? '-');

        try {
            $tgl_kep = Carbon::parse($request->tgl_keputusan)->translatedFormat('d F Y');
        } catch (Exception $e) {
            $tgl_kep = $request->tgl_keputusan ?? '-';
        }
        $templateProcessor->setValue('tgl_keputusan', $tgl_kep);

        $templateProcessor->setValue('tentang_permohonan', $request->tentang_permohonan ?? '-');
        $templateProcessor->setValue('kewarganegaraan', $request->kewarganegaraan ?? 'Indonesia (WNI)');
        $templateProcessor->setValue('nama_narkotika', $request->nama_narkotika_medis ?? '-');
        $templateProcessor->setValue('keterangan_diagnosis', $request->keterangan_diagnosis ?? '-');
        $templateProcessor->setValue('lama_perawatan', $request->lama_perawatan ?? '-');

        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nama_langkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nam_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nik', $asesmen->nik);
        $templateProcessor->setValue('tempat_lahir', $asesmen->tempat_lahir ?? '-');
        $templateProcessor->setValue('tgl_lahir', $asesmen->tgl_lahir ? Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-');

        $jk = $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-');
        $templateProcessor->setValue('jenis_kelamin', $jk);
        $templateProcessor->setValue('alamat_ktp', $asesmen->alamat_ktp ?? '-');
        $templateProcessor->setValue('alamat_domisili', $asesmen->alamat_domisili ?? '-');
        $templateProcessor->setValue('no_surat_pengajuan', $asesmen->no_surat_pengajuan ?? '-');

        $tgl_pelaksanaan = $asesmen->tgl_pelaksanaan ? Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('d F Y') : '-';
        $hari_pelaksanaan = $asesmen->tgl_pelaksanaan ? Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('l') : '-';

        $templateProcessor->setValue('tgl_pelaksanaan', $tgl_pelaksanaan);
        $templateProcessor->setValue('tanggal_tat', $tgl_pelaksanaan);
        $templateProcessor->setValue('hari', $hari_pelaksanaan);
        $templateProcessor->setValue('jenis_narkotika', $asesmen->narkotika->jenis_narkotika ?? '-');
        $templateProcessor->setValue('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan ?? '-');
        $templateProcessor->setValue('rekomendasi_tat', $asesmen->rekomendasi->tempat_rehabilitasi ?? '-');

        $fileName = 'Surat_Rekomendasi_TAT_' . preg_replace('/[^A-Za-z0-9]/', '_', $asesmen->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp_' . $fileName);

        $templateProcessor->saveAs($tempPath);
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export data ke Excel berdasarkan filter aktif
     */
    public function exportExcel(Request $request)
    {
        $namaFile = 'Rekap_Asesmen_TAT_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new AsesmenExport($request), $namaFile);
    }
}