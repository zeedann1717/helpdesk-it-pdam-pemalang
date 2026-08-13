@extends('layouts.app')

@section('title', 'Statistik Tiket')
@section('page-title', 'Statistik Tiket')

@push('styles')
<style>
    .periode-toggle {
        display: flex;
        background: #eef1f6;
        border-radius: 12px;
        padding: 4px;
        gap: 4px;
    }
    .periode-toggle a {
        flex: 1;
        text-align: center;
        padding: 8px 6px;
        border-radius: 9px;
        font-weight: 600;
        font-size: .85rem;
        color: #4b5563;
        text-decoration: none;
    }
    .periode-toggle a.active {
        background: #0d3b8c;
        color: #fff;
    }
    .periode-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin: 12px 0 18px;
    }
    .periode-nav .btn-nav {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #0d3b8c;
        flex-shrink: 0;
    }
    .periode-nav .btn-nav.disabled {
        color: #c7ccd4;
        pointer-events: none;
    }
    .periode-label {
        text-align: center;
        font-weight: 700;
        color: #1f2937;
        font-size: .95rem;
        flex-grow: 1;
    }
    .stat-mini {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 16px;
        text-align: center;
        height: 100%;
    }
    .stat-mini .angka { font-size: 1.6rem; font-weight: 800; color: #0d3b8c; }
    .stat-mini .label { font-size: .75rem; color: #6b7280; font-weight: 600; }
    .chart-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 18px;
        margin-bottom: 18px;
    }
    .chart-card h6 { font-weight: 700; margin-bottom: 14px; }
    .chart-wrap {
        position: relative;
        width: 100%;
        height: 260px;
    }
    @media (min-width: 768px) {
        .chart-wrap { height: 320px; }
    }
    .status-chip-row { display: flex; gap: 8px; flex-wrap: wrap; }
    .status-chip {
        flex: 1;
        min-width: 100px;
        border-radius: 12px;
        padding: 10px 12px;
        text-align: center;
    }
    .status-chip .n { font-weight: 800; font-size: 1.15rem; display: block; }
    .status-chip .l { font-size: .72rem; font-weight: 600; }
    .rank-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 12px 14px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .rank-badge {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #eef1f6;
        color: #4b5563;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
        flex-shrink: 0;
        font-size: .85rem;
    }
    .rank-card.top .rank-badge { background: #fee2e2; color: #dc2626; }
    .rank-bar-bg {
        height: 8px;
        background: #eef1f6;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 4px;
    }
    .rank-bar-fill {
        height: 100%;
        background: #0d3b8c;
        border-radius: 999px;
    }
    .rank-card.top .rank-bar-fill { background: #dc2626; }
</style>
@endpush

@section('content')

    {{-- Filter periode: Harian / Mingguan / Bulanan --}}
    <div class="periode-toggle">
        @foreach (['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan'] as $key => $label)
            <a href="{{ route('statistik.index', ['periode' => $key, 'tanggal' => $tanggalAcuan]) }}"
               class="{{ $periode === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- Navigasi geser periode --}}
    <div class="periode-nav">
        <a href="{{ route('statistik.index', ['periode' => $periode, 'tanggal' => $prevDate]) }}" class="btn-nav" aria-label="Periode sebelumnya">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <div class="periode-label">{{ $labelPeriode }}</div>
        <a href="{{ $isPeriodeSaatIni ? '#' : route('statistik.index', ['periode' => $periode, 'tanggal' => $nextDate]) }}"
           class="btn-nav {{ $isPeriodeSaatIni ? 'disabled' : '' }}" aria-label="Periode berikutnya">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    {{-- Stat ringkas --}}
    <div class="row g-3 mb-1">
        <div class="col-4">
            <div class="stat-mini">
                <div class="angka">{{ $totalTiket }}</div>
                <div class="label">Total Tiket</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-mini">
                <div class="angka" style="font-size:1rem; line-height:1.3;">
                    {{ $divisiTertinggi['kode'] ?? '-' }}
                </div>
                <div class="label">Divisi Terbanyak</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-mini">
                <div class="angka">{{ $rataRata }}</div>
                <div class="label">Rata-rata / Divisi</div>
            </div>
        </div>
    </div>

    <div class="mt-4"></div>

    {{-- Chart batang: jumlah tiket per divisi --}}
    <div class="chart-card">
        <h6><i class="fa-solid fa-chart-column me-2 text-primary"></i>Jumlah Tiket per Divisi</h6>
        @if ($totalTiket === 0)
            <p class="text-muted mb-0 small">Belum ada tiket pada periode ini.</p>
        @else
            <div class="chart-wrap">
            <canvas id="chartDivisi"></canvas>
        </div>
        @endif
    </div>

    {{-- Chart garis: tren jumlah tiket --}}
    <div class="chart-card">
        <h6><i class="fa-solid fa-chart-line me-2 text-primary"></i>Tren Tiket Masuk</h6>
        <div class="chart-wrap">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    {{-- Breakdown status --}}
    <div class="chart-card">
        <h6 class="mb-3"><i class="fa-solid fa-list-check me-2 text-primary"></i>Status Tiket Periode Ini</h6>
        <div class="status-chip-row">
            <div class="status-chip" style="background:#fef2f2;">
                <span class="n" style="color:#dc2626;">{{ $statusBreakdown['waiting'] }}</span>
                <span class="l" style="color:#dc2626;">Waiting</span>
            </div>
            <div class="status-chip" style="background:#fffbeb;">
                <span class="n" style="color:#b45309;">{{ $statusBreakdown['in_progress'] }}</span>
                <span class="l" style="color:#b45309;">In Progress</span>
            </div>
            <div class="status-chip" style="background:#f0fdf4;">
                <span class="n" style="color:#15803d;">{{ $statusBreakdown['done'] }}</span>
                <span class="l" style="color:#15803d;">Done</span>
            </div>
        </div>
    </div>

    {{-- Ranking divisi --}}
    <h6 class="fw-bold mb-2 mt-4">Ranking Divisi Paling Sering Bermasalah</h6>
    @forelse ($rank as $i => $item)
        <div class="rank-card {{ $i === 0 && $item['total'] > 0 ? 'top' : '' }}">
            <div class="rank-badge">{{ $i + 1 }}</div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold">{{ $item['nama'] }}</span>
                    <span class="fw-bold">{{ $item['total'] }}</span>
                </div>
                <div class="rank-bar-bg">
                    <div class="rank-bar-fill" style="width: {{ $totalTiket > 0 ? round(($item['total'] / $totalTiket) * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted small">Belum ada data divisi.</p>
    @endforelse

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const rankData = @json($rank);
    const trenData = @json($tren);

    if (rankData.some(d => d.total > 0)) {
        const maxTotal = Math.max(...rankData.map(d => d.total));
        new Chart(document.getElementById('chartDivisi'), {
            type: 'bar',
            data: {
                labels: rankData.map(d => d.kode),
                datasets: [{
                    data: rankData.map(d => d.total),
                    backgroundColor: rankData.map(d => d.total === maxTotal && maxTotal > 0 ? '#dc2626' : '#0d3b8c'),
                    borderRadius: 6,
                    maxBarThickness: 36,
                }],
            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: (items) => rankData[items[0].dataIndex].nama,
                        },
                    },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    }

    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: trenData.map(d => d.label),
            datasets: [{
                data: trenData.map(d => d.total),
                borderColor: '#0d3b8c',
                backgroundColor: 'rgba(13,59,140,.12)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
            },
        },
    });
})();
</script>
@endpush
