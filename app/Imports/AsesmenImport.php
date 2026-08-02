<?php

namespace App\Imports;

use App\Models\Asesmen;
use App\Models\Narkotika;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsesmenImport implements ToModel, WithStartRow
{
    /**
     * Memulai baca data dari baris ke-4 (karena baris 1-3 adalah header/keterangan)
     */
    public function startRow(): int
    {
        return 4;
    }

    /**
     * Memetakan data dari baris Excel ke dalam Model Asesmen
     */
    public function model(array $row)
    {
        // 1. Cek apakah baris ini kosong (misal nama klien kosong, abaikan baris ini)
        if (!isset($row[9]) || empty($row[9])) {
            return null;
        }

        // 2. Format Tanggal (Konversi dari format Excel/Teks menjadi format SQL YYYY-MM-DD)
        $tgl_surat = $this->transformDate($row[3]);
        $tgl_berkas = $this->transformDate($row[4]);
        $tgl_pelaksanaan = $this->transformDate($row[5]);
        $tgl_tangkap = $this->transformDate($row[8]);
        $tgl_lahir = $this->transformDate($row[14]);

        // 3. Cari ID dari Tabel Relasi (Master Data) berdasarkan teks dari Excel

        // Cari ID Pendidikan (Kolom R / index 17)
        $pendidikanId = null;
        if (!empty($row[17])) {
            $pendidikan = Pendidikan::where('nama_pendidikan', $row[17])
                ->orWhere('jenis_pendidikan', $row[17])
                ->first();
            $pendidikanId = $pendidikan ? $pendidikan->id : null;
        }

        // Cari ID Pekerjaan (Kolom S / index 18)
        $pekerjaanId = null;
        if (!empty($row[18])) {
            $pekerjaan = Pekerjaan::where('nama_pekerjaan', $row[18])
                ->orWhere('jenis_pekerjaan', $row[18])
                ->first();
            $pekerjaanId = $pekerjaan ? $pekerjaan->id : null;
        }

        // Cari ID Narkotika (Kolom W / index 22)
        $narkotikaId = null;
        if (!empty($row[22])) {
            $narkotika = Narkotika::where('jenis_narkotika', $row[22])->first();
            $narkotikaId = $narkotika ? $narkotika->id : null;
        }

        // Cari ID Rekomendasi TAT (Kolom AN / index 39)
        $rekomendasiId = null;
        if (!empty($row[39])) {
            $rekomendasi = Rekomendasi::where('tempat_rehabilitasi', $row[39])->first();
            $rekomendasiId = $rekomendasi ? $rekomendasi->id : null;
        }

        // 4. Masukkan data ke dalam Model
        return new Asesmen([
            // --- DATA ADMINISTRASI & IDENTITAS (Sama Seperti Sebelumnya) ---
            'no_bln' => $row[2],
            'tgl_surat' => $tgl_surat,
            'tgl_berkas' => $tgl_berkas,
            'tgl_pelaksanaan' => $tgl_pelaksanaan,
            'no_surat_pengajuan' => $row[6],
            'no_lkn' => $row[7],
            'tgl_tangkap' => $tgl_tangkap,

            'nama_lengkap' => $row[9],
            'no_register' => $row[10],
            'alamat_ktp' => $row[11],
            'alamat_domisili' => $row[12],
            'tempat_lahir' => $row[13],
            'tgl_lahir' => $tgl_lahir,
            'jenis_kelamin' => in_array(strtoupper($row[15]), ['L', 'P']) ? strtoupper($row[15]) : null,
            // (Usia di kolom 16 biasanya tidak disimpan ke DB, karena bisa dikalkulasi dari tgl_lahir)

            'pendidikan_id' => $pendidikanId,
            'pekerjaan_id' => $pekerjaanId,
            'no_hp' => $row[19],
            'nik' => $row[20],

            // --- KOLOM BARU CASE CONFERENCE DIMULAI DARI SINI ---
            'penghasilan_rata_rata' => $row[21],

            // Aspek Perkara & Hukum
            'narkotika_id' => $narkotikaId,
            'berat_bb' => $row[23],
            'pasal_sangkaan' => $row[24],
            'status_hukum' => $row[25],
            'keterlibatan_jaringan' => $row[26],
            'cara_mendapatkan' => $row[27],
            'dapat_dari' => $row[28],

            // Aspek Medis & Psikososial
            'kesehatan' => $row[29],
            'psikologi' => $row[30],
            'tes_urine' => in_array(ucfirst(strtolower($row[31])), ['Positif', 'Negatif']) ? ucfirst(strtolower($row[31])) : null,
            'alasan_penggunaan' => $row[32],
            'kondisi_keluarga' => $row[33],
            'tingkat_ketergantungan' => $row[34],
            'pola_pemakaian' => $row[35],
            'kondisi_lingkungan' => $row[36],

            // Kesimpulan TAT
            'hasil_asesmen_hukum' => $row[37],
            'hasil_asesmen_medis' => $row[38],
            'rekomendasi_id' => $rekomendasiId,
            'pelaksanaan' => in_array(strtoupper($row[40]), ['YA', 'TIDAK']) ? strtoupper($row[40]) : null,
            'keterangan' => $row[41],
            'saran' => $row[42],
        ]);
    }

    /**
     * Helper untuk mengubah format tanggal dari Excel (yang biasanya berupa angka seri)
     * menjadi format YYYY-MM-DD yang diterima MySQL.
     */
    private function transformDate($value, $format = 'Y-m-d')
    {
        if (empty($value))
            return null;

        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format($format);
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::parse($value)->format($format);
        }
    }
}