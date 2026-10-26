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

class AsesmenRekapImport implements ToModel, WithStartRow
{
    public function startRow(): int { return 1; }

    private function parseDecimal($value) {
        if ($value === null || trim((string)$value) === '' || trim((string)$value) === '-') return null;
        $cleaned = str_replace(',', '.', trim((string) $value));
        $cleaned = preg_replace('/[^0-9.]/', '', $cleaned);
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    private function parseDate($value) {
        if (empty($value) || trim((string)$value) === '-' || trim((string)$value) === '') return null;
        if (is_numeric($value)) {
            try { return Date::excelToDateTimeObject($value)->format('Y-m-d'); } catch (\Exception $e) { return null; }
        }
        $bulanIndo = ['januari' => 'January', 'februari' => 'February', 'maret' => 'March', 'april' => 'April', 'mei' => 'May', 'juni' => 'June', 'juli' => 'July', 'agustus' => 'August', 'september' => 'September', 'oktober' => 'October', 'november' => 'November', 'desember' => 'December', 'jan' => 'Jan', 'feb' => 'Feb', 'mar' => 'Mar', 'apr' => 'Apr', 'jun' => 'Jun', 'jul' => 'Jul', 'agt' => 'Aug', 'aug' => 'Aug', 'sep' => 'Sep', 'okt' => 'Oct', 'oct' => 'Oct', 'nov' => 'Nov', 'des' => 'Dec'];
        $dateStr = str_ireplace(array_keys($bulanIndo), array_values($bulanIndo), strtolower(trim((string) $value)));
        try { return Carbon::parse($dateStr)->format('Y-m-d'); } catch (\Exception $e) { return null; }
    }

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
        if (strpos($kolomA, 'NO') !== false || strpos($kolomA, 'REKAP DATA TAT') !== false) return null;

        $nikVal = trim($row[20] ?? '-');

        // ===================================================================
        // BLOK PENCEGAH DUPLIKASI (ANTI-DOUBLE IMPORT REKAP)
        // ===================================================================
        // Cek berdasarkan NIK (karena di rekap NIK wajib unik)
        if ($nikVal !== '-' && $nikVal !== '') {
            $isDuplicate = Asesmen::where('nik', $nikVal)->exists();
            if ($isDuplicate) {
                // Jika NIK sudah ada di database, skip baris ini agar tidak error merah!
                return null;
            }
        }

        $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => trim($row[18] ?? '-') ?: '-']);
        $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => trim($row[17] ?? '-') ?: '-']);
        $narkotika = Narkotika::firstOrCreate(['jenis_narkotika' => trim($row[21] ?? '-') ?: '-']);

        $usiaBersih = preg_replace('/[^0-9]/', '', $row[16] ?? '');

        $rekRaw = '';
        if (!empty(trim($row[26] ?? ''))) $rekRaw = 'Rawat Jalan - ' . trim($row[26]);
        elseif (!empty(trim($row[27] ?? ''))) $rekRaw = 'Rawat Inap - ' . trim($row[27]);
        elseif (!empty(trim($row[28] ?? ''))) $rekRaw = 'Rehab di Lapas / Rutan - ' . trim($row[28]);
        elseif (!empty(trim($row[29] ?? ''))) $rekRaw = 'Tidak Rehab (Proses Hukum) - ' . trim($row[29]);
        else $rekRaw = '-';
        $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => $rekRaw]);

        $statusPelaksanaan = 'TIDAK';
        if (!empty(trim($row[30] ?? ''))) $statusPelaksanaan = 'YA';

        return new Asesmen([
            'no_bln' => $row[1] ?? '-', 'asal_pengajuan' => $row[2] ?? '-',
            'tgl_surat' => $this->parseDate($row[3] ?? null), 'tgl_berkas' => $this->parseDate($row[4] ?? null),
            'tgl_pelaksanaan' => $this->parseDate($row[5] ?? null), 'no_surat_pengajuan' => $row[6] ?? '-',
            'no_lkn' => $row[7] ?? '-', 'tgl_tangkap' => $this->parseDate($row[8] ?? null),
            'nama_lengkap' => isset($row[9]) ? mb_strtoupper($row[9], 'UTF-8') : '-',
            'no_register' => $row[10] ?? '-', 'alamat_ktp' => $row[11] ?? '-',
            'alamat_domisili' => $row[12] ?? '-', 'tempat_lahir' => $row[13] ?? '-',
            'tgl_lahir' => $this->parseDate($row[14] ?? null),
            'jenis_kelamin' => $this->parseJenisKelamin($row[15]),
            'usia' => $usiaBersih !== '' ? (int)$usiaBersih : null,
            'pendidikan_id' => $pendidikan->id, 'pekerjaan_id' => $pekerjaan->id,
            'narkotika_id' => $narkotika->id, 'rekomendasi_id' => $rekomendasi->id,
            'no_hp' => $row[19] ?? '-', 'nik' => $nikVal,
            'berat_bb' => $this->parseDecimal($row[22] ?? null),
            'pasal_sangkaan' => $row[23] ?? '-', 'hasil_asesmen_hukum' => $row[24] ?? '-',
            'hasil_asesmen_medis' => $row[25] ?? '-', 'pelaksanaan' => $statusPelaksanaan,
            'keterangan_tambahan' => $row[32] ?? '-',
        ]);
    }
}
