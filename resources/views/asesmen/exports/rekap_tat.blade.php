<table style="border-collapse: collapse;">
    <thead>
        <tr><th colspan="33" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #d9d9d9;">REKAP DATA TAT BNNP DAN JAJARAN TAHUN {{ date('Y') }}</th></tr>
        <tr>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO.</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO/ BLN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">ASAL PENGAJUAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TANGGAL SURAT</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TANGGAL BERKAS DITERIMA</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TANGGAL PELAKSANAAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO SURAT PENGAJUAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO LKN / LP/LI</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TGL PENANGKAPAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NAMA</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO REGISTER</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">ALAMAT KTP</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">ALAMAT DOMISILI</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TEMPAT LAHIR</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TANGGAL LAHIR</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">JENIS KELAMIN (L/P)</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">USIA</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">PENDIDIKAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">PEKERJAAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NO HANDPHONE</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">NIK</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">JENIS NARKOTIKA</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">BERAT (gr)</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">PASAL YANG DISANGKAKAN</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">HASIL ASESMEN HUKUM</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">HASIL ASESMEN MEDIS</th>
            <th colspan="4" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">REKOMENDASI TAT</th>
            <th colspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">PELAKSANAAN REKOMENDASI</th>
            <th rowspan="3" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">KET</th>
        </tr>
        <tr>
            <th colspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">REHAB DI LEMBAGA REHAB</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">REHAB DI LAPAS / RUTAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TIDAK REHAB (PROSES HUKUM)</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">DILAKSANAKAN</th>
            <th rowspan="2" style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">TIDAK DILAKSANAKAN</th>
        </tr>
        <tr>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">RAWAT JALAN</th>
            <th style="vertical-align: center; text-align: center; font-weight: bold; background-color: #e2efda;">RAWAT INAP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asesmens as $index => $item)
            @php
                $usia = ''; if ($item->tgl_lahir) { try { $usia = \Carbon\Carbon::parse($item->tgl_lahir)->diff(\Carbon\Carbon::parse($item->created_at ?? now()))->y; } catch (\Exception $e) {} }
                $rekom = strtolower($item->rekomendasi_input ?? $item->rekomendasi->tempat_rehabilitasi ?? '');
                $rJalan = str_contains($rekom, 'rawat jalan') ? 'V' : ''; $rInap = str_contains($rekom, 'rawat inap') ? 'V' : '';
                $rLapas = (str_contains($rekom, 'lapas') || str_contains($rekom, 'rutan')) ? 'V' : ''; $rHukum = str_contains($rekom, 'tidak rehab') ? 'V' : '';
                $pel_ya = $item->pelaksanaan == 'YA' ? 'V' : ''; $pel_tidak = $item->pelaksanaan == 'TIDAK' ? 'V' : '';
            @endphp
            <tr>
                <td style="vertical-align: top; text-align: center;">{{ $index + 1 }}</td>
                <td style="vertical-align: top;">{{ $item->no_bln }}</td>
                <td style="vertical-align: top;">{{ $item->asal_pengajuan }}</td>
                <td style="vertical-align: top;">{{ $item->tgl_surat ? \Carbon\Carbon::parse($item->tgl_surat)->format('d-m-Y') : '' }}</td>
                <td style="vertical-align: top;">{{ $item->tgl_berkas ? \Carbon\Carbon::parse($item->tgl_berkas)->format('d-m-Y') : '' }}</td>
                <td style="vertical-align: top;">{{ $item->tgl_pelaksanaan ? \Carbon\Carbon::parse($item->tgl_pelaksanaan)->format('d-m-Y') : '' }}</td>
                <td style="vertical-align: top;">{{ $item->no_surat_pengajuan }}</td>
                <td style="vertical-align: top;">{{ $item->no_lkn }}</td>
                <td style="vertical-align: top;">{{ $item->tgl_tangkap ? \Carbon\Carbon::parse($item->tgl_tangkap)->format('d-m-Y') : '' }}</td>
                <td style="vertical-align: top;">{{ $item->nama_lengkap }}</td>
                <td style="vertical-align: top;">{{ $item->no_register }}</td>
                <td style="vertical-align: top;">{{ $item->alamat_ktp }}</td>
                <td style="vertical-align: top;">{{ $item->alamat_domisili }}</td>
                <td style="vertical-align: top;">{{ $item->tempat_lahir }}</td>
                <td style="vertical-align: top;">{{ $item->tgl_lahir ? \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') : '' }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $item->jenis_kelamin }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $usia }}</td>
                <td style="vertical-align: top;">{{ $item->pendidikan_input ?? $item->pendidikan->nama_pendidikan ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->pekerjaan_input ?? $item->pekerjaan->nama_pekerjaan ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->no_hp ? "'" . $item->no_hp : '' }}</td>
                <td style="vertical-align: top;">{{ $item->nik ? "'" . $item->nik : '' }}</td>
                <td style="vertical-align: top;">{{ $item->narkotika->jenis_narkotika ?? '' }}</td>
                <td style="vertical-align: top;">{{ $item->berat_bb }}</td>
                <td style="vertical-align: top;">{{ $item->pasal_sangkaan }}</td>
                <td style="vertical-align: top;">{{ $item->hasil_asesmen_hukum }}</td>
                <td style="vertical-align: top;">{{ $item->hasil_asesmen_medis }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $rJalan }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $rInap }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $rLapas }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $rHukum }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $pel_ya }}</td>
                <td style="vertical-align: top; text-align: center;">{{ $pel_tidak }}</td>
                <td style="vertical-align: top;">{{ $item->keterangan_tambahan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>