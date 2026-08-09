<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        // Format: 'KODE' => 'Nama Divisi'
        $divisis = [
            'TI'  => 'Bagian Teknologi Informasi',
            'AK'  => 'Administrasi & Keuangan',
            'PLY' => 'Pelayanan',
            'TEK' => 'Teknik',
            'SDM' => 'SDM/Kepegawaian',
            'SPI' => 'Pengawasan/Audit',
            'DIR' => 'Direksi',
            'CAB' => 'Kantor Cabang', 
        ];

        foreach ($divisis as $kode => $nama) {
            // updateOrCreate: cek berdasarkan kode_divisi
            // Kalau kodenya udah ada, update namanya. Kalau belum ada, bikin baru.
            Divisi::updateOrCreate(
                ['kode_divisi' => $kode],
                ['nama_divisi' => $nama]
            );
        }
    }
}