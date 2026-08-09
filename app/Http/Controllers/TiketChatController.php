<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TiketChatController extends Controller
{
    /**
     * Ambil riwayat chat pada sebuah tiket (dipanggil saat halaman detail dibuka).
     */
    public function index(Request $request, Tiket $tiket): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $tiket->user_id === $user->id, 403);

        // Tandai pesan lawan bicara sebagai sudah dibaca
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

    /**
     * Kirim pesan baru pada sebuah tiket.
     */
    public function store(Request $request, Tiket $tiket): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $tiket->user_id === $user->id, 403);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $pesan = $tiket->messages()->create([
            'user_id' => $user->id,
            'message' => $data['message'],
        ]);

        // MATIKAN SEMENTARA DULU JIKA BELUM ADA WEBSOCKET
        // broadcast(new \App\Events\NewTicketMessage($pesan))->toOthers();

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id' => $pesan->id,
                'message' => $pesan->message,
                'sender_id' => $user->id,
                'sender_name' => $user->name,
                'sender_is_admin' => $user->isAdmin(),
                'is_me' => true,
                'created_at' => $pesan->created_at->format('d-m-Y H:i'),
            ],
        ]);
    }
}