<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    private function filteredQuery(Request $request)
    {
        $dari = $request->filled('dari') ? Carbon::parse($request->string('dari'))->startOfDay() : null;
        $sampai = $request->filled('sampai') ? Carbon::parse($request->string('sampai'))->endOfDay() : null;

        $query = Tiket::with(['user', 'divisi', 'lokasi'])->latest();

        if ($dari) {
            $query->where('created_at', '>=', $dari);
        }

        if ($sampai) {
            $query->where('created_at', '<=', $sampai);
        }

        if ($request->filled('status')) {
            $query->status($request->string('status'));
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->integer('divisi_id'));
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $tikets = $this->filteredQuery($request)->paginate(20)->withQueryString();

        return view('laporan.index', compact('tikets'));
    }

    public function exportPdf(Request $request): Response
    {
        $tikets = $this->filteredQuery($request)->get();

        $periode = [
            'dari' => $request->filled('dari') ? Carbon::parse($request->string('dari'))->format('d-m-Y') : '-',
            'sampai' => $request->filled('sampai') ? Carbon::parse($request->string('sampai'))->format('d-m-Y') : '-',
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('tikets', 'periode'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-tiket-'.now()->format('Ymd_His').'.pdf');
    }
}
