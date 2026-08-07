@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@if (auth()->user()->isAdmin())
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-location-dot fs-3 text-primary mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['lokasi'] }}</div>
                <div class="text-muted small">Lokasi</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-sitemap fs-3 text-info mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['divisi'] }}</div>
                <div class="text-muted small">Devisi</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-users fs-3 text-secondary mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['user'] }}</div>
                <div class="text-muted small">User</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-clock fs-3 text-danger mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['tiket_waiting'] }}</div>
                <div class="text-muted small">Tiket Waiting</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-spinner fs-3 text-warning mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['tiket_in_progress'] }}</div>
                <div class="text-muted small">In Progress</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 text-center h-100">
                <i class="fa-solid fa-circle-check fs-3 text-success mb-2"></i>
                <div class="fs-4 fw-bold">{{ $stats['tiket_done'] }}</div>
                <div class="text-muted small">Tiket Done</div>
            </div>
        </div>
    </div>
@else
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center h-100">
                <div class="fs-4 fw-bold">{{ $stats['tiket_saya'] }}</div>
                <div class="text-muted small">Total Tiket Saya</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center h-100">
                <div class="fs-4 fw-bold text-danger">{{ $stats['tiket_waiting'] }}</div>
                <div class="text-muted small">Waiting</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center h-100">
                <div class="fs-4 fw-bold text-warning">{{ $stats['tiket_in_progress'] }}</div>
                <div class="text-muted small">In Progress</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center h-100">
                <div class="fs-4 fw-bold text-success">{{ $stats['tiket_done'] }}</div>
                <div class="text-muted small">Done</div>
            </div>
        </div>
    </div>

    <a href="{{ route('tiket.create') }}" class="btn btn-primary mb-4">
        <i class="fa-solid fa-plus me-1"></i> Buat Tiket Baru
    </a>
@endif

<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Tiket Terbaru</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Tiket</th>
                    @if(auth()->user()->isAdmin())<th>User</th>@endif
                    <th>Devisi</th>
                    <th>Kendala</th>
                    <th>Tanggal Buat</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tiketTerbaru as $tiket)
                    <tr>
                        <td>{{ $tiket->kode_tiket }}</td>
                        @if(auth()->user()->isAdmin())<td>{{ $tiket->user->name }}</td>@endif
                        <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                        <td>{{ Str::limit($tiket->keluhan, 40) }}</td>
                        <td>{{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                        <td><span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                        <td><a href="{{ route('tiket.show', $tiket) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data tiket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
