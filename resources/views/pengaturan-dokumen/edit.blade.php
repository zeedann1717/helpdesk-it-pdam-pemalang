@extends('layouts.app')

@section('title', 'Pengaturan Dokumen')
@section('page-title', 'Pengaturan Dokumen')

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

<p class="text-muted mb-3">
    Nama & jabatan di sini otomatis dipakai sebagai penandatangan di semua laporan PDF
    (Data Stok Barang, Laporan Tiket, Hasil Pemeriksaan Berkala)
</p>

<form action="{{ route('pengaturanDokumen.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        {{-- ==== Diperiksa Oleh ==== --}}
        <div class="col-12 col-lg-6">
            <div class="card stat-card h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="fa-solid fa-user-check me-2 text-primary"></i> Diperiksa Oleh
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="diperiksa_nama" class="form-control"
                               value="{{ old('diperiksa_nama', $pengaturan->diperiksa_nama) }}"
                               placeholder="Kosongkan jika belum ditentukan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="diperiksa_jabatan" class="form-control"
                               value="{{ old('diperiksa_jabatan', $pengaturan->diperiksa_jabatan) }}"
                               placeholder="Contoh: Kepala Divisi PDE">
                    </div>
                    <div>
                        <label class="form-label">NPP</label>
                        <input type="text" name="diperiksa_npp" class="form-control"
                               value="{{ old('diperiksa_npp', $pengaturan->diperiksa_npp) }}"
                               placeholder="Kosongkan jika belum ada">
                    </div>
                </div>
            </div>
        </div>

        {{-- ==== Dibuat Oleh ==== --}}
        <div class="col-12 col-lg-6">
            <div class="card stat-card h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="fa-solid fa-pen-nib me-2 text-primary"></i> Dibuat Oleh
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="dibuat_nama" class="form-control"
                               value="{{ old('dibuat_nama', $pengaturan->dibuat_nama) }}"
                               placeholder="Kosongkan jika belum ditentukan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="dibuat_jabatan" class="form-control"
                               value="{{ old('dibuat_jabatan', $pengaturan->dibuat_jabatan) }}"
                               placeholder="Contoh: Staff Divisi PDE">
                    </div>
                    <div>
                        <label class="form-label">NPP</label>
                        <input type="text" name="dibuat_npp" class="form-control"
                               value="{{ old('dibuat_npp', $pengaturan->dibuat_npp) }}"
                               placeholder="Kosongkan jika belum ada">
                    </div>
                </div>
            </div>
        </div>

        {{-- ==== Disetujui Oleh (opsional, ada saklar) ==== --}}
        <div class="col-12">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="fa-solid fa-signature me-2 text-primary"></i> Disetujui Oleh
                    </span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="tampilkanDisetujui" name="tampilkan_disetujui" value="1"
                               {{ old('tampilkan_disetujui', $pengaturan->tampilkan_disetujui) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampilkanDisetujui">Tampilkan di PDF</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Nama</label>
                            <input type="text" name="disetujui_nama" class="form-control"
                                   value="{{ old('disetujui_nama', $pengaturan->disetujui_nama) }}"
                                   placeholder="Kosongkan jika belum ditentukan">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="disetujui_jabatan" class="form-control"
                                   value="{{ old('disetujui_jabatan', $pengaturan->disetujui_jabatan) }}"
                                   placeholder="Contoh: Direktur Utama">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">NPP</label>
                            <input type="text" name="disetujui_npp" class="form-control"
                                   value="{{ old('disetujui_npp', $pengaturan->disetujui_npp) }}"
                                   placeholder="Kosongkan jika belum ada">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
        </button>
    </div>
</form>

@endsection