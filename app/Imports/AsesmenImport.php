<?php

namespace App\Imports;

use App\Models\Asesmen;
use App\Models\Narkotika;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Rekomendasi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AsesmenImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        // Tetap mulai dari baris 1 agar filter pintar kita bekerja
        return 1;
    }

    private function parseDecimal($value)
    {
        if ($value === null || trim((string)$value) === '' || trim((string)$value) === '-') {
            return null;
        }
        $cleaned = str_replace(',', '.', trim((string) $value));
        $cleaned = preg_replace('/[^0-9.]/', '', $cleaned);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    private function parseDate($value)
    {
        if (empty($value) || trim((string)$value) === '-' || trim((string)$value) === '') {
            return null;
        }
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $bulanIndo = [
            'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
            'april' => 'April', 'mei' => 'May', 'juni' => 'June',
            'juli' => 'July', 'agustus' => 'August', 'september' => 'September',
            'oktober' => 'October', 'november' => 'November', 'desember' => 'December',
            'jan' => 'Jan', 'feb' => 'Feb', 'mar' => 'Mar', 'apr' => 'Apr',
            'jun' => 'Jun', 'jul' => 'Jul', 'agt' => 'Aug', 'aug' => 'Aug',
            'sep' => 'Sep', 'okt' => 'Oct', 'oct' => 'Oct', 'nov' => 'Nov', 'des' => 'Dec'
        ];

        $dateStr = strtolower(trim((string) $value));
        $dateStr = str_ireplace(array_keys($bulanIndo), array_values($bulanIndo), $dateStr);

        try {
            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    // =========================================================================
    // FUNGSI PENERJEMAH OTOMATIS JENIS KELAMIN (L/P)
    // =========================================================================
    private function parseJenisKelamin($value)
    {
        $val = strtoupper(trim((string)$value));
        if ($val === 'L' || $val === 'LAKI-LAKI' || $val === 'LAKI LAKI' || $val === 'PRIA') {
            return 'L';
        } elseif ($val === 'P' || $val === 'PEREMPUAN' || $val === 'WANITA') {
            return 'P';
        }
        return '-'; // Default jika kosong/tidak jelas
    }

    public function model(array $row)
    {
        // Bersihkan semua kolom dari spasi nyasar
        $row = array_map(function($value) {
            return is_string($value) ? trim($value) : $value;
        }, $row);

        // =========================================================================
        // FILTER PINTAR: SKIP BARIS KOSONG DAN BARIS HEADER
        // =========================================================================
        if (!isset($row[1]) || trim((string)$row[1]) === '') {
            return null;
        }

        $kolomA = strtoupper(trim((string)($row[0] ?? '')));
        $kolomB = strtoupper(trim((string)($row[1] ?? '')));

        if (
            strpos($kolomA, 'NO') !== false ||
            strpos($kolomA, 'DATA CASE CONFERENCE') !== false ||
            strpos($kolomB, 'NAMA KLIEN') !== false ||
            strpos($kolomB, 'NO/ BLN') !== false ||
            strpos($kolomA, 'REKAP DATA TAT BNNP') !== false
        ) {
            return null;
        }

        // =========================================================================
        // DETEKSI OTOMATIS JENIS TEMPLATE YANG DI-UPLOAD
        // =========================================================================
        // Pada Template Case Conf, Kolom [3] adalah P/L.
        $jkCaseConf = strtoupper(trim((string)($row[3] ?? '')));
        $isCaseConference = in_array($jkCaseConf, ['L', 'P', 'LAKI-LAKI', 'PEREMPUAN', 'LAKI LAKI', 'PRIA', 'WANITA']);

        if ($isCaseConference) {
            // =======================================================
            // 1. IMPORT EXCEL CASE CONFERENCE (Format Baru)
            // =======================================================
            $pekerjaanVal = trim($row[4] ?? '-');
            $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => $pekerjaanVal ?: '-']);

            $pendidikanVal = trim($row[5] ?? '-');
            $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => $pendidikanVal ?: '-']);

            $rekomendasiVal = trim($row[20] ?? '-');
            $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => $rekomendasiVal ?: '-']);

            $rawDataUsia = isset($row[2]) ? (string)$row[2] : '';
            $usiaString = explode('.', $rawDataUsia)[0];
            $usiaBersih = preg_replace('/[^0-9]/', '', $usiaString);

            return new Asesmen([
                'nama_lengkap'              => isset($row[1]) ? mb_strtoupper($row[1], 'UTF-8') : '-',
                'usia'                      => $usiaBersih !== '' ? (int)$usiaBersih : null,

                // MENGGUNAKAN PENERJEMAH JENIS KELAMIN
                'jenis_kelamin'             => $this->parseJenisKelamin($row[3]),

                'pekerjaan_id'              => $pekerjaan->id,
                'pendidikan_id'             => $pendidikan->id,
                'rekomendasi_id'            => $rekomendasi->id,

                'alamat_domisili'           => $row[6] ?? '-',
                'status_hukum'              => $row[7] ?? '-',
                'keterlibatan_jaringan'     => $row[8] ?? '-',
                'deskripsi_bb'              => $row[9] ?? '-',
                'cara_mendapatkan'          => $row[10] ?? '-',
                'dapat_dari_siapa'          => $row[11] ?? '-',
                'kesehatan_fisik'           => $row[12] ?? '-',
                'psikologi'                 => $row[13] ?? '-',
                'tes_urine'                 => $row[14] ?? '-',
                'alasan_penggunaan'         => $row[15] ?? '-',
                'kondisi_keluarga'          => $row[16] ?? '-',
                'tingkat_ketergantungan'    => $row[17] ?? '-',
                'pola_pemakaian'            => $row[18] ?? '-',
                'kondisi_lingkungan'        => $row[19] ?? '-',

                // Menyimpan Saran
                'keterangan_tambahan'       => $row[21] ?? '-',
                'saran_1'                   => $row[22] ?? null,
                'saran_2'                   => $row[23] ?? null,
                'saran_3'                   => $row[24] ?? null,
                'saran_4'                   => $row[25] ?? null,

                // Field Bawaan yang kosong
                'nik'                       => '-',
                'no_bln'                    => '-',
                'asal_pengajuan'            => '-',
                'no_surat_pengajuan'        => '-',
                'no_lkn'                    => '-',
                'no_register'               => '-',
                'alamat_ktp'                => '-',
                'tempat_lahir'              => '-',
                'hasil_asesmen_hukum'       => '-',
                'hasil_asesmen_medis'       => '-',
                'pasal_sangkaan'            => '-',
            ]);

        } else {
            // =======================================================
            // 2. IMPORT EXCEL REKAP TAT (Format Lama)
            // =======================================================
            $pekerjaanVal = trim($row[18] ?? '-');
            $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => $pekerjaanVal ?: '-']);

            $pendidikanVal = trim($row[17] ?? '-');
            $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => $pendidikanVal ?: '-']);

            $narkotikaVal = trim($row[21] ?? '-');
            $narkotika = Narkotika::firstOrCreate(['jenis_narkotika' => $narkotikaVal ?: '-']);

            $usiaBersihLama = preg_replace('/[^0-9]/', '', $row[16] ?? '');

            $rekomendasiRaw = '';
            if (!empty(trim($row[26] ?? ''))) {
                $rekomendasiRaw = 'Rawat Jalan - ' . trim($row[26]);
            } elseif (!empty(trim($row[27] ?? ''))) {
                $rekomendasiRaw = 'Rawat Inap - ' . trim($row[27]);
            } elseif (!empty(trim($row[28] ?? ''))) {
                $rekomendasiRaw = 'Rehab di Lapas / Rutan - ' . trim($row[28]);
            } elseif (!empty(trim($row[29] ?? ''))) {
                $rekomendasiRaw = 'Tidak Rehab (Proses Hukum) - ' . trim($row[29]);
            } else {
                $rekomendasiRaw = '-';
            }
            $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => $rekomendasiRaw]);

            $statusPelaksanaan = 'TIDAK';
            if (!empty(trim($row[30] ?? ''))) {
                $statusPelaksanaan = 'YA';
            } elseif (!empty(trim($row[31] ?? ''))) {
                $statusPelaksanaan = 'TIDAK';
            }

            return new Asesmen([
                'no_bln'                => $row[1] ?? '-',
                'asal_pengajuan'        => $row[2] ?? '-',
                'tgl_surat'             => $this->parseDate($row[3] ?? null),
                'tgl_berkas'            => $this->parseDate($row[4] ?? null),
                'tgl_pelaksanaan'       => $this->parseDate($row[5] ?? null),
                'no_surat_pengajuan'    => $row[6] ?? '-',
                'no_lkn'                => $row[7] ?? '-',
                'tgl_tangkap'           => $this->parseDate($row[8] ?? null),

                'nama_lengkap'          => isset($row[9]) ? mb_strtoupper($row[9], 'UTF-8') : '-',
                'no_register'           => $row[10] ?? '-',
                'alamat_ktp'            => $row[11] ?? '-',
                'alamat_domisili'       => $row[12] ?? '-',
                'tempat_lahir'          => $row[13] ?? '-',
                'tgl_lahir'             => $this->parseDate($row[14] ?? null),

                // MENGGUNAKAN PENERJEMAH JENIS KELAMIN
                'jenis_kelamin'         => $this->parseJenisKelamin($row[15]),

                'usia'                  => $usiaBersihLama !== '' ? (int)$usiaBersihLama : null,

                'pendidikan_id'         => $pendidikan->id,
                'pekerjaan_id'          => $pekerjaan->id,
                'narkotika_id'          => $narkotika->id,
                'rekomendasi_id'        => $rekomendasi->id,

                'no_hp'                 => $row[19] ?? '-',
                'nik'                   => $row[20] ?? '-',
                'berat_bb'              => $this->parseDecimal($row[22] ?? null),

                'pasal_sangkaan'        => $row[23] ?? '-',

                'hasil_asesmen_hukum'   => $row[24] ?? '-',
                'hasil_asesmen_medis'   => $row[25] ?? '-',

                'pelaksanaan'           => $statusPelaksanaan,
                'keterangan_tambahan'   => $row[32] ?? '-',
            ]);
        }
    }
}
