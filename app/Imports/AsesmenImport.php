<?php

namespace App\Imports;

use App\Models\Asesmen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsesmenImport implements ToCollection, WithStartRow
{
    /**
     * Data di Excel dimulai dari baris ke-4
     * (karena baris 1-3 digunakan untuk Header Tabel)
     */
    public function startRow(): int
    {
        return 4;
    }

    /**
     * Memproses semua baris sekaligus dalam sebuah Collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Abaikan baris kosong atau jika Nama (Indeks 9) kosong
            if (!isset($row[9]) || trim($row[9]) === '') {
                continue;
            }

            // Bersihkan data NIK, No Register, dan No Handphone
            $no_register = isset($row[10]) ? trim(str_replace("'", "", $row[10])) : null;
            $nama_lengkap = trim($row[9]);
            $nik = isset($row[20]) ? trim(str_replace("'", "", $row[20])) : null;
            $no_hp = isset($row[19]) ? trim(str_replace("'", "", $row[19])) : null;

            // ==========================================
            // LOGIKA UPSERT: CEK DUPLIKAT BERDASARKAN NIK ATAU NO REGISTER
            // ==========================================
            $klien = null;
            if (!empty($nik)) {
                $klien = Asesmen::where('nik', $nik)->first();
            }
            if (!$klien && !empty($no_register)) {
                $klien = Asesmen::where('no_register', $no_register)->first();
            }

            // 1. Cek & Ambil ID Narkotika
            $narkotikaId = null;
            if (!empty($row[21])) {
                try {
                    $narkotika = DB::table('master_narkotikas')
                        ->where('jenis_narkotika', 'like', '%' . trim($row[21]) . '%')
                        ->first();
                    $narkotikaId = $narkotika ? $narkotika->id : null;
                } catch (\Exception $e) {
                    try {
                        $narkotika = DB::table('m_narkotika')
                            ->where('jenis_narkotika', 'like', '%' . trim($row[21]) . '%')
                            ->first();
                        $narkotikaId = $narkotika ? $narkotika->id : null;
                    } catch (\Exception $e2) {
                        $narkotikaId = null;
                    }
                }
            }

            // 2. Olah Nilai Rekomendasi TAT dari Kolom V, W, X, Y (Indeks 26, 27, 28, 29)
            $rekomendasiInput = null;
            if (!empty($row[26]) && strtoupper(trim($row[26])) === 'V') {
                $rekomendasiInput = 'Rawat Jalan';
            } elseif (!empty($row[27]) && strtoupper(trim($row[27])) === 'V') {
                $rekomendasiInput = 'Rawat Inap';
            } elseif (!empty($row[28]) && strtoupper(trim($row[28])) === 'V') {
                $rekomendasiInput = 'Rehab di Lapas / Rutan';
            } elseif (!empty($row[29]) && strtoupper(trim($row[29])) === 'V') {
                $rekomendasiInput = 'Tidak Rehab (Proses Hukum)';
            }

            // 3. Olah Status Pelaksanaan dari Kolom Z, AA (Indeks 30, 31)
            $pelaksanaanStatus = null;
            if (!empty($row[30]) && strtoupper(trim($row[30])) === 'V') {
                $pelaksanaanStatus = 'YA';
            } elseif (!empty($row[31]) && strtoupper(trim($row[31])) === 'V') {
                $pelaksanaanStatus = 'TIDAK';
            }

            // ==========================================
            // MAPPING DATA SESUAI EXCEL EXPORT (rekap_tat.blade.php)
            // Indeks array dimulai dari 0
            // ==========================================
            $dataAsesmen = [
                'no_bln' => $row[1] ?? null,
                'asal_pengajuan' => $row[2] ?? null,
                'tgl_surat' => $this->transformDate($row[3]),
                'tgl_berkas' => $this->transformDate($row[4]),
                'tgl_pelaksanaan' => $this->transformDate($row[5]),
                'no_surat_pengajuan' => $row[6] ?? null,
                'no_lkn' => $row[7] ?? null,
                'tgl_tangkap' => $this->transformDate($row[8]),
                'nama_lengkap' => $nama_lengkap,
                'no_register' => $no_register,
                'alamat_ktp' => $row[11] ?? null,
                'alamat_domisili' => $row[12] ?? null,
                'tempat_lahir' => $row[13] ?? null,
                'tgl_lahir' => $this->transformDate($row[14]),
                'jenis_kelamin' => strtoupper(trim($row[15] ?? '')), // Pastikan formatnya sama (L/P)
                // Usia pada Index 16 di-skip karena akan dihitung otomatis atau bukan primary field
                'pendidikan_input' => $row[17] ?? null,
                'pekerjaan_input' => $row[18] ?? null,
                'no_hp' => $no_hp,
                'nik' => $nik,
                'narkotika_id' => $narkotikaId,
                'berat_bb' => $row[22] ?? null,
                'pasal_sangkaan' => $row[23] ?? null,
                'hasil_asesmen_hukum' => $row[24] ?? null,
                'hasil_asesmen_medis' => $row[25] ?? null,
                'rekomendasi_input' => $rekomendasiInput,
                'pelaksanaan' => $pelaksanaanStatus,
                'keterangan_tambahan' => $row[32] ?? null,
            ];

            // 4. Lakukan Insert atau Update
            if ($klien) {
                // UPDATE KLIEN LAMA
                $klien->update($dataAsesmen);
            } else {
                // INSERT KLIEN BARU
                $dataAsesmen['status_kelengkapan'] = 'Belum Lengkap';
                Asesmen::create($dataAsesmen);
            }
        }
    }

    /**
     * Helper: Mencegah error format tanggal dari Excel
     */
    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

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