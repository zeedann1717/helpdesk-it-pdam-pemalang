<?php

use App\Models\Tiket;
use Illuminate\Support\Facades\Broadcast;

// Channel default notifikasi personal per user (dipakai Laravel notifications
// & juga kita pakai untuk pop-up toast saat admin membalas tiket seorang user).
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel khusus semua admin, untuk notifikasi setiap ada tiket baru dibalas user.
Broadcast::channel('admins', function ($user) {
    return $user->isAdmin() ? ['id' => $user->id, 'name' => $user->name] : false;
});

// Channel per tiket: hanya admin atau pemilik tiket yang boleh bergabung/mendengarkan.
Broadcast::channel('tiket.{tiketId}', function ($user, $tiketId) {
    $tiket = Tiket::find($tiketId);

    if (! $tiket) {
        return false;
    }

    return $user->isAdmin() || $user->id === $tiket->user_id
        ? ['id' => $user->id, 'name' => $user->name]
        : false;
});