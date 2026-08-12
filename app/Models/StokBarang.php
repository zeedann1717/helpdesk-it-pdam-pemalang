<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'divisi_id',
        'nama_barang',
        'jumlah',
        'satuan',
        'kondisi',
        'keterangan',
        'diinput_oleh',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function diinputOlehUser()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function kondisiBadgeClass(): string
    {
        return $this->kondisi === 'baik' ? 'bg-success' : 'bg-danger';
    }

    public function kondisiLabel(): string
    {
        return $this->kondisi === 'baik' ? 'Baik' : 'Rusak';
    }
}
