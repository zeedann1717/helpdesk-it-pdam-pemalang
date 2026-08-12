<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
        h2 { margin-bottom: 2px; }
        .subtitle { color: #666; margin-top: 0; margin-bottom: 16px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 7px; text-align: left; }
        th { background-color: #f0f0f0; }
        .badge { padding: 2px 8px; border-radius: 4px; color: #fff; font-size: 10px; }
        .badge-baik { background-color: #198754; }
        .badge-rusak { background-color: #dc3545; }
        .footer { margin-top: 20px; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h2>Data Stok Barang</h2>
    <p class="subtitle">
        PDAM Tirta Mulia — Help Desk IT
        @if ($divisiTerpilih)
            &mdash; Divisi: {{ $divisiTerpilih->nama_divisi }}
        @else
            &mdash; Semua Divisi
        @endif
    </p>

    <table>
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

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>
