<?php

namespace App\Http\Controllers;

use App\Models\PengaturanDokumen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanDokumenController extends Controller
{
    public function edit(): View
    {
        $pengaturan = PengaturanDokumen::current();

        return view('pengaturan-dokumen.edit', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'diperiksa_nama' => ['nullable', 'string', 'max:255'],
            'diperiksa_jabatan' => ['nullable', 'string', 'max:255'],
            'diperiksa_npp' => ['nullable', 'string', 'max:50'],

            'dibuat_nama' => ['nullable', 'string', 'max:255'],
            'dibuat_jabatan' => ['nullable', 'string', 'max:255'],
            'dibuat_npp' => ['nullable', 'string', 'max:50'],

            'disetujui_nama' => ['nullable', 'string', 'max:255'],
            'disetujui_jabatan' => ['nullable', 'string', 'max:255'],
            'disetujui_npp' => ['nullable', 'string', 'max:50'],
        ]);

        $data['tampilkan_disetujui'] = $request->boolean('tampilkan_disetujui');

        $pengaturan = PengaturanDokumen::current();
        $pengaturan->update($data);

        return back()->with('success', 'Pengaturan dokumen berhasil disimpan. Perubahan otomatis berlaku di semua laporan PDF.');
    }
}