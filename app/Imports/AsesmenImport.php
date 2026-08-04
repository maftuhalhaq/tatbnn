<?php

namespace App\Imports;

use App\Models\Asesmen;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Narkotika;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class AsesmenImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * Menentukan baris ke berapa header kolom berada di file Excel Anda
     * (Karena baris 1 petunjuk, baris 2 judul kolom, maka data mulai baris 3 atau 4 tergantung heading row)
     */
    public function headingRow(): int
    {
        return 2; // Sesuai template di mana judul kolom ada di baris ke-2
    }

    public function model(array $row)
    {
        // 1. Tangani Relasi Master Pendidikan (Jika diisi teks, cari atau buat baru)
        $pendidikanId = null;
        if (!empty($row['pendidikan'])) {
            $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => trim($row['pendidikan'])]);
            $pendidikanId = $pendidikan->id;
        }

        // 2. Tangani Relasi Master Pekerjaan
        $pekerjaanId = null;
        if (!empty($row['pekerjaan'])) {
            $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => trim($row['pekerjaan'])]);
            $pekerjaanId = $pekerjaan->id;
        }

        // 3. Tangani Relasi Master Narkotika
        $narkotikaId = null;
        if (!empty($row['jenis_narkotika'])) {
            $narkotika = Narkotika::firstOrCreate(['jenis_narkotika' => trim($row['jenis_narkotika'])]);
            $narkotikaId = $narkotika->id;
        }

        // 4. Tangani Relasi Master Rekomendasi TAT
        $rekomendasiId = null;
        if (!empty($row['rekomendasi_tat'])) {
            $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => trim($row['rekomendasi_tat'])]);
            $rekomendasiId = $rekomendasi->id;
        }

        // Fungsi helper untuk membersihkan format tanggal Excel
        $parseDate = function ($value) {
            if (empty($value))
                return null;
            try {
                if (is_numeric($value)) {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                }
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        };

        // 5. Simpan atau Update data berdasarkan NIK (mencegah duplikat jika diimport ulang)
        return Asesmen::updateOrCreate(
            ['nik' => trim($row['nik'] ?? '0000000000000000')], // Kunci pencarian unik
            [
                'nama_lengkap' => $row['nama_lengkap'] ?? '-',
                'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tgl_lahir' => $parseDate($row['tanggal_lahir'] ?? null),
                'jenis_kelamin' => strtoupper($row['jenis_kelamin_lp'] ?? 'L') === 'P' ? 'P' : 'L',
                'no_hp' => $row['no_hp'] ?? null,
                'pendidikan_id' => $pendidikanId,
                'pekerjaan_id' => $pekerjaanId,
                'penghasilan_rata_rata' => $row['penghasilan_rata_rata'] ?? null,
                'alamat_ktp' => $row['alamat_ktp'] ?? null,
                'alamat_domisili' => $row['alamat_domisili'] ?? null,

                // Administrasi
                'no_register' => $row['no_registrasi'] ?? null,
                'no_bln' => $row['nobln'] ?? null,
                'asal_pengajuan' => $row['asal_pengajuan'] ?? null,
                'no_surat_pengajuan' => $row['no_surat_pengajuan'] ?? null,
                'no_lkn' => $row['no_lkn'] ?? null,
                'tgl_surat' => $parseDate($row['tgl_surat'] ?? null),
                'tgl_berkas' => $parseDate($row['tgl_berkas'] ?? null),
                'tgl_pelaksanaan' => $parseDate($row['tgl_pelaksanaan'] ?? null),
                'tgl_tangkap' => $parseDate($row['tgl_tangkap'] ?? null),

                // Perkara & Hukum
                'narkotika_id' => $narkotikaId,
                'berat_bb' => is_numeric($row['berat_bukti_gr'] ?? null) ? $row['berat_bukti_gr'] : null,
                'deskripsi_bb' => $row['barang_bukti_deskripsi'] ?? null,
                'pasal_sangkaan' => $row['pasal_yang_disangkakan'] ?? null,
                'status_hukum' => $row['status_hukum'] ?? null,
                'keterlibatan_jaringan' => $row['keterlibatan_jaringan'] ?? null,
                'cara_mendapatkan' => $row['cara_mendapatkan'] ?? null,
                'dapat_dari_siapa' => $row['dapat_dari_siapa'] ?? null,

                // Medis & Psikososial
                'kesehatan_fisik' => $row['kesehatan_fisik'] ?? null,
                'psikologi' => $row['psikologi'] ?? null,
                'tes_urine' => $row['hasil_tes_urine'] ?? null,
                'tingkat_ketergantungan' => $row['tingkat_ketergantungan'] ?? null,
                'pola_pemakaian' => $row['pola_pemakaian'] ?? null,
                'alasan_penggunaan' => $row['alasan_penggunaan'] ?? null,
                'kondisi_keluarga' => $row['kondisi_keluarga'] ?? null,
                'kondisi_lingkungan' => $row['kondisi_lingkungan'] ?? null,

                // Hasil Asesmen & Case Conference
                'hasil_asesmen_hukum' => $row['hasil_asesmen_hukum'] ?? null,
                'hasil_asesmen_medis' => $row['hasil_asesmen_medis'] ?? null,
                'rekomendasi_id' => $rekomendasiId,
                'pelaksanaan' => strtoupper($row['pelaksanaan_rekomendasi_yatidak'] ?? 'TIDAK') === 'YA' ? 'YA' : 'TIDAK',
                'keterangan_tambahan' => $row['keterangan_tambahan'] ?? null,
                'saran_case_conference' => $row['saran_case_conference'] ?? null,
            ]
        );
    }
}