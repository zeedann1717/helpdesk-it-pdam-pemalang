@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan Tiket')

@section('content')
<div class="card stat-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="waiting" @selected(request('status') == 'waiting')>Waiting</option>
                    <option value="in_progress" @selected(request('status') == 'in_progress')>In Progress</option>
                    <option value="done" @selected(request('status') == 'done')>Done</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button>
            </div>
            <div class="col-12 col-md-2">
                <a href="{{ route('laporan.exportPdf', request()->query()) }}" class="btn btn-outline-danger w-100">
                    <i class="fa-solid fa-file-pdf me-1"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">No</th>
                    <th>Kode Tiket</th>
                    <th>User</th>
                    <th>Devisi</th>
                    <th>Lokasi</th>
                    <th>Kendala</th>
                    <th>Tgl Buat</th>
                    <th>Tgl Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tikets as $tiket)
                    <tr>
                        <td>{{ $loop->iteration + ($tikets->currentPage() - 1) * $tikets->perPage() }}</td>
                        <td>{{ $tiket->kode_tiket }}</td>
                        <td>{{ $tiket->user?->name ?? '-' }}</td>
                        <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                        <td>{{ $tiket->lokasi?->nama_lokasi ?? '-' }}</td>
                        <td>{{ Str::limit($tiket->keluhan, 30) }}</td>
                        <td>{{ $tiket->created_at->format('d-m-Y') }}</td>
                        <td>{{ $tiket->tanggal_selesai?->format('d-m-Y') ?? '-' }}</td>
                        <td><span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data untuk periode/filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $tikets->links() }}
    </div>
</div>
@endsection
