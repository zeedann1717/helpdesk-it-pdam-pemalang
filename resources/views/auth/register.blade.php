@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="card guest-card p-4 p-md-5 bg-white">
    <div class="text-center mb-4">
        <div class="brand-logo"><img src="{{ asset('images/logopdam.jpg') }}" alt="Logo PDAM Tirta Mulia Pemalang" style="width:56px;height:56px;object-fit:contain;"></div>
        <h6 class="text-primary text-uppercase fw-bold mb-1">PDAM Tirta Mulia Pemalang</h6>
        <h4 class="fw-bold">Buat Akun Baru</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Email (opsional)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">Pilih...</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin') == 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin') == 'Perempuan')>Perempuan</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">No. HP (opsional)</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control">
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Divisi / Bagian</label>
                <select name="divisi_id" class="form-select">
                    <option value="">Pilih divisi...</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}" @selected(old('divisi_id') == $divisi->id)>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Jabatan / Unit Kerja</label>
                <input type="text" name="unit" value="{{ old('unit') }}" class="form-control" placeholder="Contoh: Staf Keuangan">
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mt-4">Register</button>
    </form>

    <p class="text-center text-muted mt-4 mb-0">
        Sudah memiliki akun? <a href="{{ route('login') }}">Login di sini</a>
    </p>
</div>
@endsection
