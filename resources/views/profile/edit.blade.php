@extends('layouts.app')

@section('title', 'Profile Saya')
@section('page-title', 'Profile Saya')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-semibold"><i class="fa-solid fa-id-card me-2 text-primary"></i>Data Diri</div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                        <div class="form-text">Username tidak dapat diubah sendiri, hubungi admin.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (opsional)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP (opsional)</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan / Unit Kerja (opsional)</label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $user->unit) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <input type="text" class="form-control" value="{{ $user->divisi?->nama_divisi ?? 'Administrator' }}" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-semibold"><i class="fa-solid fa-lock me-2 text-primary"></i>Ganti Password</div>
            <div class="card-body">
                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.updatePassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-key me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection