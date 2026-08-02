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

class AsesmenController extends Controller
{
    /**
     * Menampilkan halaman utama Data Asesmen TAT (Tabel Index)
     */
    public function index()
    {
        $asesmens = Asesmen::with(['narkotika', 'pendidikan', 'pekerjaan'])->paginate(10);
        return view('asesmen.index', compact('asesmens'));
    }

    /**
     * Menampilkan form tambah data manual (Create)
     */
    public function create()
    {
        $pendidikans = Pendidikan::all();
        $pekerjaans = Pekerjaan::all();
        $narkotikas = Narkotika::all();
        $rekomendasis = Rekomendasi::all();

        $asal_pengajuans = Asesmen::select('asal_pengajuan')->whereNotNull('asal_pengajuan')->distinct()->get();
        $pasal_sangkaans = Asesmen::select('pasal_sangkaan')->whereNotNull('pasal_sangkaan')->distinct()->get();
        $tes_urines      = Asesmen::select('tes_urine')->whereNotNull('tes_urine')->distinct()->get();
        $hasil_hukums    = Asesmen::select('hasil_asesmen_hukum')->whereNotNull('hasil_asesmen_hukum')->distinct()->get();
        $hasil_medis     = Asesmen::select('hasil_asesmen_medis')->whereNotNull('hasil_asesmen_medis')->distinct()->get();

        return view('asesmen.create', compact(
            'pendidikans', 'pekerjaans', 'narkotikas', 'rekomendasis',
            'asal_pengajuans', 'pasal_sangkaans', 'tes_urines', 'hasil_hukums', 'hasil_medis'
        ));
    }

