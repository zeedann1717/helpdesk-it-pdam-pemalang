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
        $user = $request->user();

        // Divisi tiket SELALU dipaksa ikut divisi akun yang login, tidak boleh
        // dipilih bebas dari form (mencegah tiket "nyasar" ke divisi lain,
        // baik karena kesalahan maupun manipulasi form dari sisi client).
        abort_unless($user->divisi_id, 422, 'Akun Anda belum terdaftar di divisi manapun.');

        $data = $request->validate([
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'unit' => ['required', 'string', 'max:255'],
            'keluhan' => ['required', 'string', 'max:2000'],
            'foto' => ['required', 'image', 'max:10240'],
        ]);

        $data['divisi_id'] = $user->divisi_id;
        $data['user_id'] = $user->id;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_tiket', 'public');
        }
        $tiket = Tiket::create($data);
        return redirect()
            ->route('tiket.show', $tiket)
            ->with('success', 'Tiket berhasil dibuat dengan kode '.$tiket->kode_tiket.'.');
    }
    /**
     * Daftar semua tiket. Super Admin lihat semua, Admin Divisi lihat divisinya aja.
     */
    public function index(Request $request): View
    {
        $query = Tiket::with(['user', 'divisi', 'lokasi'])->latest();

        if ($request->user()->isAdminDivisi()) {
            $query->where('divisi_id', $request->user()->divisi_id);
        }

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
     * Daftar tiket berstatus waiting saja. Super Admin lihat semua, Admin Divisi lihat divisinya aja.
     */
    public function waiting(Request $request): View
    {
        $query = Tiket::with(['user', 'divisi', 'lokasi'])
            ->status('waiting')
            ->latest();

        if ($request->user()->isAdminDivisi()) {
            $query->where('divisi_id', $request->user()->divisi_id);
        }

        $tikets = $query->paginate(15);
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
        $bolehLihat = $user->isSuperAdmin()
            || ($user->isAdminDivisi() && $tiket->divisi_id === $user->divisi_id)
            || $tiket->user_id === $user->id;
        abort_unless($bolehLihat, 403);
        $tiket->load(['user', 'divisi', 'lokasi']);
        return view('tiket.show', compact('tiket'));
    }
    /**
     * Update status tiket. Admin Divisi cuma boleh update tiket divisinya.
     */
    public function updateStatus(Request $request, Tiket $tiket): RedirectResponse
    {
        $user = $request->user();
        $bolehUpdate = $user->isSuperAdmin()
            || ($user->isAdminDivisi() && $tiket->divisi_id === $user->divisi_id);
        abort_unless($bolehUpdate, 403);

        $data = $request->validate([
            'status' => ['required', 'in:waiting,in_progress,done'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
            'foto_sesudah' => ['nullable', 'image', 'max:10240'],
        ]);
        $data['tanggal_selesai'] = $data['status'] === 'done' ? now() : null;

        if ($request->hasFile('foto_sesudah')) {
            if ($tiket->foto_sesudah) {
                Storage::disk('public')->delete($tiket->foto_sesudah);
            }
            $data['foto_sesudah'] = $request->file('foto_sesudah')->store('foto_pengerjaan', 'public');
        }

        $tiket->update($data);
        return back()->with('success', 'Status tiket berhasil diperbarui.');
    }
    public function destroyFoto(Request $request, Tiket $tiket): RedirectResponse
    {
        $user = $request->user();
        $bolehHapus = $user->isSuperAdmin()
            || ($user->isAdminDivisi() && $tiket->divisi_id === $user->divisi_id);
        abort_unless($bolehHapus, 403);

        if ($tiket->foto) {
            Storage::disk('public')->delete($tiket->foto);
            $tiket->update(['foto' => null]);
        }
        return back()->with('success', 'Foto tiket berhasil dihapus.');
    }
}
