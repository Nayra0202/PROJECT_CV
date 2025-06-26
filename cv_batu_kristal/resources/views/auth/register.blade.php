<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CV Batu Kristal</title>
    <link rel="shortcut icon" href="{{ asset('images/logos/logocv-removebg-preview.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
        }
        .register-card {
            max-width: 400px;
            margin: 60px auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            background: #fff;
        }
        .register-logo {
            width: 80px;
            margin-bottom: 10px;
        }
        .brand-title {
            font-weight: bold;
            color: #2563eb;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }
        .btn-primary:hover {
            background-color: #1e40af !important;
            border-color: #1e40af !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card card p-4 mt-5">
            <div class="text-center">
                <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo CV" class="register-logo">
                <div class="brand-title">CV BATU KRISTAL</div>
                <div class="mb-3 text-muted">Silakan daftar akun baru</div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label text-primary">Nama</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-primary">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label text-primary">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Klien" {{ old('role') == 'Klien' ? 'selected' : '' }}>Klien</option>
                        <option value="Direktur" {{ old('role') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                        <option value="Sales Manager" {{ old('role') == 'Sales Manager' ? 'selected' : '' }}>Sales Manager</option>
                        <option value="Sales Marketing" {{ old('role') == 'Sales Marketing' ? 'selected' : '' }}>Sales Marketing</option>
                        <option value="Sekretaris" {{ old('role') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bagian Gudang" {{ old('role') == 'Bagian Gudang' ? 'selected' : '' }}>Bagian Gudang</option>
                        <option value="Bagian Pengiriman" {{ old('role') == 'Bagian Pengiriman' ? 'selected' : '' }}>Bagian Pengiriman</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-primary">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-primary">Konfirmasi Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="text-decoration-underline text-primary" href="{{ route('login') }}">
                        Sudah punya akun?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
        </div>
    </div>
</body>
</html>
