<?php

namespace App\Imports;

use App\Models\Asesmen;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsesmenImport implements ToModel, WithStartRow
{
    /**
     * Data di Excel dimulai dari baris ke-4
     */
    public function startRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        // Skip/Abaikan baris jika Nama Lengkap atau NIK kosong
        if (!isset($row[9]) || trim($row[9]) === '' || !isset($row[10])) {
            return null;
        }

        // 1. Cek & Ambil ID Pendidikan (Aman dari error)
        $pendidikanId = null;
        if (!empty($row[17])) {
            try {
                $pendidikan = DB::table('m_pendidikan')->where('nama_pendidikan', 'like', '%' . trim($row[17]) . '%')->first();
                if ($pendidikan) {
                    $pendidikanId = $pendidikan->id;
                } else {
                    $pendidikanId = DB::table('m_pendidikan')->insertGetId(['nama_pendidikan' => trim($row[17])]);
                }
            } catch (\Exception $e) {
                $pendidikanId = null;
            }
        }

        // 2. Cek & Ambil ID Pekerjaan (Aman dari error)
        $pekerjaanId = null;
        if (!empty($row[18])) {
            try {
                $pekerjaan = DB::table('m_pekerjaan')->where('nama_pekerjaan', 'like', '%' . trim($row[18]) . '%')->first();
                if ($pekerjaan) {
                    $pekerjaanId = $pekerjaan->id;
                } else {
                    $pekerjaanId = DB::table('m_pekerjaan')->insertGetId(['nama_pekerjaan' => trim($row[18])]);
                }
            } catch (\Exception $e) {
                $pekerjaanId = null;
            }
        }

        // 3. Cek & Ambil ID Narkotika (SANGAT AMAN DARI ERROR TABEL HILANG)
        $narkotikaId = null;
        if (!empty($row[22])) {
            try {
                // Mencoba tabel master_narkotikas
                $narkotika = DB::table('master_narkotikas')->where('jenis_narkotika', 'like', '%' . trim($row[22]) . '%')->first();
                $narkotikaId = $narkotika ? $narkotika->id : null;
            } catch (\Exception $e) {
                try {
                    // Fallback: Jika nama tabelnya ternyata m_narkotika
                    $narkotika = DB::table('m_narkotika')->where('jenis_narkotika', 'like', '%' . trim($row[22]) . '%')->first();
                    $narkotikaId = $narkotika ? $narkotika->id : null;
                } catch (\Exception $e2) {
                    // Jika tetap gagal, biarkan kosong agar import 20 data tetap BERHASIL
                    $narkotikaId = null;
                }
            }
        }

        // Kembalikan Data ke Model Asesmen
        return new Asesmen([
            // --- Administrasi ---
            'no_register' => $row[0] ?? null,
            'no_bln' => $row[1] ?? null,
            'asal_pengajuan' => $row[2] ?? null,
            'no_surat_pengajuan' => $row[3] ?? null,
            'no_lkn' => $row[4] ?? null,
            'tgl_surat' => $this->transformDate($row[5]),
            'tgl_berkas' => $this->transformDate($row[6]),
            'tgl_pelaksanaan' => $this->transformDate($row[7]),
            'tgl_tangkap' => $this->transformDate($row[8]),

            // --- Identitas ---
            'nama_lengkap' => $row[9],
            'nik' => $row[10],
            'no_hp' => $row[11] ?? null,
            'tempat_lahir' => $row[12] ?? null,
            'tgl_lahir' => $this->transformDate($row[13]),
            'jenis_kelamin' => $row[14] ?? null,
            'kewarganegaraan' => $row[15] ?? null,
            'agama' => $row[16] ?? null,
            'pendidikan_id' => $pendidikanId,
            'pekerjaan_id' => $pekerjaanId,
            'penghasilan_rata_rata' => $row[19] ?? null,
            'alamat_ktp' => $row[20] ?? null,
            'alamat_domisili' => $row[21] ?? null,

            // --- Perkara & BB ---
            'narkotika_id' => $narkotikaId,
            'berat_bb' => $row[23] ?? null,
            'status_hukum' => $row[24] ?? null,
            'deskripsi_bb' => $row[25] ?? null,
            'pasal_sangkaan' => $row[26] ?? null,
            'keterlibatan_jaringan' => $row[27] ?? null,
            'tes_urine' => $row[28] ?? null,
            'cara_mendapatkan' => $row[29] ?? null,
            'dapat_dari_siapa' => $row[30] ?? null,

            // --- TAT Mentah ---
            'hasil_asesmen_hukum' => $row[31] ?? null,
            'hasil_asesmen_medis' => $row[32] ?? null,
            'rekomendasi_input' => $row[33] ?? null,
            'pelaksanaan' => $row[34] ?? null,
            'keterangan_tambahan' => $row[35] ?? null,

            // --- Case Conference ---
            'aspek_hukum' => $row[36] ?? null,
            'aspek_medis' => $row[37] ?? null,
            'kesehatan_fisik' => $row[38] ?? null,
            'psikologi' => $row[39] ?? null,
            'alasan_penggunaan' => $row[40] ?? null,
            'kondisi_keluarga' => $row[41] ?? null,
            'tingkat_ketergantungan' => $row[42] ?? null,
            'pola_pemakaian' => $row[43] ?? null,
            'kondisi_lingkungan' => $row[44] ?? null,
            'saran_case_conference' => $row[45] ?? null,
        ]);
    }

    /**
     * Helper: Mencegah error format tanggal dari Excel
     */
    private function transformDate($value)
    {
        if (empty($value))
            return null;

        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}