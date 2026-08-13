@extends('layouts.app')

@section('title', 'Edit Tiket')
@section('page-title', 'Edit Tiket')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Edit Pengaduan Kendala IT — {{ $tiket->kode_tiket }}</div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tiket.update', $tiket) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Nama Pelapor</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Devisi</label>
                    <input type="text" class="form-control" value="{{ $tiket->divisi?->nama_divisi ?? '-' }}" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Unit / Perangkat Bermasalah</label>
                    <input type="text" name="unit" value="{{ old('unit', $tiket->unit) }}" class="form-control" placeholder="Contoh: Laptop, PC, Printer, Jaringan" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" class="form-select" required>
                        <option value="">Pilih lokasi...</option>
                        @foreach ($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" @selected(old('lokasi_id', $tiket->lokasi_id) == $lokasi->id)>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Foto Kendala/Masalah</label>
                    @if ($tiket->foto)
                        <div class="mb-2">
                            <img src="{{ Storage::url($tiket->foto) }}" alt="Foto saat ini" style="max-width:160px;border-radius:8px;">
                            <div class="form-text">Foto saat ini. Upload file baru di bawah kalau mau menggantinya.</div>
                        </div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan kalau tidak ingin mengganti foto.</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Kendala / Masalah</label>
                    <textarea name="keluhan" rows="4" class="form-control" placeholder="Jelaskan kendala yang dialami..." required>{{ old('keluhan', $tiket->keluhan) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('tiket.show', $tiket) }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
