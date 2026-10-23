<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Login | ROK Revamp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page-body">
    <div class="login-card-wrapper">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('img/rok-logo.png') }}" alt="ROK Logo" class="login-logo" onerror="this.src='{{ asset('img/dummy-division.svg') }}'">
                <h1 class="login-title">ROK MANAGEMENT PORTAL</h1>
                <p class="login-subtitle">Akses Khusus Administrator, Pelatih &amp; Manajer</p>
            </div>

            @if (session('error'))
                <div class="mgmt-alert mgmt-alert--error">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="mgmt-alert mgmt-alert--success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('management.authenticate') }}" class="login-form">
                @csrf

                <div class="form-field">
                    <label for="email" class="form-label">Email Administrator</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="admin@domain.com"
                        required
                        autofocus
                    >
                </div>

                <div class="form-field">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="login-remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="btn-rok btn-rok--gold btn-rok--full">
                    Masuk ke Sistem Manajemen &rarr;
                </button>
            </form>

            <div class="login-footer">
                <a href="{{ url('/') }}" class="back-link">&larr; Kembali ke Website Publik</a>
            </div>
        </div>
    </div>
</body>
</html>
