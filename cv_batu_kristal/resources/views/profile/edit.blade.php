@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4>Edit Profil Saya</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Update Profil --}}
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <input type="text" id="role" class="form-control" value="{{ auth()->user()->role }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <hr class="my-4">

    {{-- Update Password --}}
    <h5>Ubah Password</h5>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <input type="password" id="current_password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning">Ubah Password</button>
    </form>

    <hr class="my-4">

    {{-- Delete Account --}}
    <h5 class="text-danger">Hapus Akun</h5>
    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">Hapus Akun</button>
    </form>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection