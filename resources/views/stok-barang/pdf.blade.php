<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Stok Barang</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10.5px; color: #222; line-height: 1.35; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background-color: #f0f0f0; font-size: 10.5px; }
        .badge { padding: 1px 6px; border-radius: 3px; color: #fff; font-size: 9.5px; }
        .badge-baik { background-color: #198754; }
        .badge-rusak { background-color: #dc3545; }
        .footer { margin-top: 12px; font-size: 9px; color: #777; }
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
        $rowsPerPage = 15;
        $stokChunks = collect($stokBarangs)->values()->chunk($rowsPerPage);
        $no = 0;
    @endphp

    @forelse ($stokChunks as $chunkIndex => $chunk)
        <div @if ($chunkIndex > 0) style="page-break-before: always;" @endif>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width:28px">No</th>
                        <th>Nama Barang</th>
                        <th>Divisi</th>
                        <th style="width:65px">Jumlah</th>
                        <th style="width:75px">Satuan</th>
                        <th style="width:75px">Kondisi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chunk as $s)
                        @php $no++; @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $s->nama_barang }}</td>
                            <td>{{ $s->divisi->nama_divisi }}</td>
                            <td>{{ $s->jumlah }}</td>
                            <td>{{ $s->satuan }}</td>
                            <td>
                                <span class="badge {{ $s->kondisi === 'baik' ? 'badge-baik' : 'badge-rusak' }}">
                                    {{ $s->kondisi === 'baik' ? 'Baik' : 'Rusak' }}
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
                    <th style="width:28px">No</th>
                    <th>Nama Barang</th>
                    <th>Divisi</th>
                    <th style="width:65px">Jumlah</th>
                    <th style="width:75px">Satuan</th>
                    <th style="width:75px">Kondisi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align:center;">Belum ada data stok barang.</td></tr>
            </tbody>
        </table>
    @endforelse

    @include('pdf.partials.ttd', ['tampilkanDisetujui' => false])

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>
