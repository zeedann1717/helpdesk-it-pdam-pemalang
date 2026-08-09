<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Lokasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LokasiController extends Controller
{
    public function index(): View
    {
        $lokasis = Lokasi::withCount('tikets')->with('divisi')->orderBy('nama_lokasi')->paginate(10);
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('lokasi.index', compact('lokasis', 'divisis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'divisi_id' => ['required', 'exists:divisis,id'], // TAMBAHAN BARU
        ]);

        Lokasi::create($data);

        return back()->with('success', 'Data lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, Lokasi $lokasi): RedirectResponse
    {
        $data = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'divisi_id' => ['required', 'exists:divisis,id'], // TAMBAHAN BARU
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

    /**
     * Endpoint AJAX: ambil daftar lokasi berdasarkan divisi tertentu.
     */
    public function byDivisi(Divisi $divisi): JsonResponse
    {
        $lokasis = $divisi->lokasis()
            ->orderBy('nama_lokasi')
            ->get(['id', 'nama_lokasi']);

        return response()->json($lokasis);
    }
}