<?php

namespace App\Events;

use App\Models\PasswordResetRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordResetRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PasswordResetRequest $requestModel;

    public function __construct(PasswordResetRequest $requestModel)
    {
        $this->requestModel = $requestModel->load('user');
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admins')];
    }

    public function broadcastAs(): string
    {
        return 'password-reset-requested';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->requestModel->id,
            'username' => $this->requestModel->user->username,
            'name' => $this->requestModel->user->name,
            'catatan' => $this->requestModel->catatan,
        ];
    }
}