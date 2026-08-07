<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Lokasi;
use App\Models\Tiket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TiketController extends Controller
{
    /**
     * Form buat tiket baru (khusus user).
     */
    public function create(): View
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();

        return view('tiket.create', compact('divisis', 'lokasis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'divisi_id' => ['required', 'exists:divisis,id'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'unit' => ['required', 'string', 'max:255'],
            'keluhan' => ['required', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_tiket', 'public');
        }

        $tiket = Tiket::create($data);

        return redirect()
            ->route('tiket.show', $tiket)
            ->with('success', 'Tiket berhasil dibuat dengan kode '.$tiket->kode_tiket.'.');
    }

    /**
     * Daftar semua tiket (admin), dengan filter status opsional.
     */
    public function index(Request $request): View
    {
        $query = Tiket::with(['user', 'divisi', 'lokasi'])->latest();

        if ($request->filled('status')) {
            $query->status($request->string('status'));
        }

        if ($request->filled('cari')) {
            $cari = $request->string('cari');
            $query->where('kode_tiket', 'like', "%{$cari}%");
        }

        $tikets = $query->paginate(15)->withQueryString();

        return view('tiket.index', compact('tikets'));
    }

    /**
     * Daftar tiket berstatus waiting saja (admin).
     */
    public function waiting(): View
    {
        $tikets = Tiket::with(['user', 'divisi', 'lokasi'])
            ->status('waiting')
            ->latest()
            ->paginate(15);

        return view('tiket.waiting', compact('tikets'));
    }

    /**
     * Riwayat tiket milik user yang sedang login.
     */
    public function my(Request $request): View
    {
        $tikets = Tiket::with(['divisi', 'lokasi'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('tiket.my', compact('tikets'));
    }

    public function show(Request $request, Tiket $tiket): View
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $tiket->user_id === $user->id, 403);

        $tiket->load(['user', 'divisi', 'lokasi']);

        return view('tiket.show', compact('tiket'));
    }

    /**
     * Update status tiket oleh admin.
     */
    public function updateStatus(Request $request, Tiket $tiket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:waiting,in_progress,done'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['tanggal_selesai'] = $data['status'] === 'done' ? now() : null;

        $tiket->update($data);

        return back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function destroyFoto(Request $request, Tiket $tiket): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        if ($tiket->foto) {
            Storage::disk('public')->delete($tiket->foto);
            $tiket->update(['foto' => null]);
        }

        return back()->with('success', 'Foto tiket berhasil dihapus.');
    }
}
