@extends('layouts.app')

@section('title', 'Data User')
@section('page-title', 'Data User')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card stat-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Data User</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Data
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jenis Kelamin</th>
                    <th>No. HP</th>
                    <th>Devisi</th>
                    <th>Role</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $user->no_hp ?? '-' }}</td>
                        <td>{{ $user->divisi?->nama_divisi ?? '-' }}</td>
                        <td><span class="badge {{ $user->role === 'super_admin' ? 'bg-primary' : ($user->role === 'admin_divisi' ? 'bg-info text-dark' : 'bg-secondary') }}">{{ $user->role === 'super_admin' ? 'Super Admin' : ($user->role === 'admin_divisi' ? 'Admin Divisi' : 'User') }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('user.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="Laki-laki" @selected($user->jenis_kelamin == 'Laki-laki')>Laki-laki</option>
                                                <option value="Perempuan" @selected($user->jenis_kelamin == 'Perempuan')>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. HP</label>
                                            <input type="text" name="no_hp" value="{{ $user->no_hp }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Devisi</label>
                                            <select name="divisi_id" class="form-select">
                                                <option value="">Pilih divisi...</option>
                                                @foreach ($divisis as $divisi)
                                                    <option value="{{ $divisi->id }}" @selected($user->divisi_id == $divisi->id)>{{ $divisi->nama_divisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan / Unit Kerja</label>
                                            <input type="text" name="unit" value="{{ $user->unit }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <option value="user" @selected($user->role == 'user')>User</option>
                                                <option value="admin_divisi" @selected($user->role == 'admin_divisi')>Admin Divisi</option>
                                                <option value="super_admin" @selected($user->role == 'super_admin')>Super Admin (IT Pusat)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru (kosongkan jika tidak diganti)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $users->links() }}
    </div>
</div>

<div class="modal fade" id="tambahUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Devisi</label>
                        <select name="divisi_id" class="form-select">
                            <option value="">Pilih divisi...</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan / Unit Kerja</label>
                        <input type="text" name="unit" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user">User</option>
                            <option value="admin_divisi">Admin Divisi</option>
                            <option value="super_admin">Super Admin (IT Pusat)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
