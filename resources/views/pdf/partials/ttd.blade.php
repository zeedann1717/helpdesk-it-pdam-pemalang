{{--
    Partial kolom tanda tangan buat semua laporan PDF.
    Nama & jabatan diambil OTOMATIS dari halaman Pengaturan Dokumen
    (menu "Pengaturan Dokumen", khusus Super Admin) — supaya kalau
    penandatangan berganti, tinggal update di 1 tempat tanpa ubah kode.

    Variabel di bawah SEMUA opsional; kalau diisi manual saat @include,
    nilai manual itu yang menang (override), kalau tidak, otomatis
    dari Pengaturan Dokumen:
    - $diperiksaNama, $diperiksaJabatan, $diperiksaNpp
    - $dibuatNama, $dibuatJabatan, $dibuatNpp
    - $tampilkanDisetujui, $disetujuiNama, $disetujuiJabatan, $disetujuiNpp
    - $tempatTanggal          default 'Pemalang, {tanggal hari ini}'
--}}
@php
    $pengaturanTtd = \App\Models\PengaturanDokumen::current();

    $diperiksaNama    = $diperiksaNama    ?? $pengaturanTtd->diperiksa_nama;
    $diperiksaJabatan = $diperiksaJabatan ?? ($pengaturanTtd->diperiksa_jabatan ?: 'Admin Divisi IT');
    $diperiksaNpp     = $diperiksaNpp     ?? $pengaturanTtd->diperiksa_npp;

    $dibuatNama    = $dibuatNama    ?? $pengaturanTtd->dibuat_nama;
    $dibuatJabatan = $dibuatJabatan ?? ($pengaturanTtd->dibuat_jabatan ?: 'Petugas Help Desk IT');
    $dibuatNpp     = $dibuatNpp     ?? $pengaturanTtd->dibuat_npp;

    $tampilkanDisetujui = $tampilkanDisetujui ?? $pengaturanTtd->tampilkan_disetujui;
    $disetujuiNama     = $disetujuiNama     ?? $pengaturanTtd->disetujui_nama;
    $disetujuiJabatan  = $disetujuiJabatan  ?? ($pengaturanTtd->disetujui_jabatan ?: 'Direktur Utama');
    $disetujuiNpp      = $disetujuiNpp      ?? $pengaturanTtd->disetujui_npp;

    $tempatTanggal = $tempatTanggal ?? ('Pemalang, ' . \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'));
@endphp

<div style="page-break-inside: avoid;">
<table style="width:100%; margin-top:8px; font-size:10px;">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">{{ $tempatTanggal }}</td>
    </tr>
</table>

<table style="width:100%; margin-top:4px; font-size:10px; text-align:center;">
    <tr>
        <td style="width:50%;">Diperiksa</td>
        <td style="width:50%;">Dibuat Oleh</td>
    </tr>
    <tr>
        <td style="width:50%; font-weight:bold; padding-top:2px;">{{ $diperiksaJabatan }}</td>
        <td style="width:50%; font-weight:bold; padding-top:2px;">{{ $dibuatJabatan }}</td>
    </tr>
    <tr><td style="height:42px;"></td><td></td></tr>
    <tr>
        <td style="width:50%; font-weight:bold; border-top:1px solid #333; padding-top:3px;">{{ $diperiksaNama ?: '&nbsp;' }}</td>
        <td style="width:50%; font-weight:bold; border-top:1px solid #333; padding-top:3px;">{{ $dibuatNama ?: '&nbsp;' }}</td>
    </tr>
    <tr>
        <td style="width:50%; color:#666;">NPP: {{ $diperiksaNpp ?: '-' }}</td>
        <td style="width:50%; color:#666;">NPP: {{ $dibuatNpp ?: '-' }}</td>
    </tr>
</table>

@if ($tampilkanDisetujui)
    <table style="width:200px; margin:16px auto 0; font-size:10px; text-align:center;">
        <tr>
            <td>Disetujui</td>
        </tr>
        <tr>
            <td style="font-weight:bold; padding-top:2px;">{{ $disetujuiJabatan }}</td>
        </tr>
        <tr><td style="height:42px;"></td></tr>
        <tr>
            <td style="font-weight:bold; border-top:1px solid #333; padding-top:3px;">{{ $disetujuiNama ?: '&nbsp;' }}</td>
        </tr>
        <tr>
            <td style="color:#666;">NPP: {{ $disetujuiNpp ?: '-' }}</td>
        </tr>
    </table>
@endif
</div>
