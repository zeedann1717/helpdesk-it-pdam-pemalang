<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #999; padding: 5px 7px; text-align: left; }
        th { background-color: #f0f0f0; }
        .badge { padding: 2px 8px; border-radius: 4px; color: #fff; font-size: 10px; }
        .badge-baik { background-color: #198754; }
        .badge-rusak { background-color: #dc3545; }
        .footer { margin-top: 14px; font-size: 9px; color: #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop', [
        'judulLaporan' => 'Data Stok Barang',
        'subjudulLines' => [
            $divisiTerpilih ? 'Divisi: ' . $divisiTerpilih->nama_divisi : 'Semua Divisi',
        ],
    ])

    <table class="items">
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama Barang</th>
                <th>Divisi</th>
                <th style="width:70px">Jumlah</th>
                <th style="width:80px">Satuan</th>
                <th style="width:80px">Kondisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stokBarangs as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
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
            @empty
                <tr><td colspan="7" style="text-align:center;">Belum ada data stok barang.</td></tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.partials.ttd')

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>