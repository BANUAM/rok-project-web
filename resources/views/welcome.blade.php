@extends('layouts.app')

@section('title', 'ROK | Ruler Of Kings — Official Esports Organization')
@section('description', 'Organisasi esports kompetitif yang berfokus pada prestasi, pengembangan atlet, trial pro-player, dan platform bagan turnamen live real-time.')

@section('content')
    {{-- =========================================================================
         LUXURY ARCHITECTURAL HERO & FLOATING WINDOW DECK
         Inspired by ultra-modern editorial layout:
         Floating window canvas, vertical dock, bento cards, real-time stats
         ========================================================================= --}}
    <div class="newhome-wrapper">
        <div class="newhome-window">

            {{-- 1. Scenic Atmospheric Background --}}
            <div class="newhome-bg" aria-hidden="true">
                @php
                    $primaryBg = $heroSlides->isNotEmpty() && $heroSlides->first()->image_path
                        ? \Illuminate\Support\Facades\Storage::url($heroSlides->first()->image_path)
                        : asset('storage/hero-slides/o310aKoeeiWqifvw0ZOLwPxnfprrDB1vXtYOYDe7.jpg');
                @endphp
                <img
                    class="newhome-bg__img"
                    src="{{ $primaryBg }}"
                    alt="ROK Championship Backdrop"
                    onerror="this.src='{{ asset('storage/page-headers/EV8EUOMLKaWZvBKrMSU2ln3kUdgXnVX1zjGWbm3q.jpg') }}'"
                >
                <div class="newhome-bg__gradient"></div>
            </div>

            {{-- 2. Integrated Top Navigation Bar --}}
            <header class="newhome-topbar">
                {{-- Brand Monogram & Title --}}
                <a href="{{ url('/') }}" class="newhome-brand" aria-label="Ruler Of Kings Home">
                    <div class="newhome-brand__mark">
                        <img
                            src="{{ asset('img/rok-logo.png') }}"
                            alt="ROK"
                            onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
                        >
                    </div>
                    <div class="newhome-brand__text">
                        <span class="newhome-brand__name">RULER OF KINGS</span>
                        <span class="newhome-brand__sub">Ruler of Kings Esports</span>
                    </div>
                </a>

                {{-- Center Floating Capsule Navigation --}}
                <nav class="newhome-pillnav" aria-label="Primary Navigation">
                    <a href="{{ url('/') }}" class="newhome-pillnav__item is-active">Home</a>
                    <a href="{{ url('/teams') }}" class="newhome-pillnav__item">Divisi &amp; Tim</a>
                    <a href="{{ url('/achievements') }}" class="newhome-pillnav__item">Prestasi</a>
                    <a href="{{ url('/bracket') }}" class="newhome-pillnav__item">Bagan Turnamen</a>
                    <a href="{{ url('/learning-development') }}" class="newhome-pillnav__item">Akademi</a>
                    <a href="{{ url('/trial') }}" class="newhome-pillnav__item">Trial Pro</a>
                    <a href="{{ url('/membership') }}" class="newhome-pillnav__item">Membership</a>
                </nav>

                {{-- Right Action Buttons --}}
                <div class="newhome-topbar__actions">
                    {{-- Theme Toggle Button --}}
                    <button
                        type="button"
                        class="newhome-icon-btn"
                        data-theme-toggle
                        title="Ubah Tema Gelap / Terang"
                        aria-label="Toggle Theme Gelap atau Terang"
                    >
                        <span class="theme-toggle-icon" data-theme-icon>
                            <i data-lucide="sun" style="width:17px;height:17px;"></i>
                        </span>
                    </button>

                    {{-- CTA Capsule with Round Arrow Icon --}}
                    <a href="{{ url('/bracket/free') }}" class="newhome-cta-pill" title="Buat Bagan Turnamen Gratis">
                        <span>Mulai Turnamen</span>
                        <span class="newhome-cta-pill__circle" aria-hidden="true">
                            <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
                        </span>
                    </a>

                    {{-- Mobile Hamburger Trigger --}}
                    <button
                        type="button"
                        class="newhome-hamburger"
                        id="newhomeMenuBtn"
                        aria-label="Buka Menu Navigasi"
                        aria-expanded="false"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </header>

            {{-- Mobile Drawer Navigation --}}
            <nav class="newhome-mobile-drawer" id="newhomeMobileDrawer" aria-label="Mobile Navigation">
                <div class="newhome-mobile-drawer__inner">
                    <a href="{{ url('/') }}" class="is-active">Home</a>
                    <a href="{{ url('/teams') }}">Divisi &amp; Tim Roster</a>
                    <a href="{{ url('/achievements') }}">Prestasi &amp; Kejayaan</a>
                    <a href="{{ url('/bracket') }}">Bagan Turnamen Simulator</a>
                    <a href="{{ url('/learning-development') }}">Akademi &amp; Learning</a>
                    <a href="{{ url('/trial') }}">Pendaftaran Trial Pro</a>
                    <a href="{{ url('/membership') }}">Tier Membership</a>
                    @auth
                        @if(Auth::user()->hasBracketAccess())
                            <a href="{{ route('bracket.studio.index') }}">Bracket Studio</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:inherit;font:inherit;padding:10px 14px;width:100%;text-align:left;cursor:pointer;">Keluar ({{ Auth::user()->username ?? 'Akun' }})</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Masuk Akun</a>
                    @endauth
                </div>
            </nav>

            {{-- 3. Hero Interactive Stage Scene --}}
            <div class="newhome-hero-scene">

                {{-- Left Floating Dock Capsule --}}
                <aside class="newhome-dock" aria-label="Quick Navigation Dock">
                    <a href="{{ url('/') }}" class="newhome-dock__item is-active" title="Beranda Utama">
                        <i data-lucide="home" style="width:17px;height:17px;"></i>
                    </a>
                    <a href="{{ url('/teams') }}" class="newhome-dock__item" title="Divisi &amp; Roster Atlet">
                        <i data-lucide="layout-grid" style="width:17px;height:17px;"></i>
                    </a>
                    <a href="{{ url('/achievements') }}" class="newhome-dock__item" title="Prestasi &amp; Trofi ROK">
                        <i data-lucide="trophy" style="width:17px;height:17px;"></i>
                    </a>
                    <a href="{{ url('/bracket') }}" class="newhome-dock__item" title="Bagan Turnamen &amp; Live Sync">
                        <i data-lucide="layers" style="width:17px;height:17px;"></i>
                    </a>
                    <a href="{{ url('/learning-development') }}" class="newhome-dock__item" title="Learning &amp; Development">
                        <i data-lucide="book-open" style="width:17px;height:17px;"></i>
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20ROK" target="_blank" rel="noopener noreferrer" class="newhome-dock__item" title="Hubungi Kami (WhatsApp Komunitas)">
                        <i data-lucide="message-square" style="width:17px;height:17px;"></i>
                    </a>

                    <div class="newhome-dock__divider"></div>

                    {{-- Pro Roster Avatar --}}
                    <a href="{{ url('/teams') }}" class="newhome-dock__avatar" title="Lihat Profil Atlet &amp; Founder">
                        <img
                            src="{{ asset('storage/founders/32HkbIAjT9LVYk5BHOCwsoWJIqdAkLRjmIml3oAL.webp') }}"
                            alt="ROK Pro Player Avatar"
                            onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
                        >
                    </a>

                    {{-- Dock Showreel Trigger --}}
                    <button type="button" class="newhome-dock__showreel" id="btnDockShowreel" title="Putar Showreel Video ROK">
                        <span class="newhome-dock__playicon">
                            <i data-lucide="play" style="width:12px;height:12px;fill:currentColor;margin-left:1px;"></i>
                        </span>
                        <span class="newhome-dock__showreel-text">Watch Showreel</span>
                    </button>
                </aside>

                {{-- Center Hero Content --}}
                <div class="newhome-hero-center">
                    <div class="newhome-hero-content">
                        <p class="newhome-hero-eyebrow">
                            ESPORTS ARCHITECTURE — TOURNAMENTS — ACADEMY • BUILD. COMPETE. CONQUER.
                        </p>

                        <h1 class="newhome-hero-headline">
                            Champions That Inspire
                        </h1>

                        <p class="newhome-hero-desc">
                            Kami merancang ekosistem esports modern yang memadukan talenta pro player bermental juara, sistem seleksi terukur, dan platform bagan turnamen live real-time untuk Community kompetitif Indonesia.
                        </p>

                        <div class="newhome-hero-ctas">
                            <a href="{{ url('/bracket/free') }}" class="newhome-btn-primary">
                                <span>Explore Our Work</span>
                                <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                            </a>

                            <button type="button" class="newhome-btn-showreel" id="btnHeroShowreel">
                                <span class="newhome-play-circle">
                                    <i data-lucide="play" style="width:13px;height:13px;fill:currentColor;margin-left:1px;"></i>
                                </span>
                                <span>Watch Showreel</span>
                            </button>
                        </div>
                    </div>

                    {{-- Top-Right Stacked Editorial Keywords --}}
                    <aside class="newhome-hero-keywords" aria-label="Nilai Inti ROK">
                        <span>MODERN</span>
                        <span>SUSTAINABLE</span>
                        <span>COMPETITIVE</span>
                        <span>TIMELESS</span>
                    </aside>
                </div>

                {{-- 4. Bottom Bento Deck (3 Docked Elements) --}}
                <div class="newhome-bento-dock">

                    {{-- Bento Card 1: Featured Project / Tournament Card --}}
                    <div class="bento-card--featured" id="bentoFeaturedCard">
                        <div class="bento-card__top">
                            <span class="bento-badge">FEATURED PROJECT</span>
                            <span class="bento-counter" id="featuredCounter">
                                01 / {{ sprintf('%02d', max(count($featuredTournaments ?? []), 1)) }}
                            </span>
                        </div>

                        <div class="bento-featured-content" id="bentoSliderContent">
                            @forelse ($featuredTournaments as $idx => $tournament)
                                <div class="bento-slide {{ $idx === 0 ? 'is-active' : '' }}" data-bento-index="{{ $idx }}">
                                    <div class="bento-featured-main">
                                        <div class="bento-featured-thumb">
                                            @php
                                                $thumbSrc = $tournament->banner_path
                                                    ? \Illuminate\Support\Facades\Storage::url($tournament->banner_path)
                                                    : asset('storage/page-headers/bracket-banner.svg');
                                            @endphp
                                            <img
                                                src="{{ $thumbSrc }}"
                                                alt="{{ $tournament->name }}"
                                                onerror="this.src='{{ asset('storage/page-headers/bracket-banner.svg') }}'"
                                            >
                                        </div>
                                        <div class="bento-featured-info">
                                            <h3 class="bento-featured-title" title="{{ $tournament->name }}">
                                                {{ $tournament->name }}
                                            </h3>
                                            <p class="bento-featured-desc">
                                                Turnamen {{ $tournament->format ?? 'Single Elimination' }} dengan sistem live scoring, bracket sync real-time, dan slot tim terverifikasi.
                                            </p>
                                            <a
                                                href="{{ $tournament->slug ? url('/bracket/live/' . $tournament->slug) : url('/bracket') }}"
                                                class="bento-featured-link"
                                            >
                                                <span>View Project</span>
                                                <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="bento-featured-bottom">
                                        <div class="bento-team-avatars">
                                            @php
                                                $displayTeams = $tournament->teams->take(3);
                                                $extraCount = max(0, $tournament->teams->count() - 3);
                                            @endphp
                                            @foreach ($displayTeams as $t)
                                                <span class="bento-avatar-dot" title="{{ $t->name }}">
                                                    <img
                                                        src="{{ $t->logo_path ? \Illuminate\Support\Facades\Storage::url($t->logo_path) : asset('img/dummy-division.svg') }}"
                                                        alt="{{ $t->name }}"
                                                        onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
                                                    >
                                                </span>
                                            @endforeach
                                            @if ($extraCount > 0)
                                                <span class="bento-avatar-count">+{{ $extraCount }}</span>
                                            @elseif ($tournament->teams->isEmpty())
                                                <span class="bento-avatar-dot" title="Open Registration">
                                                    <img src="{{ asset('img/dummy-division.svg') }}" alt="Open">
                                                </span>
                                            @endif
                                        </div>

                                        <div class="bento-slider-nav">
                                            <div class="bento-dots" id="bentoDots">
                                                @foreach ($featuredTournaments as $dIdx => $tDot)
                                                    <button
                                                        type="button"
                                                        class="bento-dot {{ $dIdx === 0 ? 'is-active' : '' }}"
                                                        data-slide-to="{{ $dIdx }}"
                                                        aria-label="Slide {{ $dIdx + 1 }}"
                                                    ></button>
                                                @endforeach
                                            </div>

                                            <div class="bento-arrows">
                                                <button type="button" class="bento-arrow-btn" id="bentoPrev" aria-label="Turnamen Sebelumnya">
                                                    <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                                                </button>
                                                <button type="button" class="bento-arrow-btn" id="bentoNext" aria-label="Turnamen Selanjutnya">
                                                    <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bento-slide is-active" data-bento-index="0">
                                    <div class="bento-featured-main">
                                        <div class="bento-featured-thumb">
                                            <img src="{{ asset('storage/page-headers/bracket-banner.svg') }}" alt="ROK Championship">
                                        </div>
                                        <div class="bento-featured-info">
                                            <h3 class="bento-featured-title">ROK Invitational 2026</h3>
                                            <p class="bento-featured-desc">
                                                Turnamen komunitas esports tingkat nasional dengan live bracket engine real-time.
                                            </p>
                                            <a href="{{ url('/bracket') }}" class="bento-featured-link">
                                                <span>View Project</span>
                                                <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="bento-featured-bottom">
                                        <div class="bento-team-avatars">
                                            <span class="bento-avatar-dot"><img src="{{ asset('img/dummy-division.svg') }}" alt="ROK"></span>
                                            <span class="bento-avatar-count">+8</span>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Bento Card 2: Frosted Stats Strip --}}
                    <div class="bento-card--stats" aria-label="ROK Statistics">
                        <div class="bento-stat-item">
                            <span class="bento-stat-icon" aria-hidden="true">
                                <i data-lucide="award" style="width:16px;height:16px;"></i>
                            </span>
                            <span class="bento-stat-num">250+</span>
                            <span class="bento-stat-label">Turnamen Selesai</span>
                        </div>

                        <div class="bento-stat-divider" aria-hidden="true"></div>

                        <div class="bento-stat-item">
                            <span class="bento-stat-icon" aria-hidden="true">
                                <i data-lucide="users" style="width:16px;height:16px;"></i>
                            </span>
                            <span class="bento-stat-num">120+</span>
                            <span class="bento-stat-label">Komunitas &amp; Tim</span>
                        </div>

                        <div class="bento-stat-divider" aria-hidden="true"></div>

                        <div class="bento-stat-item">
                            <span class="bento-stat-icon" aria-hidden="true">
                                <i data-lucide="globe" style="width:16px;height:16px;"></i>
                            </span>
                            <span class="bento-stat-num">15+</span>
                            <span class="bento-stat-label">Gelar Juara</span>
                        </div>
                    </div>

                    {{-- Bento Card 3: Dark Elevated Action Card --}}
                    <a href="{{ url('/trial') }}" class="bento-card--action" title="Daftar Trial Atlet ROK">
                        <div class="bento-action-top">
                            <div class="bento-action-badge">
                                <img
                                    src="{{ asset('img/rok-logo.png') }}"
                                    alt="ROK"
                                    onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
                                >
                            </div>
                            <span class="bento-action-arrow" aria-hidden="true">
                                <i data-lucide="arrow-up-right" style="width:16px;height:16px;"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="bento-action-title">Bring Your Vision To Life</h3>
                            <p class="bento-action-desc">
                                Bergabunglah dengan seleksi pro player Ruler of Kings atau kelola turnamenmu sekarang.
                            </p>
                        </div>
                    </a>

                </div>

            </div>

        </div>
    </div>

    {{-- =========================================================================
         SHOWREEL MODAL (VIDEO DIALOG)
         ========================================================================= --}}
    <div class="newhome-modal" id="showreelModal" hidden role="dialog" aria-modal="true" aria-labelledby="showreelTitle">
        <div class="newhome-modal__backdrop" id="showreelBackdrop"></div>
        <div class="newhome-modal__dialog">
            <button type="button" class="newhome-modal__close" id="showreelClose" aria-label="Tutup Dialog Showreel">&times;</button>
            <div style="margin-bottom:16px;">
                <span style="font-size:11px;font-weight:800;letter-spacing:2px;color:var(--rok-gold);text-transform:uppercase;">ROK OFFICIAL SHOWREEL</span>
                <h2 id="showreelTitle" style="font-size:22px;font-weight:800;margin:4px 0 0 0;color:#ffffff;">Dominance &amp; Glory in Every Match</h2>
            </div>
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:16px;background:#05070a;border:1px solid rgba(255,255,255,0.1);">
                <iframe
                    id="showreelIframe"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
                    src="https://www.youtube.com/embed/dQw4w9WgXcQ?enablejsapi=1"
                    title="ROK Showreel Video"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;font-size:12px;color:#94a3b8;">
                <span>Ruler of Kings Championship Highlight</span>
                <a href="{{ url('/teams') }}" style="color:var(--rok-gold);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    Lihat Roster Tim <i data-lucide="arrow-right" style="width:12px;height:12px;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         ROK CORE HOMEPAGE SECTIONS
         Smooth continuation under the floating window deck
         ========================================================================= --}}
    <div class="newhome-content-body">
        {{-- 02. TEAMS PREVIEW SECTION --}}
        @include('components.home.teams-preview')

        {{-- 03. ACHIEVEMENTS SECTION --}}
        @include('components.home.achievements-preview')

        {{-- 04. LEARNING & DEVELOPMENT SECTION --}}
        @include('components.home.learning-preview')

        {{-- 05. BRACKET SECTION --}}
        @include('components.home.bracket-preview')

        {{-- 06. TRIAL CTA SECTION --}}
        @include('components.home.trial-cta')

        {{-- 07. PARTNERS & SPONSORS SECTION --}}
        @include('components.home.partners-preview')
    </div>

    {{-- Interactive Controls for Bento Slider, Showreel Modal, and Mobile Drawer --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }

            // --- 1. Mobile Menu Drawer Toggle ---
            var menuBtn = document.getElementById('newhomeMenuBtn');
            var drawer = document.getElementById('newhomeMobileDrawer');
            if (menuBtn && drawer) {
                menuBtn.addEventListener('click', function () {
                    var isOpen = drawer.classList.toggle('is-open');
                    menuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
                document.addEventListener('click', function (e) {
                    if (!drawer.contains(e.target) && !menuBtn.contains(e.target) && drawer.classList.contains('is-open')) {
                        drawer.classList.remove('is-open');
                        menuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // --- 2. Bento Carousel Slider ---
            var slides = document.querySelectorAll('.bento-slide');
            var dots = document.querySelectorAll('.bento-dot');
            var counter = document.getElementById('featuredCounter');
            var prevBtn = document.getElementById('bentoPrev');
            var nextBtn = document.getElementById('bentoNext');
            var totalSlides = slides.length;
            var currentIdx = 0;
            var autoInterval = null;

            function goToSlide(index) {
                if (totalSlides === 0) return;
                currentIdx = (index + totalSlides) % totalSlides;

                slides.forEach(function (slide, i) {
                    if (i === currentIdx) {
                        slide.classList.add('is-active');
                    } else {
                        slide.classList.remove('is-active');
                    }
                });

                dots.forEach(function (dot, i) {
                    if (i === currentIdx) {
                        dot.classList.add('is-active');
                    } else {
                        dot.classList.remove('is-active');
                    }
                });

                if (counter) {
                    var numStr = (currentIdx + 1).toString().padStart(2, '0');
                    var totStr = totalSlides.toString().padStart(2, '0');
                    counter.textContent = numStr + ' / ' + totStr;
                }

                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    goToSlide(currentIdx - 1);
                    resetAutoSlide();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    goToSlide(currentIdx + 1);
                    resetAutoSlide();
                });
            }

            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    var targetIdx = parseInt(dot.getAttribute('data-slide-to'), 10);
                    if (!isNaN(targetIdx)) {
                        goToSlide(targetIdx);
                        resetAutoSlide();
                    }
                });
            });

            function startAutoSlide() {
                if (totalSlides > 1) {
                    autoInterval = setInterval(function () {
                        goToSlide(currentIdx + 1);
                    }, 6500);
                }
            }

            function resetAutoSlide() {
                if (autoInterval) clearInterval(autoInterval);
                startAutoSlide();
            }

            startAutoSlide();

            var bentoCard = document.getElementById('bentoFeaturedCard');
            if (bentoCard) {
                bentoCard.addEventListener('mouseenter', function () {
                    if (autoInterval) clearInterval(autoInterval);
                });
                bentoCard.addEventListener('mouseleave', function () {
                    resetAutoSlide();
                });
            }

            // --- 3. Showreel Modal Trigger ---
            var showreelModal = document.getElementById('showreelModal');
            var showreelClose = document.getElementById('showreelClose');
            var showreelBackdrop = document.getElementById('showreelBackdrop');
            var btnHeroShowreel = document.getElementById('btnHeroShowreel');
            var btnDockShowreel = document.getElementById('btnDockShowreel');
            var showreelIframe = document.getElementById('showreelIframe');

            function openShowreel() {
                if (!showreelModal) return;
                showreelModal.removeAttribute('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeShowreel() {
                if (!showreelModal) return;
                showreelModal.setAttribute('hidden', '');
                document.body.style.overflow = '';
                // Pause iframe video playback
                if (showreelIframe) {
                    var src = showreelIframe.src;
                    showreelIframe.src = src;
                }
            }

            if (btnHeroShowreel) btnHeroShowreel.addEventListener('click', openShowreel);
            if (btnDockShowreel) btnDockShowreel.addEventListener('click', openShowreel);
            if (showreelClose) showreelClose.addEventListener('click', closeShowreel);
            if (showreelBackdrop) showreelBackdrop.addEventListener('click', closeShowreel);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && showreelModal && !showreelModal.hasAttribute('hidden')) {
                    closeShowreel();
                }
            });
        });
    </script>
@endsection
