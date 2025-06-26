@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">Profil Saya</div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
        </div>
    </div>
</div>
@endsection