<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterOpsi;

class MasterOpsiSeeder extends Seeder
{
    public function run(): void
    {
        $opsi = [
            // --- OPSI ZAT POSITIF ---
            ['kategori' => 'zat', 'nilai' => 'Amphetamine (Amfetamin)'],
            ['kategori' => 'zat', 'nilai' => 'Methamphetamine (Metamfetamin)'],
            ['kategori' => 'zat', 'nilai' => 'Tetrahydrocannabinol'],
            ['kategori' => 'zat', 'nilai' => 'Benzodiazepines (Benzodiazepin)'],

            // --- OPSI TEMPAT REHABILITASI ---
            ['kategori' => 'tempat_rehab', 'nilai' => 'Yayasan NAWASENA ARSA INDONESIA'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'Yayasan Rehabilitasi Sahwahita Nusantara'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'Rehabilitasi NAPZA PLATO Foundation'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'Ashefa Griya Pusaka'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'Rumah Sakit Jiwa "RSJ" Dr Radjiman Wediodiningrat LAWANG'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'Rumah Sakit Jiwa Menur'],
            ['kategori' => 'tempat_rehab', 'nilai' => 'HMC Hayunanto Medical Center'],
        ];

        foreach ($opsi as $item) {
            MasterOpsi::create($item);
        }
    }
}