{{--
    Partial kolom tanda tangan buat semua laporan PDF.
    Default: cuma tampil 2 kolom (Diperiksa + Dibuat Oleh).
    Variabel yang dipakai (semua opsional, ada default):
    - $diperiksaJabatan     (string) jabatan kolom Diperiksa, default 'Admin Divisi IT'
    - $dibuatOlehNama       (string) default nama user yang login
    - $dibuatOlehJabatan    (string) default dari role user yang login
    - $tampilkanDisetujui   (bool)   tampilkan blok "Disetujui" di bawah, default false
    - $disetujuiJabatan     (string) jabatan blok Disetujui, default 'Direktur Utama'
    - $tempatTanggal        (string) default 'Pemalang, {tanggal hari ini}'
--}}
@php
    $diperiksaJabatan  = $diperiksaJabatan  ?? 'Admin Divisi IT';

    $userTtd = auth()->user();
    $dibuatOlehNama = $dibuatOlehNama ?? ($userTtd->name ?? '-');
    $roleLabel = match ($userTtd->role ?? null) {
        'super_admin' => 'Super Admin IT',
        'admin_divisi' => 'Admin Divisi IT',
        default => 'Petugas Help Desk IT',
    };
    $dibuatOlehJabatan = $dibuatOlehJabatan ?? $roleLabel;

    $tampilkanDisetujui = $tampilkanDisetujui ?? false;
    $disetujuiJabatan   = $disetujuiJabatan   ?? 'Direktur Utama';

    $tempatTanggal = $tempatTanggal ?? ('Pemalang, ' . \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'));
@endphp

<table style="width:100%; margin-top:10px; font-size:10.5px;">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">{{ $tempatTanggal }}</td>
    </tr>
</table>

<table style="width:100%; margin-top:6px; font-size:10.5px; text-align:center;">
    <tr>
        <td style="width:50%;">Diperiksa</td>
        <td style="width:50%;">Dibuat Oleh</td>
    </tr>
    <tr>
        <td style="width:50%; font-weight:bold; padding-top:2px;">{{ $diperiksaJabatan }}</td>
        <td style="width:50%; font-weight:bold; padding-top:2px;">{{ $dibuatOlehJabatan }}</td>
    </tr>
    <tr><td style="height:55px;"></td><td></td></tr>
    <tr>
        <td style="width:50%; font-weight:bold; border-top:1px solid #333; padding-top:3px;">&nbsp;</td>
        <td style="width:50%; font-weight:bold; border-top:1px solid #333; padding-top:3px;">{{ $dibuatOlehNama }}</td>
    </tr>
    <tr>
        <td style="width:50%; color:#666;">NPP: -</td>
        <td style="width:50%; color:#666;">NPP: -</td>
    </tr>
</table>

@if ($tampilkanDisetujui)
    <table style="width:220px; margin:22px auto 0; font-size:10.5px; text-align:center;">
        <tr>
            <td>Disetujui</td>
        </tr>
        <tr>
            <td style="font-weight:bold; padding-top:2px;">{{ $disetujuiJabatan }}</td>
        </tr>
        <tr><td style="height:55px;"></td></tr>
        <tr>
            <td style="font-weight:bold; border-top:1px solid #333; padding-top:3px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="color:#666;">NPP: -</td>
        </tr>
    </table>
@endif
