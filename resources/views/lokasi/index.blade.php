@extends('layouts.app')

@section('title', 'Data Lokasi')
@section('page-title', 'Data Lokasi')

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
        <span class="fw-semibold">Data Lokasi</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahLokasiModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Data
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Lokasi</th>
                    <th>Keterangan</th>
                    <th>Jumlah Tiket</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lokasis as $lokasi)
                    <tr>
                        <td>{{ $loop->iteration + ($lokasis->currentPage() - 1) * $lokasis->perPage() }}</td>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                        <td>{{ $lokasi->keterangan ?? '-' }}</td>
                        <td>{{ $lokasi->tikets_count }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editLokasiModal{{ $lokasi->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('lokasi.destroy', $lokasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lokasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editLokasiModal{{ $lokasi->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('lokasi.update', $lokasi) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Lokasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lokasi</label>
                                            <input type="text" name="nama_lokasi" value="{{ $lokasi->nama_lokasi }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <input type="text" name="keterangan" value="{{ $lokasi->keterangan }}" class="form-control">
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
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data lokasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $lokasis->links() }}
    </div>
</div>

<div class="modal fade" id="tambahLokasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lokasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
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
