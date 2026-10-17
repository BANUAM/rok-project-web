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
            <span class="section-badge">TOURNAMENT ENGINE</span>
            <h1 class="page-title">{{ $pageHeader?->title ?? 'BUILD. COMPETE. CONQUER.' }}</h1>
            <p class="page-subtitle-gold">
                Your Tournament. Your Bracket. Your Champion.
            </p>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Kelola turnamen esports komunitasmu dengan bagan pertandingan otomatis, seeding tim berimbang, dan sistem live score real-time.' }}
            </p>

            {{-- Dedicated Portal Banner for ROK Challenger & Immortal --}}
            <div class="studio-portal-banner">
                <div class="studio-portal-banner__info">
                    <span class="studio-portal-badge">&#x1F451; DEDICATED ORGANIZER PORTAL</span>
                    <h3>ROK BRACKET STUDIO (KHUSUS MEMBER CHALLENGER &amp; IMMORTAL)</h3>
                    <p>
                        Kelola multi-turnamen, kustomisasi logo tim &amp; kontak WhatsApp kapten, pantau poin real-time, dan bagikan tautan live bracket di ruang kerja khusus seperti portal Management.
                    </p>
                </div>
                <div class="studio-portal-banner__actions">
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold">
                            &#x2694; Buka Bracket Studio
                        </a>
                    @elseif(Auth::check())
                        <a href="{{ route('membership.payment', ['plan' => 'immortal']) }}" class="btn-rok btn-rok--gold">
                            &#x1F451; Aktifkan ROK Immortal
                        </a>
                    @else
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold">
                            &#x1F511; Masuk Bracket Studio
                        </a>
                    @endif
                    @if ($activeTournament)
                        <a href="{{ route('bracket.live', $activeTournament->slug) }}" class="btn-rok btn-rok--outline">
                            &#x1F517; Lihat Live Share Link
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive & Real-Time Tournament Generator Tool --}}
    <section class="generator-tool-section" id="generatorTool">
        <div class="section-inner">
            <header class="section-header">
                <span class="section-badge">REAL-TIME INTERACTIVE BRACKET ENGINE</span>
                <h2 class="section-title">BUAT BAGAN TURNAMEN SENDIRI</h2>
                <p class="section-description">
                    Buat bagan dan jadwal pertandingan berdasarkan format kompetisi. Masukkan nama tim &amp; nomor WhatsApp kapten, klik logo tim untuk melihat info poin &amp; kontak WA, serta nikmati sinkronisasi skor secara real-time.
                </p>
            </header>

            @include('components.bracket-engine', [
                'activeTournament' => $activeTournament,
                'studioMode' => false,
                'readonlyMode' => false,
            ])
        </div>
    </section>

    {{-- Membership Tiers Section for Organizers --}}
    <section class="membership-section" id="membership-tiers" style="padding: 60px 0;">
        <div class="section-inner">
            <header class="section-header" style="text-align: center; margin-bottom: 40px;">
                <span class="section-badge" style="letter-spacing: 2px;">ORGANIZER MEMBERSHIP TIERS</span>
                <h2 class="section-title">PAKET AKSES &amp; KEANGGOTAAN ORGANIZER</h2>
                <p class="section-description" style="max-width: 700px; margin: 0 auto;">
                    Tingkatkan kualitas turnamen Anda dengan akses halaman khusus <strong>Bracket Studio</strong>, logo tim interaktif dengan tautan WhatsApp &amp; poin pertandingan, serta kurikulum taktik pro esports Ruler of Kings.
                </p>
            </header>

            <div class="membership-pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
                @foreach ($membershipPlans as $plan)
                    <div class="pricing-card {{ $plan->is_featured ? 'pricing-card--featured' : '' }}" style="background: var(--rok-card-bg, #111622); border: 1px solid {{ $plan->is_featured ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: var(--radius-md); padding: 32px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div class="pricing-header" style="margin-bottom: 16px;">
                                <span class="pricing-badge {{ $plan->is_featured ? 'pricing-badge--gold' : '' }}">{{ $plan->badge_title }}</span>
                                <h3 class="pricing-title" style="font-size: 24px; font-weight: 800; margin: 6px 0;">{{ $plan->name }}</h3>
                                <p class="pricing-sub" style="font-size: 13px; color: var(--rok-text-muted);">{{ $plan->description }}</p>
                            </div>

                            <div style="margin: 16px 0; padding: 12px 0; border-top: 1px solid var(--rok-surface-border); border-bottom: 1px solid var(--rok-surface-border);">
                                <div style="font-size: 28px; font-weight: 900; font-family: var(--font-sub);">
                                    {{ $plan->formatted_price }}
                                    <span style="font-size: 13px; font-weight: 600; color: var(--rok-text-muted); font-family: var(--font-main);">/ {{ $plan->billing_cycle }}</span>
                                </div>
                            </div>

                            <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 24px 0; font-size: 13px; color: var(--rok-text-secondary); display: grid; gap: 10px;">
                                @if (is_array($plan->features))
                                    @foreach ($plan->features as $feature)
                                        <li style="display: flex; gap: 8px; align-items: flex-start;">
                                            <span style="color: #4ade80; font-weight: bold;">&#10003;</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            <a href="{{ route('membership.payment', ['plan' => $plan->slug]) }}" class="btn-rok {{ $plan->is_featured ? 'btn-rok--gold' : 'btn-rok--outline' }} btn-rok--full" style="text-align: center; text-decoration: none;">
                                @if($plan->slug === 'challenger')
                                    Buka Fitur Bracket
                                @elseif($plan->slug === 'immortal')
                                    Pilih Paket Immortal
                                @else
                                    Pelajari Akses
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Organizer Branding Highlight Box --}}
            <div class="branding-card-box" style="margin-top: 30px;">
                <div class="branding-content">
                    <span class="badge-mini">ORGANIZER MEMBERSHIP PRIVILEGES</span>
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
