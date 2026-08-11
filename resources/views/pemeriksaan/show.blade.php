@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')
@section('page-title', 'Detail Hasil Pemeriksaan')

@section('content')
<div class="card stat-card mb-4">
    <div class="card-header bg-white fw-semibold">Informasi Pemeriksaan</div>
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr><td class="text-muted" style="width:200px">Perangkat</td><td>: {{ $pemeriksaan->perangkat->kode_inventaris }} — {{ $pemeriksaan->perangkat->nama_perangkat }}</td></tr>
            <tr><td class="text-muted">Lokasi</td><td>: {{ $pemeriksaan->perangkat->lokasi?->nama_lokasi ?? '-' }}</td></tr>
            <tr><td class="text-muted">Tanggal</td><td>: {{ $pemeriksaan->tanggal_pemeriksaan->format('d-m-Y') }} ({{ $pemeriksaan->hari }})</td></tr>
            <tr><td class="text-muted">Jadwal</td><td>: {{ $pemeriksaan->jadwal }}</td></tr>
            <tr><td class="text-muted">Nama Pemeriksa</td><td>: {{ $pemeriksaan->nama_pemeriksa }}</td></tr>
            <tr><td class="text-muted">Diinput Oleh</td><td>: {{ $pemeriksaan->diinputOlehUser->name }}</td></tr>
            @if ($pemeriksaan->catatan_umum)
                <tr><td class="text-muted align-top">Catatan Umum</td><td>: {{ $pemeriksaan->catatan_umum }}</td></tr>
            @endif
        </table>
    </div>
</div>

@foreach ($itemsByKategori as $kategoriKode => $items)
    <div class="card stat-card mb-4">
        <div class="card-header bg-white fw-semibold text-primary">{{ $kategoriKode }}. {{ $items->first()->checklistItem->kategori_label }}</div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Item Pemeriksaan</th>
                        <th style="width:120px">Kondisi</th>
                        <th style="width:150px">Hasil</th>
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
                                <span class="badge {{ $item->hasil === 'layak' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->hasil === 'layak' ? 'Layak digunakan' : 'Tidak layak' }}
                                </span>
                            </td>
                            <td>{{ $item->catatan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

<a href="{{ route('pemeriksaan.index') }}" class="btn btn-outline-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
</a>
@endsection
