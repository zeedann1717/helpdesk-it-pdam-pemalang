<?php

namespace Database\Seeders;

use App\Models\ChecklistItem;
use Illuminate\Database\Seeder;

class ChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'A' => [
                'label' => 'PEMERIKSAAN FISIK',
                'kondisi_positif' => 'Baik',
                'items' => [
                    'Kondisi casing perangkat',
                    'Tidak terdapat kerusakan fisik',
                    'Kebersihan perangkat dari debu',
                    'Kondisi Kabel Power',
                    'Kondisi Kabel jaringan (LAN)',
                    'Port USB/LAN/Display berfungsi',
                    'Kondisi monitor',
                    'Keyboard berfungsi',
                    'Mouse berfungsi',
                ],
            ],
            'B' => [
                'label' => 'PEMERIKSAAN OPERASIONAL',
                'kondisi_positif' => 'Baik',
                'items' => [
                    'Perangkat dapat menyala normal',
                    'Booting sistem normal',
                    'Tidak terdapat bunyi abnormal',
                    'Kipas pendingin berfungsi',
                    'Suhu perangkat normal',
                    'Lampu indikator normal',
                    'Power Supply berfungsi baik',
                ],
            ],
            'C' => [
                'label' => 'PEMERIKSAAN PENYIMPANAN (STORAGE)',
                'kondisi_positif' => 'Baik',
                'items' => [
                    'Kapasitas Harddisk/SSD mencukupi',
                    'Status kesehatan Harddisk/SSD baik',
                    'Tidak terdapat bad sector',
                    'Ruang penyimpanan tersedia cukup',
                ],
            ],
            'D' => [
                'label' => 'PEMERIKSAAN JARINGAN',
                'kondisi_positif' => 'Baik',
                'items' => [
                    'Koneksi LAN normal',
                    'Alamat IP sesuai',
                    'Internet berfungsi',
                    'Kecepatan jaringan normal',
                ],
            ],
            'E' => [
                'label' => 'PEMERIKSAAN SERVER',
                'kondisi_positif' => 'Baik',
                'items' => [
                    'Server berjalan normal',
                    'CPU Usage normal',
                    'Memory Usage normal',
                    'Storage tidak penuh',
                    'RAID Status Normal',
                    'Backup berjalan',
                    'Temperatur Server normal',
                    'AC Ruangan',
                ],
            ],
            'F' => [
                'label' => 'TINDAKAN PERAWATAN',
                'kondisi_positif' => 'Ya',
                'items' => [
                    'Membersihkan debu perangkat',
                    'Merapikan kabel',
                    'Mengencangkan konektor',
                    'Mengganti komponen rusak',
                    'Restart perangkat',
                    'Update Driver/Firmware (bila diperlukan)',
                ],
            ],
        ];

        foreach ($kategoris as $kode => $data) {
            foreach ($data['items'] as $urutan => $item) {
                ChecklistItem::firstOrCreate(
                    [
                        'kategori_kode' => $kode,
                        'item_pemeriksaan' => $item,
                    ],
                    [
                        'kategori_label' => $data['label'],
                        'urutan' => $urutan + 1,
                        'label_kondisi_positif' => $data['kondisi_positif'],
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}
