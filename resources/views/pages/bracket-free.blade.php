@extends('layouts.app')

@section('title', 'Free Tournament Bracket Generator | ROK Web')
@section('description', 'Platform pembuatan bagan turnamen esports gratis: Single Elimination, Double Elimination, Swiss Stage, kontak WhatsApp kapten tim, dan live scoring real-time.')

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
            <span class="section-badge">&#x1F3AE; FREE TOURNAMENT GENERATOR</span>
            <h1 class="page-title">BAGAN TURNAMEN GRATIS</h1>
            <p class="page-subtitle-gold">
                Buat &amp; Kelola Bagan Pertandingan Komunitas Esports Tanpa Biaya
            </p>
            <p class="page-subtitle">
                Gunakan seluruh fitur generator bagan secara gratis: format Single &amp; Double Elimination, Swiss Stage M7, League System, kontak WhatsApp kapten tim, live score real-time, serta kontrol zoom bagan. Fitur kustomisasi logo tim resmi tersedia pada paket keanggotaan ROK Challenger &amp; Immortal.
            </p>

            {{-- Free Edition Banner with link to Membership & Promo --}}
            <div class="studio-portal-banner" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.85) 0%, rgba(15, 23, 42, 0.95) 100%); border-left: 4px solid var(--rok-gold);">
                <div class="studio-portal-banner__info">
                    <span class="studio-portal-badge" style="background: rgba(251, 191, 36, 0.15); color: var(--rok-gold); border: 1px solid rgba(251, 191, 36, 0.4);">&#x1F513; EDISI GRATIS AKTIF</span>
                    <h3 style="font-size: 18px; margin: 6px 0;">SEMUA FITUR BAGAN AKTIF (KECUALI KUSTOMISASI LOGO TIM)</h3>
                    <p style="font-size: 13px; margin: 0; color: var(--rok-text-muted);">
                        Butuh halaman kerja khusus <strong>Bracket Studio</strong> seperti panel Management dan ingin memasang <strong>Logo Resmi Tim</strong> sendiri? Anda bisa meng-upgrade ke paket <strong>ROK Challenger</strong> atau <strong>ROK Immortal</strong> kapan saja.
                    </p>
                </div>
                <div class="studio-portal-banner__actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('bracket') }}" class="btn-rok btn-rok--outline">
                        &#x1F4CB; Halaman Promosi &amp; Info Paket
                    </a>
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold">
                            &#x2694; Buka Bracket Studio Member
                        </a>
                    @else
                        <a href="{{ route('membership.index') }}" class="btn-rok btn-rok--gold">
                            &#x1F451; Lihat Paket Membership
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive & Real-Time Free Tournament Generator Tool --}}
    <section class="generator-tool-section" id="generatorTool">
        <div class="section-inner">
            <header class="section-header">
                <span class="section-badge">INTERACTIVE BRACKET WORKSPACE</span>
                <h2 class="section-title">GENERATOR BAGAN TURNAMEN GRATIS</h2>
                <p class="section-description">
                    Atur nama turnamen, pilih format kompetisi, dan masukkan nama tim serta nomor WhatsApp kapten. Anda dapat memperbesar/memperkecil bagan dengan tombol zoom, memantau skor secara real-time, dan membagikan tautan live ke penonton.
                </p>
            </header>

            @include('components.bracket-engine', [
                'activeTournament' => $activeTournament,
                'tournamentSlug' => $activeTournament->slug,
                'initialBracketState' => $activeTournament->bracket_state,
                'studioMode' => false,
                'readonlyMode' => false,
                'isFreePage' => true,
                'unlockLogos' => false,
            ])
        </div>
    </section>

    {{-- Upgrade Notice Box --}}
    <section class="membership-section" style="padding: 30px 0 60px 0;">
        <div class="section-inner">
            <div class="branding-card-box">
                <div class="branding-content">
                    <span class="badge-mini">ORGANIZER MEMBERSHIP PRIVILEGES</span>
                    <h3>Ingin Mengunggah Logo Tim Sendiri &amp; Akses Halaman Organizer Khusus?</h3>
                    <p>
                        Pada versi gratis ini, semua tim menggunakan emblem standar ROK Web. Untuk membuka fitur unggah logo tim custom, ikon emblem turnamen eksklusif, serta portal manajemen khusus <strong>ROK Bracket Studio</strong>, aktifkan paket keanggotaan <strong>ROK Challenger</strong> atau <strong>ROK Immortal</strong>.
                    </p>
                </div>
                <div class="branding-action">
                    <a href="{{ route('membership.index') }}" class="btn-rok btn-rok--gold">
                        &#x1F451; Pelajari Paket Membership
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
