<?php

namespace App\Exports;

use App\Models\Asesmen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsesmenRekapExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Asesmen::with(['narkotika', 'pendidikan', 'pekerjaan', 'rekomendasi']);

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->orWhere('no_register', 'like', "%{$search}%")->orWhere('no_lkn', 'like', "%{$search}%");
            });
        }
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

        $sort = $this->request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->oldest('created_at');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_lengkap', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_lengkap', 'desc');
        } else {
            $query->latest('created_at');
        }

        $asesmens = $query->get();
        return view('asesmen.exports.rekap_tat', compact('asesmens'));
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ]);
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->getAlignment()->setWrapText(true);
    }
}