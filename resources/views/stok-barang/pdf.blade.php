<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Stok Barang</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; line-height: 1.4; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-size: 12px; }
        .badge { padding: 2px 7px; border-radius: 4px; color: #fff; font-size: 10.5px; }
        .badge-baik { background-color: #198754; }
        .badge-rusak { background-color: #dc3545; }
        .badge-baru { background-color: #0d6efd; }
        .badge-lama { background-color: #6c757d; }
        .footer { margin-top: 14px; font-size: 9.5px; color: #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop', [
        'judulLaporan' => 'Data Stok Barang',
        'subjudulLines' => [
            $divisiTerpilih ? 'Divisi: ' . $divisiTerpilih->nama_divisi : 'Semua Divisi',
        ],
    ])

    @php
        // Sama seperti Laporan Tiket: dibagi 15 baris per halaman biar teks
        // gak dipaksa kecil. Ttd sengaja gak dipaksa page-break, jadi nempel
        // otomatis di sisa halaman terakhir kalau masih muat.
        $stokChunks = collect($stokBarangs)->values()->chunk(15);
    @endphp

    @forelse ($stokChunks as $chunkIndex => $chunk)
        <div @if ($chunkIndex > 0) style="page-break-before: always;" @endif>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width:30px">No</th>
                        <th>Nama Barang</th>
                        <th>Divisi</th>
                        <th style="width:70px">Jumlah</th>
                        <th style="width:80px">Satuan</th>
                        <th style="width:80px">Kondisi Unit</th>
                        <th style="width:80px">Kondisi Barang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chunk as $localIndex => $s)
                        <tr>
                            <td>{{ $localIndex + 1 }}</td>
                            <td>{{ $s->nama_barang }}</td>
                            <td>{{ $s->divisi->nama_divisi }}</td>
                            <td>{{ $s->jumlah }}</td>
                            <td>{{ $s->satuan }}</td>
                            <td>
                                <span class="badge {{ $s->kondisi === 'baik' ? 'badge-baik' : 'badge-rusak' }}">
                                    {{ $s->kondisi === 'baik' ? 'Baik' : 'Rusak' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $s->kondisi_barang === 'baru' ? 'badge-baru' : 'badge-lama' }}">
                                    {{ $s->kondisi_barang === 'baru' ? 'Stok Baru' : 'Stok Lama' }}
                                </span>
                            </td>
                            <td>{{ $s->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <table class="items">
            <thead>
                <tr>
                    <th style="width:30px">No</th>
                    <th>Nama Barang</th>
                    <th>Divisi</th>
                    <th style="width:70px">Jumlah</th>
                    <th style="width:80px">Satuan</th>
                    <th style="width:80px">Kondisi Unit</th>
                    <th style="width:80px">Kondisi Barang</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="8" style="text-align:center;">Belum ada data stok barang.</td></tr>
            </tbody>
        </table>
    @endforelse

    @include('pdf.partials.ttd', ['tampilkanDisetujui' => false])

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>