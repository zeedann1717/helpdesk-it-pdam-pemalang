<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tiket</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        table.items th { background: #e9ecef; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 4px; color: #fff; font-size: 10px; }
        .bg-waiting { background: #dc3545; }
        .bg-progress { background: #ffc107; color:#000; }
        .bg-done { background: #198754; }
        .footer { margin-top: 14px; font-size: 9px; color: #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop', [
        'judulLaporan' => 'Laporan Data Tiket Help Desk IT',
        'subjudulLines' => [
            'Periode: ' . $periode['dari'] . ' s/d ' . $periode['sampai'],
        ],
    ])

    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Nama User</th>
                <th>Devisi</th>
                <th>Unit</th>
                <th>Lokasi</th>
                <th>Kendala</th>
                <th>Tgl Buat</th>
                <th>Tgl Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tikets as $i => $tiket)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $tiket->kode_tiket }}</td>
                    <td>{{ $tiket->user->name }}</td>
                    <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                    <td>{{ $tiket->unit ?? '-' }}</td>
                    <td>{{ $tiket->lokasi?->nama_lokasi ?? '-' }}</td>
                    <td>{{ $tiket->keluhan }}</td>
                    <td>{{ $tiket->created_at->format('d-m-Y') }}</td>
                    <td>{{ $tiket->tanggal_selesai?->format('d-m-Y') ?? '-' }}</td>
                    <td>
                        @php
                            $cls = match($tiket->status) {
                                'waiting' => 'bg-waiting',
                                'in_progress' => 'bg-progress',
                                'done' => 'bg-done',
                                default => '',
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $tiket->statusLabel() }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    @include('pdf.partials.ttd')

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>