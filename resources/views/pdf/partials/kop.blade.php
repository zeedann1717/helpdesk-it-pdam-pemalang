{{--
    Partial kop surat (letterhead) buat semua laporan PDF.
    Variabel yang dipakai:
    - $judulLaporan   (string, wajib)  contoh: 'LAPORAN DATA STOK BARANG'
    - $subjudulLines  (array, opsional) contoh: ['Divisi: Administrasi & Keuangan']
--}}
@php
    $logoPath = public_path('images/logopdam.jpg');
    $logoBase64 = file_exists($logoPath)
        ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

<table style="width:100%; border-bottom: 2px solid #0d3b66; padding-bottom: 6px; margin-bottom: 10px;">
    <tr>
        @if ($logoBase64)
            <td style="width:55px; vertical-align:middle;">
                <img src="{{ $logoBase64 }}" style="width:48px; height:48px;">
            </td>
        @endif
        <td style="vertical-align:middle;">
            <div style="font-size:13.5px; font-weight:bold; color:#0d3b66; letter-spacing:0.3px;">
                PERUMDA AIR MINUM TIRTA MULIA
            </div>
            <div style="font-size:11.5px; font-weight:bold; color:#0d3b66;">
                KABUPATEN PEMALANG
            </div>
        </td>
    </tr>
</table>

<div style="text-align:center; margin-bottom:12px;">
    <div style="font-size:13px; font-weight:bold; text-decoration:underline; text-transform:uppercase;">
        {{ $judulLaporan }}
    </div>
    @if (!empty($subjudulLines))
        @foreach ($subjudulLines as $line)
            <div style="font-size:10px; color:#333; margin-top:2px;">{{ $line }}</div>
        @endforeach
    @endif
</div>
