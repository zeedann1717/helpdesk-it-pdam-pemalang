<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tiket</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10.5px; color: #222; line-height: 1.35; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        table.items th { background: #e9ecef; font-size: 10.5px; }
        .text-center { text-align: center; }
        .badge { padding: 1px 6px; border-radius: 3px; color: #fff; font-size: 9.5px; }
        .bg-waiting { background: #dc3545; }
        .bg-progress { background: #ffc107; color:#000; }
        .bg-done { background: #198754; }
        .footer { margin-top: 12px; font-size: 9px; color: #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop', [
        'judulLaporan' => 'Laporan Data Tiket Help Desk IT',
        'subjudulLines' => [
            'Periode: ' . $periode['dari'] . ' s/d ' . $periode['sampai'],
        ],
    ])

    @php
        // Dipecah maksimal 15 baris per halaman supaya halaman terakhir
        // (yang jadi satu halaman dengan blok ttd) tidak pernah penuh
        // sesak, sehingga ttd selalu punya ruang menyatu dengan data.
        // Nomor urut pakai counter global ($no++), BUKAN rumus dari
        // index chunk, supaya tidak pernah loncat di halaman manapun.
        $rowsPerPage = 15;
        $tiketChunks = collect($tikets)->values()->chunk($rowsPerPage);
        $no = 0;
    @endphp

    @forelse ($tiketChunks as $chunkIndex => $chunk)
        <div @if ($chunkIndex > 0) style="page-break-before: always;" @endif>
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
                    @foreach ($chunk as $tiket)
                        @php $no++; @endphp
                        <tr>
                            <td class="text-center">{{ $no }}</td>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
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
                <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
            </tbody>
        </table>
    @endforelse

    @include('pdf.partials.ttd')

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>
