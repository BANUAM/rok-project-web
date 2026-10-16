<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROK Bracket Studio | Organizer Portal')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('rok_theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme);
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="mgmt-body">
    <div class="mgmt-layout">
        {{-- Sidebar Khusus Bracket Studio --}}
        <aside class="mgmt-sidebar">
            <div class="mgmt-sidebar__brand">
                <a href="{{ url('/') }}" class="brand-link" title="Kembali ke Website Publik">
                    <img src="{{ asset('img/rok-logo.png') }}" alt="ROK Logo" class="brand-logo" onerror="this.src='{{ asset('img/dummy-division.svg') }}'">
                    <div>
                        <strong class="brand-name">BRACKET STUDIO</strong>
                        <span class="brand-sub">ORGANIZER PORTAL ({{ strtoupper(Auth::user()?->membershipTierSlug() ?? 'PRO') }})</span>
                    </div>
                </a>
            </div>

            <nav class="mgmt-nav">
                <div class="nav-section-label">WORKSPACE TURNAMEN</div>
                <a href="{{ route('bracket.studio.index') }}" class="mgmt-nav-link {{ request()->routeIs('bracket.studio.index') ? 'is-active' : '' }}">
                    <span class="nav-icon">&#x1F3C6;</span> Daftar Turnamen Saya
                </a>
                <a href="{{ route('bracket.studio.editor') }}" class="mgmt-nav-link {{ request()->routeIs('bracket.studio.editor*') ? 'is-active' : '' }}">
                    <span class="nav-icon">&#x2694;</span> Live Bracket Editor
                </a>

                <div class="nav-section-label">FITUR MEMBERSHIP</div>
                <a href="{{ route('learning-development') }}" class="mgmt-nav-link">
                    <span class="nav-icon">&#x1F4DA;</span> Akademi &amp; Playbook PDF
                </a>
                <a href="{{ route('bracket') }}" class="mgmt-nav-link">
                    <span class="nav-icon">&#x1F310;</span> Halaman Bracket Publik
                </a>
                @if (Auth::user()?->isAdmin())
                    <div class="nav-section-label">ADMIN PANEL</div>
                    <a href="{{ route('management.dashboard') }}" class="mgmt-nav-link">
                        <span class="nav-icon">&#x2699;</span> Management Portal
                    </a>
                @endif
            </nav>

            <div class="mgmt-sidebar__footer">
                <div class="user-badge-mini">
                    <div class="user-avatar-mini">&#x1F451;</div>
                    <div class="user-details">
                        <strong>{{ Auth::user()->name ?? 'Organizer' }}</strong>
                        <span style="color: #38bdf8;">PAKET: {{ strtoupper(Auth::user()?->membershipTierSlug() ?? 'PRO') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar">
                        &#x23FB; Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="mgmt-main">
            <header class="mgmt-topbar">
                <div class="topbar-title">
                    <h2>@yield('header_title', 'ROK Bracket Studio')</h2>
                </div>
                <div class="topbar-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="button" class="theme-toggle-btn" data-theme-toggle title="Ubah Tema Gelap / Terang">
                        <span class="theme-toggle-btn__icon" data-theme-icon>&#x2600;&#xFE0F;</span>
                        <span class="theme-toggle-btn__label" data-theme-label>Mode Terang</span>
                    </button>
                    <a href="{{ url('/') }}" class="btn-public-site">
                        <span>&#x1F310;</span> Web Publik &rarr;
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="mgmt-alert mgmt-alert--success" role="alert">
                    <span>&#10003;</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mgmt-alert mgmt-alert--error" role="alert">
                    <span>&excl;</span>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="mgmt-content">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
