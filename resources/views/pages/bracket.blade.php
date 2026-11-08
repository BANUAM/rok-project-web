@extends('layouts.app')

@section('title', 'Tournament Bracket Generator | ROK Web')
@section('description', 'Platform pembuatan bagan turnamen esports, generator bracket single elimination, seeding tim, dan live match scoring.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Tournament Engine' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'BUILD. COMPETE. CONQUER.' }}</h1>
            <p class="page-subtitle-gold">
                Your Tournament. Your Bracket. Your Champion.
            </p>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Kelola turnamen esports komunitasmu dengan bagan pertandingan otomatis, seeding tim berimbang, dan sistem live score real-time.' }}
            </p>

            {{-- Dedicated Organic Cyber-Island Dock for Organizer Portal --}}
            <div class="studio-portal-banner studio-cyber-dock" id="studioCyberDock">
                <div class="studio-portal-banner__info">
                    <span class="studio-portal-badge">
                        <i data-lucide="crown" style="width:13px;height:13px;"></i> ORGANIZER PORTAL
                    </span>
                    <h3 class="portal-island-title">ROK BRACKET STUDIO</h3>
                    <p>
                        Kelola multi-turnamen, kustomisasi logo tim, kontak WhatsApp kapten, dan bagikan tautan live bracket.
                    </p>
                </div>
                <div class="studio-portal-banner__actions">
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="cyber-chip-btn cyber-chip-btn--primary">
                            <i data-lucide="swords" style="width:13px;height:13px;"></i> Buka Bracket Studio
                        </a>
                    @elseif(Auth::check())
                        <a href="{{ route('membership.payment', ['plan' => 'immortal']) }}" class="cyber-chip-btn cyber-chip-btn--primary">
                            <i data-lucide="crown" style="width:13px;height:13px;"></i> Aktifkan Immortal
                        </a>
                    @else
                        <a href="{{ route('bracket.studio.index') }}" class="cyber-chip-btn cyber-chip-btn--primary">
                            <i data-lucide="key" style="width:13px;height:13px;"></i> Masuk Studio
                        </a>
                    @endif
                    @if ($activeTournament)
                        <a href="{{ route('bracket.live', $activeTournament->slug) }}" class="cyber-chip-btn cyber-chip-btn--secondary">
                            <i data-lucide="external-link" style="width:13px;height:13px;"></i> Live View
                        </a>
                    @endif
                    <a href="{{ route('bracket.free') }}" class="cyber-chip-btn cyber-chip-btn--secondary">
                        <i data-lucide="gamepad-2" style="width:13px;height:13px;"></i> Versi Gratis
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive & Real-Time Tournament Generator Tool --}}
    <section class="generator-tool-section" id="generatorTool">
        <div class="section-inner">
            <header class="section-header" style="margin-bottom: 24px;">
                <h2 class="section-title">BUAT BAGAN TURNAMEN SENDIRI</h2>
            </header>

            @include('components.bracket-engine', [
                'activeTournament' => $activeTournament,
                'studioMode' => false,
                'readonlyMode' => false,
                'isPromoPage' => true,
                'showRealtimeBar' => false,
            ])
        </div>
    </section>

    {{-- Organizer Branding Highlight Box --}}
    <section class="branding-box-section" style="padding: 30px 0 60px 0;">
        <div class="section-inner">
            <div class="branding-card-box">
                <div class="branding-content">
                    <h3>Ingin Menggunakan Logo Tim Resmi, Popover WhatsApp + Poin &amp; Halaman Khusus Bracket Studio?</h3>
                    <p>
                        Free Organizer dapat membuat bagan pertandingan standar dan menghubungi WhatsApp lawan. Untuk membuka halaman khusus <strong>ROK Bracket Studio</strong>, mengganti logo tim resmi yang bisa diklik untuk menampilkan <strong>Poin Pertandingan &amp; Kontak WhatsApp</strong>, serta membuat turnamen live tak terbatas, aktifkan paket <strong>ROK Challenger</strong> atau <strong>ROK Immortal</strong>.
                    </p>
                </div>
                <div class="branding-action">
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold">
                            Masuk Bracket Studio
                        </a>
                    @else
                        <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="btn-rok btn-rok--gold">
                            Buka Fitur Bracket
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
