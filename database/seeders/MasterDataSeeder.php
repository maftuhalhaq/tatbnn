<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Narkotika;
use App\Models\Rekomendasi;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Referensi Pendidikan
        $pendidikan = [
            'Tidak Sekolah',
            'SD',
            'SMP',
            'SMA/SMK',
            'Diploma',
            'S1',
            'S2',
            'S3'
        ];

        foreach ($pendidikan as $item) {
            Pendidikan::create([
                'nama_pendidikan' => $item
            ]);
        }

        // 2. Data Referensi Pekerjaan
        $pekerjaan = [
            'Pelajar/Mahasiswa',
            'Karyawan Swasta',
            'Wiraswasta',
            'PNS/TNI/POLRI',
            'Buruh Harian Lepas',
            'Belum/Tidak Bekerja'
        ];

        foreach ($pekerjaan as $item) {
            Pekerjaan::create([
                'nama_pekerjaan' => $item
            ]);
        }

        // 3. Data Referensi Jenis Narkotika
        $narkotika = [
            'Sabu',
            'Ganja',
            'Ekstasi',
            'Heroin',
            'Kokain',
            'Tembakau Gorilla',
            'Obat Keras (Pil Koplo)'
        ];

        foreach ($narkotika as $item) {
            Narkotika::create([
                'jenis_narkotika' => $item
            ]);
        }

        // 4. Data Referensi Rekomendasi
        $rekomendasi = [
            'Yayasan Nawasena Arsa Indonesia Lawang Kab. Malang',
            'RSJ Dr. Radjiman Wediodiningrat Lawang',
            'Klinik Pratama BNN',
            'Rawat Jalan',
            'Proses Hukum Lanjut (Tanpa Rehab)'
        ];

        foreach ($rekomendasi as $item) {
            Rekomendasi::create([
                'tempat_rehabilitasi' => $item
            ]);
        }
    }
}