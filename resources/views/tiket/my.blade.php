@extends('layouts.app')

@section('title', 'Tiket Saya')
@section('page-title', 'Tiket Saya')

@section('content')
<a href="{{ route('tiket.create') }}" class="btn btn-primary mb-3">
    <i class="fa-solid fa-plus me-1"></i> Buat Tiket Baru
</a>

<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Riwayat Tiket Saya</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">No</th>
                    <th>Kode Tiket</th>
                    <th>Devisi</th>
                    <th>Unit</th>
                    <th>Lokasi</th>
                    <th>Kendala</th>
                    <th>Tanggal Buat</th>
                    <th>Status</th>
                    <th style="width:220px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tikets as $tiket)
                    <tr>
                        <td>{{ $loop->iteration + ($tikets->currentPage() - 1) * $tikets->perPage() }}</td>
                        <td>{{ $tiket->kode_tiket }}</td>
                        <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                        <td>{{ $tiket->unit ?? '-' }}</td>
                        <td>{{ $tiket->lokasi?->nama_lokasi ?? '-' }}</td>
                        <td>{{ Str::limit($tiket->keluhan, 35) }}</td>
                        <td>{{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                        <td><span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tiket.show', $tiket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                @if ($tiket->status === 'waiting')
                                    <a href="{{ route('tiket.edit', $tiket) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('tiket.destroy', $tiket) }}" method="POST" onsubmit="return confirm('Yakin hapus tiket {{ $tiket->kode_tiket }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Anda belum pernah membuat tiket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $tikets->links() }}
    </div>
</div>
@endsection
