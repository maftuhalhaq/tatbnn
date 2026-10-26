<?php

namespace App\Imports;

use App\Models\Asesmen;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsesmenCaseConfImport implements ToModel, WithStartRow
{
    public function startRow(): int { return 1; }

    private function parseJenisKelamin($value) {
        $val = strtoupper(trim((string)$value));
        if (in_array($val, ['L', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA'])) return 'L';
        if (in_array($val, ['P', 'PEREMPUAN', 'WANITA'])) return 'P';
        return '-';
    }

    public function model(array $row)
    {
        $row = array_map(function($value) { return is_string($value) ? trim($value) : $value; }, $row);
        if (!isset($row[1]) || trim((string)$row[1]) === '') return null;

        $kolomA = strtoupper(trim((string)($row[0] ?? '')));
        if (strpos($kolomA, 'NO') !== false || strpos($kolomA, 'DATA CASE CONFERENCE') !== false) return null;

        $namaLengkap = isset($row[1]) ? mb_strtoupper($row[1], 'UTF-8') : '-';
        $usiaString = explode('.', (string)($row[2] ?? ''))[0];
        $usiaBersih = preg_replace('/[^0-9]/', '', $usiaString);
        $usiaValue = $usiaBersih !== '' ? (int)$usiaBersih : null;
        $jenisKelamin = $this->parseJenisKelamin($row[3]);

        // ===================================================================
        // BLOK PENCEGAH DUPLIKASI (ANTI-DOUBLE IMPORT CASE CONFERENCE)
        // ===================================================================
        // Karena tidak punya NIK, kita mengecek keunikan dari kombinasi Nama, Usia, dan Jenis Kelamin
        $isDuplicate = Asesmen::where('nama_lengkap', $namaLengkap)
            ->where('usia', $usiaValue)
            ->where('jenis_kelamin', $jenisKelamin)
            ->exists();

        if ($isDuplicate) {
            // Jika data sudah ada di database, langsung lewati (skip) baris ini
            return null;
        }

        $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => trim($row[4] ?? '-') ?: '-']);
        $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => trim($row[5] ?? '-') ?: '-']);
        $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => trim($row[20] ?? '-') ?: '-']);

        return new Asesmen([
            'nik' => 'CC' . time() . rand(10, 99) . rand(10, 99), // Dummy NIK

            'nama_lengkap' => $namaLengkap,
            'usia' => $usiaValue,
            'jenis_kelamin' => $jenisKelamin,

            'pekerjaan_id' => $pekerjaan->id,
            'pendidikan_id' => $pendidikan->id,
            'rekomendasi_id' => $rekomendasi->id,

            'alamat_domisili' => $row[6] ?? '-', 'status_hukum' => $row[7] ?? '-',
            'keterlibatan_jaringan' => $row[8] ?? '-', 'deskripsi_bb' => $row[9] ?? '-',
            'cara_mendapatkan' => $row[10] ?? '-', 'dapat_dari_siapa' => $row[11] ?? '-',
            'kesehatan_fisik' => $row[12] ?? '-', 'psikologi' => $row[13] ?? '-',
            'tes_urine' => $row[14] ?? '-', 'alasan_penggunaan' => $row[15] ?? '-',
            'kondisi_keluarga' => $row[16] ?? '-', 'tingkat_ketergantungan' => $row[17] ?? '-',
            'pola_pemakaian' => $row[18] ?? '-', 'kondisi_lingkungan' => $row[19] ?? '-',
            'keterangan_tambahan' => $row[21] ?? '-',

            'saran_1' => $row[22] ?? null, 'saran_2' => $row[23] ?? null,
            'saran_3' => $row[24] ?? null, 'saran_4' => $row[25] ?? null,

            'no_bln' => '-', 'asal_pengajuan' => '-', 'no_surat_pengajuan' => '-',
            'no_lkn' => '-', 'no_register' => '-', 'alamat_ktp' => '-',
            'tempat_lahir' => '-', 'hasil_asesmen_hukum' => '-',
            'hasil_asesmen_medis' => '-', 'pasal_sangkaan' => '-',
        ]);
    }
}
