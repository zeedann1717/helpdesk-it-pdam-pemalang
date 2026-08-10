@extends('layouts.app')

@section('title', 'Permintaan Reset Password')
@section('page-title', 'Permintaan Reset Password')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Daftar Permintaan</div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $r)
                    <tr>
                        <td>{{ $r->user->name }} <span class="text-muted">({{ $r->user->username }})</span></td>
                        <td>{{ $r->catatan ?? '-' }}</td>
                        <td>
                            @if ($r->status === 'pending')
                                <span class="badge bg-danger">Pending</span>
                            @else
                                <span class="badge bg-success">Selesai oleh {{ $r->handler?->name }}</span>
                            @endif
                        </td>
                        <td>{{ $r->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            @if ($r->status === 'pending')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalReset{{ $r->id }}">
                                    Proses
                                </button>

                                <div class="modal fade" id="modalReset{{ $r->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('passwordRequests.approve', $r) }}" method="POST" class="modal-content">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title">Set Password Baru — {{ $r->user->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Password Baru</label>
                                                    <input type="text" name="password" class="form-control" minlength="6" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Konfirmasi Password</label>
                                                    <input type="text" name="password_confirmation" class="form-control" minlength="6" required>
                                                </div>
                                                <p class="small text-muted mb-0">Sampaikan password ini langsung ke user secara manual.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan & Selesaikan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada permintaan reset password.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $requests->links() }}
    </div>
</div>
@endsection