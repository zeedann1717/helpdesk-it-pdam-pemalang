@extends('layouts.app')

@section('title', 'Input Pemeriksaan')
@section('page-title', 'Input Hasil Pemeriksaan Berkala')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white fw-semibold">Data Pemeriksaan</div>
    <div class="card-body">
        <form action="{{ route('pemeriksaan.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Perangkat</label>
                    <select name="perangkat_id" class="form-select" required>
                        <option value="">Pilih perangkat...</option>
                        @foreach ($perangkats as $p)
                            <option value="{{ $p->id }}">{{ $p->kode_inventaris }} — {{ $p->nama_perangkat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Pemeriksaan</label>
                    <input type="date" name="tanggal_pemeriksaan" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jadwal</label>
                    <select name="jadwal" class="form-select" required>
                        <option value="Harian">Harian</option>
                        <option value="Mingguan">Mingguan</option>
                        <option value="Bulanan">Bulanan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Pemeriksa (Teknisi)</label>
                    <input type="text" name="nama_pemeriksa" class="form-control" required placeholder="Nama teknisi yang periksa di lapangan">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catatan Umum (opsional)</label>
                    <input type="text" name="catatan_umum" class="form-control">
                </div>
            </div>

            @foreach ($checklistItems as $kategoriKode => $items)
                <div class="mb-4">
                    <div class="fw-bold text-primary mb-2">{{ $kategoriKode }}. {{ $items->first()->kategori_label }}</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px">No</th>
                                    <th>Item Pemeriksaan</th>
                                    <th style="width:160px">Kondisi</th>
                                    <th style="width:160px">Hasil</th>
                                    <th style="width:220px">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->urutan }}</td>
                                        <td>{{ $item->item_pemeriksaan }}</td>
                                        <td>
                                            <div class="form-check">
                                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="baik" class="form-check-input" required>
                                                <label class="form-check-label">{{ $item->label_kondisi_positif }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="tidak" class="form-check-input">
                                                <label class="form-check-label">Tidak</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="radio" name="items[{{ $item->id }}][hasil]" value="layak" class="form-check-input" required>
                                                <label class="form-check-label">Layak digunakan</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="items[{{ $item->id }}][hasil]" value="tidak_layak" class="form-check-input">
                                                <label class="form-check-label">Tidak layak</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $item->id }}][catatan]" class="form-control form-control-sm" placeholder="Opsional">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Hasil Pemeriksaan
            </button>
            <a href="{{ route('pemeriksaan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
