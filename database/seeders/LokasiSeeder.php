<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Bagian Teknologi Informasi' => [
                'Ruang Kepala Bagian TI',
                'Ruang Server / Data Center',
                'Ruang Staf IT',
            ],
            'Administrasi & Keuangan' => [
                'Ruang Kepala Bagian Administrasi & Keuangan',
                'Ruang Staf Keuangan',
                'Ruang Kasir / Loket Pembayaran',
                'Ruang Arsip',
            ],
            'Pelayanan' => [
                'Ruang Kepala Bagian Pelayanan',
                'Ruang Hubungan Langganan / Loket',
                'Ruang Pengaduan Pelanggan',
            ],
            'Teknik' => [
                'Ruang Kepala Bagian Teknik',
                'Ruang Perencanaan Teknik',
                'Ruang Operasional & Distribusi',
                'Instalasi Produksi',
                'Gudang Teknik',
            ],
            'SDM/Kepegawaian' => [
                'Ruang Kepala Bagian SDM',
                'Ruang Staf Kepegawaian',
            ],
            'Pengawasan/Audit' => [
                'Ruang Satuan Pengawas Internal (SPI)',
            ],
            'Direksi' => [
                'Ruang Direktur Utama',
                'Ruang Rapat Direksi',
            ],
            'Kantor Cabang' => [
                'Kantor Cabang Taman',
                'Kantor Cabang Petarukan',
                'Kantor Cabang Randudongkal',
                'Kantor Cabang Moga',
                'Kantor Cabang Pulosari',
                'Kantor Cabang Warungpring',
            ],
        ];

        foreach ($data as $namaDivisi => $ruangans) {
            // Karena DivisiSeeder udah jalan duluan, kita tinggal panggil divisinya
            $divisi = Divisi::where('nama_divisi', $namaDivisi)->first();

            // Kalo divisinya ketemu, baru kita masukin lokasinya
            if ($divisi) {
                foreach ($ruangans as $namaLokasi) {
                    Lokasi::firstOrCreate([
                        'nama_lokasi' => $namaLokasi,
                        'divisi_id' => $divisi->id,
                    ]);
                }
            }
        }
    }
}