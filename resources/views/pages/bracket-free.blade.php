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
            <div class="studio-portal-banner" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.85) 0%, rgba(15, 23, 42, 0.95) 100%); border-left: 4px solid #38bdf8; border-radius: var(--radius-md); padding: 22px;">
                <div class="studio-portal-banner__info">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                        <span class="studio-portal-badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4);">&#x1F513; EDISI GRATIS TERISOLASI</span>
                        <span style="font-size: 11px; background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; color: var(--rok-silver); font-family: monospace;">
                            ID: {{ $activeTournament->slug }}
                        </span>
                    </div>
                    <h3 style="font-size: 18px; margin: 4px 0 8px 0;">SEMUA FITUR BAGAN AKTIF (RUANG KERJA TURNAMEN ANDA)</h3>
                    <p style="font-size: 13px; margin: 0; color: var(--rok-text-muted); line-height: 1.5;">
                        Setiap turnamen gratis memiliki URL unik dan tersimpan mandiri di database. Bagikan <strong>Link Pra-Turnamen Free</strong> di bawah ke kapten tim Anda. Kapten hanya akan mengisi slot di turnamen ini tanpa risiko bentrok dengan penyelenggara turnamen lain.
                    </p>
                </div>
                <div class="studio-portal-banner__actions" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-top: 14px;">
                    <a href="{{ route('bracket.free') }}?new=1" class="btn-rok btn-rok--sm" style="background: #0284c7; color: #fff; font-weight: 700; border-radius: 20px; padding: 7px 16px;" onclick="return confirm('Buat turnamen gratis baru dengan tautan dan bagan yang baru?')">
                        &#x2795; Buat Turnamen Baru
                    </a>
                    <a href="{{ route('bracket') }}" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 20px; padding: 7px 16px;">
                        &#x1F4CB; Halaman Promosi
                    </a>
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 20px; padding: 7px 16px;">
                            &#x2694; Buka Bracket Studio Member
                        </a>
                    @else
                        <a href="{{ route('membership.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 20px; padding: 7px 16px;">
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
