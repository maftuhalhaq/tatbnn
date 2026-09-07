<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Asesmen - {{ $asesmen->nama_lengkap }}</title>
    <style>
        @page { margin: 2cm; }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 12px; 
            line-height: 1.5; 
            color: #1e293b; 
        }
        .header { 
            text-align: center; 
            border-bottom: 3px solid #1e3a8a; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 18px; 
            text-transform: uppercase; 
            color: #1e3a8a; 
            font-weight: bold;
        }
        .header h2 { 
            margin: 5px 0 0 0; 
            font-size: 13px; 
            color: #64748b; 
            font-weight: normal;
        }
        .section-title {
            background-color: #f1f5f9;
            color: #1e3a8a;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            border-left: 4px solid #3b82f6;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-radius: 0 4px 4px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table-data { margin-bottom: 15px; }
        .table-data td { 
            padding: 6px 8px; 
            vertical-align: top; 
            border-bottom: 1px solid #e2e8f0; 
        }
        .table-data td.label { 
            font-weight: bold; 
            width: 30%; 
            color: #475569; 
            font-size: 11px;
            text-transform: uppercase;
        }
        .table-data td.colon { width: 2%; text-align: center; font-weight: bold; }
        .table-data td.value { width: 68%; font-weight: bold; color: #0f172a; }
        
        .box-text {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            border-radius: 6px;
            background-color: #f8fafc;
            margin-bottom: 15px;
            text-align: justify;
        }
        .box-title {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .highlight-box {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- KOP SURAT / HEADER -->
    <div class="header">
        <h1>RINGKASAN DATA ASESMEN TERPADU (TAT)</h1>
        <h2>Badan Narkotika Nasional</h2>
        <div style="margin-top: 5px; font-size: 11px;">Dicetak pada: {{ date('d F Y - H:i') }} | Status: <span style="font-weight: bold; color: {{ $asesmen->status_kelengkapan === 'Data Lengkap' ? '#10b981' : '#f59e0b' }}">{{ $asesmen->status_kelengkapan ?? 'Data Belum Lengkap' }}</span></div>
    </div>

    <!-- 1. DATA ADMINISTRASI -->
    <div class="section-title">1. Data Administrasi & Registrasi</div>
    <table class="table-data">
        <tr>
            <td class="label">Nomor Register</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->no_register ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Asal Pengajuan / LKN</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->asal_pengajuan ?? '-' }} / {{ $asesmen->no_lkn ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tgl. Berkas / Pelaksanaan</td>
            <td class="colon">:</td>
            <td class="value">
                {{ $asesmen->tgl_berkas ? \Carbon\Carbon::parse($asesmen->tgl_berkas)->format('d-m-Y') : '-' }} / 
                {{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->format('d-m-Y') : '-' }}
            </td>
        </tr>
    </table>

    <!-- 2. IDENTITAS KLIEN -->
    <div class="section-title">2. Identitas Profil Klien</div>
    <table class="table-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td class="value" style="font-size: 14px;">{{ $asesmen->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">NIK / No. HP</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->nik ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp; {{ $asesmen->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->tempat_lahir ?? '-' }}, {{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin / Agama</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }} &nbsp;&nbsp;|&nbsp;&nbsp; {{ $asesmen->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pendidikan / Pekerjaan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->pendidikan->nama_pendidikan ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp; {{ $asesmen->pekerjaan->nama_pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat KTP</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->alamat_ktp ?? '-' }}</td>
        </tr>
    </table>

    <!-- 3. PERKARA HUKUM & BARANG BUKTI -->
    <div class="section-title">3. Perkara Hukum & Barang Bukti</div>
    <table class="table-data">
        <tr>
            <td class="label">Status Hukum</td>
            <td class="colon">:</td>
            <td class="value"><span class="badge">{{ $asesmen->status_hukum ?? 'Belum Diinput' }}</span></td>
        </tr>
        <tr>
            <td class="label">Pasal Sangkaan</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->pasal_sangkaan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Zat/Narkotika & Berat</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->narkotika->jenis_narkotika ?? '-' }} ({{ $asesmen->berat_bb ? $asesmen->berat_bb . ' gram' : '-' }})</td>
        </tr>
        <tr>
            <td class="label">Hasil Tes Urine</td>
            <td class="colon">:</td>
            <td class="value" style="color: {{ strpos(strtoupper($asesmen->tes_urine), 'POSITIF') !== false ? '#dc2626' : '#059669' }};">
                {{ $asesmen->tes_urine ?? 'Belum ada data' }}
            </td>
        </tr>
    </table>
    
    <div class="box-text" style="margin-top:-5px;">
        <div class="box-title">Detail Riwayat Barang Bukti</div>
        <table width="100%" style="font-size: 11px;">
            <tr><td width="20%">Dapat Dari</td><td width="3%">:</td><td>{{ $asesmen->dapat_dari_siapa ?? '-' }}</td></tr>
            <tr><td>Cara Dapat</td><td>:</td><td>{{ $asesmen->cara_mendapatkan ?? '-' }}</td></tr>
            <tr><td>Keterlibatan Jaringan</td><td>:</td><td>{{ $asesmen->keterlibatan_jaringan ?? '-' }}</td></tr>
            <tr><td>Deskripsi Bukti</td><td>:</td><td>{{ $asesmen->deskripsi_bb ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- BREAK PAGE UNTUK NARASI AGAR TIDAK TERPOTONG -->
    <div class="page-break"></div>

    <!-- 4. ANALISIS CASE CONFERENCE -->
    <div class="section-title">4. Analisis Sidang Case Conference</div>
    
    <table width="100%" cellpadding="5">
        <tr>
            <td width="50%" valign="top">
                <div class="box-text">
                    <div class="box-title">Analisis Aspek Hukum</div>
                    {!! nl2br(e($asesmen->aspek_hukum ?? 'Belum ada data')) !!}
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="box-text">
                    <div class="box-title">Analisis Aspek Medis</div>
                    {!! nl2br(e($asesmen->aspek_medis ?? 'Belum ada data')) !!}
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="top">
                <div class="box-text">
                    <div class="box-title">Kondisi Psikologi & Fisik</div>
                    <strong>Fisik:</strong> {{ $asesmen->kesehatan_fisik ?? '-' }}<br><br>
                    <strong>Psikologi:</strong> {{ $asesmen->psikologi ?? '-' }}
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="box-text">
                    <div class="box-title">Riwayat Pemakaian & Lingkungan</div>
                    <strong>Alasan:</strong> {{ $asesmen->alasan_penggunaan ?? '-' }}<br>
                    <strong>Pola Pakai:</strong> {{ $asesmen->pola_pemakaian ?? '-' }}<br>
                    <strong>Ketergantungan:</strong> {{ $asesmen->tingkat_ketergantungan ?? '-' }}<br>
                    <strong>Lingkungan:</strong> {{ $asesmen->kondisi_lingkungan ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="box-text" style="background-color: #fffbeb; border-color: #fcd34d;">
        <div class="box-title" style="color: #b45309; border-bottom-color: #fde68a;">Saran / Catatan Case Conference</div>
        {{ $asesmen->saran_case_conference ?? 'Tidak ada saran khusus yang tercatat.' }}
    </div>

    <!-- 5. KESIMPULAN DIAGNOSTIK & REKOMENDASI -->
    <div class="section-title">5. Kesimpulan Diagnostik & Keputusan Rekomendasi TAT</div>
    
    <table class="table-data">
        <tr>
            <td class="label">Status Klien</td>
            <td class="colon">:</td>
            <td class="value">{{ ucwords($asesmen->status_klien ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">Zat Yang Dipakai</td>
            <td class="colon">:</td>
            <td class="value">{{ $asesmen->kesimpulan_jenis_zat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Diagnosis Medis Final</td>
            <td class="colon">:</td>
            <td class="value" style="color: #4338ca;">{{ $asesmen->diagnosis_medis ?? 'Belum ada diagnosis' }}</td>
        </tr>
    </table>

    <!-- KOTAK REKOMENDASI UTAMA -->
    <div class="highlight-box">
        <div style="font-size: 11px; font-weight: bold; color: #047857; text-transform: uppercase; margin-bottom: 5px;">Keputusan Tempat Rehabilitasi</div>
        <div style="font-size: 20px; font-weight: bold; color: #064e3b; margin-bottom: 5px;">
            {{ $asesmen->rekomendasi_tempat_rehab ?? $asesmen->rekomendasi_input ?? 'BELUM DITENTUKAN' }}
        </div>
        <div style="font-size: 13px; color: #047857; border-top: 1px solid #6ee7b7; padding-top: 5px;">
            <strong>Durasi / Lama Rawat:</strong> {{ $asesmen->rekomendasi_durasi ?? '-' }}
        </div>
    </div>

    <div style="margin-top: 15px; font-size: 11px; color: #475569; font-style: italic; text-align: justify;">
        <strong>Keterangan Rekomendasi Hukum:</strong> "{{ $asesmen->rekomendasi_keterangan ?? 'Belum ada keterangan hukum dari form Rekomendasi' }}"
    </div>

    <div class="footer">
        <p>Dokumen ini di-*generate* secara otomatis oleh Sistem TAT BNN.</p>
    </div>

</body>
</html>