<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DivisiController extends Controller
{
    public function index(): View
    {
        $divisis = Divisi::withCount('users')->orderBy('nama_divisi')->paginate(10);

        return view('divisi.index', compact('divisis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_divisi' => ['required', 'string', 'max:20', 'unique:divisis,kode_divisi'],
            'nama_divisi' => ['required', 'string', 'max:255'],
        ]);

        Divisi::create($data);

        return back()->with('success', 'Data divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Divisi $divisi): RedirectResponse
    {
        $data = $request->validate([
            'kode_divisi' => ['required', 'string', 'max:20', 'unique:divisis,kode_divisi,'.$divisi->id],
            'nama_divisi' => ['required', 'string', 'max:255'],
        ]);

        $divisi->update($data);

        return back()->with('success', 'Data divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        if ($divisi->tikets()->exists() || $divisi->users()->exists()) {
            return back()->with('error', 'Divisi tidak bisa dihapus karena masih dipakai oleh data user/tiket.');
        }

        $divisi->delete();

        return back()->with('success', 'Data divisi berhasil dihapus.');
    }
}
