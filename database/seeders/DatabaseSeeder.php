<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed data awal untuk aplikasi IT Helpdesk PDAM Tirta Mulia Pemalang.
     *
     * Catatan: daftar divisi di bawah disusun berdasarkan struktur umum
     * PDAM (Bagian Umum, Keuangan, Hubungan Langganan, Teknologi Informasi,
     * Produksi, Distribusi, Perencanaan Teknik, SPI). Struktur organisasi
     * resmi PDAM Tirta Mulia Pemalang dipublikasikan dalam bentuk gambar di
     * https://www.tirtamulia.com/page/69/struktur-organisasi — silakan
     * sesuaikan nama/kode divisi di menu "Data Divisi" pada panel admin
     * agar 100% cocok dengan SOTK resmi terbaru sebelum dipakai untuk
     * laporan magang.
     */
    public function run(): void
    {
        $divisis = [
            ['kode_divisi' => 'DIR', 'nama_divisi' => 'Direksi'],
            ['kode_divisi' => 'UMKEP', 'nama_divisi' => 'Bagian Umum dan Kepegawaian'],
            ['kode_divisi' => 'KEU', 'nama_divisi' => 'Bagian Keuangan'],
            ['kode_divisi' => 'HL', 'nama_divisi' => 'Bagian Hubungan Langganan'],
            ['kode_divisi' => 'TI', 'nama_divisi' => 'Bagian Teknologi Informasi'],
            ['kode_divisi' => 'PRD', 'nama_divisi' => 'Bagian Produksi'],
            ['kode_divisi' => 'DIST', 'nama_divisi' => 'Bagian Distribusi'],
            ['kode_divisi' => 'PERC', 'nama_divisi' => 'Bagian Perencanaan Teknik'],
            ['kode_divisi' => 'SPI', 'nama_divisi' => 'Satuan Pengawas Internal'],
        ];

        foreach ($divisis as $d) {
            Divisi::firstOrCreate(['kode_divisi' => $d['kode_divisi']], $d);
        }

        $lokasis = [
            ['nama_lokasi' => 'Kantor Pusat - Ruang Direksi', 'keterangan' => 'Jl. Gatot Subroto No.30, Pemalang'],
            ['nama_lokasi' => 'Kantor Pusat - Ruang Umum & Kepegawaian'],
            ['nama_lokasi' => 'Kantor Pusat - Ruang Keuangan'],
            ['nama_lokasi' => 'Kantor Pusat - Ruang Hubungan Langganan / Loket'],
            ['nama_lokasi' => 'Kantor Pusat - Ruang Teknologi Informasi'],
            ['nama_lokasi' => 'Instalasi Produksi'],
            ['nama_lokasi' => 'Kantor Cabang / Unit Pelayanan'],
            ['nama_lokasi' => 'Gudang'],
        ];

        foreach ($lokasis as $l) {
            Lokasi::firstOrCreate(['nama_lokasi' => $l['nama_lokasi']], $l);
        }

        $tiDivisi = Divisi::where('kode_divisi', 'TI')->first();

        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator IT',
                'email' => 'admin@pdamtirtamulia.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'divisi_id' => $tiDivisi?->id,
                'unit' => 'Admin Sistem',
            ]
        );

        User::firstOrCreate(
            ['username' => 'pegawai'],
            [
                'name' => 'Pegawai Contoh',
                'email' => 'pegawai@pdamtirtamulia.test',
                'password' => Hash::make('pegawai123'),
                'role' => 'user',
                'divisi_id' => Divisi::where('kode_divisi', 'KEU')->first()?->id,
                'unit' => 'Staf',
            ]
        );
    }
}
