<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lokasi',
        'keterangan',
        'divisi_id', // TAMBAHAN BARU
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    // ==== TAMBAHAN BARU ====
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}