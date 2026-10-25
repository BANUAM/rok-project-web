<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROK Management Portal')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('rok-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="mgmt-body">
    <div class="mgmt-layout">
        {{-- Sidebar --}}
        <aside class="mgmt-sidebar">
            <div class="mgmt-sidebar__brand">
                <a href="{{ url('/') }}" target="_blank" class="brand-link" title="Buka Website Publik">
                    <img src="{{ asset('img/rok-logo.png') }}" alt="ROK Logo" class="brand-logo" onerror="this.src='{{ asset('img/dummy-division.svg') }}'">
                    <div>
                        <strong class="brand-name">ROK MANAGEMENT</strong>
                        <span class="brand-sub">INTERNAL PORTAL</span>
                    </div>
                </a>
            </div>

            <nav class="mgmt-nav">
                <div class="nav-section-label">OVERVIEW</div>
                <a href="{{ route('management.dashboard') }}" class="mgmt-nav-link {{ request()->routeIs('management.dashboard') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="layout-dashboard"></i></span> Dashboard
                </a>

                <div class="nav-section-label">KONTEN WEBSITE</div>
                <a href="{{ route('management.hero-slides') }}" class="mgmt-nav-link {{ request()->routeIs('management.hero-slides*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="film"></i></span> Hero Banners
                </a>
                <a href="{{ route('management.page-headers') }}" class="mgmt-nav-link {{ request()->routeIs('management.page-headers*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="image"></i></span> Header Halaman
                </a>
                <a href="{{ route('management.founders') }}" class="mgmt-nav-link {{ request()->routeIs('management.founders*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="crown"></i></span> Founders
                </a>
                <a href="{{ route('management.divisions') }}" class="mgmt-nav-link {{ request()->routeIs('management.divisions*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="shield"></i></span> Divisi Tim
                </a>
                <a href="{{ route('management.players') }}" class="mgmt-nav-link {{ request()->routeIs('management.players*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="gamepad-2"></i></span> Roster &amp; Staff
                </a>
                <a href="{{ route('management.achievements') }}" class="mgmt-nav-link {{ request()->routeIs('management.achievements*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="trophy"></i></span> Prestasi Turnamen
                </a>
                <a href="{{ route('management.partners') }}" class="mgmt-nav-link {{ request()->routeIs('management.partners*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="handshake"></i></span> Partner &amp; Sponsor
                </a>

                <div class="nav-section-label">REKRUTMEN &amp; AKADEMI</div>
                <a href="{{ route('management.candidates') }}" class="mgmt-nav-link {{ request()->routeIs('management.candidates*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="clipboard-list"></i></span> Kandidat Trial
                    @php $pendingCount = \App\Models\TrialCandidate::whereIn('status', ['lsm_completed', 'under_review'])->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="badge-count">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('management.lsm') }}" class="mgmt-nav-link {{ request()->routeIs('management.lsm*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="brain"></i></span> Manajemen LSM
                </a>
                <a href="{{ route('management.learning') }}" class="mgmt-nav-link {{ request()->routeIs('management.learning*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="book-open"></i></span> Modul Pembelajaran
                </a>

                <div class="nav-section-label">KOMPETISI</div>
                <a href="{{ route('management.tournaments') }}" class="mgmt-nav-link {{ request()->routeIs('management.tournaments*') ? 'is-active' : '' }}">
                    <span class="nav-icon"><i data-lucide="swords"></i></span> Turnamen &amp; Bracket
                </a>
                <a href="{{ route('bracket.studio.index') }}" class="mgmt-nav-link">
                    <span class="nav-icon"><i data-lucide="target"></i></span> Bracket Studio
                </a>
            </nav>

            <div class="mgmt-sidebar__footer">
                <div class="user-badge-mini">
                    <div class="user-avatar-mini"><i data-lucide="user"></i></div>
                    <div class="user-details">
                        <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
                        <span>{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('management.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar" style="display:inline-flex;align-items:center;gap:6px;">
                        <i data-lucide="log-out" style="width:14px;height:14px;"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="mgmt-main">
            <header class="mgmt-topbar">
                <div class="topbar-title">
                    <h2>@yield('header_title', 'Dashboard')</h2>
                </div>
                <div class="topbar-actions" style="display: flex; align-items: center; gap: 10px;">
                    <button type="button" class="theme-toggle-btn" data-theme-toggle title="Ubah Tema Gelap / Terang" aria-label="Toggle Tema">
                        <span class="theme-toggle-icon" data-theme-icon><i data-lucide="sun" style="width:14px;height:14px;"></i></span>
                        <span class="theme-toggle-label" data-theme-label>Terang</span>
                    </button>
                    <a href="{{ url('/') }}" target="_blank" class="btn-public-site" style="display:inline-flex;align-items:center;gap:5px;">
                        <span><i data-lucide="globe" style="width:14px;height:14px;"></i></span> Buka Web Publik
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="mgmt-alert mgmt-alert--success" role="alert">
                    <span><i data-lucide="check" style="width:16px;height:16px;"></i></span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mgmt-alert mgmt-alert--error" role="alert">
                    <span><i data-lucide="alert-circle" style="width:16px;height:16px;"></i></span>
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
