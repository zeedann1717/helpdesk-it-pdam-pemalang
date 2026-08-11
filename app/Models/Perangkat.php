<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    use HasFactory;

    protected $table = 'perangkat';

    protected $fillable = [
        'kode_inventaris',
        'nama_perangkat',
        'jenis_perangkat',
        'lokasi_id',
        'keterangan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function pemeriksaanTerakhir()
    {
        return $this->hasOne(Pemeriksaan::class)->latestOfMany('tanggal_pemeriksaan');
    }
}
