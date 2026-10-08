@php
    // LOGIKA KELENGKAPAN DATA (REVISI MUTLAK 100% FIELD)
    $kolomWajib = [
        'no_register', 'no_bln', 'asal_pengajuan', 'no_surat_pengajuan', 'no_lkn',
        'tgl_surat', 'tgl_berkas', 'tgl_pelaksanaan', 'tgl_tangkap',
        'nama_lengkap', 'nik', 'kewarganegaraan', 'agama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'no_hp',
        'pendidikan_id', 'pekerjaan_id', 'penghasilan_rata_rata', 'alamat_ktp', 'alamat_domisili',
        'narkotika_id', 'berat_bb', 'deskripsi_bb', 'pasal_sangkaan',
        'status_hukum', 'keterlibatan_jaringan', 'cara_mendapatkan', 'dapat_dari_siapa',
        'tes_urine', 'hasil_asesmen_hukum', 'hasil_asesmen_medis',
        'aspek_hukum', 'aspek_medis', 'kesehatan_fisik', 'psikologi',
        'alasan_penggunaan', 'kondisi_keluarga', 'tingkat_ketergantungan', 'pola_pemakaian', 'kondisi_lingkungan', 'saran_case_conference'
    ];

    $isDataUtamaLengkap = true;
    foreach($kolomWajib as $kol) {
        if ($asesmen->$kol === null || trim((string)$asesmen->$kol) === '') {
            $isDataUtamaLengkap = false;
            break;
        }
    }

    // Berita Acara Terisi (Minimal Nomor BA & Ketua TAT)
    $isBaLengkap = !empty($asesmen->no_ba) && !empty($asesmen->ketua_tat_nama);

    // Rekomendasi Terisi (Minimal No Surat & Kepada Yth)
    $isRekomLengkap = !empty($asesmen->no_surat_rekomendasi) && !empty($asesmen->kepada_yth);

    // Final Lengkap Status
    $isLengkapTotal = ($isDataUtamaLengkap && $isBaLengkap && $isRekomLengkap);
    $teksStatus = $isLengkapTotal ? 'Data Lengkap' : 'Data Belum Lengkap';
    $warnaStatus = $isLengkapTotal ? '#10b981' : '#f59e0b';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Asesmen - {{ $asesmen->nama_lengkap }}</title>
    <style>
        @page { margin: 1.5cm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px; /* Dikecilkan sedikit agar muat banyak data */
            line-height: 1.4;
            color: #1e293b;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #1e3a8a;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #64748b;
            font-weight: normal;
        }
        .section-title {
            background-color: #f1f5f9;
            color: #1e3a8a;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: bold;
            border-left: 4px solid #3b82f6;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-data { margin-bottom: 10px; }
        .table-data td {
            padding: 4px 6px;
            vertical-align: top;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-data td.label {
            font-weight: bold;
            width: 25%;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
        }
        .table-data td.colon { width: 2%; text-align: center; font-weight: bold; }
        .table-data td.value { width: 73%; color: #0f172a; }

        .box-text {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            border-radius: 4px;
            background-color: #f8fafc;
            margin-bottom: 10px;
            text-align: justify;
        }
        .box-title {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .highlight-box {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: center;
        }
        .badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #64748b;
            font-style: italic;
        }
        .page-break { page-break-after: always; }
        .half-width { width: 48%; display: inline-block; vertical-align: top; }
    </style>
</head>
<body>

    <!-- KOP SURAT / HEADER -->
    <div class="header">
        <h1>RINGKASAN DATA ASESMEN TERPADU (TAT)</h1>
        <h2>Badan Narkotika Nasional</h2>
        <div style="margin-top: 5px; font-size: 10px;">Dicetak pada: {{ date('d F Y - H:i') }} | Status Kelengkapan: <span style="font-weight: bold; color: {{ $warnaStatus }}">{{ $teksStatus }}</span></div>
    </div>

    <!-- 1. DATA ADMINISTRASI -->
    <div class="section-title">1. Data Administrasi & Registrasi</div>
    <table class="table-data">
        <tr>
            <td class="label">No / Bulan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->no_bln ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Register</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $asesmen->no_register ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Asal Pengajuan / No LKN</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->asal_pengajuan ?? '-' }} / {{ $asesmen->no_lkn ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Surat Pengajuan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->no_surat_pengajuan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tgl. Surat / Tgl. Berkas</td>
            <td class="colon">:</td>
            <td class="value">
                {{ $asesmen->tgl_surat ? \Carbon\Carbon::parse($asesmen->tgl_surat)->format('d-m-Y') : '-' }} /
                {{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('d-m-Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Tgl. Tangkap / Tgl. Pelaksanaan</td>
            <td class="colon">:</td>
            <td class="value">
                {{ $asesmen->tgl_tangkap ? \Carbon\Carbon::parse($asesmen->tgl_tangkap)->format('d-m-Y') : '-' }} /
                <strong>{{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('d-m-Y') : '-' }}</strong>
            </td>
        </tr>
    </table>

    <!-- 2. IDENTITAS KLIEN -->
    <div class="section-title">2. Identitas Profil Klien</div>
    <table class="table-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td class="value" style="font-size: 13px; font-weight: bold;">{{ $asesmen->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">NIK KTP</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->tempat_lahir ?? '-' }}, {{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin / Agama</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-Laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-') }} &nbsp;|&nbsp; {{ $asesmen->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kewarganegaraan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->kewarganegaraan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pendidikan / Pekerjaan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->pendidikan_input ?? $asesmen->pendidikan->nama_pendidikan ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->pekerjaan_input ?? $asesmen->pekerjaan->nama_pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Handphone</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Penghasilan Rata-rata</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->penghasilan_rata_rata ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Sesuai KTP</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->alamat_ktp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Domisili Saat Ini</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->alamat_domisili ?? '-' }}</td>
        </tr>
    </table>

    <!-- 3. PERKARA HUKUM & BARANG BUKTI -->
    <div class="section-title">3. Perkara Hukum & Barang Bukti</div>
    <table class="table-data">
        <tr>
            <td class="label">Status Hukum</td>
            <td class="colon">:</td>
            <td class="value"><span class="badge">{{ $asesmen->status_hukum ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label">Keterlibatan Jaringan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->keterlibatan_jaringan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Zat/Narkotika & Berat BB</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $asesmen->narkotika->jenis_narkotika ?? '-' }}</strong> ({{ $asesmen->berat_bb ? $asesmen->berat_bb . ' Gram' : '-' }})</td>
        </tr>
        <tr>
            <td class="label">Cara / Dapat Dari Siapa</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->cara_mendapatkan ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->dapat_dari_siapa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Hasil Tes Urine</td>
            <td class="colon">:</td>
            <td class="value" style="font-weight: bold; color: {{ strpos(strtoupper($asesmen->tes_urine), 'POSITIF') !== false ? '#dc2626' : (strpos(strtoupper($asesmen->tes_urine), 'NEGATIF') !== false ? '#059669' : '#0f172a') }};">
                {{ $asesmen->tes_urine ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="box-text" style="margin-top:-5px;">
        <div class="box-title">Detail / Deskripsi Barang Bukti</div>
        {{ $asesmen->deskripsi_bb ?? '-' }}
    </div>

    <!-- 4. ANALISIS CASE CONFERENCE -->
    <div class="section-title">4. Analisis Sidang Case Conference</div>
    <table width="100%" cellpadding="5" style="margin-bottom: 10px;">
        <tr>
            <td width="50%" valign="top">
                <div class="box-text" style="height: 100%;">
                    <div class="box-title">Analisis Aspek Hukum</div>
                    {!! nl2br(e($asesmen->aspek_hukum ?? '-')) !!}
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="box-text" style="height: 100%;">
                    <div class="box-title">Analisis Aspek Medis</div>
                    {!! nl2br(e($asesmen->aspek_medis ?? '-')) !!}
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="top">
                <div class="box-text" style="height: 100%;">
                    <div class="box-title">Kondisi Psikologi & Fisik</div>
                    <strong>Fisik:</strong> {{ $asesmen->kesehatan_fisik ?? '-' }}<br><br>
                    <strong>Psikologi:</strong> {{ $asesmen->psikologi ?? '-' }}
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="box-text" style="height: 100%;">
                    <div class="box-title">Riwayat Pemakaian & Lingkungan</div>
                    <strong>Alasan Pakai:</strong> {{ $asesmen->alasan_penggunaan ?? '-' }}<br>
                    <strong>Pola Pakai:</strong> {{ $asesmen->pola_pemakaian ?? '-' }}<br>
                    <strong>Ketergantungan:</strong> {{ $asesmen->tingkat_ketergantungan ?? '-' }}<br>
                    <strong>Kond. Keluarga:</strong> {{ $asesmen->kondisi_keluarga ?? '-' }}<br>
                    <strong>Lingkungan:</strong> {{ $asesmen->kondisi_lingkungan ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="box-text" style="background-color: #fffbeb; border-color: #fcd34d;">
        <div class="box-title" style="color: #b45309; border-bottom-color: #fde68a;">Saran Sidang Case Conference</div>
        <ul style="margin: 0; padding-left: 15px; font-weight: bold;">
            @php
                $saranList = array_filter(array_map('trim', explode(',', $asesmen->saran_case_conference ?? '')));
            @endphp
            @if(count($saranList) > 0)
                @foreach($saranList as $saran) <li>{{ $saran }}</li> @endforeach
            @else
                <li>-</li>
            @endif
        </ul>
    </div>

    <!-- BREAK PAGE -->
    <div class="page-break"></div>

    <!-- 5. HASIL ASESMEN FINAL & PELAKSANAAN -->
    <div class="section-title">5. Hasil Asesmen Final & Pelaksanaan</div>
    <table class="table-data">
        <tr>
            <td class="label">Pasal Yang Disangkakan</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $asesmen->pasal_sangkaan ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Hasil Asesmen Hukum Final</td>
            <td class="colon">:</td>
            <td class="value">{!! nl2br(e($asesmen->hasil_asesmen_hukum ?? '-')) !!}</td>
        </tr>
        <tr>
            <td class="label">Hasil Rujukan Medis Final</td>
            <td class="colon">:</td>
            <td class="value">{!! nl2br(e($asesmen->hasil_asesmen_medis ?? '-')) !!}</td>
        </tr>
        <tr>
            <td class="label">Status Pelaksanaan Rekomendasi</td>
            <td class="colon">:</td>
            <td class="value">
                @if($asesmen->pelaksanaan === 'YA')
                    <strong style="color: #059669;">SUDAH DILAKSANAKAN (YA)</strong>
                @elseif($asesmen->pelaksanaan === 'TIDAK')
                    <strong style="color: #dc2626;">BELUM / BATAL DILAKSANAKAN (TIDAK)</strong>
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <!-- 6. DATA SURAT BERITA ACARA -->
    <div class="section-title">6. Data Surat Berita Acara (B.A)</div>
    @if($asesmen->no_ba || $asesmen->ketua_tat_nama)
        <table class="table-data">
            <tr>
                <td class="label">Nama / NRP Ketua TAT</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->ketua_tat_nama ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->ketua_tat_nrp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. / Tanggal SK Tim TAT</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->no_kep_tim ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->tgl_kep_tim ? \Carbon\Carbon::parse($asesmen->tgl_kep_tim)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Rapat B.A</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->tgl_ba ? \Carbon\Carbon::parse($asesmen->tgl_ba)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. / Tgl SK Narkotika</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->alat_bukti_no_sk ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->alat_bukti_tgl_sk ? \Carbon\Carbon::parse($asesmen->alat_bukti_tgl_sk)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Dokter Pemeriksa</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->alat_bukti_dokter ?? '-' }}</td>
            </tr>
        </table>

        <table width="100%" cellpadding="5" style="margin-bottom: 10px;">
            <tr>
                <td width="50%" valign="top">
                    <div class="box-text">
                        <div class="box-title">Narasi Tim Hukum</div>
                        {!! nl2br(e($asesmen->narasi_hukum ?? '-')) !!}
                    </div>
                </td>
                <td width="50%" valign="top">
                    <div class="box-text">
                        <div class="box-title">Narasi Tim Medis</div>
                        {!! nl2br(e($asesmen->narasi_medis ?? '-')) !!}
                    </div>
                </td>
            </tr>
        </table>
    @else
        <div style="font-style: italic; color: #64748b; padding-left: 5px;">Belum ada data Berita Acara yang diinput.</div>
    @endif

    <!-- 7. DATA SURAT REKOMENDASI -->
    <div class="section-title">7. Data Surat Rekomendasi</div>
    @if($asesmen->no_surat_rekomendasi || $asesmen->kepada_yth)
        <table class="table-data">
            <tr>
                <td class="label">Nomor Surat BNN</td>
                <td class="colon">:</td>
                <td class="value"><strong>{{ $asesmen->no_surat_rekomendasi ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Tujuan (Kepada Yth)</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->kepada_yth ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Surat Rekomendasi</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->tgl_rekomendasi ? \Carbon\Carbon::parse($asesmen->tgl_rekomendasi)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">No / Tgl Keputusan Tim TAT</td>
                <td class="colon">:</td>
                <td class="value">{{ $asesmen->no_keputusan ?? '-' }} &nbsp;|&nbsp; {{ $asesmen->tgl_keputusan ? \Carbon\Carbon::parse($asesmen->tgl_keputusan)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tentang Permohonan</td>
                <td class="colon">:</td>
                <td class="value">{!! nl2br(e($asesmen->tentang_permohonan ?? '-')) !!}</td>
            </tr>
        </table>
    @else
        <div style="font-style: italic; color: #64748b; padding-left: 5px;">Belum ada data Surat Rekomendasi yang diinput.</div>
    @endif

    <!-- 8. KESIMPULAN & HIGHLIGHT -->
    <div class="highlight-box">
        <div style="font-size: 11px; font-weight: bold; color: #047857; text-transform: uppercase; margin-bottom: 8px;">Keputusan Akhir & Rekomendasi TAT</div>

        <table width="100%" style="text-align: left; margin-bottom: 10px; font-size: 11px;">
            <tr>
                <td width="25%" style="font-weight:bold; color:#064e3b;">Status Klien</td><td width="2%">:</td>
                <td>{{ ucwords($asesmen->status_klien ?? '-') }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold; color:#064e3b;">Zat / Golongan Narkotika</td><td>:</td>
                <td>{{ $asesmen->kesimpulan_jenis_zat ?? $asesmen->nama_narkotika_medis ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold; color:#064e3b;">Diagnosis Medis Final</td><td>:</td>
                <td style="font-weight:bold;">{{ $asesmen->diagnosis_medis ?? '-' }}</td>
            </tr>
        </table>

        <div style="background-color: #d1fae5; padding: 10px; border-radius: 4px; border: 1px solid #6ee7b7;">
            <span style="font-size: 10px; color: #064e3b; text-transform:uppercase;">Tempat Rehabilitasi</span><br>
            <strong style="font-size: 16px; color: #064e3b;">{{ $asesmen->rekomendasi_input ?? $asesmen->rekomendasi->tempat_rehabilitasi ?? 'BELUM DITENTUKAN' }}</strong><br>
            <span style="font-size: 12px; color: #047857;"><strong>Durasi Rawat:</strong> {{ $asesmen->lama_perawatan ?? $asesmen->rekomendasi_durasi ?? '-' }}</span>
        </div>

        <div style="margin-top: 10px; font-size: 10px; color: #064e3b; font-style: italic; text-align: left;">
            <strong>Keterangan Diagnosis:</strong> "{{ $asesmen->keterangan_diagnosis ?? '-' }}" <br>
            <strong>Keterangan Rekomendasi Hukum:</strong> "{{ $asesmen->rekomendasi_keterangan ?? $asesmen->keterangan_tambahan ?? '-' }}"
        </div>
    </div>

    <div class="footer">
        <p>Dokumen cetak ini di-generate secara otomatis oleh Sistem TAT BNN dan telah disesuaikan dengan kelengkapan data web.</p>
    </div>

</body>
</html>
