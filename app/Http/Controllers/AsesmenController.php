<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesmen;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Narkotika;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; // WAJIB DITAMBAHKAN UNTUK PROTEKSI RUTE
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
        if (!Gate::allows('read-only')) {
            abort(403, 'Akses ditolak. Akun Anda tidak memiliki peran yang valid.');
        }

        $query = Asesmen::with(['narkotika', 'pendidikan', 'pekerjaan', 'rekomendasi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_register', 'like', "%{$search}%")
                    ->orWhere('no_lkn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_pelaksanaan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tgl_pelaksanaan', $request->tahun);
        }

        if ($request->filled('narkotika')) {
            $query->where('narkotika_id', $request->narkotika);
        }

        if ($request->filled('status')) {
            $query->where('pelaksanaan', strtoupper($request->status));
        }

        $sort = $request->get('sort', 'terbaru');

        if ($sort === 'terlama') {
            $query->oldest('created_at');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->latest('created_at');
        }

        $asesmens = $query->paginate(10)->withQueryString();

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
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

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
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        // === BLOK VALIDASI NIK ===
        $request->validate([
            'nik' => 'required|max:16|unique:asesmens,nik',
        ], [
            'nik.unique' => 'Peringatan: NIK ini sudah pernah terdaftar di dalam sistem! Silakan gunakan NIK lain atau gunakan fitur Edit Data.',
        ]);

        // === GABUNGKAN ARRAY NARKOTIKA MENJADI STRING SEBELUM VALIDASI ===
        if ($request->has('narkotika_id') && is_array($request->narkotika_id)) {
            $request->merge([
                'narkotika_id' => implode(',', $request->narkotika_id)
            ]);
        }

        $validatedData = $request->validate([
            // Identitas Dasar & Foto
            'foto_klien' => 'nullable|image|mimes:jpeg,png,jpg',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',

            // Validasi Input Master Data
            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_input' => 'nullable|string|max:255',
            'rekomendasi_input' => 'nullable|string|max:255',

            // Alamat & Administrasi
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

            // Kasus & Medis
            'narkotika_id' => 'nullable|string',
            'berat_bb' => 'nullable|numeric',
            'pasal_sangkaan' => 'nullable|string',
            'deskripsi_bb' => 'nullable|string',
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'saran_case_conference' => 'nullable|string',
            'aspek_hukum' => 'nullable|string',
            'aspek_medis' => 'nullable|string',
        ]);

        // LOGIKA PENYIMPANAN FOTO KLIEN
        if ($request->hasFile('foto_klien')) {
            $validatedData['foto_klien'] = $request->file('foto_klien')->store('foto-klien', 'public');
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER PENDIDIKAN
        if ($request->filled('pendidikan_input')) {
            $pendidikan = Pendidikan::firstOrCreate([
                'nama_pendidikan' => $request->pendidikan_input
            ]);
            $validatedData['pendidikan_id'] = $pendidikan->id;
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER PEKERJAAN
        if ($request->filled('pekerjaan_input')) {
            $pekerjaan = Pekerjaan::firstOrCreate([
                'nama_pekerjaan' => $request->pekerjaan_input
            ]);
            $validatedData['pekerjaan_id'] = $pekerjaan->id;
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER REKOMENDASI TAT
        if ($request->filled('rekomendasi_input')) {
            $rekomendasi = Rekomendasi::firstOrCreate([
                'tempat_rehabilitasi' => $request->rekomendasi_input
            ]);
            $validatedData['rekomendasi_id'] = $rekomendasi->id;
        }

        Asesmen::create($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman Detail Data (Show)
     */
    public function show(string $id)
    {
        if (!Gate::allows('read-only')) {
            abort(403, 'Akses ditolak. Akun Anda tidak memiliki peran yang valid.');
        }

        $asesmen = \App\Models\Asesmen::with([
            'pendidikan',
            'pekerjaan',
            'narkotika',
            'anggotaTim'
        ])->findOrFail($id);

        return view('asesmen.show', compact('asesmen'));
    }

    /**
     * Menampilkan form edit data (Edit)
     */
    public function edit(string $id)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

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
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $request->validate([
            'nik' => 'required|max:16|unique:asesmens,nik,' . $id,
        ], [
            'nik.unique' => 'Peringatan: NIK ini sudah digunakan oleh Klien lain.',
        ]);

        // === GABUNGKAN ARRAY NARKOTIKA MENJADI STRING SEBELUM VALIDASI ===
        if ($request->has('narkotika_id') && is_array($request->narkotika_id)) {
            $request->merge([
                'narkotika_id' => implode(',', $request->narkotika_id)
            ]);
        }

        $validatedData = $request->validate([
            // Identitas Dasar & Foto
            'foto_klien' => 'nullable|image|mimes:jpeg,png,jpg',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',

            // Validasi Input Master Data
            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_input' => 'nullable|string|max:255',
            'rekomendasi_input' => 'nullable|string|max:255',

            // Alamat & Administrasi
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

            // Kasus & Medis
            'narkotika_id' => 'nullable|string',
            'berat_bb' => 'nullable|numeric',
            'pasal_sangkaan' => 'nullable|string',
            'deskripsi_bb' => 'nullable|string',
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'saran_case_conference' => 'nullable|string',
            'aspek_hukum' => 'nullable|string',
            'aspek_medis' => 'nullable|string',
        ]);

        $asesmen = Asesmen::findOrFail($id);

        // LOGIKA PENYIMPANAN FOTO KLIEN
        if ($request->hasFile('foto_klien')) {
            // Hapus foto lama jika ada
            if ($asesmen->foto_klien) {
                Storage::disk('public')->delete($asesmen->foto_klien);
            }
            $validatedData['foto_klien'] = $request->file('foto_klien')->store('foto-klien', 'public');
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER PENDIDIKAN
        if ($request->filled('pendidikan_input')) {
            $pendidikan = Pendidikan::firstOrCreate([
                'nama_pendidikan' => $request->pendidikan_input
            ]);
            $validatedData['pendidikan_id'] = $pendidikan->id;
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER PEKERJAAN
        if ($request->filled('pekerjaan_input')) {
            $pekerjaan = Pekerjaan::firstOrCreate([
                'nama_pekerjaan' => $request->pekerjaan_input
            ]);
            $validatedData['pekerjaan_id'] = $pekerjaan->id;
        }

        // LOGIKA PENYIMPANAN OTOMATIS MASTER REKOMENDASI TAT
        if ($request->filled('rekomendasi_input')) {
            $rekomendasi = Rekomendasi::firstOrCreate([
                'tempat_rehabilitasi' => $request->rekomendasi_input
            ]);
            $validatedData['rekomendasi_id'] = $rekomendasi->id;
        }

        $asesmen->update($validatedData);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil diperbarui!');
    }

    /**
     * Menghapus data dari database (Destroy)
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $asesmen = Asesmen::findOrFail($id);

        // Menghapus file foto jika ada
        if ($asesmen->foto_klien) {
            Storage::disk('public')->delete($asesmen->foto_klien);
        }

        $asesmen->delete();

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil dihapus!');
    }

    /**
     * Memproses upload dan import file Excel
     */
    public function import(\Illuminate\Http\Request $request)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file_excel.required' => 'Anda belum memilih file Excel.',
            'file_excel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv'
        ]);

        try {
            Excel::import(new \App\Imports\AsesmenImport, $request->file('file_excel'));
            return redirect()->route('asesmen.index')->with('success', 'Data Excel Asesmen massal berhasil diimpor dan disimpan ke database!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data! (Detail: ' . $e->getMessage() . ')');
        }
    }

    /**
     * Mengunduh Template Excel Kosong beserta petunjuk pengisian
     */
    public function downloadTemplate()
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. JUDUL DAN PANDUAN
        $sheet->mergeCells('A1:AT1');
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA ASESMEN TERPADU (TAT) BNN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E3A8A');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->mergeCells('A2:AT2');
        $sheet->setCellValue('A2', 'PANDUAN: Hapus/timpa data contoh di baris 4. Mulai isi data asli di baris 4 ke bawah. Jangan ubah struktur kolom baris 1-3. Kolom dengan tanda (*) wajib diisi. Format tanggal harus YYYY-MM-DD (Contoh: 2026-08-17).');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setARGB('FFDC2626');
        $sheet->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFBEB');
        $sheet->getStyle('A2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // 2. HEADER KOLOM
        $headers = [
            'A' => 'No. Register',
            'B' => 'No / BLN',
            'C' => 'Asal Pengajuan',
            'D' => 'No. Surat Pengajuan',
            'E' => 'No. LKN / LP / LI',
            'F' => 'Tgl Surat (YYYY-MM-DD)',
            'G' => 'Tgl Berkas (YYYY-MM-DD)',
            'H' => 'Tgl Pelaksanaan (YYYY-MM-DD)',
            'I' => 'Tgl Tangkap (YYYY-MM-DD)',
            'J' => 'Nama Lengkap (*)',
            'K' => 'NIK (*)',
            'L' => 'No. HP',
            'M' => 'Tempat Lahir',
            'N' => 'Tgl Lahir (YYYY-MM-DD)',
            'O' => 'Jenis Kelamin (L/P) (*)',
            'P' => 'Kewarganegaraan',
            'Q' => 'Agama',
            'R' => 'Pendidikan',
            'S' => 'Pekerjaan',
            'T' => 'Penghasilan',
            'U' => 'Alamat KTP',
            'V' => 'Alamat Domisili',
            'W' => 'Jenis Narkotika',
            'X' => 'Berat BB (Gram)',
            'Y' => 'Status Hukum',
            'Z' => 'Deskripsi BB',
            'AA' => 'Pasal Sangkaan',
            'AB' => 'Terlibat Jaringan (Ya/Tidak)',
            'AC' => 'Hasil Tes Urine',
            'AD' => 'Cara Mendapatkan',
            'AE' => 'Dapat Dari Siapa',
            'AF' => 'Asesmen Hukum (Mentah)',
            'AG' => 'Asesmen Medis (Mentah)',
            'AH' => 'Rekomendasi TAT',
            'AI' => 'Pelaksanaan (YA/TIDAK)',
            'AJ' => 'Ket. Tambahan TAT',
            'AK' => 'Analisis Hukum (CC)',
            'AL' => 'Analisis Medis (CC)',
            'AM' => 'Kesehatan Fisik',
            'AN' => 'Psikologi',
            'AO' => 'Alasan Penggunaan',
            'AP' => 'Kondisi Keluarga',
            'AQ' => 'Tingkat Ketergantungan',
            'AR' => 'Pola Pemakaian',
            'AS' => 'Kondisi Lingkungan',
            'AT' => 'Saran Case Conference'
        ];

        foreach ($headers as $col => $val) {
            $sheet->setCellValue($col . '3', $val);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle($col . '3')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        $warnaKategori = [
            'A3:I3' => 'FF3B82F6',
            'J3:V3' => 'FF10B981',
            'W3:AE3' => 'FFF59E0B',
            'AF3:AJ3' => 'FF6366F1',
            'AK3:AT3' => 'FF8B5CF6'
        ];
        foreach ($warnaKategori as $range => $color) {
            $sheet->getStyle($range)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($range)->getBorders()->getAllBorders()->getColor()->setARGB('FFFFFFFF');
        }
        $sheet->getRowDimension(3)->setRowHeight(30);

        // 3. BARIS CONTOH DATA
        $dummyData = [
            'A' => 'REG/001/2026',
            'B' => '01/VIII',
            'C' => 'Polres',
            'D' => 'SPRIN/123/2026',
            'E' => 'LKN/456/2026',
            'F' => '2026-08-01',
            'G' => '2026-08-02',
            'H' => '2026-08-05',
            'I' => '2026-07-30',
            'J' => 'Budi Santoso',
            'K' => '3573001122334455',
            'L' => '08123456789',
            'M' => 'Malang',
            'N' => '1990-01-01',
            'O' => 'L',
            'P' => 'WNI',
            'Q' => 'Islam',
            'R' => 'SMA',
            'S' => 'Swasta',
            'T' => 'Rp 3.000.000',
            'U' => 'Jl. Merdeka 1, Malang',
            'V' => 'Jl. Merdeka 1, Malang',
            'W' => 'Sabu',
            'X' => '2.5',
            'Y' => 'Tersangka',
            'Z' => '1 klip plastik kecil',
            'AA' => 'Pasal 112',
            'AB' => 'Tidak',
            'AC' => 'Positif Sabu',
            'AD' => 'Membeli',
            'AE' => 'Teman',
            'AF' => 'Tersangka kooperatif...',
            'AG' => 'Tidak ada riwayat sakit...',
            'AH' => 'Rawat Inap',
            'AI' => 'TIDAK',
            'AJ' => '-',
            'AK' => 'Analisis hukum lengkap...',
            'AL' => 'Analisis medis lengkap...',
            'AM' => 'Sehat',
            'AN' => 'Cemas',
            'AO' => 'Coba-coba',
            'AP' => 'Kurang harmonis',
            'AQ' => 'Ringan',
            'AR' => 'Situasional',
            'AS' => 'Rentan',
            'AT' => 'Rehab medis'
        ];
        foreach ($dummyData as $col => $val) {
            $sheet->setCellValue($col . '4', $val);
        }

        $sheet->getStyle('A4:AT4')->getFont()->setItalic(true)->getColor()->setARGB('FF94A3B8');
        $sheet->getStyle('A4:AT4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
        $sheet->freezePane('A5');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Template_Import_SistemTAT_BNN.xlsx';

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
        if (!Gate::allows('read-only')) {
            abort(403, 'Akses ditolak. Akun Anda tidak memiliki peran yang valid.');
        }

        $asesmen = Asesmen::findOrFail($id);
        $pdf = Pdf::loadView('asesmen.pdf', compact('asesmen'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Ringkasan_Asesmen_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.pdf');
    }

    /**
     * Tampilan Halaman Generator Berita Acara
     */
    public function beritaAcara(string $id)
    {
        if (!Gate::allows('akses-dokumen')) {
            abort(403, 'Anda tidak memiliki akses ke fitur dokumen.');
        }

        $asesmen = \App\Models\Asesmen::with(['pendidikan', 'pekerjaan', 'anggotaTim'])->findOrFail($id);

        $masterMedis = \App\Models\MasterAnggota::where('kategori', 'medis')->get();
        $masterHukum = \App\Models\MasterAnggota::where('kategori', 'hukum')->get();
        $masterZat = \App\Models\MasterOpsi::where('kategori', 'zat')->get();
        $masterTempatRehab = \App\Models\MasterOpsi::where('kategori', 'tempat_rehab')->get();
        $masterDiagnosis = \App\Models\MasterOpsi::where('kategori', 'diagnosis')->get();

        $klienData = [
            'nama' => $asesmen->nama_lengkap ?? '-',
            'nik' => $asesmen->nik ?? '-',
            'usia' => $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->age : '-',
            'tempat_lahir' => $asesmen->tempat_lahir ?? '-',
            'tgl_lahir' => $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-',
            'jk' => $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            'agama' => $asesmen->agama ?? '-',
            'pendidikan' => $asesmen->pendidikan_input ?? '-',
            'pekerjaan' => $asesmen->pekerjaan_input ?? '-',
            'alamat_ktp' => $asesmen->alamat_ktp ?? '-',
            'alamat_domisili' => $asesmen->alamat_domisili ?? '-',
        ];

        return view('asesmen.berita-acara', compact('asesmen', 'masterMedis', 'masterHukum', 'masterZat', 'masterTempatRehab', 'masterDiagnosis', 'klienData'));
    }

    /**
     * Memproses & Generate Berita Acara Word (Dari Form BA)
     */
    public function generateBeritaAcara(\Illuminate\Http\Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) {
            abort(403, 'Anda tidak memiliki akses ke fitur dokumen.');
        }

        $asesmen = \App\Models\Asesmen::findOrFail($id);

        // 1. SIMPAN MANUAL SEMUA DATA DARI FORM (Termasuk Status Klien & Diagnosis)
        $asesmen->no_ba = $request->no_ba;
        $asesmen->tgl_ba = $request->tgl_ba;
        $asesmen->ketua_tat_nama = $request->ketua_tat_nama;
        $asesmen->ketua_tat_nrp = $request->ketua_tat_nrp;
        $asesmen->no_kep_tim = $request->no_kep_tim;
        $asesmen->tgl_kep_tim = $request->tgl_kep_tim;

        $asesmen->narasi_medis = $request->narasi_medis;
        $asesmen->narasi_hukum = $request->narasi_hukum;

        $asesmen->alat_bukti_no_sk = $request->alat_bukti_no_sk;
        $asesmen->alat_bukti_tgl_sk = $request->alat_bukti_tgl_sk;
        $asesmen->alat_bukti_dokter = $request->alat_bukti_dokter;
        $asesmen->alat_bukti_hasil = $request->alat_bukti_hasil;

        $asesmen->status_klien = $request->status_klien;
        $asesmen->kesimpulan_jenis_zat = $request->kesimpulan_jenis_zat;
        $asesmen->kesimpulan_pola_pakai = $request->kesimpulan_pola_pakai;
        $asesmen->kesimpulan_kategori = $request->kesimpulan_kategori;
        $asesmen->diagnosis_medis = $request->diagnosis_medis;

        $asesmen->rekomendasi_tempat_rehab = $request->rekomendasi_tempat_rehab;
        $asesmen->rekomendasi_durasi = $request->rekomendasi_durasi;
        $asesmen->rekomendasi_keterangan = $request->rekomendasi_keterangan;

        // --- SINKRONISASI KE HALAMAN SHOW ---
        // Jika Berita Acara di-save, nilai 'lama_perawatan' di database juga otomatis diupdate
        // sehingga halaman 'show' (Detail Klien) akan menampilkan data yang sama persis
        $asesmen->lama_perawatan = $request->rekomendasi_durasi;
        // ------------------------------------

        // Eksekusi penyimpanan ke database
        $asesmen->save();

        // Simpan relasi anggota tim (Tim Medis & Hukum) ke tabel pivot
        $asesmen->anggotaTim()->sync(array_merge(
            (array) $request->tim_medis,
            (array) $request->tim_hukum
        ));

        // 2. Redirect Ke Halaman Show Jika Klik "Simpan Perubahan"
        if ($request->input('action') === 'save_only' || $request->input('action') === 'save') {
            return redirect()->route('asesmen.show', $asesmen->id)
                ->with('success', 'Data Berita Acara berhasil disimpan dan diperbarui!');
        }

        // 3. Jika "Simpan & Unduh" ditekan, Word di-generate dengan data dari Database
        return $this->prosesCetakBeritaAcaraWord($asesmen);
    }

    /**
     * Memproses & Generate Berita Acara Word (Hanya Unduh, Dari Card 5)
     */
    public function unduhBeritaAcara(Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) {
            abort(403, 'Anda tidak memiliki akses ke fitur dokumen.');
        }

        $asesmen = Asesmen::with(['rekomendasi', 'narkotika', 'pendidikan', 'pekerjaan', 'anggotaTim'])->findOrFail($id);
        return $this->prosesCetakBeritaAcaraWord($asesmen);
    }

    /**
     * PRIVATE FUNCTION: Fungsi Terpusat Untuk Membaca dan Render Word Berita Acara
     */
    private function prosesCetakBeritaAcaraWord(\App\Models\Asesmen $asesmen)
    {
        $templatePath = storage_path('app/templates/template_berita_acara.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template Word Berita Acara tidak ditemukan di folder storage.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // --- PEMOTONGAN STRING (STRING PARSING) UNTUK TEMPAT REHABILITASI ---
        $rawTempat = $asesmen->rekomendasi_tempat_rehab ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '-';
        $tempatBersih = $rawTempat;

        if (str_starts_with($rawTempat, 'Rawat Jalan - ')) {
            $tempatBersih = trim(str_replace('Rawat Jalan - ', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Rawat Inap - ')) {
            $tempatBersih = trim(str_replace('Rawat Inap - ', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan - ')) {
            $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan - ', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum) - ')) {
            $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum) - ', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Rawat Jalan')) {
            $tempatBersih = trim(str_replace('Rawat Jalan', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Rawat Inap')) {
            $tempatBersih = trim(str_replace('Rawat Inap', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan')) {
            $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan', '', $rawTempat));
        } elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum)')) {
            $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawTempat));
        }

        $tempatBersih = empty($tempatBersih) ? 'Tanpa Instansi' : trim(ltrim($tempatBersih, ' -'));
        // ----------------------------------------------------------------------

        // --- MAPPING IDENTITAS DASAR ---
        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap ?? '-');
        $templateProcessor->setValue('no_register', $asesmen->no_register ?? '-');
        $templateProcessor->setValue('no_ba', $asesmen->no_ba ?? '-');
        $templateProcessor->setValue('ketua_tat_nama', $asesmen->ketua_tat_nama ?? '-');
        $templateProcessor->setValue('ketua_tat_nrp', $asesmen->ketua_tat_nrp ?? '-');
        $templateProcessor->setValue('no_kep_tim', $asesmen->no_kep_tim ?? '-');

        // --- MAPPING TANGGAL BA ---
        if ($asesmen->tgl_ba) {
            $tglBa = \Carbon\Carbon::parse($asesmen->tgl_ba);
            $templateProcessor->setValue('tgl_ba', $tglBa->translatedFormat('d'));
            $templateProcessor->setValue('hari_ba', $tglBa->translatedFormat('l'));
            $templateProcessor->setValue('bln_ba', $tglBa->translatedFormat('F'));
            $templateProcessor->setValue('thn_ba', $tglBa->year);
        } else {
            $templateProcessor->setValue('tgl_ba', '-');
            $templateProcessor->setValue('hari_ba', '-');
            $templateProcessor->setValue('bln_ba', '-');
            $templateProcessor->setValue('thn_ba', '-');
        }

        $templateProcessor->setValue('tgl_kep_tim', $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('alat_bukti_tgl_sk', $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->translatedFormat('d F Y') : '-');

        // --- MAPPING TIM MEDIS ---
        $medis = $asesmen->anggotaTim->where('kategori', 'medis')->values();
        $templateProcessor->cloneBlock('block_medis', max(count($medis), 1), true, true);
        if (count($medis) > 0) {
            foreach ($medis as $index => $m) {
                $i = $index + 1;
                $templateProcessor->setValue("no_medis#{$i}", $i);
                $templateProcessor->setValue("med_nama#{$i}", $m->nama);
                $templateProcessor->setValue("med_nip#{$i}", $m->nip_nrp_sip ?? '-');
                $templateProcessor->setValue("med_jabatan#{$i}", $m->jabatan ?? '-');
            }
        } else {
            $templateProcessor->setValue("no_medis#1", '-');
            $templateProcessor->setValue("med_nama#1", '-');
            $templateProcessor->setValue("med_nip#1", '-');
            $templateProcessor->setValue("med_jabatan#1", '-');
        }

        // --- MAPPING TIM HUKUM ---
        $hukum = $asesmen->anggotaTim->where('kategori', 'hukum')->values();
        $templateProcessor->cloneBlock('block_hukum', max(count($hukum), 1), true, true);
        if (count($hukum) > 0) {
            foreach ($hukum as $index => $m) {
                $i = $index + 1;
                $templateProcessor->setValue("no_hukum#{$i}", $i);
                $templateProcessor->setValue("huk_nama#{$i}", $m->nama);
                $templateProcessor->setValue("huk_pangkat#{$i}", $m->pangkat ?? '-');
                $templateProcessor->setValue("huk_nip#{$i}", $m->nip_nrp_sip ?? '-');
                $templateProcessor->setValue("huk_jabatan#{$i}", $m->jabatan ?? '-');
            }
        } else {
            $templateProcessor->setValue("no_hukum#1", '-');
            $templateProcessor->setValue("huk_nama#1", '-');
            $templateProcessor->setValue("huk_pangkat#1", '-');
            $templateProcessor->setValue("huk_nip#1", '-');
            $templateProcessor->setValue("huk_jabatan#1", '-');
        }

        // --- MAPPING TEKS ASESMEN & KESIMPULAN ---
        $templateProcessor->setValue('narasi_medis', $asesmen->narasi_medis ?? '-');
        $templateProcessor->setValue('narasi_hukum', $asesmen->narasi_hukum ?? '-');
        $templateProcessor->setValue('alat_bukti_no_sk', $asesmen->alat_bukti_no_sk ?? '-');
        $templateProcessor->setValue('alat_bukti_dokter', $asesmen->alat_bukti_dokter ?? '-');
        $templateProcessor->setValue('alat_bukti_hasil', $asesmen->alat_bukti_hasil ?? '-');
        $templateProcessor->setValue('status_klien', $asesmen->status_klien ?? 'penyalahguna');
        $templateProcessor->setValue('kesimpulan_jenis_zat', $asesmen->kesimpulan_jenis_zat ?? '-');
        $templateProcessor->setValue('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai ?? '-');
        $templateProcessor->setValue('kesimpulan_kategori', $asesmen->kesimpulan_kategori ?? '-');
        $templateProcessor->setValue('diagnosis_medis', $asesmen->diagnosis_medis ?? '-');
        $templateProcessor->setValue('rekomendasi_tempat_rehab', $tempatBersih);
        $templateProcessor->setValue('rekomendasi_durasi', $asesmen->rekomendasi_durasi ?? '-');
        $templateProcessor->setValue('rekomendasi_keterangan', $asesmen->rekomendasi_keterangan ?? '-');

        // --- PROSES UNDUH FILE ---
        $fileName = 'Berita_Acara_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Tampilan Halaman Generator Surat Rekomendasi
     */
    public function rekomendasi(string $id)
    {
        if (!Gate::allows('akses-dokumen')) {
            abort(403, 'Anda tidak memiliki akses ke Surat Rekomendasi.');
        }

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

    /**
     * Memproses & Generate Surat Rekomendasi Word
     */
    public function unduhRekomendasi(Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) {
            abort(403, 'Anda tidak memiliki akses ke Surat Rekomendasi.');
        }

        // 1. CARI DATA KLIEN
        $asesmen = Asesmen::with(['rekomendasi', 'narkotika'])->findOrFail($id);

        // 2. SIMPAN MANUAL (Lebih Kuat & Anti-Silent Failure)
        $asesmen->no_surat_rekomendasi = $request->input('no_surat_rekomendasi');
        $asesmen->tgl_rekomendasi = $request->input('tgl_rekomendasi');
        $asesmen->kepada_yth = $request->input('kepada_yth');
        $asesmen->no_keputusan = $request->input('no_keputusan');
        $asesmen->tgl_keputusan = $request->input('tgl_keputusan');
        $asesmen->tentang_permohonan = $request->input('tentang_permohonan');
        $asesmen->kewarganegaraan = $request->input('kewarganegaraan');
        $asesmen->nama_narkotika_medis = $request->input('nama_narkotika_medis');
        $asesmen->lama_perawatan = $request->input('lama_perawatan');
        $asesmen->keterangan_diagnosis = $request->input('keterangan_diagnosis');

        // Simpan input tempat rekomendasi rehab
        $asesmen->rekomendasi_tempat_rehab = $request->input('rekomendasi_tempat_rehab');

        // --- SINKRONISASI KE HALAMAN SHOW ---
        // Jika Rekomendasi di-save, nilai durasi di Berita Acara ikut disamakan
        $asesmen->rekomendasi_durasi = $request->input('lama_perawatan');
        // ------------------------------------

        // EKSEKUSI SIMPAN KE DATABASE
        $asesmen->save();

        // 3. CEK AKSI TOMBOL YANG DIKLIK
        if ($request->input('action') === 'save') {
            return redirect()->route('asesmen.show', $asesmen->id)
                ->with('success', 'Data Form Rekomendasi berhasil disimpan!');
        }

        // 4. JIKA KLIK "SIMPAN & UNDUH", PROSES WORD BERJALAN
        $templatePath = storage_path('app/templates/template_rekomendasi.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template Word tidak ditemukan.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // --- PEMOTONGAN STRING (STRING PARSING) UNTUK TEMPAT REHABILITASI ---
        $rawTempatRek = $asesmen->rekomendasi_tempat_rehab ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '-';
        $tempatBersihRek = $rawTempatRek;

        if (str_starts_with($rawTempatRek, 'Rawat Jalan - ')) {
            $tempatBersihRek = trim(str_replace('Rawat Jalan - ', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Rawat Inap - ')) {
            $tempatBersihRek = trim(str_replace('Rawat Inap - ', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Rehab di Lapas / Rutan - ')) {
            $tempatBersihRek = trim(str_replace('Rehab di Lapas / Rutan - ', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Tidak Rehab (Proses Hukum) - ')) {
            $tempatBersihRek = trim(str_replace('Tidak Rehab (Proses Hukum) - ', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Rawat Jalan')) {
            $tempatBersihRek = trim(str_replace('Rawat Jalan', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Rawat Inap')) {
            $tempatBersihRek = trim(str_replace('Rawat Inap', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Rehab di Lapas / Rutan')) {
            $tempatBersihRek = trim(str_replace('Rehab di Lapas / Rutan', '', $rawTempatRek));
        } elseif (str_starts_with($rawTempatRek, 'Tidak Rehab (Proses Hukum)')) {
            $tempatBersihRek = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawTempatRek));
        }

        $tempatBersihRek = empty($tempatBersihRek) ? 'Tanpa Instansi' : trim(ltrim($tempatBersihRek, ' -'));
        // ----------------------------------------------------------------------

        // Mapping Data Input Manual
        $templateProcessor->setValue('no_surat_rekomendasi', $asesmen->no_surat_rekomendasi ?? '-');
        $templateProcessor->setValue('tgl_rekomendasi', $asesmen->tgl_rekomendasi ? \Carbon\Carbon::parse($asesmen->tgl_rekomendasi)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('kepada_yth', $asesmen->kepada_yth ?? '-');
        $templateProcessor->setValue('no_keputusan', $asesmen->no_keputusan ?? '-');
        $templateProcessor->setValue('tgl_keputusan', $asesmen->tgl_keputusan ? \Carbon\Carbon::parse($asesmen->tgl_keputusan)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('tentang_permohonan', $asesmen->tentang_permohonan ?? '-');
        $templateProcessor->setValue('kewarganegaraan', $asesmen->kewarganegaraan ?? 'Indonesia (WNI)');
        $templateProcessor->setValue('nama_narkotika', $asesmen->nama_narkotika_medis ?? '-');
        $templateProcessor->setValue('keterangan_diagnosis', $asesmen->keterangan_diagnosis ?? '-');
        $templateProcessor->setValue('lama_perawatan', $asesmen->lama_perawatan ?? '-');

        // Mapping Data Otomatis dari Database
        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap);
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
        $templateProcessor->setValue('hari', $hari_pelaksanaan);
        $templateProcessor->setValue('jenis_narkotika', $asesmen->narkotika->jenis_narkotika ?? '-');
        $templateProcessor->setValue('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan ?? '-');

        $templateProcessor->setValue('rekomendasi_tat', $tempatBersihRek);

        // Proses Unduh File
        $fileName = 'Surat_Rekomendasi_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp_' . $fileName);

        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export data ke Excel berdasarkan filter aktif
     */
    /**
     * Export data ke Excel berdasarkan filter aktif
     */
    public function exportExcel(Request $request)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $type = $request->get('type', 'default');

        // JIKA MENEKAN TOMBOL FORMAT CASE CONFERENCE BARU
        if ($type === 'cc') {
            $namaFile = 'Data_Case_Conference_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new \App\Exports\AsesmenCCExport($request), $namaFile);
        }
        // JIKA MENEKAN TOMBOL FORMAT REKAP TAT BARU
        elseif ($type === 'rekap') {
            $namaFile = 'Rekap_Data_TAT_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new \App\Exports\AsesmenRekapExport($request), $namaFile);
        }

        // FUNGSI LAMA TETAP DIPERTAHANKAN (TIDAK ADA YANG DIUBAH)
        $namaFile = 'Export_Asesmen_TAT_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new AsesmenExport($request), $namaFile);
    }

    /**
     * Memperbarui "Tanggal Ditambahkan" (created_at) dari halaman Index
     */
    public function updateTanggal(Request $request, string $id)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        $request->validate([
            'tanggal_ditambahkan' => 'required|date',
        ]);

        $asesmen = Asesmen::findOrFail($id);

        $jamAsli = $asesmen->created_at ? $asesmen->created_at->format('H:i:s') : '00:00:00';
        $asesmen->created_at = $request->tanggal_ditambahkan . ' ' . $jamAsli;

        $asesmen->save();

        return redirect()->back()->with('success', 'Tanggal klien ditambahkan berhasil diperbarui!');
    }

    /**
     * Menghapus master data Pendidikan
     */
    public function destroyPendidikan(string $id)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        try {
            $pendidikan = \App\Models\Pendidikan::findOrFail($id);
            $pendidikan->delete();

            return redirect()->back()->with('success', 'Pilihan Pendidikan berhasil dihapus secara permanen dari sistem!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->back()->with('error', 'Gagal menghapus! Pilihan pendidikan ini tidak bisa dihapus karena pilihan ini sudah digunakan dalam penambahan data Klien.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus master data Rekomendasi TAT
     */
    public function destroyRekomendasi(string $id)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.');
        }

        try {
            $rekomendasi = \App\Models\Rekomendasi::findOrFail($id);
            $rekomendasi->delete();

            return redirect()->back()->with('success', 'Opsi Rekomendasi berhasil dihapus secara permanen dari sistem!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->back()->with('error', 'Gagal menghapus! Pilihan rekomendasi ini tidak bisa dihapus karena sudah digunakan dalam data Klien.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}