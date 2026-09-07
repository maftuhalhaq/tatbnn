<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterAnggota;

class MasterAnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = [
            // --- TIM MEDIS ---
            [
                'nama' => 'dr. Evi Desi Puspa Lis Santy',
                'nip_nrp_sip' => '198406152025212053',
                'pangkat' => null,
                'jabatan' => 'Dokter Ahli Pertama',
                'kategori' => 'medis',
            ],
            [
                'nama' => 'dr. Agustina',
                'nip_nrp_sip' => '440.03/0920/35.73.406/2021',
                'pangkat' => null,
                'jabatan' => 'Dokter Praktik Mandiri',
                'kategori' => 'medis',
            ],

            // --- TIM HUKUM ---
            [
                'nama' => 'Dedi Firmansya H., S.H.',
                'nip_nrp_sip' => '198509052012121002',
                'pangkat' => 'Penata / IIIc',
                'jabatan' => 'Penyidik Ahli Muda BNN Kab. Malang',
                'kategori' => 'hukum',
            ],
            [
                'nama' => 'Ferry Satria Digdo Anjani',
                'nip_nrp_sip' => '89010462',
                'pangkat' => 'Ipda',
                'jabatan' => 'KBO Satresnarkoba Polres Malang',
                'kategori' => 'hukum',
            ],
            [
                'nama' => 'Berdi Despar Magrhobi, S.H.',
                'nip_nrp_sip' => '199212242019021005',
                'pangkat' => 'Jaksa Muda',
                'jabatan' => 'Kasubsi Pra Tut Kejari Kab. Malang',
                'kategori' => 'hukum',
            ],
        ];

        foreach ($anggota as $item) {
            MasterAnggota::create($item);
        }
    }
}