@extends('layouts.app')

@section('title', 'Tiket Waiting')
@section('page-title', 'Tiket Waiting')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Tiket yang belum diproses</div>
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
                    <th style="width:90px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tikets as $tiket)
                    <tr class="{{ $tiket->pesan_baru_count > 0 ? 'table-warning' : '' }}">
                        <td>{{ $loop->iteration + ($tikets->currentPage() - 1) * $tikets->perPage() }}</td>
                        <td>
                            {{ $tiket->kode_tiket }}
                            @if ($tiket->pesan_baru_count > 0)
                                <span class="badge bg-danger ms-1" title="Ada pesan chat belum dibaca">
                                    <i class="fa-solid fa-comment-dots"></i> {{ $tiket->pesan_baru_count }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $tiket->user->name }}</td>
                        <td>{{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                        <td>{{ $tiket->unit ?? '-' }}</td>
                        <td>{{ Str::limit($tiket->keluhan, 35) }}</td>
                        <td>{{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                        <td><a href="{{ route('tiket.show', $tiket) }}" class="btn btn-sm btn-outline-primary">Proses</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada tiket yang menunggu. 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $tikets->links() }}
    </div>
</div>
@endsection
