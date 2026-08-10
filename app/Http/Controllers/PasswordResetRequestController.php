<?php

namespace App\Http\Controllers;

use App\Events\PasswordResetRequested;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PasswordResetRequestController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'exists:users,username'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('username', $data['username'])->firstOrFail();

        $prr = PasswordResetRequest::create([
            'user_id' => $user->id,
            'catatan' => $data['catatan'] ?? null,
        ]);

        broadcast(new PasswordResetRequested($prr))->toOthers();

        return back()->with('success', 'Permintaan reset password terkirim. Admin IT akan segera menghubungi Anda.');
    }

    public function index(): View
    {
        $requests = PasswordResetRequest::with(['user', 'handler'])
            ->orderByRaw("status = 'pending' desc")
            ->latest()
            ->paginate(15);

        return view('admin.password-requests', compact('requests'));
    }

    public function approve(Request $request, PasswordResetRequest $passwordResetRequest): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $passwordResetRequest->user->update(['password' => $data['password']]);

        $passwordResetRequest->update([
            'status' => 'selesai',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Password '.$passwordResetRequest->user->name.' berhasil direset.');
    }
}