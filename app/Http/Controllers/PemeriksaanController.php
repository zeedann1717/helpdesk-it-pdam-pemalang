<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Models\Pemeriksaan;
use App\Models\PemeriksaanItem;
use App\Models\Perangkat;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PemeriksaanController extends Controller
{
    private const HARI_INDONESIA = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => "Jum'at",
        'Saturday' => 'Sabtu',
    ];

    public function index(Request $request): View
    {
        $query = Pemeriksaan::with(['perangkat', 'items'])->latest('tanggal_pemeriksaan');

        if ($request->filled('perangkat_id')) {
            $query->where('perangkat_id', $request->integer('perangkat_id'));
        }

        $pemeriksaans = $query->paginate(15)->withQueryString();
        $perangkats = Perangkat::orderBy('nama_perangkat')->get();

        return view('pemeriksaan.index', compact('pemeriksaans', 'perangkats'));
    }

    public function create(): View
    {
        $perangkats = Perangkat::where('aktif', true)->orderBy('nama_perangkat')->get();
        $checklistItems = ChecklistItem::where('aktif', true)
            ->orderBy('kategori_kode')
            ->orderBy('urutan')
            ->get()
            ->groupBy('kategori_kode');

        return view('pemeriksaan.create', compact('perangkats', 'checklistItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'perangkat_id' => ['required', 'exists:perangkat,id'],
            'tanggal_pemeriksaan' => ['required', 'date'],
            'jadwal' => ['required', 'in:Harian,Mingguan,Bulanan'],
            'nama_pemeriksa' => ['required', 'string', 'max:255'],
            'catatan_umum' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kondisi' => ['required', 'in:baik,tidak'],
            'items.*.hasil' => ['required', 'in:layak,tidak_layak'],
            'items.*.catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $hari = self::HARI_INDONESIA[Carbon::parse($data['tanggal_pemeriksaan'])->format('l')];

        $pemeriksaan = DB::transaction(function () use ($data, $hari, $request) {
            $pemeriksaan = Pemeriksaan::create([
                'perangkat_id' => $data['perangkat_id'],
                'tanggal_pemeriksaan' => $data['tanggal_pemeriksaan'],
                'hari' => $hari,
                'jadwal' => $data['jadwal'],
                'nama_pemeriksa' => $data['nama_pemeriksa'],
                'diinput_oleh' => $request->user()->id,
                'catatan_umum' => $data['catatan_umum'] ?? null,
            ]);

            foreach ($data['items'] as $checklistItemId => $itemData) {
                PemeriksaanItem::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'checklist_item_id' => $checklistItemId,
                    'kondisi' => $itemData['kondisi'],
                    'hasil' => $itemData['hasil'],
                    'catatan' => $itemData['catatan'] ?? null,
                ]);
            }

            return $pemeriksaan;
        });

        return redirect()
            ->route('pemeriksaan.show', $pemeriksaan)
            ->with('success', 'Hasil pemeriksaan berhasil disimpan.');
    }

    public function show(Pemeriksaan $pemeriksaan): View
    {
        $pemeriksaan->load(['perangkat.lokasi', 'diinputOlehUser', 'items.checklistItem']);

        $itemsByKategori = $pemeriksaan->items
            ->sortBy(fn ($item) => $item->checklistItem->urutan)
            ->groupBy(fn ($item) => $item->checklistItem->kategori_kode);

        return view('pemeriksaan.show', compact('pemeriksaan', 'itemsByKategori'));
    }

    public function destroy(Pemeriksaan $pemeriksaan): RedirectResponse
    {
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan.index')->with('success', 'Riwayat pemeriksaan berhasil dihapus.');
    }
}
