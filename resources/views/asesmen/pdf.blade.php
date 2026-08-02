<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara TAT - {{ $asesmen->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px 40px;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .uppercase {
            text-transform: uppercase;
        }
        .kop-surat {
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h3, .kop-surat h4, .kop-surat p {
            margin: 0;
            padding: 0;
        }
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-surat span {
            text-decoration: underline;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .tabel-data td {
            vertical-align: top;
            padding: 3px 0;
        }
        .tabel-data .label {
            width: 30%;
        }
        .tabel-data .titik-dua {
            width: 3%;
        }
        .paragraf {
            text-indent: 40px;
            text-align: justify;
        }
        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }
        .ttd-box {
            float: right;
            width: 40%;
            text-align: center;
        }
        /* Clearfix untuk float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <!-- Kop Surat (Sesuaikan dengan format instansi) -->
    <div class="kop-surat text-center">
        <h4>BADAN NARKOTIKA NASIONAL REPUBLIK INDONESIA</h4>
        <h3 class="font-bold uppercase">KOTA MALANG</h3>
        <p>Jl. Mayjend Sungkono No. 55, Kota Malang</p>
    </div>

    <!-- Judul Surat -->
    <div class="judul-surat">
        <span>BERITA ACARA HASIL ASESMEN TERPADU</span><br>
        Nomor: {{ $asesmen->no_surat_pengajuan ?? '..../TAT/BNN/'.date('Y') }}
    </div>

    <!-- Paragraf Pembuka -->
    <p class="paragraf">
        Pada hari ini, tanggal {{ $asesmen->tgl_pelaksanaan ? \Carbon\Carbon::parse($asesmen->tgl_pelaksanaan)->isoFormat('D MMMM Y') : '......................' }}, berdasarkan permohonan asesmen terpadu, Tim Asesmen Terpadu (TAT) BNN Kota Malang telah melakukan asesmen terhadap tersangka penyalahgunaan narkotika dengan identitas sebagai berikut:
    </p>

    <!-- Tabel Data Diri -->
    <table class="tabel-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="titik-dua">:</td>
            <td><strong>{{ $asesmen->nama_lengkap }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Induk Kependudukan (NIK)</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->tempat_lahir ?? '-' }}, {{ $asesmen->tgl_lahir ? \Carbon\Carbon::parse($asesmen->tgl_lahir)->isoFormat('D MMMM Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->jenis_kelamin == 'L' ? 'Laki-laki' : ($asesmen->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
        </tr>
        <tr>
            <td class="label">Agama</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Sesuai KTP</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->alamat_ktp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Domisili</td>
            <td class="titik-dua">:</td>
            <td>{{ $asesmen->alamat_domisili ?? '-' }}</td>
        </tr>
    </table>

    <br>
    <p class="font-bold">HASIL ASESMEN:</p>
    
    <!-- Tabel Hasil -->
    <table class="tabel-data">
        <tr>
            <td class="label">Asesmen Hukum</td>
            <td class="titik-dua">:</td>
            <td style="text-align: justify;">{{ $asesmen->hasil_asesmen_hukum ?? 'Belum ada data hasil asesmen hukum.' }}</td>
        </tr>
        <tr>
            <td class="label" style="padding-top: 10px;">Asesmen Medis</td>
            <td class="titik-dua" style="padding-top: 10px;">:</td>
            <td style="text-align: justify; padding-top: 10px;">{{ $asesmen->hasil_asesmen_medis ?? 'Belum ada data hasil asesmen medis.' }}</td>
        </tr>
    </table>

    <br>
    <p class="font-bold">REKOMENDASI TAT:</p>
    <p style="text-align: justify; padding-left: 20px; border-left: 3px solid #333; margin-left: 10px;">
        <em>"{{ $asesmen->rekomendasi_tat ?? '................................................................................' }}"</em>
    </p>

    <!-- Paragraf Penutup -->
    <p class="paragraf" style="margin-top: 20px;">
        Demikian Berita Acara Hasil Asesmen Terpadu ini dibuat dengan sebenarnya dan dapat dipertanggungjawabkan untuk digunakan sebagaimana mestinya.
    </p>

    <!-- Tanda Tangan -->
    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Malang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p style="margin-bottom: 70px;">Ketua Tim Asesmen Terpadu</p>
            <p class="font-bold underline" style="text-decoration: underline;">( Nama Ketua TAT )</p>
            <p>NIP. ........................................</p>
        </div>
    </div>

</body>
</html>