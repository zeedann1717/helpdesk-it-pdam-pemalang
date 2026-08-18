<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanDokumen extends Model
{
    protected $table = 'pengaturan_dokumen';

    protected $fillable = [
        'diperiksa_nama', 'diperiksa_jabatan', 'diperiksa_npp',
        'dibuat_nama', 'dibuat_jabatan', 'dibuat_npp',
        'tampilkan_disetujui',
        'disetujui_nama', 'disetujui_jabatan', 'disetujui_npp',
    ];

    protected function casts(): array
    {
        return [
            'tampilkan_disetujui' => 'boolean',
        ];
    }

    /**
     * Cuma ada 1 baris pengaturan di seluruh sistem (id=1). Kalau belum ada,
     * otomatis dibuat dengan nilai default yang sudah dipakai sebelumnya.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'diperiksa_jabatan' => 'Kepala Divisi PDE',
                'dibuat_jabatan' => 'Staff Divisi PDE',
                'disetujui_jabatan' => 'Direktur Utama',
                'tampilkan_disetujui' => false,
            ]
        );
    }
}