<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Pemeriksaan - {{ $pemeriksaan->perangkat->nama_perangkat }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
        table.info { width: 100%; margin-bottom: 16px; }
        table.info td { padding: 3px 6px; vertical-align: top; }
        table.info td.label { width: 150px; color: #555; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        table.items th, table.items td { border: 1px solid #999; padding: 5px 7px; text-align: left; }
        table.items th { background-color: #f0f0f0; }
        .kategori-title { font-weight: bold; color: #1a4d8f; margin: 14px 0 6px; font-size: 12px; }
        .badge { padding: 2px 8px; border-radius: 4px; color: #fff; font-size: 10px; }
        .badge-layak { background-color: #198754; }
        .badge-tidak { background-color: #dc3545; }
        .footer { margin-top: 14px; font-size: 9px; color: #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop', [
        'judulLaporan' => 'Hasil Pemeriksaan Berkala Perangkat',
        'subjudulLines' => [
            $pemeriksaan->perangkat->kode_inventaris . ' — ' . $pemeriksaan->perangkat->nama_perangkat,
        ],
    ])

    <table class="info">
        <tr><td class="label">Perangkat</td><td>: {{ $pemeriksaan->perangkat->kode_inventaris }} — {{ $pemeriksaan->perangkat->nama_perangkat }}</td></tr>
        <tr><td class="label">Lokasi</td><td>: {{ $pemeriksaan->perangkat->lokasi?->nama_lokasi ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal</td><td>: {{ $pemeriksaan->tanggal_pemeriksaan->format('d-m-Y') }} ({{ $pemeriksaan->hari }})</td></tr>
        <tr><td class="label">Jadwal</td><td>: {{ $pemeriksaan->jadwal }}</td></tr>
        <tr><td class="label">Nama Pemeriksa</td><td>: {{ $pemeriksaan->nama_pemeriksa }}</td></tr>
        <tr><td class="label">Diinput Oleh</td><td>: {{ $pemeriksaan->diinputOlehUser->name }}</td></tr>
        @if ($pemeriksaan->catatan_umum)
            <tr><td class="label">Catatan Umum</td><td>: {{ $pemeriksaan->catatan_umum }}</td></tr>
        @endif
    </table>

    @foreach ($itemsByKategori as $kategoriKode => $items)
        <div class="kategori-title">{{ $kategoriKode }}. {{ $items->first()->checklistItem->kategori_label }}</div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width:30px">No</th>
                    <th>Item Pemeriksaan</th>
                    <th style="width:90px">Kondisi</th>
                    <th style="width:110px">Hasil</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->checklistItem->urutan }}</td>
                        <td>{{ $item->checklistItem->item_pemeriksaan }}</td>
                        <td>{{ $item->kondisi === 'baik' ? $item->checklistItem->label_kondisi_positif : 'Tidak' }}</td>
                        <td>
                            <span class="badge {{ $item->hasil === 'layak' ? 'badge-layak' : 'badge-tidak' }}">
                                {{ $item->hasil === 'layak' ? 'Layak digunakan' : 'Tidak layak' }}
                            </span>
                        </td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    @include('pdf.partials.ttd')

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB
    </div>
</body>
</html>