    /**
     * Menyimpan data baru ke database (Store)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',

            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_id' => 'nullable|integer|exists:m_pekerjaan,id',

            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',

            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',

            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',

            'narkotika_id' => 'nullable|integer',
            'berat_bb' => 'nullable|numeric', // PERBAIKAN: Wajib numeric
            'pasal_sangkaan' => 'nullable|string',

            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'rekomendasi_input' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',

            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari' => 'nullable|string|max:255',
            'kesehatan' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'saran' => 'nullable|string',
        ]);

        if ($request->filled('pendidikan_input')) {
            $pendidikan = Pendidikan::firstOrCreate([
                'nama_pendidikan' => $request->pendidikan_input
            ]);
            $validatedData['pendidikan_id'] = $pendidikan->id;
        }
        unset($validatedData['pendidikan_input']);

        if ($request->filled('rekomendasi_input')) {
            $rekomendasi = Rekomendasi::firstOrCreate([
                'tempat_rehabilitasi' => $request->rekomendasi_input
            ]);
            $validatedData['rekomendasi_id'] = $rekomendasi->id;
        }
        unset($validatedData['rekomendasi_input']);

        Asesmen::create($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman Detail Data (Show)
     */
    public function show(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);
        return view('asesmen.show', compact('asesmen'));
    }

    /**
     * Menampilkan form edit data (Edit)
     */
    public function edit(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);

        $pendidikans = Pendidikan::all();
        $pekerjaans = Pekerjaan::all();
        $narkotikas = Narkotika::all();
        $rekomendasis = Rekomendasi::all();

        // PERBAIKAN: Menambahkan data datalist ke halaman edit
        $asal_pengajuans = Asesmen::select('asal_pengajuan')->whereNotNull('asal_pengajuan')->distinct()->get();
        $pasal_sangkaans = Asesmen::select('pasal_sangkaan')->whereNotNull('pasal_sangkaan')->distinct()->get();
        $tes_urines      = Asesmen::select('tes_urine')->whereNotNull('tes_urine')->distinct()->get();
        $hasil_hukums    = Asesmen::select('hasil_asesmen_hukum')->whereNotNull('hasil_asesmen_hukum')->distinct()->get();
        $hasil_medis     = Asesmen::select('hasil_asesmen_medis')->whereNotNull('hasil_asesmen_medis')->distinct()->get();

        return view('asesmen.edit', compact(
            'asesmen', 'pendidikans', 'pekerjaans', 'narkotikas', 'rekomendasis',
            'asal_pengajuans', 'pasal_sangkaans', 'tes_urines', 'hasil_hukums', 'hasil_medis'
        ));
    }

    /**
     * Menyimpan perubahan data ke database (Update)
     */
    public function update(Request $request, string $id)
    {
        // PERBAIKAN: Menyesuaikan validasi update agar identik dengan store
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',

            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_id' => 'nullable|integer|exists:m_pekerjaan,id',

            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',

            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',

            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',

            'narkotika_id' => 'nullable|integer',
            'berat_bb' => 'nullable|numeric', // PERBAIKAN: Wajib numeric
            'pasal_sangkaan' => 'nullable|string',

            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'rekomendasi_input' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',

            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari' => 'nullable|string|max:255',
            'kesehatan' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'saran' => 'nullable|string',
        ]);

        // PERBAIKAN: Menerapkan logika firstOrCreate di Update
        if ($request->filled('pendidikan_input')) {
            $pendidikan = Pendidikan::firstOrCreate([
                'nama_pendidikan' => $request->pendidikan_input
            ]);
            $validatedData['pendidikan_id'] = $pendidikan->id;
        }
        unset($validatedData['pendidikan_input']);

        if ($request->filled('rekomendasi_input')) {
            $rekomendasi = Rekomendasi::firstOrCreate([
                'tempat_rehabilitasi' => $request->rekomendasi_input
            ]);
            $validatedData['rekomendasi_id'] = $rekomendasi->id;
        }
        unset($validatedData['rekomendasi_input']);

        $asesmen = Asesmen::findOrFail($id);
        $asesmen->update($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil diperbarui!');
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
            'A' => 'KOSONG',
            'B' => 'NO.',
            'C' => 'NO/BLN',
            'D' => 'TGL SURAT',
            'E' => 'TGL BERKAS',
            'F' => 'TGL PELAKSANAAN',
            'G' => 'NO SURAT',
            'H' => 'NO LKN',
            'I' => 'TGL TANGKAP',
            'J' => 'NAMA',
            'K' => 'NO REG',
            'L' => 'ALAMAT KTP',
            'M' => 'ALAMAT DOMISILI',
            'N' => 'TMP LAHIR',
            'O' => 'TGL LAHIR',
            'P' => 'JENIS KELAMIN (L/P)',
            'Q' => 'USIA',
            'R' => 'PENDIDIKAN',
            'S' => 'PEKERJAAN',
            'T' => 'NO HP',
            'U' => 'NIK',
            'V' => 'PENGHASILAN RATA-RATA',

            'W' => 'JENIS NARKOTIKA',
            'X' => 'BERAT (gr)',
            'Y' => 'PASAL YANG DISANGKAKAN',
            'Z' => 'STATUS HUKUM',
            'AA' => 'KETERLIBATAN JARINGAN',
            'AB' => 'CARA MENDAPATKAN',
            'AC' => 'DAPAT DARI SIAPA',

            'AD' => 'KESEHATAN FISIK',
            'AE' => 'PSIKOLOGI',
            'AF' => 'HASIL TES URINE',
            'AG' => 'ALASAN PENGGUNAAN',
            'AH' => 'KONDISI KELUARGA',
            'AI' => 'TINGKAT KETERGANTUNGAN',
            'AJ' => 'POLA PEMAKAIAN',
            'AK' => 'KONDISI LINGKUNGAN',

            'AL' => 'HASIL ASESMEN HUKUM',
            'AM' => 'HASIL ASESMEN MEDIS',
            'AN' => 'REKOMENDASI TAT',
            'AO' => 'PELAKSANAAN REKOMENDASI',
            'AP' => 'KETERANGAN TAMBAHAN',
            'AQ' => 'SARAN CASE CONFERENCE'
        ];

        foreach ($headers as $col => $val) {
            $sheet->setCellValue($col . '2', $val);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle($col . '2')->getFont()->setBold(true);
        }

        $sheet->setCellValue('J3', 'Mohon isi data mulai baris ke-4 ke bawah.');
        $sheet->getStyle('J3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

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

    public function rekomendasi(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);

        // Menarik riwayat ketikan sebelumnya untuk Datalist
        $riwayat_kepada = Asesmen::select('kepada_yth')->whereNotNull('kepada_yth')->distinct()->get();
        $riwayat_no_keputusan = Asesmen::select('no_keputusan')->whereNotNull('no_keputusan')->distinct()->get();
        $riwayat_tentang = Asesmen::select('tentang_permohonan')->whereNotNull('tentang_permohonan')->distinct()->get();
        $riwayat_narkotika = Asesmen::select('nama_narkotika_medis')->whereNotNull('nama_narkotika_medis')->distinct()->get();
        $riwayat_perawatan = Asesmen::select('lama_perawatan')->whereNotNull('lama_perawatan')->distinct()->get();
        $riwayat_diagnosis = Asesmen::select('keterangan_diagnosis')->whereNotNull('keterangan_diagnosis')->distinct()->get();

        return view('asesmen.rekomendasi', compact(
            'asesmen', 'riwayat_kepada', 'riwayat_no_keputusan',
            'riwayat_tentang', 'riwayat_narkotika', 'riwayat_perawatan', 'riwayat_diagnosis'
        ));
    }
    public function unduhRekomendasi(Request $request, string $id)
    {
        $asesmen = Asesmen::with(['rekomendasi', 'narkotika'])->findOrFail($id);

        // 1. SIMPAN KE DATABASE SEBAGAI RIWAYAT AUTOMATIS
        $asesmen->update([
            'no_surat_rekomendasi' => $request->no_surat_rekomendasi,
            'tgl_rekomendasi' => $request->tgl_rekomendasi,
            'kepada_yth' => $request->kepada_yth,
            'no_keputusan' => $request->no_keputusan,
            'tgl_keputusan' => $request->tgl_keputusan,
            'tentang_permohonan' => $request->tentang_permohonan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'nama_narkotika_medis' => $request->nama_narkotika_medis,
            'lama_perawatan' => $request->lama_perawatan,
            'keterangan_diagnosis' => $request->keterangan_diagnosis,
        ]);

        // 2. CEK TEMPLATE WORD
        $templatePath = storage_path('app/templates/template_rekomendasi.docx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template Word tidak ditemukan.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // 3. MAPPING DATA INPUT MANUAL (Format Tanggal Indonesia)
        $templateProcessor->setValue('no_surat_rekomendasi', $request->no_surat_rekomendasi ?? '-');
        $templateProcessor->setValue('tgl_rekomendasi', \Carbon\Carbon::parse($request->tgl_rekomendasi)->translatedFormat('d F Y'));
        $templateProcessor->setValue('kepada_yth', $request->kepada_yth ?? '-');
        $templateProcessor->setValue('no_keputusan', $request->no_keputusan ?? '-');
        $templateProcessor->setValue('tgl_keputusan', \Carbon\Carbon::parse($request->tgl_keputusan)->translatedFormat('d F Y'));
        $templateProcessor->setValue('tentang_permohonan', $request->tentang_permohonan ?? '-');
        $templateProcessor->setValue('kewarganegaraan', $request->kewarganegaraan ?? 'Indonesia (WNI)');
        $templateProcessor->setValue('nama_narkotika', $request->nama_narkotika_medis ?? '-');
        $templateProcessor->setValue('keterangan_diagnosis', $request->keterangan_diagnosis ?? '-');
        $templateProcessor->setValue('lama_perawatan', $request->lama_perawatan ?? '-');

        // 4. MAPPING DATA OTOMATIS DARI DATABASE
        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nama_langkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nam_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nik', $asesmen->nik);
        $templateProcessor->setValue('tempat_lahir', $asesmen->tempat_lahir ?? '-');
        $templateProcessor->setValue('tgl_lahir', $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-');

        $jk = $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-');
        $templateProcessor->setValue('jenis_kelamin', $jk);
        $templateProcessor->setValue('alamat_ktp', $asesmen->alamat_ktp ?? '-');
        $templateProcessor->setValue('alamat_domisili', $asesmen->alamat_domisili ?? '-');
        $templateProcessor->setValue('no_surat_pengajuan', $asesmen->no_surat_pengajuan ?? '-');

        $tgl_pelaksanaan = $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('d F Y') : '-';
        $hari_pelaksanaan = $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('l') : '-';

        $templateProcessor->setValue('tgl_pelaksanaan', $tgl_pelaksanaan);
        $templateProcessor->setValue('tanggal_tat', $tgl_pelaksanaan);
        $templateProcessor->setValue('hari', $hari_pelaksanaan);
        $templateProcessor->setValue('jenis_narkotika', $asesmen->narkotika->jenis_narkotika ?? '-');
        $templateProcessor->setValue('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan ?? '-');
        $templateProcessor->setValue('rekomendasi_tat', $asesmen->rekomendasi->tempat_rehabilitasi ?? '-');

        // 5. PROSES PENGUNDUHAN
        $fileName = 'Surat_Rekomendasi_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp_' . $fileName);

        $templateProcessor->saveAs($tempPath);
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
