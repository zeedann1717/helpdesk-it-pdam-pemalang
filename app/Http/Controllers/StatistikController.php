<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StatistikController extends Controller
{
    /**
     * Statistik jumlah tiket per divisi + tren dari waktu ke waktu.
     * Periode bisa dilihat harian, mingguan, atau bulanan, digeser maju/mundur
     * dari tanggal acuan (default hari ini). Khusus Super Admin (route sudah
     * dibatasi middleware 'superadmin').
     */
    public function index(Request $request): View
    {
        $periode = in_array($request->get('periode'), ['harian', 'mingguan', 'bulanan'], true)
            ? $request->get('periode')
            : 'mingguan';

        $tanggalAcuan = $request->filled('tanggal')
            ? Carbon::parse($request->string('tanggal'))
            : now();

        [$mulai, $selesai, $labelPeriode] = match ($periode) {
            'harian' => [
                $tanggalAcuan->copy()->startOfDay(),
                $tanggalAcuan->copy()->endOfDay(),
                $tanggalAcuan->translatedFormat('l, d F Y'),
            ],
            'bulanan' => [
                $tanggalAcuan->copy()->startOfMonth(),
                $tanggalAcuan->copy()->endOfMonth(),
                $tanggalAcuan->translatedFormat('F Y'),
            ],
            default => [
                $tanggalAcuan->copy()->startOfWeek(Carbon::MONDAY),
                $tanggalAcuan->copy()->endOfWeek(Carbon::SUNDAY),
                $tanggalAcuan->copy()->startOfWeek(Carbon::MONDAY)->translatedFormat('d M')
                    .' - '.$tanggalAcuan->copy()->endOfWeek(Carbon::SUNDAY)->translatedFormat('d M Y'),
            ],
        };

        // ==== Jumlah tiket per divisi (buat chart batang + ranking) ====
        $tiketPerDivisi = Tiket::selectRaw('divisi_id, COUNT(*) as total')
            ->whereBetween('created_at', [$mulai, $selesai])
            ->groupBy('divisi_id')
            ->pluck('total', 'divisi_id');

        $rank = Divisi::orderBy('nama_divisi')->get()
            ->map(fn (Divisi $divisi) => [
                'nama' => $divisi->nama_divisi,
                'kode' => $divisi->kode_divisi,
                'total' => (int) ($tiketPerDivisi[$divisi->id] ?? 0),
            ])
            ->sortByDesc('total')
            ->values();

        $totalTiket = (int) $rank->sum('total');
        $divisiTertinggi = $totalTiket > 0 ? $rank->first() : null;
        $rataRata = $rank->count() > 0 ? round($totalTiket / $rank->count(), 1) : 0;

        // ==== Tren jumlah tiket dalam periode (buat chart garis) ====
        if ($periode === 'harian') {
            $rows = Tiket::selectRaw('HOUR(created_at) as bucket, COUNT(*) as total')
                ->whereBetween('created_at', [$mulai, $selesai])
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $tren = collect(range(0, 23))->map(fn ($jam) => [
                'label' => sprintf('%02d:00', $jam),
                'total' => (int) ($rows[$jam] ?? 0),
            ]);
        } else {
            $rows = Tiket::selectRaw('DATE(created_at) as bucket, COUNT(*) as total')
                ->whereBetween('created_at', [$mulai, $selesai])
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            $tren = collect();
            $cursor = $mulai->copy()->startOfDay();
            $batasLoop = $selesai->copy()->startOfDay();
            while ($cursor->lte($batasLoop)) {
                $tren->push([
                    'label' => $cursor->translatedFormat('d M'),
                    'total' => (int) ($rows[$cursor->toDateString()] ?? 0),
                ]);
                $cursor->addDay();
            }
        }

        // ==== Breakdown status dalam periode ====
        $statusRows = Tiket::selectRaw('status, COUNT(*) as total')
            ->whereBetween('created_at', [$mulai, $selesai])
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusBreakdown = [
            'waiting' => (int) ($statusRows['waiting'] ?? 0),
            'in_progress' => (int) ($statusRows['in_progress'] ?? 0),
            'done' => (int) ($statusRows['done'] ?? 0),
        ];

        // ==== Navigasi geser periode (sebelumnya / berikutnya) ====
        $prevDate = match ($periode) {
            'harian' => $tanggalAcuan->copy()->subDay()->toDateString(),
            'bulanan' => $tanggalAcuan->copy()->subMonthNoOverflow()->toDateString(),
            default => $tanggalAcuan->copy()->subWeek()->toDateString(),
        };
        $nextDate = match ($periode) {
            'harian' => $tanggalAcuan->copy()->addDay()->toDateString(),
            'bulanan' => $tanggalAcuan->copy()->addMonthNoOverflow()->toDateString(),
            default => $tanggalAcuan->copy()->addWeek()->toDateString(),
        };
        $isPeriodeSaatIni = $selesai->greaterThanOrEqualTo(now());

        return view('statistik.index', [
            'periode' => $periode,
            'tanggalAcuan' => $tanggalAcuan->toDateString(),
            'labelPeriode' => $labelPeriode,
            'rank' => $rank,
            'totalTiket' => $totalTiket,
            'divisiTertinggi' => $divisiTertinggi,
            'rataRata' => $rataRata,
            'tren' => $tren,
            'statusBreakdown' => $statusBreakdown,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'isPeriodeSaatIni' => $isPeriodeSaatIni,
        ]);
    }
}
