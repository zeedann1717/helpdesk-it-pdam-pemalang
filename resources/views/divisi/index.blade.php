@extends('layouts.app')

@section('title', 'Data Devisi')
@section('page-title', 'Data Devisi')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Data Devisi</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDivisiModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Data
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Kode Devisi</th>
                    <th>Nama Devisi</th>
                    <th>Jumlah User</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($divisis as $divisi)
                    <tr>
                        <td>{{ $loop->iteration + ($divisis->currentPage() - 1) * $divisis->perPage() }}</td>
                        <td>{{ $divisi->kode_divisi }}</td>
                        <td>{{ $divisi->nama_divisi }}</td>
                        <td>{{ $divisi->users_count }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDivisiModal{{ $divisi->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('divisi.destroy', $divisi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus divisi ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editDivisiModal{{ $divisi->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('divisi.update', $divisi) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Devisi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Devisi</label>
                                            <input type="text" name="kode_divisi" value="{{ $divisi->kode_divisi }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Devisi</label>
                                            <input type="text" name="nama_divisi" value="{{ $divisi->nama_divisi }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data devisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $divisis->links() }}
    </div>
</div>

<div class="modal fade" id="tambahDivisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('divisi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Devisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Devisi</label>
                        <input type="text" name="kode_divisi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Devisi</label>
                        <input type="text" name="nama_divisi" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
