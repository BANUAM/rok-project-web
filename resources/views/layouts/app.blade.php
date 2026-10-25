<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ROK Revamp | Ruler Of Kings')</title>
    <meta name="description" content="@yield('description', 'Ruler Of Kings (ROK) — Competitive Esports Organization focused on competition, development, discipline and unity.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Teko:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('rok-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="rok-app">
    {{-- Pure CSS Hexagonal drifting backdrop --}}
    <div class="hex-ambient" aria-hidden="true">
        <div class="hex-ambient__cell hex-ambient__cell--1"></div>
        <div class="hex-ambient__cell hex-ambient__cell--2"></div>
        <div class="hex-ambient__cell hex-ambient__cell--3"></div>
        <div class="hex-ambient__cell hex-ambient__cell--4"></div>
        <div class="hex-ambient__cell hex-ambient__cell--5"></div>
    </div>

    @include('components.site-header')

    @if (session('success'))
        <aside class="flash-toast flash-toast--success" role="status" aria-live="polite">
            <span class="flash-toast__icon"><i data-lucide="check" style="width:16px;height:16px;"></i></span>
            <div class="flash-toast__content">
                <strong>Berhasil!</strong>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="flash-toast__close" onclick="this.parentElement.remove()" aria-label="Tutup pesan">&times;</button>
        </aside>
    @endif

    @if (session('error'))
        <aside class="flash-toast flash-toast--error" role="alert">
            <span class="flash-toast__icon"><i data-lucide="alert-circle" style="width:16px;height:16px;"></i></span>
            <div class="flash-toast__content">
                <strong>Perhatian!</strong>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" class="flash-toast__close" onclick="this.parentElement.remove()" aria-label="Tutup pesan">&times;</button>
        </aside>
    @endif

    <main class="rok-main">
        @yield('content')
    </main>

    @include('components.site-footer')

    @stack('scripts')
</body>
</html>
