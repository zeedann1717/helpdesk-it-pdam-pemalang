<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Perangkat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerangkatController extends Controller
{
    public function index(): View
    {
        $perangkats = Perangkat::with('lokasi')->orderBy('nama_perangkat')->paginate(15);
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();

        return view('perangkat.index', compact('perangkats', 'lokasis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_inventaris' => ['required', 'string', 'max:255', 'unique:perangkat,kode_inventaris'],
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'jenis_perangkat' => ['required', 'in:Server,PC,Laptop,Printer,Switch,Router,UPS,Lainnya'],
            'lokasi_id' => ['nullable', 'exists:lokasis,id'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        Perangkat::create($data);

        return back()->with('success', 'Data perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, Perangkat $perangkat): RedirectResponse
    {
        $data = $request->validate([
            'kode_inventaris' => ['required', 'string', 'max:255', 'unique:perangkat,kode_inventaris,'.$perangkat->id],
            'nama_perangkat' => ['required', 'string', 'max:255'],
            'jenis_perangkat' => ['required', 'in:Server,PC,Laptop,Printer,Switch,Router,UPS,Lainnya'],
            'lokasi_id' => ['nullable', 'exists:lokasis,id'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        $perangkat->update($data);

        return back()->with('success', 'Data perangkat berhasil diperbarui.');
    }

    public function destroy(Perangkat $perangkat): RedirectResponse
    {
        if ($perangkat->pemeriksaans()->exists()) {
            return back()->with('error', 'Perangkat tidak bisa dihapus karena sudah memiliki riwayat pemeriksaan.');
        }

        $perangkat->delete();

        return back()->with('success', 'Data perangkat berhasil dihapus.');
    }
}
