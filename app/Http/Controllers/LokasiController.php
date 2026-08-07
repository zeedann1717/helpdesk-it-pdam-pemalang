<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LokasiController extends Controller
{
    public function index(): View
    {
        $lokasis = Lokasi::withCount('tikets')->orderBy('nama_lokasi')->paginate(10);

        return view('lokasi.index', compact('lokasis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        Lokasi::create($data);

        return back()->with('success', 'Data lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, Lokasi $lokasi): RedirectResponse
    {
        $data = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $lokasi->update($data);

        return back()->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi): RedirectResponse
    {
        if ($lokasi->tikets()->exists()) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih dipakai oleh data tiket.');
        }

        $lokasi->delete();

        return back()->with('success', 'Data lokasi berhasil dihapus.');
    }
}
