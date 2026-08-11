<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_kode',
        'kategori_label',
        'urutan',
        'item_pemeriksaan',
        'label_kondisi_positif',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function pemeriksaanItems()
    {
        return $this->hasMany(PemeriksaanItem::class);
    }
}
