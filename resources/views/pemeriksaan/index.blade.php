@extends('layouts.app')

@section('title', 'Pemeriksaan Berkala')
@section('page-title', 'Pemeriksaan Berkala Perangkat')

@section('content')
<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-semibold">Riwayat Pemeriksaan</span>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="perangkat_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Perangkat</option>
                    @foreach ($perangkats as $p)
                        <option value="{{ $p->id }}" @selected(request('perangkat_id') == $p->id)>{{ $p->nama_perangkat }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('pemeriksaan.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Input Pemeriksaan
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Perangkat</th>
                    <th>Jadwal</th>
                    <th>Pemeriksa</th>
                    <th>Status</th>
                    <th style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pemeriksaans as $pm)
                    <tr>
                        <td>{{ $pm->tanggal_pemeriksaan->format('d-m-Y') }} ({{ $pm->hari }})</td>
                        <td>{{ $pm->perangkat->nama_perangkat }}</td>
                        <td>{{ $pm->jadwal }}</td>
                        <td>{{ $pm->nama_pemeriksa }}</td>
                        <td>
                            @if ($pm->adaYangTidakLayak())
                                <span class="badge bg-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Ada Tidak Layak</span>
                            @else
                                <span class="badge bg-success">Semua Layak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pemeriksaan.show', $pm) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pemeriksaan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $pemeriksaans->links() }}</div>
</div>
@endsection
