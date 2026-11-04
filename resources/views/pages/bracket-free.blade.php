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
            <span class="section-badge" style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="gamepad-2" style="width:13px;height:13px;"></i> FREE TOURNAMENT GENERATOR</span>
            <h1 class="page-title">BAGAN TURNAMEN GRATIS</h1>
            <p class="page-subtitle-gold">
                Buat &amp; Kelola Bagan Pertandingan Komunitas Esports Tanpa Biaya
            </p>
            <p class="page-subtitle">
                Platform pembuatan bagan turnamen gratis dengan format Single &amp; Double Elimination, Swiss Stage, kontak WhatsApp kapten tim, dan live scoring real-time.
            </p>

            {{-- Free Edition Banner with link to Membership & Promo --}}
            <div class="studio-portal-banner" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.45) 0%, rgba(15, 23, 42, 0.7) 100%); border-left: 3px solid #38bdf8; border-radius: 12px; padding: 16px 20px; backdrop-filter: blur(10px);">
                <div class="studio-portal-banner__info">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                        <span class="studio-portal-badge" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 999px; padding: 2px 9px; font-size: 11px; display:inline-flex; align-items:center; gap:4px;"><i data-lucide="unlock" style="width:12px;height:12px;"></i> SEMUA FITUR BAGAN AKTIF</span>
                        <span style="font-size: 11px; color: var(--rok-silver); font-family: monospace; opacity: 0.75;">
                            {{ $activeTournament->slug }}
                        </span>
                    </div>
                    <p style="font-size: 12px; margin: 0; color: var(--rok-text-muted); line-height: 1.5;">
                        Ruang kerja mandiri dengan tautan tersimpan. Bagikan link pra-turnamen ke kapten tim untuk registrasi mandiri.
                    </p>
                </div>
                <div class="studio-portal-banner__actions" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-top: 10px;">
                    <a href="{{ route('bracket.free') }}?new=1" class="btn-rok btn-rok--sm" style="background: #0284c7; color: #fff; font-weight: 700; border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex; align-items:center; gap:4px;" onclick="return confirm('Buat turnamen gratis baru dengan tautan dan bagan yang baru?')">
                        <i data-lucide="plus" style="width:13px;height:13px;"></i> Turnamen Baru
                    </a>
                    <a href="{{ route('bracket') }}" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex; align-items:center; gap:4px;">
                        <i data-lucide="clipboard-list" style="width:13px;height:13px;"></i> Halaman Promosi
                    </a>
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex; align-items:center; gap:4px;">
                            <i data-lucide="swords" style="width:13px;height:13px;"></i> Bracket Studio
                        </a>
                    @else
                        <a href="{{ route('membership.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex; align-items:center; gap:4px;">
                            <i data-lucide="crown" style="width:13px;height:13px;"></i> Membership
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive & Real-Time Free Tournament Generator Tool --}}
    <section class="generator-tool-section" id="generatorTool">
        <div class="section-inner">
            <header class="section-header" style="margin-bottom: 24px;">
                <span class="section-badge">INTERACTIVE BRACKET</span>
                <h2 class="section-title">GENERATOR BAGAN TURNAMEN</h2>
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
                    <a href="{{ route('membership.index') }}" class="btn-rok btn-rok--gold" style="display:inline-flex;align-items:center;gap:6px;">
                        <i data-lucide="crown" style="width:14px;height:14px;"></i> Pelajari Paket Membership
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
(() => {
    const currentSlug = @json($activeTournament->slug);
    const currentTitle = @json($activeTournament->name);
    try {
        localStorage.setItem('rok_last_free_slug', currentSlug);
        let recents = JSON.parse(localStorage.getItem('rok_free_recent_tourneys') || '[]');
        recents = recents.filter(t => t.slug !== currentSlug);
        recents.unshift({ slug: currentSlug, name: currentTitle, time: Date.now() });
        if (recents.length > 5) recents = recents.slice(0, 5);
        localStorage.setItem('rok_free_recent_tourneys', JSON.stringify(recents));
    } catch (e) {}
})();
</script>
@endsection
