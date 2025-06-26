@extends('layouts.main')

@section('content')
<div class="container mt-4">

    {{-- Pesan sukses setelah update profil --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="ti ti-user"></i> Profil Saya
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('images/profile/user.png') }}" alt="Foto Profil" width="80" height="80" class="rounded-circle border border-2 border-primary me-3">
                        <div>
                            <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                            <span class="badge bg-info text-dark">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="150">Nama</th>
                            <td>: {{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>: {{ auth()->user()->role }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer bg-light text-end">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="ti ti-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection