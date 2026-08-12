@extends('layouts.app')

@section('title', 'Buat Tiket')
@section('page-title', 'Input Tiket')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Form Pengaduan Kendala IT</div>
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

        @if (! auth()->user()->divisi_id)
            <div class="alert alert-warning">
                Akun Anda belum terdaftar di divisi manapun. Hubungi Super Admin untuk mengatur divisi akun Anda sebelum membuat tiket.
            </div>
        @endif

        <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Nama Pelapor</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Tanggal &amp; Jam</label>
                    <input type="text" class="form-control" value="{{ now()->format('d-m-Y H:i') }}" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Devisi</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->divisi?->nama_divisi ?? 'Belum ada divisi' }}" disabled>
                    {{-- Devisi dikunci sesuai akun yang login, tidak bisa dipilih bebas.
                         Nilai sesungguhnya yang dipakai server tetap dari sesi login (lihat TiketController::store),
                         input hidden ini hanya membantu JS memuat daftar lokasi. --}}
                    <input type="hidden" id="divisiIdLocked" value="{{ auth()->user()->divisi_id }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Unit / Perangkat Bermasalah</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" class="form-control" placeholder="Contoh: Laptop, PC, Printer, Jaringan" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" id="lokasiSelect" class="form-select" required disabled>
                        <option value="">Memuat lokasi...</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Foto Kendala/Masalah <span class="text-danger">*</span></label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                    <small class="text-muted">Wajib diisi sebagai dokumentasi kondisi sebelum diperbaiki.</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Kendala / Masalah</label>
                    <textarea name="keluhan" rows="4" class="form-control" placeholder="Jelaskan kendala yang dialami..." required>{{ old('keluhan') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 px-4" @disabled(! auth()->user()->divisi_id)>
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Tiket
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const divisiId = document.getElementById('divisiIdLocked').value;
    const lokasiSelect = document.getElementById('lokasiSelect');
    const oldLokasiId = '{{ old('lokasi_id') }}';

    function loadLokasi(id) {
        if (!id) {
            lokasiSelect.innerHTML = '<option value="">Divisi akun belum diatur</option>';
            lokasiSelect.disabled = true;
            return;
        }

        fetch(`/lokasi/by-divisi/${id}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    lokasiSelect.innerHTML = '<option value="">Belum ada lokasi untuk divisi ini</option>';
                    lokasiSelect.disabled = true;
                    return;
                }

                let options = '<option value="">Pilih lokasi...</option>';
                data.forEach(l => {
                    const selected = String(l.id) === oldLokasiId ? 'selected' : '';
                    options += `<option value="${l.id}" ${selected}>${l.nama_lokasi}</option>`;
                });
                lokasiSelect.innerHTML = options;
                lokasiSelect.disabled = false;
            })
            .catch(() => {
                lokasiSelect.innerHTML = '<option value="">Gagal memuat lokasi</option>';
            });
    }

    loadLokasi(divisiId);
})();

// ==== Cegah submit ganda ====
(function () {
    const form = document.querySelector('form[action="{{ route('tiket.store') }}"]');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) {
            e.preventDefault();
            return;
        }
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Mengirim...';
    });
})();
</script>
@endpush
