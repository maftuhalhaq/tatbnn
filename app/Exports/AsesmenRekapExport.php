<?php

namespace App\Exports;

use App\Models\Asesmen;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsesmenRekapExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell, WithTitle, WithEvents
{
    protected $request;
    protected $rowNumber = 1;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        if ($this->request->get('search') === 'XXXXXXXXX_EMPTY_TEMPLATE_XXXXXXXXX') {
            return Asesmen::whereNull('id'); // Query kosong untuk template
        }

        $query = Asesmen::query()->with(['pendidikan', 'pekerjaan', 'narkotika', 'rekomendasi']);

        // Logika Filter (Sama seperti Controller)
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_register', 'like', "%{$search}%");
            });
        }

        if ($this->request->filled('tanggal')) {
            $query->whereDate('created_at', $this->request->tanggal);
        }

        if ($this->request->filled('bulan')) {
            $query->whereMonth('created_at', $this->request->bulan);
        }

        if ($this->request->filled('tahun')) {
            $query->whereYear('created_at', $this->request->tahun);
        }

        $sort = $this->request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function title(): string
    {
        return 'Rekap Data TAT';
    }

    public function headings(): array
    {
        return [
            ['REKAP DATA TAT BNNP DAN JAJARAN TAHUN ' . date('Y')], // Baris 1
            [], // Baris 2 kosong (Sesuai Template)
            // Baris 3: Header Utama (Bagian atas)
            [
                'NO.', 'NO/ BLN', 'ASAL PENGAJUAN', 'TANGGAL SURAT', 'TANGGAL BERKAS DITERIMA',
                'TANGGAL PELAKSANAAN', 'NO SURAT PENGAJUAN', 'NO LKN / LP/LI', 'TGL PENANGKAPAN',
                'NAMA', 'NO REGISTER', 'ALAMAT KTP', 'ALAMAT DOMISILI', 'TEMPAT LAHIR', 'TANGGAL LAHIR',
                'JENIS KELAMIN (L/P)', 'USIA', 'PENDIDIKAN', 'PEKERJAAN', 'NO HANDPHONE', 'NIK',
                'JENIS NARKOTIKA', 'BERAT (gr)', 'PASAL YANG DISANGKAKAN', 'HASIL ASESMEN HUKUM',
                'HASIL ASESMEN MEDIS', 'REKOMENDASI TAT', '', '', '', 'PELAKSANAAN REKOMENDASI', '', 'KET'
            ],
            // Baris 4: Sub-Header Level 1 (Khusus untuk kolom yang dipecah)
            [
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                'REHAB DI LEMBAGA REHAB', '', 'REHAB DI LAPAS / RUTAN', 'TIDAK REHAB (PROSES HUKUM)',
                'DILAKSANAKAN', 'TIDAK DILAKSANAKAN', ''
            ],
            // Baris 5: Sub-Header Level 2 (Khusus untuk Rawat Jalan / Inap)
            [
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                'RAWAT JALAN', 'RAWAT INAP', '', '', '', '', ''
            ]
        ];
    }

    public function map($asesmen): array
    {
        // 1. Parsing Tanggal
        $tgl_surat = $asesmen->tgl_surat ? Carbon::parse($asesmen->tgl_surat)->format('d F Y') : '';
        $tgl_berkas = $asesmen->tgl_berkas ? Carbon::parse($asesmen->tgl_berkas)->format('d F Y') : '';
        $tgl_pelaksanaan = $asesmen->tgl_pelaksanaan ? Carbon::parse($asesmen->tgl_pelaksanaan)->format('d F Y') : '';
        $tgl_tangkap = $asesmen->tgl_tangkap ? Carbon::parse($asesmen->tgl_tangkap)->format('d F Y') : '';
        $tgl_lahir = $asesmen->tgl_lahir ? Carbon::parse($asesmen->tgl_lahir)->format('d F Y') : '';

        // 2. Format Berat (Menambahkan ' gr')
        $berat_bb = '-';
        if ($asesmen->berat_bb !== null && $asesmen->berat_bb !== '') {
            $berat_bb = $asesmen->berat_bb . ' gr';
        }

        // 3. Logika Pemecahan Kolom Rekomendasi (Menuliskan Nama Instansi, Bukan Sekadar 'V')
        $rawatJalan = '';
        $rawatInap = '';
        $lapasRutan = '';
        $tidakRehab = '';

        $rekomendasiRaw = $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '';

        if (str_starts_with($rekomendasiRaw, 'Rawat Jalan')) {
            $instansi = trim(str_replace('Rawat Jalan -', '', $rekomendasiRaw));
            // Jika dia hanya terdeteksi "Rawat Jalan" tanpa nama tempat, isikan 'YA'
            $rawatJalan = empty($instansi) || $instansi == 'Rawat Jalan' ? 'YA' : $instansi;
        } elseif (str_starts_with($rekomendasiRaw, 'Rawat Inap')) {
            $instansi = trim(str_replace('Rawat Inap -', '', $rekomendasiRaw));
            $rawatInap = empty($instansi) || $instansi == 'Rawat Inap' ? 'YA' : $instansi;
        } elseif (str_starts_with($rekomendasiRaw, 'Rehab di Lapas / Rutan')) {
            $instansi = trim(str_replace('Rehab di Lapas / Rutan -', '', $rekomendasiRaw));
            $lapasRutan = empty($instansi) || $instansi == 'Rehab di Lapas / Rutan' ? 'YA' : $instansi;
        } elseif (str_starts_with($rekomendasiRaw, 'Tidak Rehab (Proses Hukum)')) {
            $instansi = trim(str_replace('Tidak Rehab (Proses Hukum) -', '', $rekomendasiRaw));
            $tidakRehab = empty($instansi) || $instansi == 'Tidak Rehab (Proses Hukum)' ? 'YA' : $instansi;
        }

        // 4. Logika Pemecahan Kolom Pelaksanaan ('YA' dan 'TIDAK' bukan 'V')
        $dilaksanakan = '';
        $tidakDilaksanakan = '';

        if ($asesmen->pelaksanaan === 'YA') {
            $dilaksanakan = 'YA';
        } elseif ($asesmen->pelaksanaan === 'TIDAK') {
            $tidakDilaksanakan = 'TIDAK';
        }

        return [
            $this->rowNumber++,
            $asesmen->no_bln ?? '',
            $asesmen->asal_pengajuan ?? '',
            $tgl_surat,
            $tgl_berkas,
            $tgl_pelaksanaan,
            $asesmen->no_surat_pengajuan ?? '',
            $asesmen->no_lkn ?? '',
            $tgl_tangkap,
            $asesmen->nama_lengkap ?? '',
            $asesmen->no_register ?? '',
            $asesmen->alamat_ktp ?? '',
            $asesmen->alamat_domisili ?? '',
            $asesmen->tempat_lahir ?? '',
            $tgl_lahir,
            $asesmen->jenis_kelamin ?? '',
            $asesmen->usia ?? '',
            $asesmen->pendidikan->nama_pendidikan ?? $asesmen->pendidikan_input ?? '',
            $asesmen->pekerjaan->nama_pekerjaan ?? $asesmen->pekerjaan_input ?? '',
            "'" . ($asesmen->no_hp ?? ''), // Penanganan No Handphone agar dibaca sbg String di Excel (mencegah 0 di depan hilang)
            "'" . ($asesmen->nik ?? ''), // Penanganan NIK agar dibaca sbg String (mencegah E+ notation di Excel)
            $asesmen->narkotika->jenis_narkotika ?? $asesmen->narkotika_input ?? '',
            $berat_bb,
            $asesmen->pasal_sangkaan ?? '',
            $asesmen->hasil_asesmen_hukum ?? '',
            $asesmen->hasil_asesmen_medis ?? '',

            // Output dari Pemecahan Rekomendasi
            $rawatJalan,
            $rawatInap,
            $lapasRutan,
            $tidakRehab,

            // Output dari Pemecahan Pelaksanaan
            $dilaksanakan,
            $tidakDilaksanakan,

            // Keterangan Tambahan
            $asesmen->keterangan_tambahan ?? 'Belum ada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // MERGE TITLE (Baris 1)
                $sheet->mergeCells('A1:AG2');

                // ==========================================
                // MERGE CELL UNTUK HEADER & SUB-HEADER (Baris 3-5)
                // ==========================================
                // Merge secara vertikal (kolom yang tidak punya sub-kategori di baris 4 dan 5)
                $colToMergeVertical = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AG'];
                foreach ($colToMergeVertical as $col) {
                    $sheet->mergeCells($col.'3:'.$col.'5');
                }

                // Merge Sub-Header REKOMENDASI TAT (Kolom AA sampai AD)
                $sheet->mergeCells('AA3:AD3'); // Header Utama
                $sheet->mergeCells('AA4:AB4'); // Sub Header 'REHAB DI LEMBAGA REHAB'
                $sheet->mergeCells('AC4:AC5'); // Sub Header 'REHAB DI LAPAS / RUTAN' (Merge s/d baris 5)
                $sheet->mergeCells('AD4:AD5'); // Sub Header 'TIDAK REHAB (PROSES HUKUM)' (Merge s/d baris 5)

                // Merge Sub-Header PELAKSANAAN REKOMENDASI (Kolom AE sampai AF)
                $sheet->mergeCells('AE3:AF4'); // Header Utama memakan baris 3 & 4
                // Baris 5 nya terisi DILAKSANAKAN (AE5) dan TIDAK DILAKSANAKAN (AF5) secara otomatis oleh array headings.

                // ==========================================
                // STYLING FONT & WARNA
                // ==========================================
                // Style Judul (A1)
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9D9D9'] // Abu-abu muda
                    ],
                ]);

                // Style Header Tabel (Baris 3 s/d 5) -> Menggunakan warna Hijau Pudar (D5E8D4)
                $headerStyle = [
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD5E8D4'] // Hex Code untuk hijau pucat di Excel
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000']
                        ]
                    ]
                ];
                $sheet->getStyle('A3:AG5')->applyFromArray($headerStyle);

                // Style Area Data (Baris 6 ke bawah)
                if ($highestRow > 5) {
                    $sheet->getStyle('A6:AG' . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000']
                            ]
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ]
                    ]);

                    // Center Align untuk Kolom Spesifik di Area Data
                    $sheet->getStyle('A6:K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('N6:Q' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tempat, Tgl Lahir, JK, Usia
                    $sheet->getStyle('R6:W' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Pendidikan s/d Berat(gr)
                    $sheet->getStyle('AA6:AG' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Rekomendasi s/d Keterangan
                }

                // ==========================================
                // PENGATURAN LEBAR KOLOM (COLUMN DIMENSIONS)
                // ==========================================
                $sheet->getColumnDimension('J')->setWidth(25); // Nama
                $sheet->getColumnDimension('K')->setWidth(20); // No Register
                $sheet->getColumnDimension('L')->setWidth(30); // Alamat KTP
                $sheet->getColumnDimension('M')->setWidth(30); // Alamat Domisili
                $sheet->getColumnDimension('T')->setWidth(20); // No HP
                $sheet->getColumnDimension('U')->setWidth(20); // NIK
                $sheet->getColumnDimension('X')->setWidth(35); // Pasal
                $sheet->getColumnDimension('Y')->setWidth(35); // Asesmen Hukum
                $sheet->getColumnDimension('Z')->setWidth(35); // Asesmen Medis
                $sheet->getColumnDimension('AA')->setWidth(20); // Rawat Jalan
                $sheet->getColumnDimension('AB')->setWidth(20); // Rawat Inap
                $sheet->getColumnDimension('AC')->setWidth(20); // Lapas
                $sheet->getColumnDimension('AD')->setWidth(20); // Tidak Rehab
                $sheet->getColumnDimension('AG')->setWidth(25); // Keterangan
            },
        ];
    }
}
