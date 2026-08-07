<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Lokasi;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'lokasi' => Lokasi::count(),
                'divisi' => Divisi::count(),
                'user' => User::where('role', 'user')->count(),
                'tiket_waiting' => Tiket::status('waiting')->count(),
                'tiket_in_progress' => Tiket::status('in_progress')->count(),
                'tiket_done' => Tiket::status('done')->count(),
            ];

            $tiketTerbaru = Tiket::with(['user', 'divisi', 'lokasi'])
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact('stats', 'tiketTerbaru'));
        }

        $stats = [
            'tiket_saya' => Tiket::where('user_id', $user->id)->count(),
            'tiket_waiting' => Tiket::where('user_id', $user->id)->status('waiting')->count(),
            'tiket_in_progress' => Tiket::where('user_id', $user->id)->status('in_progress')->count(),
            'tiket_done' => Tiket::where('user_id', $user->id)->status('done')->count(),
        ];

        $tiketTerbaru = Tiket::with(['divisi', 'lokasi'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'tiketTerbaru'));
    }
}
