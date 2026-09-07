<table style="border-collapse: collapse;">
    <thead>
        <tr><th colspan="23" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #9bc2e6;">DATA CASE CONFERENCE</th></tr>
        <tr>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">NO</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">NAMA KLIEN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">USIA</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">P/L</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">PEKERJAAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">PENDIDIKAN TERAKHIR</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">DOMISILI</th>
            <th colspan="5" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">HUKUM</th>
            <th colspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">MEDIS</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">ALASAN PENGGUNAAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">KONDISI KELUARGA</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">TINGKAT KETERGANTUNGAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">POLA PEMAKAIAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">KONDISI LINGKUNGAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">REKOMENDASI</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">KETERANGAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">SARAN</th>
        </tr>
        <tr>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">STATUS HUKUM</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">KETERLIBATAN JARINGAN</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">BARANG BUKTI</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">CARA MENDAPATKAN</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">DAPAT DARI</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">KESEHATAN</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">PSIKOLOGI</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #9bc2e6;">TES URINE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asesmens as $index => $item)
            @php
                $usia = ''; if ($item->tgl_lahir) { try { $usia = \Carbon\Carbon::parse($item->tgl_lahir)->diff(\Carbon\Carbon::parse($item->created_at ?? now()))->y; } catch (\Exception $e) {} }
            @endphp
            <tr>
                <td style="vertical-align: top; text-align: center;">{{ $index + 1 }}</td>
                <td style="vertical-align: top;">{{ $item->nama_lengkap }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $usia }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $item->jenis_kelamin }}</td>
                <td style="vertical-align: top;">{{ $item->pekerjaan_input ?? $item->pekerjaan->nama_pekerjaan ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->pendidikan_input ?? $item->pendidikan->nama_pendidikan ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->alamat_domisili }}</td>
                <td style="vertical-align: top;">{{ $item->status_hukum }}</td>
                <td style="vertical-align: top;">{{ $item->keterlibatan_jaringan }}</td>
                <td style="vertical-align: top;">{{ $item->deskripsi_bb }}</td>
                <td style="vertical-align: top;">{{ $item->cara_mendapatkan }}</td>
                <td style="vertical-align: top;">{{ $item->dapat_dari_siapa }}</td>
                <td style="vertical-align: top;">{{ $item->kesehatan_fisik }}</td>
                <td style="vertical-align: top;">{{ $item->psikologi }}</td>
                <td style="vertical-align: top;">{{ $item->tes_urine }}</td>
                <td style="vertical-align: top;">{{ $item->alasan_penggunaan }}</td>
                <td style="vertical-align: top;">{{ $item->kondisi_keluarga }}</td>
                <td style="vertical-align: top;">{{ $item->tingkat_ketergantungan }}</td>
                <td style="vertical-align: top;">{{ $item->pola_pemakaian }}</td>
                <td style="vertical-align: top;">{{ $item->kondisi_lingkungan }}</td>
                <td style="vertical-align: top;">{{ $item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->keterangan_tambahan }}</td>
                <td style="vertical-align: top;">{{ $item->saran_case_conference }}</td>
            </tr>
        @endforeach
    </tbody>
</table>