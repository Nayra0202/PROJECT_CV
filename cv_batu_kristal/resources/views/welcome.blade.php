<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            background: #0d6efd; /* Biru gelap seperti login Laravel */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        .login-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px #0002;
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            text-align: center;
            min-width: 350px;
            max-width: 95vw;
        }
        .logo-cv {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 1.2rem;
        }
        h1 {
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: #22223b;
        }
        p {
            margin-bottom: 2rem;
            color: #4b5563;
        }
        .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.75rem 0;
            border-radius: 6px;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-login {
            background: #6366f1;
            color: #fff;
        }
        .btn-login:hover {
            background: #4f46e5;
        }
        .btn-register {
            background: #f59e42;
            color: #fff;
        }
        .btn-register:hover {
            background: #d97706;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 1.2rem 0.5rem;
                min-width: unset;
            }
            .logo-cv {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo CV" class="logo-cv">
        <h1>Selamat Datang</h1>
        <p>Silakan login atau register untuk melanjutkan ke aplikasi CV Batu Kristal.</p>
        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        @endif
    </div>
</body>
</html>
