@extends('layouts.app')

@section('title', 'Detail Tiket')
@section('page-title', 'Detail Tiket / '.$tiket->kode_tiket)

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white fw-semibold">Detail Tiket ({{ $tiket->kode_tiket }})</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:180px">Kode Tiket</td>
                        <td class="fw-semibold">: {{ $tiket->kode_tiket }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Lengkap</td>
                        <td>: {{ $tiket->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Devisi</td>
                        <td>: {{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Unit</td>
                        <td>: {{ $tiket->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>: {{ $tiket->lokasi?->nama_lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted align-top">Keluhan/Masalah</td>
                        <td>: {{ $tiket->keluhan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted align-top">Foto</td>
                        <td>
                            :
                            @if ($tiket->foto)
                                <br>
                                <a href="{{ asset('storage/'.$tiket->foto) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$tiket->foto) }}" alt="Foto kendala" class="img-fluid rounded mt-2" style="max-width:320px;">
                                </a>
                            @else
                                Tidak ada foto
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Dibuat</td>
                        <td>: {{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Selesai</td>
                        <td>: {{ $tiket->tanggal_selesai?->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>: <span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                    </tr>
                    @if ($tiket->catatan_admin)
                        <tr>
                            <td class="text-muted align-top">Catatan Admin</td>
                            <td>: {{ $tiket->catatan_admin }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <div class="col-12 col-lg-5">
            <div class="card stat-card">
                <div class="card-header bg-white fw-semibold">Update Status Tiket</div>
                <div class="card-body">
                    <form action="{{ route('tiket.updateStatus', $tiket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="waiting" @selected($tiket->status == 'waiting')>Waiting</option>
                                <option value="in_progress" @selected($tiket->status == 'in_progress')>In Progress</option>
                                <option value="done" @selected($tiket->status == 'done')>Done</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin (opsional)</label>
                            <textarea name="catatan_admin" rows="3" class="form-control">{{ $tiket->catatan_admin }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">
    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
</a>
@endsection
