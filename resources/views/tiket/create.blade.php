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
                    <select name="divisi_id" id="divisiSelect" class="form-select" required>
                        <option value="">Pilih devisi...</option>
                        @foreach ($divisis as $divisi)
                            <option value="{{ $divisi->id }}" @selected(old('divisi_id') == $divisi->id || auth()->user()->divisi_id == $divisi->id)>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Unit / Perangkat Bermasalah</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" class="form-control" placeholder="Contoh: Laptop, PC, Printer, Jaringan" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" id="lokasiSelect" class="form-select" required disabled>
                        <option value="">Pilih devisi dahulu...</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Foto Kendala/Masalah (opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label">Kendala / Masalah</label>
                    <textarea name="keluhan" rows="4" class="form-control" placeholder="Jelaskan kendala yang dialami..." required>{{ old('keluhan') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 px-4">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Tiket
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const divisiSelect = document.getElementById('divisiSelect');
    const lokasiSelect = document.getElementById('lokasiSelect');
    const oldLokasiId = '{{ old('lokasi_id') }}';

    function loadLokasi(divisiId) {
        if (!divisiId) {
            lokasiSelect.innerHTML = '<option value="">Pilih devisi dahulu...</option>';
            lokasiSelect.disabled = true;
            return;
        }

        lokasiSelect.disabled = true;
        lokasiSelect.innerHTML = '<option value="">Memuat lokasi...</option>';

        fetch(`/lokasi/by-divisi/${divisiId}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    lokasiSelect.innerHTML = '<option value="">Belum ada lokasi untuk devisi ini</option>';
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

    divisiSelect.addEventListener('change', function () {
        loadLokasi(this.value);
    });

    // Kalau Divisi sudah terpilih dari awal (misal otomatis dari profil user), langsung fetch data lokasinya
    if (divisiSelect.value) {
        loadLokasi(divisiSelect.value);
    }
})();
</script>
@endpush