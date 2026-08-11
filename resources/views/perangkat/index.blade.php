@extends('layouts.app')

@section('title', 'Data Perangkat')
@section('page-title', 'Data Perangkat')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Data Perangkat</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPerangkatModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Data
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Inventaris</th>
                    <th>Nama Perangkat</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perangkats as $p)
                    <tr>
                        <td>{{ $p->kode_inventaris }}</td>
                        <td>{{ $p->nama_perangkat }}</td>
                        <td>{{ $p->jenis_perangkat }}</td>
                        <td>{{ $p->lokasi?->nama_lokasi ?? '-' }}</td>
                        <td><span class="badge {{ $p->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $p->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPerangkatModal{{ $p->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('perangkat.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus perangkat ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editPerangkatModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('perangkat.update', $p) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Perangkat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Inventaris</label>
                                            <input type="text" name="kode_inventaris" value="{{ $p->kode_inventaris }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Perangkat</label>
                                            <input type="text" name="nama_perangkat" value="{{ $p->nama_perangkat }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Perangkat</label>
                                            <select name="jenis_perangkat" class="form-select" required>
                                                @foreach (['Server','PC','Laptop','Printer','Switch','Router','UPS','Lainnya'] as $jenis)
                                                    <option value="{{ $jenis }}" @selected($p->jenis_perangkat == $jenis)>{{ $jenis }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Lokasi</label>
                                            <select name="lokasi_id" class="form-select">
                                                <option value="">Pilih lokasi...</option>
                                                @foreach ($lokasis as $lokasi)
                                                    <option value="{{ $lokasi->id }}" @selected($p->lokasi_id == $lokasi->id)>{{ $lokasi->nama_lokasi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea name="keterangan" rows="2" class="form-control">{{ $p->keterangan }}</textarea>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif{{ $p->id }}" @checked($p->aktif)>
                                            <label class="form-check-label" for="aktif{{ $p->id }}">Aktif</label>
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
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data perangkat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $perangkats->links() }}</div>
</div>

<div class="modal fade" id="tambahPerangkatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('perangkat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Inventaris</label>
                        <input type="text" name="kode_inventaris" class="form-control" required placeholder="mis. SRV-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat</label>
                        <input type="text" name="nama_perangkat" class="form-control" required placeholder="mis. Server Database Utama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Perangkat</label>
                        <select name="jenis_perangkat" class="form-select" required>
                            @foreach (['Server','PC','Laptop','Printer','Switch','Router','UPS','Lainnya'] as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <select name="lokasi_id" class="form-select">
                            <option value="">Pilih lokasi...</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control"></textarea>
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
