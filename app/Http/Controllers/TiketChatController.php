<?php
namespace App\Http\Controllers;
use App\Models\Tiket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class TiketChatController extends Controller
{
    private function bolehAkses($user, Tiket $tiket): bool
    {
        return $user->isSuperAdmin()
            || ($user->isAdminDivisi() && $tiket->divisi_id === $user->divisi_id)
            || $tiket->user_id === $user->id;
    }

    public function index(Request $request, Tiket $tiket): JsonResponse
    {
        $user = $request->user();
        abort_unless($this->bolehAkses($user, $tiket), 403);
        $tiket->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        $messages = $tiket->messages()->with('user')->get()->map(function ($m) use ($user) {
            return [
                'id' => $m->id,
                'message' => $m->message,
                'sender_id' => $m->user_id,
                'sender_name' => $m->user->name,
                'sender_is_admin' => $m->user->isAdmin(),
                'is_me' => $m->user_id === $user->id,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ];
        });
        return response()->json($messages);
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

        // ==== TAMBAHAN: cegah tiket dobel akibat submit ganda ====
        $duplikat = Tiket::where('user_id', $data['user_id'])
            ->where('divisi_id', $data['divisi_id'])
            ->where('lokasi_id', $data['lokasi_id'])
            ->where('unit', $data['unit'])
            ->where('keluhan', $data['keluhan'])
            ->where('created_at', '>=', now()->subSeconds(15))
            ->first();

        if ($duplikat) {
            return redirect()
                ->route('tiket.show', $duplikat)
                ->with('success', 'Tiket sudah tercatat sebelumnya dengan kode '.$duplikat->kode_tiket.'.');
        }
        // ==== AKHIR TAMBAHAN ====

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_tiket', 'public');
        }

        $tiket = Tiket::create($data);

        // ==== TAMBAHAN: broadcast realtime ke admin (lihat Bug 2 di bawah) ====
        broadcast(new \App\Events\NewTicketCreated($tiket))->toOthers();
        // ==== AKHIR TAMBAHAN ====

        return redirect()
            ->route('tiket.show', $tiket)
            ->with('success', 'Tiket berhasil dibuat dengan kode '.$tiket->kode_tiket.'.');
    }
}
