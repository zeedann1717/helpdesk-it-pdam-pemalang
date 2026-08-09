<?php

namespace App\Events;

use App\Models\TicketMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTicketMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TicketMessage $ticketMessage;

    public function __construct(TicketMessage $ticketMessage)
    {
        $this->ticketMessage = $ticketMessage->load(['user', 'tiket']);
    }

    /**
     * Channel tujuan broadcast:
     * - channel tiket itu sendiri (untuk update live saat halaman detail tiket dibuka)
     * - channel personal lawan bicara (untuk notifikasi pop-up walau sedang tidak
     *   membuka halaman tiket tersebut)
     */
    public function broadcastOn(): array
    {
        $tiket = $this->ticketMessage->tiket;

        $channels = [
            new PrivateChannel('tiket.'.$tiket->id),
        ];

        if ($this->ticketMessage->user->isAdmin()) {
            // Admin membalas -> notifikasi ke user pemilik tiket
            $channels[] = new PrivateChannel('App.Models.User.'.$tiket->user_id);
        } else {
            // User mengirim pesan -> notifikasi ke seluruh admin
            $channels[] = new PrivateChannel('admins');
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }

    public function broadcastWith(): array
    {
        $tiket = $this->ticketMessage->tiket;

        return [
            'id' => $this->ticketMessage->id,
            'tiket_id' => $tiket->id,
            'kode_tiket' => $tiket->kode_tiket,
            'message' => $this->ticketMessage->message,
            'sender_id' => $this->ticketMessage->user_id,
            'sender_name' => $this->ticketMessage->user->name,
            'sender_is_admin' => $this->ticketMessage->user->isAdmin(),
            'created_at' => $this->ticketMessage->created_at->format('d-m-Y H:i'),
        ];
    }
}