<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'user_id',
        'divisi_id',
        'lokasi_id',
        'unit',
        'keluhan',
        'foto',
        'status',
        'catatan_admin',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_selesai' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Tiket $tiket) {
            if (empty($tiket->kode_tiket)) {
                $tiket->kode_tiket = static::generateKodeTiket();
            }
            if (empty($tiket->status)) {
                $tiket->status = 'waiting';
            }
        });
    }

    public static function generateKodeTiket(): string
    {
        $prefix = 'TKT'.now()->format('Ymd');

        $lastNumber = static::whereDate('created_at', now()->toDateString())
            ->lockForUpdate()
            ->count();

        $urut = str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
        $kode = $prefix.$urut;

        // Jaga-jaga kalau ada kode yang sudah kepakai (race condition sederhana)
        while (static::where('kode_tiket', $kode)->exists()) {
            $lastNumber++;
            $urut = str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
            $kode = $prefix.$urut;
        }

        return $kode;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'waiting' => 'Waiting',
            'in_progress' => 'In Progress',
            'done' => 'Done',
            default => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'waiting' => 'bg-danger',
            'in_progress' => 'bg-warning text-dark',
            'done' => 'bg-success',
            default => 'bg-secondary',
        };
    }
}
