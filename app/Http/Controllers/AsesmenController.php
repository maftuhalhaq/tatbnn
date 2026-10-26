<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesmen;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Narkotika;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Exception;
use Carbon\Carbon;
use App\Exports\AsesmenExport;
use Illuminate\Support\Arr;

class AsesmenController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Asesmen::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_register', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $sort = $request->get('sort', 'terbaru');

        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $asesmens = $query->paginate(10)->withQueryString();

        $tahunTersedia = \App\Models\Asesmen::selectRaw('YEAR(created_at) as tahun')
            ->whereNotNull('created_at')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunTersedia->isEmpty()) {
            $tahunTersedia = collect([date('Y')]);
        }

        return view('asesmen.index', compact('asesmens', 'tahunTersedia'));
    }

    public function create()
    {
        $masterPendidikan = Pendidikan::orderBy('nama_pendidikan', 'asc')->get();
        $masterPekerjaan = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $masterNarkotika = Narkotika::orderBy('jenis_narkotika', 'asc')->get();
        $masterRekomendasi = Rekomendasi::orderBy('tempat_rehabilitasi', 'asc')->get();
        $masterZat = \App\Models\MasterOpsi::where('kategori', 'zat')->get();

        return view('asesmen.create', compact(
            'masterPendidikan', 'masterPekerjaan', 'masterNarkotika', 'masterRekomendasi', 'masterZat'
        ));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses.'); }

        $request->validate(['nik' => 'required|max:16|unique:asesmens,nik'], [
            'nik.unique' => 'Peringatan: NIK ini sudah pernah terdaftar di dalam sistem!',
        ]);

        $validatedData = $request->validate([
            'foto_klien' => 'nullable|image|mimes:jpeg,png,jpg',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'usia' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_input' => 'nullable|string|max:255',
            'rekomendasi_input' => 'nullable|string|max:255',
            'narkotika_input' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',
            'berat_bb' => 'nullable|numeric',
            'pasal_sangkaan' => 'nullable|string',
            'deskripsi_bb' => 'nullable|string',
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'saran_1' => 'nullable|string', 'saran_2' => 'nullable|string', 'saran_3' => 'nullable|string', 'saran_4' => 'nullable|string',
        ]);

        if (!empty($validatedData['usia'])) {
            $validatedData['usia'] = (int) preg_replace('/[^0-9]/', '', $validatedData['usia']);
        } else { $validatedData['usia'] = null; }

        if (!empty($validatedData['nama_lengkap'])) {
            $validatedData['nama_lengkap'] = mb_strtoupper($validatedData['nama_lengkap'], 'UTF-8');
        }

        if ($request->hasFile('foto_klien')) {
            $validatedData['foto_klien'] = $request->file('foto_klien')->store('foto-klien', 'public');
        }

        $pendidikanVal = $request->filled('pendidikan_input') ? $request->pendidikan_input : '-';
        $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => $pendidikanVal]);
        $validatedData['pendidikan_id'] = $pendidikan->id;

        $pekerjaanVal = $request->filled('pekerjaan_input') ? $request->pekerjaan_input : '-';
        $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => $pekerjaanVal]);
        $validatedData['pekerjaan_id'] = $pekerjaan->id;

        $rekomendasiVal = $request->filled('rekomendasi_input') ? $request->rekomendasi_input : '-';
        $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => $rekomendasiVal]);
        $validatedData['rekomendasi_id'] = $rekomendasi->id;

        $narkotikaVal = $request->filled('narkotika_input') ? $request->narkotika_input : '-';
        $narkotika = Narkotika::firstOrCreate(['jenis_narkotika' => $narkotikaVal]);
        $validatedData['narkotika_id'] = $narkotika->id;

        $validatedData['pelaksanaan'] = $request->pelaksanaan ?? 'TIDAK';

        $dataSiapSimpan = Arr::except($validatedData, ['pendidikan_input','pekerjaan_input','rekomendasi_input','narkotika_input']);
        Asesmen::create($dataSiapSimpan);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        if (!Gate::allows('read-only')) { abort(403, 'Akses ditolak. Akun Anda tidak memiliki peran yang valid.'); }
        $asesmen = Asesmen::with(['pendidikan','pekerjaan','narkotika','anggotaTim'])->findOrFail($id);
        return view('asesmen.show', compact('asesmen'));
    }

    public function edit(string $id)
    {
        $asesmen = Asesmen::findOrFail($id);
        $masterPendidikan = Pendidikan::orderBy('nama_pendidikan', 'asc')->get();
        $masterPekerjaan = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $masterNarkotika = Narkotika::orderBy('jenis_narkotika', 'asc')->get();
        $masterRekomendasi = Rekomendasi::orderBy('tempat_rehabilitasi', 'asc')->get();
        $masterZat = \App\Models\MasterOpsi::where('kategori', 'zat')->get();

        return view('asesmen.edit', compact(
            'asesmen','masterPendidikan','masterPekerjaan','masterNarkotika','masterRekomendasi','masterZat'
        ));
    }

    public function update(Request $request, string $id)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.'); }

        $request->validate(['nik' => 'required|max:16|unique:asesmens,nik,' . $id], [
            'nik.unique' => 'Peringatan: NIK ini sudah digunakan oleh Klien lain.',
        ]);

        $validatedData = $request->validate([
            'foto_klien' => 'nullable|image|mimes:jpeg,png,jpg',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'usia' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'pendidikan_input' => 'nullable|string|max:255',
            'pekerjaan_input' => 'nullable|string|max:255',
            'rekomendasi_input' => 'nullable|string|max:255',
            'narkotika_input' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_register' => 'nullable|string|max:100',
            'no_bln' => 'nullable|string|max:100',
            'asal_pengajuan' => 'nullable|string',
            'no_surat_pengajuan' => 'nullable|string|max:100',
            'no_lkn' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'tgl_berkas' => 'nullable|date',
            'tgl_pelaksanaan' => 'nullable|date',
            'tgl_tangkap' => 'nullable|date',
            'berat_bb' => 'nullable|numeric',
            'pasal_sangkaan' => 'nullable|string',
            'deskripsi_bb' => 'nullable|string',
            'hasil_asesmen_hukum' => 'nullable|string',
            'hasil_asesmen_medis' => 'nullable|string',
            'pelaksanaan' => 'nullable|in:YA,TIDAK',
            'penghasilan_rata_rata' => 'nullable|string|max:255',
            'status_hukum' => 'nullable|string|max:255',
            'keterlibatan_jaringan' => 'nullable|string|max:255',
            'cara_mendapatkan' => 'nullable|string|max:255',
            'dapat_dari_siapa' => 'nullable|string|max:255',
            'kesehatan_fisik' => 'nullable|string',
            'psikologi' => 'nullable|string',
            'tes_urine' => 'nullable|string',
            'alasan_penggunaan' => 'nullable|string',
            'kondisi_keluarga' => 'nullable|string',
            'tingkat_ketergantungan' => 'nullable|string|max:255',
            'pola_pemakaian' => 'nullable|string|max:255',
            'kondisi_lingkungan' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'saran_1' => 'nullable|string', 'saran_2' => 'nullable|string', 'saran_3' => 'nullable|string', 'saran_4' => 'nullable|string',
        ]);

        $asesmen = Asesmen::findOrFail($id);

        if (!empty($validatedData['usia'])) {
            $validatedData['usia'] = (int) preg_replace('/[^0-9]/', '', $validatedData['usia']);
        } else { $validatedData['usia'] = null; }

        if (!empty($validatedData['nama_lengkap'])) {
            $validatedData['nama_lengkap'] = mb_strtoupper($validatedData['nama_lengkap'], 'UTF-8');
        }

        if ($request->hasFile('foto_klien')) {
            if ($asesmen->foto_klien) { Storage::disk('public')->delete($asesmen->foto_klien); }
            $validatedData['foto_klien'] = $request->file('foto_klien')->store('foto-klien', 'public');
        }

        $pendidikanVal = $request->filled('pendidikan_input') ? $request->pendidikan_input : '-';
        $pendidikan = Pendidikan::firstOrCreate(['nama_pendidikan' => $pendidikanVal]);
        $validatedData['pendidikan_id'] = $pendidikan->id;

        $pekerjaanVal = $request->filled('pekerjaan_input') ? $request->pekerjaan_input : '-';
        $pekerjaan = Pekerjaan::firstOrCreate(['nama_pekerjaan' => $pekerjaanVal]);
        $validatedData['pekerjaan_id'] = $pekerjaan->id;

        $rekomendasiVal = $request->filled('rekomendasi_input') ? $request->rekomendasi_input : '-';
        $rekomendasi = Rekomendasi::firstOrCreate(['tempat_rehabilitasi' => $rekomendasiVal]);
        $validatedData['rekomendasi_id'] = $rekomendasi->id;

        $narkotikaVal = $request->filled('narkotika_input') ? $request->narkotika_input : '-';
        $narkotika = Narkotika::firstOrCreate(['jenis_narkotika' => $narkotikaVal]);
        $validatedData['narkotika_id'] = $narkotika->id;

        $validatedData['pelaksanaan'] = $request->pelaksanaan ?? 'TIDAK';

        $dataSiapUpdate = Arr::except($validatedData, ['pendidikan_input','pekerjaan_input','rekomendasi_input','narkotika_input']);
        $asesmen->update($dataSiapUpdate);

        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.'); }
        $asesmen = Asesmen::findOrFail($id);
        if ($asesmen->foto_klien) { Storage::disk('public')->delete($asesmen->foto_klien); }
        $asesmen->delete();
        return redirect()->route('asesmen.index')->with('success', 'Data Asesmen berhasil dihapus!');
    }

    // =========================================================================
    // FUNGSI IMPORT (DISESUAIKAN UNTUK 2 TIPE)
    // =========================================================================
    public function import(Request $request)
    {
        if (!Gate::allows('manage-data')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengimpor data.');
        }

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV.',
            'file_excel.max' => 'Ukuran file tidak boleh lebih dari 10 MB.'
        ]);

        $type = $request->query('type', 'rekap'); // Default rekap

        try {
            if ($type === 'cc') {
                Excel::import(new \App\Imports\AsesmenCaseConfImport, $request->file('file_excel'));
                return redirect()->back()->with('success', 'Data Case Conference berhasil diimport dan disimpan!');
            } else {
                Excel::import(new \App\Imports\AsesmenRekapImport, $request->file('file_excel'));
                return redirect()->back()->with('success', 'Data Rekap TAT berhasil diimport dan disimpan!');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return redirect()->back()->with('error', 'Gagal memproses data. Pastikan format tabel sesuai dengan template unduhan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses untuk mengunduh template.'); }
        $type = $request->get('type', 'rekap');
        $emptyRequest = new Request(['search' => 'XXXXXXXXX_EMPTY_TEMPLATE_XXXXXXXXX']);

        if ($type === 'cc') {
            return Excel::download(new \App\Exports\AsesmenCCExport($emptyRequest), 'Template_Data_Case_Conference.xlsx');
        } else {
            return Excel::download(new \App\Exports\AsesmenRekapExport($emptyRequest), 'Template_Rekap_Data_TAT.xlsx');
        }
    }

    public function cetakPdf(string $id)
    {
        if (!Gate::allows('read-only')) { abort(403, 'Akses ditolak. Akun Anda tidak memiliki peran yang valid.'); }
        $asesmen = Asesmen::findOrFail($id);
        $pdf = Pdf::loadView('asesmen.pdf', compact('asesmen'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Ringkasan_Asesmen_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.pdf');
    }

    public function beritaAcara(string $id)
    {
        if (!Gate::allows('akses-dokumen')) { abort(403, 'Anda tidak memiliki akses ke fitur dokumen.'); }

        $asesmen = Asesmen::with(['pendidikan', 'pekerjaan', 'anggotaTim'])->findOrFail($id);

        $masterMedis = \App\Models\MasterAnggota::where('kategori', 'medis')->get();
        $masterHukum = \App\Models\MasterAnggota::where('kategori', 'hukum')->get();
        $masterZat = \App\Models\MasterOpsi::where('kategori', 'zat')->get();
        $masterTempatRehab = \App\Models\MasterOpsi::where('kategori', 'tempat_rehab')->get();
        $masterDiagnosis = \App\Models\MasterOpsi::where('kategori', 'diagnosis')->get();

        $klienData = [
            'nama' => $asesmen->nama_lengkap ?? '-',
            'nik' => $asesmen->nik ?? '-',
            'usia' => $asesmen->tgl_lahir ? Carbon::parse($asesmen->tgl_lahir)->age : '-',
            'tempat_lahir' => $asesmen->tempat_lahir ?? '-',
            'tgl_lahir' => $asesmen->tgl_lahir ? Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-',
            'jk' => $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            'agama' => $asesmen->agama ?? '-',
            'pendidikan' => $asesmen->pendidikan->nama_pendidikan ?? '-',
            'pekerjaan' => $asesmen->pekerjaan->nama_pekerjaan ?? '-',
            'alamat_ktp' => $asesmen->alamat_ktp ?? '-',
            'alamat_domisili' => $asesmen->alamat_domisili ?? '-',
        ];

        return view('asesmen.berita-acara', compact('asesmen', 'masterMedis', 'masterHukum', 'masterZat', 'masterTempatRehab', 'masterDiagnosis', 'klienData'));
    }

    public function generateBeritaAcara(Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) { abort(403, 'Anda tidak memiliki akses ke fitur dokumen.'); }

        $asesmen = Asesmen::findOrFail($id);

        $asesmen->no_ba = $request->no_ba;
        $asesmen->tgl_ba = $request->tgl_ba;
        $asesmen->ketua_tat_nama = $request->ketua_tat_nama;
        $asesmen->ketua_tat_nrp = $request->ketua_tat_nrp;
        $asesmen->no_kep_tim = $request->no_kep_tim;
        $asesmen->tgl_kep_tim = $request->tgl_kep_tim;
        $asesmen->narasi_medis = $request->narasi_medis;
        $asesmen->narasi_hukum = $request->narasi_hukum;
        $asesmen->alat_bukti_no_sk = $request->alat_bukti_no_sk;
        $asesmen->alat_bukti_tgl_sk = $request->alat_bukti_tgl_sk;
        $asesmen->alat_bukti_dokter = $request->alat_bukti_dokter;
        $asesmen->alat_bukti_hasil = $request->alat_bukti_hasil;
        $asesmen->status_klien = $request->status_klien;
        $asesmen->kesimpulan_jenis_zat = $request->kesimpulan_jenis_zat;
        $asesmen->kesimpulan_pola_pakai = $request->kesimpulan_pola_pakai;
        $asesmen->kesimpulan_kategori = $request->kesimpulan_kategori;
        $asesmen->diagnosis_medis = $request->diagnosis_medis;
        $asesmen->rekomendasi_tempat_rehab = $request->rekomendasi_tempat_rehab;
        $asesmen->rekomendasi_durasi = $request->rekomendasi_durasi;
        $asesmen->rekomendasi_keterangan = $request->rekomendasi_keterangan;

        if ($request->has('rekomendasi_durasi')) { $asesmen->lama_perawatan = $request->rekomendasi_durasi; }
        if ($request->has('tes_urine')) { $asesmen->tes_urine = $request->tes_urine; }

        $asesmen->save();

        $asesmen->anggotaTim()->sync(array_merge((array) $request->tim_medis, (array) $request->tim_hukum));

        if ($request->input('action') === 'save_only' || $request->input('action') === 'save') {
            return redirect()->route('asesmen.show', $asesmen->id)->with('success', 'Data Berita Acara berhasil disimpan dan diperbarui!');
        }

        return $this->prosesCetakBeritaAcaraWord($asesmen);
    }

    public function unduhBeritaAcara(Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) { abort(403, 'Anda tidak memiliki akses ke fitur dokumen.'); }
        $asesmen = Asesmen::with(['rekomendasi', 'narkotika', 'pendidikan', 'pekerjaan', 'anggotaTim'])->findOrFail($id);
        return $this->prosesCetakBeritaAcaraWord($asesmen);
    }

    private function prosesCetakBeritaAcaraWord(Asesmen $asesmen)
    {
        $templatePath = storage_path('app/templates/template_berita_acara.docx');
        if (!file_exists($templatePath)) { return redirect()->back()->with('error', 'Template Word Berita Acara tidak ditemukan di folder storage.'); }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $rawTempat = $asesmen->rekomendasi_tempat_rehab ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '-';
        $tempatBersih = $rawTempat;

        if (str_starts_with($rawTempat, 'RAWAT JALAN - ')) { $tempatBersih = trim(str_replace('RAWAT JALAN - ', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'RAWAT INAP - ')) { $tempatBersih = trim(str_replace('RAWAT INAP - ', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'REHAB DI LAPAS / RUTAN - ')) { $tempatBersih = trim(str_replace('REHAB DI LAPAS / RUTAN - ', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'TIDAK REHAB (PROSES HUKUM) - ')) { $tempatBersih = trim(str_replace('TIDAK REHAB (PROSES HUKUM) - ', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'Rawat Jalan')) { $tempatBersih = trim(str_replace('Rawat Jalan', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'Rawat Inap')) { $tempatBersih = trim(str_replace('Rawat Inap', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'Rehab di Lapas / Rutan')) { $tempatBersih = trim(str_replace('Rehab di Lapas / Rutan', '', $rawTempat)); }
        elseif (str_starts_with($rawTempat, 'Tidak Rehab (Proses Hukum)')) { $tempatBersih = trim(str_replace('Tidak Rehab (Proses Hukum)', '', $rawTempat)); }

        $tempatBersih = empty($tempatBersih) ? 'Tanpa Instansi' : trim(ltrim($tempatBersih, ' -'));

        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap ?? '-');
        $templateProcessor->setValue('no_register', $asesmen->no_register ?? '-');
        $templateProcessor->setValue('no_ba', $asesmen->no_ba ?? '-');
        $templateProcessor->setValue('ketua_tat_nama', $asesmen->ketua_tat_nama ?? '-');
        $templateProcessor->setValue('ketua_tat_nrp', $asesmen->ketua_tat_nrp ?? '-');
        $templateProcessor->setValue('no_kep_tim', $asesmen->no_kep_tim ?? '-');

        if ($asesmen->tgl_ba) {
            $tglBa = Carbon::parse($asesmen->tgl_ba);
            $templateProcessor->setValue('tgl_ba', $tglBa->translatedFormat('d'));
            $templateProcessor->setValue('hari_ba', mb_strtoupper($tglBa->translatedFormat('l'), 'UTF-8'));
            $templateProcessor->setValue('bln_ba', mb_strtoupper($tglBa->translatedFormat('F'), 'UTF-8'));
            $templateProcessor->setValue('thn_ba', $tglBa->year);
        } else {
            $templateProcessor->setValue('tgl_ba', '-'); $templateProcessor->setValue('hari_ba', '-'); $templateProcessor->setValue('bln_ba', '-'); $templateProcessor->setValue('thn_ba', '-');
        }

        $templateProcessor->setValue('tgl_asesmen', $asesmen->tgl_asesmen ? Carbon::parse($asesmen->tgl_asesmen)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('tgl_kep_tim', $asesmen->tgl_kep_tim ? mb_strtoupper(Carbon::parse($asesmen->tgl_kep_tim)->translatedFormat('d F Y'), 'UTF-8') : '-');
        $templateProcessor->setValue('alat_bukti_tgl_sk', $asesmen->alat_bukti_tgl_sk ? mb_strtoupper(Carbon::parse($asesmen->alat_bukti_tgl_sk)->translatedFormat('d F Y'), 'UTF-8') : '-');

        $medis = $asesmen->anggotaTim->where('kategori', 'medis')->values();
        $templateProcessor->cloneBlock('block_medis', max(count($medis), 1), true, true);
        if (count($medis) > 0) {
            foreach ($medis as $index => $m) {
                $i = $index + 1;
                $templateProcessor->setValue("no_medis#{$i}", $i);
                $templateProcessor->setValue("med_nama#{$i}", mb_strtoupper($m->nama, 'UTF-8'));
                $templateProcessor->setValue("med_nip#{$i}", mb_strtoupper($m->nip_nrp_sip ?? '-', 'UTF-8'));
                $templateProcessor->setValue("med_jabatan#{$i}", mb_strtoupper($m->jabatan ?? '-', 'UTF-8'));
            }
        } else {
            $templateProcessor->setValue("no_medis#1", '-'); $templateProcessor->setValue("med_nama#1", '-'); $templateProcessor->setValue("med_nip#1", '-'); $templateProcessor->setValue("med_jabatan#1", '-');
        }

        $hukum = $asesmen->anggotaTim->where('kategori', 'hukum')->values();
        $templateProcessor->cloneBlock('block_hukum', max(count($hukum), 1), true, true);
        if (count($hukum) > 0) {
            foreach ($hukum as $index => $m) {
                $i = $index + 1;
                $templateProcessor->setValue("no_hukum#{$i}", $i);
                $templateProcessor->setValue("huk_nama#{$i}", mb_strtoupper($m->nama, 'UTF-8'));
                $templateProcessor->setValue("huk_pangkat#{$i}", mb_strtoupper($m->pangkat ?? '-', 'UTF-8'));
                $templateProcessor->setValue("huk_nip#{$i}", mb_strtoupper($m->nip_nrp_sip ?? '-', 'UTF-8'));
                $templateProcessor->setValue("huk_jabatan#{$i}", mb_strtoupper($m->jabatan ?? '-', 'UTF-8'));
            }
        } else {
            $templateProcessor->setValue("no_hukum#1", '-'); $templateProcessor->setValue("huk_nama#1", '-'); $templateProcessor->setValue("huk_pangkat#1", '-'); $templateProcessor->setValue("huk_nip#1", '-'); $templateProcessor->setValue("huk_jabatan#1", '-');
        }

        $templateProcessor->setValue('narasi_medis', $asesmen->narasi_medis ?? '-');
        $templateProcessor->setValue('narasi_hukum', $asesmen->narasi_hukum ?? '-');
        $templateProcessor->setValue('alat_bukti_no_sk', $asesmen->alat_bukti_no_sk ?? '-');
        $templateProcessor->setValue('alat_bukti_dokter', $asesmen->alat_bukti_dokter ?? '-');
        $templateProcessor->setValue('alat_bukti_hasil', $asesmen->alat_bukti_hasil ?? '-');
        $templateProcessor->setValue('status_klien', $asesmen->status_klien ?? 'PENYALAHGUNA');
        $templateProcessor->setValue('kesimpulan_jenis_zat', $asesmen->kesimpulan_jenis_zat ?? '-');
        $templateProcessor->setValue('kesimpulan_pola_pakai', $asesmen->kesimpulan_pola_pakai ?? '-');
        $templateProcessor->setValue('kesimpulan_kategori', $asesmen->kesimpulan_kategori ?? '-');
        $templateProcessor->setValue('diagnosis_medis', $asesmen->diagnosis_medis ?? '-');
        $templateProcessor->setValue('rekomendasi_tempat_rehab', $tempatBersih);
        $templateProcessor->setValue('rekomendasi_durasi', $asesmen->rekomendasi_durasi ?? '-');
        $templateProcessor->setValue('rekomendasi_keterangan', $asesmen->rekomendasi_keterangan ?? '-');

        $fileName = 'Berita_Acara_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function rekomendasi(string $id)
    {
        if (!Gate::allows('akses-dokumen')) { abort(403, 'Anda tidak memiliki akses ke Surat Rekomendasi.'); }

        $asesmen = Asesmen::findOrFail($id);
        $riwayat_kepada = collect(); $riwayat_no_keputusan = collect(); $riwayat_tentang = collect();
        $riwayat_narkotika = collect(); $riwayat_perawatan = collect(); $riwayat_diagnosis = collect();

        return view('asesmen.rekomendasi', compact(
            'asesmen', 'riwayat_kepada', 'riwayat_no_keputusan', 'riwayat_tentang', 'riwayat_narkotika', 'riwayat_perawatan', 'riwayat_diagnosis'
        ));
    }

    public function unduhRekomendasi(Request $request, string $id)
    {
        if (!Gate::allows('akses-dokumen')) { abort(403, 'Anda tidak memiliki akses ke Surat Rekomendasi.'); }

        $asesmen = Asesmen::with(['rekomendasi', 'narkotika'])->findOrFail($id);

        $asesmen->no_surat_rekomendasi = $request->input('no_surat_rekomendasi');
        $asesmen->kepada_yth = $request->input('kepada_yth');
        $asesmen->no_keputusan = $request->input('no_keputusan');
        $asesmen->tentang_permohonan = $request->input('tentang_permohonan');
        $asesmen->kewarganegaraan = $request->input('kewarganegaraan');
        $asesmen->nama_narkotika_medis = $request->input('nama_narkotika_medis');
        $asesmen->lama_perawatan = $request->input('lama_perawatan');
        $asesmen->keterangan_diagnosis = $request->input('keterangan_diagnosis');
        $asesmen->rekomendasi_keterangan = $request->input('rekomendasi_keterangan');
        $asesmen->rekomendasi_tempat_rehab = $request->input('rekomendasi_tempat_rehab');
        $asesmen->rekomendasi_durasi = $request->input('lama_perawatan');
        $asesmen->tgl_rekomendasi = $request->input('tgl_rekomendasi');
        $asesmen->tgl_keputusan = $request->input('tgl_keputusan');

        $asesmen->save();

        if ($request->input('action') === 'save') {
            return redirect()->route('asesmen.show', $asesmen->id)->with('success', 'Data Form Rekomendasi berhasil disimpan!');
        }

        $templatePath = storage_path('app/templates/template_rekomendasi.docx');
        if (!file_exists($templatePath)) { return redirect()->back()->with('error', 'Template Word tidak ditemukan.'); }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $rawTempatRek = $asesmen->rekomendasi_tempat_rehab ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? $asesmen->rekomendasi_input ?? '-';
        $tempatBersihRek = $rawTempatRek;

        if (str_starts_with($rawTempatRek, 'RAWAT JALAN - ')) { $tempatBersihRek = trim(str_replace('RAWAT JALAN - ', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'RAWAT INAP - ')) { $tempatBersihRek = trim(str_replace('RAWAT INAP - ', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'REHAB DI LAPAS / RUTAN - ')) { $tempatBersihRek = trim(str_replace('REHAB DI LAPAS / RUTAN - ', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'TIDAK REHAB (PROSES HUKUM) - ')) { $tempatBersihRek = trim(str_replace('TIDAK REHAB (PROSES HUKUM) - ', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'RAWAT JALAN')) { $tempatBersihRek = trim(str_replace('RAWAT JALAN', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'RAWAT INAP')) { $tempatBersihRek = trim(str_replace('RAWAT INAP', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'REHAB DI LAPAS / RUTAN')) { $tempatBersihRek = trim(str_replace('REHAB DI LAPAS / RUTAN', '', $rawTempatRek)); }
        elseif (str_starts_with($rawTempatRek, 'TIDAK REHAB (PROSES HUKUM)')) { $tempatBersihRek = trim(str_replace('TIDAK REHAB (PROSES HUKUM)', '', $rawTempatRek)); }

        $tempatBersihRek = empty($tempatBersihRek) ? 'TANPA INSTANSI' : trim(ltrim($tempatBersihRek, ' -'));

        $templateProcessor->setValue('no_surat_rekomendasi', $asesmen->no_surat_rekomendasi ?? '-');
        $templateProcessor->setValue('tgl_rekomendasi', $asesmen->tgl_rekomendasi ? mb_strtoupper(Carbon::parse($asesmen->tgl_rekomendasi)->translatedFormat('d F Y'), 'UTF-8') : '-');
        $templateProcessor->setValue('kepada_yth', $asesmen->kepada_yth ?? '-');
        $templateProcessor->setValue('no_keputusan', $asesmen->no_keputusan ?? '-');
        $templateProcessor->setValue('tgl_keputusan', $asesmen->tgl_keputusan ? mb_strtoupper(Carbon::parse($asesmen->tgl_keputusan)->translatedFormat('d F Y'), 'UTF-8') : '-');
        $templateProcessor->setValue('tentang_permohonan', $asesmen->tentang_permohonan ?? '-');
        $templateProcessor->setValue('kewarganegaraan', $asesmen->kewarganegaraan ?? 'INDONESIA (WNI)');
        $templateProcessor->setValue('nama_narkotika', $asesmen->nama_narkotika_medis ?? '-');
        $templateProcessor->setValue('keterangan_diagnosis', $asesmen->keterangan_diagnosis ?? '-');
        $templateProcessor->setValue('lama_perawatan', $asesmen->lama_perawatan ?? '-');
        $templateProcessor->setValue('rekomendasi_keterangan', $asesmen->rekomendasi_keterangan ?? '-');
        $templateProcessor->setValue('nama_lengkap', $asesmen->nama_lengkap);
        $templateProcessor->setValue('nik', $asesmen->nik);
        $templateProcessor->setValue('tempat_lahir', $asesmen->tempat_lahir ?? '-');
        $templateProcessor->setValue('tgl_lahir', $asesmen->tgl_lahir ? mb_strtoupper(Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y'), 'UTF-8') : '-');

        $jk = $asesmen->jenis_kelamin == 'L' ? 'LAKI-LAKI' : ($asesmen->jenis_kelamin == 'P' ? 'PEREMPUAN' : '-');
        $templateProcessor->setValue('jenis_kelamin', $jk);

        $templateProcessor->setValue('alamat_ktp', $asesmen->alamat_ktp ?? '-');
        $templateProcessor->setValue('alamat_domisili', $asesmen->alamat_domisili ?? '-');
        $templateProcessor->setValue('no_surat_pengajuan', $asesmen->no_surat_pengajuan ?? '-');

        $tgl_pelaksanaan = $asesmen->tgl_pelaksanaan ? mb_strtoupper(Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('d F Y'), 'UTF-8') : '-';
        $hari_pelaksanaan = $asesmen->tgl_pelaksanaan ? mb_strtoupper(Carbon::parse($asesmen->tgl_pelaksanaan)->translatedFormat('l'), 'UTF-8') : '-';

        $templateProcessor->setValue('tgl_pelaksanaan', $tgl_pelaksanaan);
        $templateProcessor->setValue('hari', $hari_pelaksanaan);
        $templateProcessor->setValue('jenis_narkotika', $asesmen->narkotika->jenis_narkotika ?? '-');
        $templateProcessor->setValue('tingkat_ketergantungan', $asesmen->tingkat_ketergantungan ?? '-');
        $templateProcessor->setValue('rekomendasi_tat', $tempatBersihRek);

        $fileName = 'Surat_Rekomendasi_TAT_' . str_replace(' ', '_', $asesmen->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp_' . $fileName);
        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    public function exportExcel(Request $request)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.'); }

        $type = $request->get('type', 'default');
        if ($type === 'cc') {
            $namaFile = 'Data_Case_Conference_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new \App\Exports\AsesmenCCExport($request), $namaFile);
        } elseif ($type === 'rekap') {
            $namaFile = 'Rekap_Data_TAT_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new \App\Exports\AsesmenRekapExport($request), $namaFile);
        }

        $namaFile = 'Export_Asesmen_TAT_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new AsesmenExport($request), $namaFile);
    }

    public function updateTanggal(Request $request, string $id)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses untuk mengelola data.'); }
        $request->validate(['tanggal_ditambahkan' => 'required|date']);
        $asesmen = Asesmen::findOrFail($id);
        $jamAsli = $asesmen->created_at ? \Carbon\Carbon::parse($asesmen->created_at)->format('H:i:s') : '00:00:00';
        $asesmen->created_at = $request->tanggal_ditambahkan . ' ' . $jamAsli;
        $asesmen->save();
        return redirect()->back()->with('success', 'Tanggal klien ditambahkan berhasil diperbarui!');
    }

    public function destroyPendidikan(string $id)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses.'); }
        try {
            $pendidikan = \App\Models\Pendidikan::findOrFail($id);
            $pendidikan->delete();
            return redirect()->back()->with('success', 'Pilihan Pendidikan berhasil dihapus secara permanen dari sistem!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') { return redirect()->back()->with('error', 'Gagal menghapus! Pilihan pendidikan ini tidak bisa dihapus karena pilihan ini sudah digunakan dalam penambahan data Klien.'); }
            return redirect()->back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) { return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage()); }
    }

    public function destroyRekomendasi(string $id)
    {
        if (!Gate::allows('manage-data')) { abort(403, 'Anda tidak memiliki hak akses.'); }
        try {
            $rekomendasi = \App\Models\Rekomendasi::findOrFail($id);
            $rekomendasi->delete();
            return redirect()->back()->with('success', 'Opsi Rekomendasi berhasil dihapus secara permanen dari sistem!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') { return redirect()->back()->with('error', 'Gagal menghapus! Pilihan rekomendasi ini tidak bisa dihapus karena sudah digunakan dalam data Klien.'); }
            return redirect()->back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) { return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage()); }
    }
}
