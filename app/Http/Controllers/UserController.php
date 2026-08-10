<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('divisi')->orderBy('name')->paginate(10);
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('user.index', compact('users', 'divisis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'divisi_id' => ['nullable', 'exists:divisis,id', 'required_if:role,admin_divisi'],
            'unit' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:super_admin,admin_divisi,user'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return back()->with('success', 'Data user berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id, 'alpha_dash'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'divisi_id' => ['nullable', 'exists:divisis,id', 'required_if:role,admin_divisi'],
            'unit' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:super_admin,admin_divisi,user'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->tikets()->exists()) {
            return back()->with('error', 'User tidak bisa dihapus karena masih memiliki riwayat tiket.');
        }

        $user->delete();

        return back()->with('success', 'Data user berhasil dihapus.');
    }
}
