<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perangkat_id',
        'tanggal_pemeriksaan',
        'hari',
        'jadwal',
        'nama_pemeriksa',
        'diinput_oleh',
        'catatan_umum',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemeriksaan' => 'date',
        ];
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }

    public function diinputOlehUser()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function items()
    {
        return $this->hasMany(PemeriksaanItem::class);
    }

    /**
     * True kalau ada minimal satu item yang hasilnya "tidak_layak" —
     * dipakai buat kasih tanda peringatan di daftar riwayat pemeriksaan.
     */
    public function adaYangTidakLayak(): bool
    {
        return $this->items->contains('hasil', 'tidak_layak');
    }
}
