<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CV Batu Kristal</title>
    <link rel="shortcut icon" href="{{ asset('images/logos/logocv-removebg-preview.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
        }
        .login-card {
            max-width: 400px;
            margin: 60px auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            background: #fff;
        }
        .login-logo {
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
        <div class="login-card card p-4 mt-5">
            <div class="text-center">
                <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo CV" class="login-logo">
                <div class="brand-title">CV BATU KRISTAL</div>
                <div class="mb-3 text-muted">Silakan login untuk melanjutkan</div>
            </div>
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label text-primary">Email</label>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-primary">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label text-secondary">Remember me</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-underline text-primary" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>
