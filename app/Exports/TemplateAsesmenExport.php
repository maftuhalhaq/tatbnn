<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TemplateAsesmenExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    /**
     * Menyediakan baris kosong contoh agar pengguna tahu mulai dari mana
     */
    public function array(): array
    {
        return [
            ['REG/001/2026', '01/VIII', 'Polres', 'SPRIN/123/2026', 'LKN/456/2026', '2026-08-01', '2026-08-02', '2026-08-05', '2026-07-30', 'Budi Santoso', '3573001122334455', '08123456789', 'Malang', '1990-01-01', 'L', 'WNI', 'Islam', 'SMA', 'Swasta', '3000000', 'Jl. Merdeka 1', 'Jl. Merdeka 1', 'Sabu', '2.5', 'Tersangka', '1 klip plastik kecil', 'Pasal 112', 'Tidak', 'Positif Sabu', 'Membeli', 'Teman', '...', '...', 'Rawat Inap', 'TIDAK', '...']
        ];
    }

    /**
     * Struktur Header Kolom
     */
    public function headings(): array
    {
        return [
            // Baris 1: Judul Utama
            ['TEMPLATE IMPORT DATA ASESMEN TERPADU (TAT) BNN'],
            // Baris 2: Panduan Pengisian
            ['PANDUAN: Isi data mulai dari baris ke-4. Jangan mengubah susunan baris 1-3. Kolom dengan tanda bintang (*) wajib diisi. Format tanggal harus YYYY-MM-DD.'],
            // Baris 3: Nama-nama Kolom (Sesuai dengan form Create)
            [
                // --- 1. Administrasi ---
                'No. Register',
                'No / BLN',
                'Asal Pengajuan',
                'No. Surat Pengajuan',
                'No. LKN / LP / LI',
                'Tgl Surat (YYYY-MM-DD)',
                'Tgl Berkas (YYYY-MM-DD)',
                'Tgl Pelaksanaan (YYYY-MM-DD)',
                'Tgl Tangkap (YYYY-MM-DD)',
                // --- 2. Identitas ---
                'Nama Lengkap (*)',
                'NIK (*)',
                'No. HP',
                'Tempat Lahir',
                'Tgl Lahir (YYYY-MM-DD)',
                'Jenis Kelamin (L/P) (*)',
                'Kewarganegaraan',
                'Agama',
                'Pendidikan',
                'Pekerjaan',
                'Penghasilan',
                'Alamat KTP',
                'Alamat Domisili',
                // --- 3. Perkara & BB ---
                'Jenis Narkotika',
                'Berat BB (Gram)',
                'Status Hukum',
                'Deskripsi BB',
                'Pasal Sangkaan',
                'Terlibat Jaringan (Ya/Tidak)',
                'Hasil Tes Urine',
                'Cara Mendapatkan',
                'Dapat Dari Siapa',
                // --- 4. TAT Mentah ---
                'Asesmen Hukum (Mentah)',
                'Asesmen Medis (Mentah)',
                'Rekomendasi TAT',
                'Pelaksanaan (YA/TIDAK)',
                'Ket. Tambahan TAT',
                // --- 5. Case Conference (CC) ---
                'Analisis Hukum (CC)',
                'Analisis Medis (CC)',
                'Kesehatan Fisik',
                'Psikologi',
                'Alasan Penggunaan',
                'Kondisi Keluarga',
                'Tingkat Ketergantungan',
                'Pola Pemakaian',
                'Kondisi Lingkungan',
                'Saran Case Conference'
            ]
        ];
    }

    /**
     * Styling Cell & Header
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style Baris 1 (Judul Besar)
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF1E3A8A']], // Biru Tua
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ],
            // Style Baris 2 (Panduan)
            2 => [
                'font' => ['italic' => true, 'size' => 11, 'color' => ['argb' => Color::COLOR_RED]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFFFFBEB']], // Kuning Muda
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ],
            // Style Baris 3 (Header Kolom)
            3 => [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
        ];
    }

    /**
     * Menerapkan warna khusus per seksi (Administrasi, Identitas, dll) & Freeze Panes
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cell untuk judul dan panduan
                $sheet->mergeCells('A1:AT1');
                $sheet->mergeCells('A2:AT2');

                // Atur tinggi baris
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(35);

                // Kunci baris 1-3 agar tidak ikut ter-scroll (Freeze Panes)
                $sheet->freezePane('A4');

                // Mewarnai Header (Baris 3) berdasarkan Kategori/Seksi
                $warnaHeader = [
                    'A3:I3' => 'FF3B82F6', // Seksi 1: Administrasi (Blue)
                    'J3:V3' => 'FF10B981', // Seksi 2: Identitas (Emerald/Hijau)
                    'W3:AE3' => 'FFF59E0B', // Seksi 3: Perkara & BB (Amber/Kuning)
                    'AF3:AJ3' => 'FF6366F1', // Seksi 4: Asesmen TAT (Indigo)
                    'AK3:AT3' => 'FF8B5CF6', // Seksi 5: Case Conference (Purple)
                ];

                foreach ($warnaHeader as $range => $warna) {
                    $sheet->getStyle($range)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($warna);
                }

                // Memberi format teks wrap agar isi contoh (baris 4) rapi
                $sheet->getStyle('A4:AT100')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A4:AT100')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            },
        ];
    }
}