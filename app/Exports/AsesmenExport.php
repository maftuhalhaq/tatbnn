<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Asesmen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AsesmenExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    // Menangkap request filter dari Controller
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Asesmen::query()->with(['narkotika', 'pendidikan', 'pekerjaan', 'rekomendasi']);

        // Logika Pencarian
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_register', 'like', "%{$search}%")
                    ->orWhere('no_lkn', 'like', "%{$search}%");
            });
        }
        // Logika Filter
        if ($this->request->filled('bulan')) {
            $query->whereMonth('tgl_pelaksanaan', $this->request->bulan);
        }
        if ($this->request->filled('tahun')) {
            $query->whereYear('tgl_pelaksanaan', $this->request->tahun);
        }
        if ($this->request->filled('narkotika')) {
            $query->where('narkotika_id', $this->request->narkotika);
        }
        if ($this->request->filled('status')) {
            $query->where('pelaksanaan', strtoupper($this->request->status));
        }

        // Logika Sorting
        $sort = $this->request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->oldest('tgl_pelaksanaan');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->latest('tgl_pelaksanaan');
        }

        return $query;
    }

    // Tambahkan properti ini di dalam class AsesmenExport Anda
    private $rowNumber = 0;

    // Judul Kolom (Sama persis dengan Template Excel)
    public function headings(): array
    {
        return [
            'NO.',
            'NAMA LENGKAP',
            'NIK',
            'KEWARGANEGARAAN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN (L/P)',
            'NO HP',
            'PENDIDIKAN',
            'PEKERJAAN',
            'PENGHASILAN RATA-RATA',
            'ALAMAT KTP',
            'ALAMAT DOMISILI',
            'NO REGISTRASI',
            'NO/BLN',
            'ASAL PENGAJUAN',
            'NO SURAT PENGAJUAN',
            'NO LKN',
            'TGL SURAT',
            'TGL BERKAS',
            'TGL PELAKSANAAN',
            'TGL TANGKAP',
            'JENIS NARKOTIKA',
            'BERAT BUKTI (gr)',
            'BARANG BUKTI (DESKRIPSI)',
            'PASAL YANG DISANGKAKAN',
            'STATUS HUKUM',
            'KETERLIBATAN JARINGAN',
            'CARA MENDAPATKAN',
            'DAPAT DARI SIAPA',
            'KESEHATAN FISIK',
            'PSIKOLOGI',
            'HASIL TES URINE',
            'TINGKAT KETERGANTUNGAN',
            'POLA PEMAKAIAN',
            'ALASAN PENGGUNAAN',
            'KONDISI KELUARGA',
            'KONDISI LINGKUNGAN',
            'HASIL ASESMEN HUKUM',
            'HASIL ASESMEN MEDIS',
            'REKOMENDASI TAT',
            'PELAKSANAAN REKOMENDASI (YA/TIDAK)',
            'KETERANGAN TAMBAHAN',
            'SARAN CASE CONFERENCE'
        ];
    }

    // Pemetaan Data (Menyesuaikan dengan 44 Kolom di atas)
    public function map($asesmen): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $asesmen->nama_lengkap ?? '-',
            "'" . ($asesmen->nik ?? '-'), // Petik agar NIK tidak jadi format angka/scientific
            $asesmen->kewarganegaraan ?? '-',
            $asesmen->tempat_lahir ?? '-',
            $asesmen->tanggal_lahir ? \Carbon\Carbon::parse($asesmen->tanggal_lahir)->format('Y-m-d') : '-',
            $asesmen->jenis_kelamin ?? '-',
            "'" . ($asesmen->no_hp ?? '-'),

            // Cek relasi jika pendidikan/pekerjaan berupa tabel master, jika string biasa akan langsung cetak
            $asesmen->pendidikan ? $asesmen->pendidikan->nama_pendidikan : ($asesmen->pendidikan ?? '-'),
            $asesmen->pekerjaan ? $asesmen->pekerjaan->nama_pekerjaan : ($asesmen->pekerjaan ?? '-'),

            $asesmen->penghasilan_rata_rata ?? '-',
            $asesmen->alamat_ktp ?? ($asesmen->alamat ?? '-'),
            $asesmen->alamat_domisili ?? '-',
            $asesmen->no_register ?? '-',
            $asesmen->no_bln ?? '-',
            $asesmen->asal_pengajuan ?? '-',
            $asesmen->no_surat_pengajuan ?? '-',
            $asesmen->no_lkn ?? '-',
            $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('Y-m-d') : '-',
            $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('Y-m-d') : '-',
            $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('Y-m-d') : '-',
            $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('Y-m-d') : '-',

            // Relasi Narkotika
            $asesmen->narkotika ? $asesmen->narkotika->jenis_narkotika : ($asesmen->jenis_narkotika ?? '-'),

            $asesmen->berat_bb ?? '-',
            $asesmen->deskripsi_bb ?? ($asesmen->barang_bukti ?? '-'), // Sesuaikan nama kolom DB Anda
            $asesmen->pasal_sangkaan ?? '-',
            $asesmen->status_hukum ?? '-',
            $asesmen->keterlibatan_jaringan ?? '-',
            $asesmen->cara_mendapatkan ?? '-',
            $asesmen->dapat_dari_siapa ?? '-',
            $asesmen->kesehatan_fisik ?? '-',
            $asesmen->psikologi ?? '-',
            $asesmen->tes_urine ?? '-',
            $asesmen->tingkat_ketergantungan ?? '-',
            $asesmen->pola_pemakaian ?? '-',
            $asesmen->alasan_penggunaan ?? '-',
            $asesmen->kondisi_keluarga ?? '-',
            $asesmen->kondisi_lingkungan ?? '-',
            $asesmen->hasil_asesmen_hukum ?? '-',
            $asesmen->hasil_asesmen_medis ?? '-',
            $asesmen->rekomendasi_tat ?? ($asesmen->rekomendasi->tempat_rehabilitasi ?? '-'), // Sesuaikan relasi rekomendasi
            $asesmen->pelaksanaan_rekomendasi ?? ($asesmen->status ?? '-'),
            $asesmen->keterangan_tambahan ?? '-',
            $asesmen->saran_case_conference ?? '-',
        ];
    }

}