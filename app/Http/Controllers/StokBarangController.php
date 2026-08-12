<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\StokBarang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StokBarangController extends Controller
{
    public function index(Request $request): View
    {
        $query = StokBarang::with('divisi')->latest();

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->integer('divisi_id'));
        }

        $stokBarangs = $query->paginate(15)->withQueryString();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('stok-barang.index', compact('stokBarangs', 'divisis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'divisi_id' => ['required', 'exists:divisis,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'kondisi' => ['required', 'in:baik,rusak'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $data['diinput_oleh'] = $request->user()->id;

        StokBarang::create($data);

        return back()->with('success', 'Data stok barang berhasil ditambahkan.');
    }

    public function update(Request $request, StokBarang $stokBarang): RedirectResponse
    {
        $data = $request->validate([
            'divisi_id' => ['required', 'exists:divisis,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'kondisi' => ['required', 'in:baik,rusak'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $stokBarang->update($data);

        return back()->with('success', 'Data stok barang berhasil diperbarui.');
    }

    public function destroy(StokBarang $stokBarang): RedirectResponse
    {
        $stokBarang->delete();

        return back()->with('success', 'Data stok barang berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = StokBarang::with('divisi')->orderBy('nama_barang');

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->integer('divisi_id'));
        }

        $stokBarangs = $query->get();

        $divisiTerpilih = null;
        if ($request->filled('divisi_id')) {
            $divisiTerpilih = Divisi::find($request->integer('divisi_id'));
        }

        $pdf = Pdf::loadView('stok-barang.pdf', compact('stokBarangs', 'divisiTerpilih'))->setPaper('a4', 'landscape');

        $namaFile = 'Stok-Barang-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($namaFile);
    }
}
