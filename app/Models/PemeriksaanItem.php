<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemeriksaan_id',
        'checklist_item_id',
        'kondisi',
        'hasil',
        'catatan',
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class);
    }
}
