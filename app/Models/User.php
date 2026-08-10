<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'jenis_kelamin',
        'no_hp',
        'divisi_id',
        'unit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    /**
     * Super Admin (IT Pusat): akses penuh ke semua data & semua divisi.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Admin Divisi: cuma bisa kelola tiket di divisinya sendiri.
     */
    public function isAdminDivisi(): bool
    {
        return $this->role === 'admin_divisi';
    }

    /**
     * True untuk kedua jenis admin (dipakai buat pengecekan akses umum).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_divisi'], true);
    }
}
