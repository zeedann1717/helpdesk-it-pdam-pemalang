@extends('layouts.app')

@section('title', 'Daftar Tiket')
@section('page-title', 'Daftar Tiket')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari kode tiket...">
            </div>
            <div class="col-8 col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="waiting" @selected(request('status') == 'waiting')>Waiting</option>
                    <option value="in_progress" @selected(request('status') == 'in_progress')>In Progress</option>
                    <option value="done" @selected(request('status') == 'done')>Done</option>
                </select>
            </div>
            <div class="col-4 col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">No</th>
                    <th>Kode Tiket</th>
                    <th>User</th>
                    <th>Devisi</th>
                    <th>Unit</th>
                    <th>Kendala</th>
                    <th>Tanggal Buat</th>
                    <th>Status</th>
                    <th style="width:90px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tikets as $tiket)
                    <tr>
                        <td>{{ $loop->iteration + ($tikets->currentPage() - 1) * $tikets->perPage() }}</td>
                        <td>{{ $tiket->kode_tiket }}</td>
                        <td>{{ $tiket->user->name }}</td>
                        <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                        <td>{{ $tiket->unit ?? '-' }}</td>
                        <td>{{ Str::limit($tiket->keluhan, 35) }}</td>
                        <td>{{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                        <td><span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                        <td><a href="{{ route('tiket.show', $tiket) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data tiket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $tikets->links() }}
    </div>
</div>
@endsection
