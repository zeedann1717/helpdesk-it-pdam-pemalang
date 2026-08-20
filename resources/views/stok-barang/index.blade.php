@extends('layouts.app')

@section('title', 'Stok Barang')
@section('page-title', 'Stok Barang')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-semibold">Data Stok Barang</span>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="divisi_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Divisi</option>
                    @foreach ($divisis as $d)
                        <option value="{{ $d->id }}" @selected(request('divisi_id') == $d->id)>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('stokBarang.exportPdf', request()->only('divisi_id')) }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahStokModal">
                <i class="fa-solid fa-plus me-1"></i> Tambah Barang
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Barang</th>
                    <th>Divisi</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Kondisi Unit</th>
                    <th>Kondisi Barang</th>
                    <th>Keterangan</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stokBarangs as $s)
                    <tr>
                        <td>{{ $s->nama_barang }}</td>
                        <td>{{ $s->divisi->nama_divisi }}</td>
                        <td>{{ $s->jumlah }}</td>
                        <td>{{ $s->satuan }}</td>
                        <td><span class="badge {{ $s->kondisiBadgeClass() }}">{{ $s->kondisiLabel() }}</span></td>
                        <td><span class="badge {{ $s->kondisiBarangBadgeClass() }}">{{ $s->kondisiBarangLabel() }}</span></td>
                        <td>{{ $s->keterangan ?? '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStokModal{{ $s->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('stokBarang.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data stok barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editStokModal{{ $s->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('stokBarang.update', $s) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Stok Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Barang</label>
                                            <input type="text" name="nama_barang" value="{{ $s->nama_barang }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Divisi</label>
                                            <select name="divisi_id" class="form-select" required>
                                                @foreach ($divisis as $d)
                                                    <option value="{{ $d->id }}" @selected($s->divisi_id == $d->id)>{{ $d->nama_divisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" name="jumlah" value="{{ $s->jumlah }}" class="form-control" min="0" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Satuan</label>
                                                <input type="text" name="satuan" value="{{ $s->satuan }}" class="form-control" required placeholder="pcs, unit, meter, dll">
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                            <div class="col-6">
                                                <label class="form-label">Kondisi Unit</label>
                                                <select name="kondisi" class="form-select" required>
                                                    <option value="baik" @selected($s->kondisi === 'baik')>Baik</option>
                                                    <option value="rusak" @selected($s->kondisi === 'rusak')>Rusak</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Kondisi Barang</label>
                                                <select name="kondisi_barang" class="form-select" required>
                                                    <option value="baru" @selected($s->kondisi_barang === 'baru')>Stok Baru</option>
                                                    <option value="lama" @selected($s->kondisi_barang === 'lama')>Stok Lama</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 mt-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea name="keterangan" rows="2" class="form-control">{{ $s->keterangan }}</textarea>
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
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data stok barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $stokBarangs->links() }}</div>
</div>

<div class="modal fade" id="tambahStokModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stokBarang.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required placeholder="mis. Router TP-Link, Kabel LAN Cat6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select name="divisi_id" class="form-select" required>
                            <option value="">Pilih divisi...</option>
                            @foreach ($divisis as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="0" value="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" required placeholder="pcs, unit, meter, dll">
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <label class="form-label">Kondisi Unit</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kondisi Barang</label>
                            <select name="kondisi_barang" class="form-select" required>
                                <option value="baru">Stok Baru</option>
                                <option value="lama">Stok Lama</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Keterangan (opsional)</label>
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
