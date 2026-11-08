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

            {{-- Dedicated Portal Banner for ROK Challenger & Immortal --}}
            <div class="studio-portal-banner" style="border-radius: 12px; padding: 18px 22px;">
                <div class="studio-portal-banner__info">
                    <span class="studio-portal-badge" style="border-radius: 999px; padding: 2px 10px; font-size: 11px; display:inline-flex;align-items:center;gap:5px;"><i data-lucide="crown" style="width:12px;height:12px;"></i> ORGANIZER PORTAL</span>
                    <h3 style="font-size: 17px; margin: 4px 0 4px 0; font-weight: 700;">ROK BRACKET STUDIO</h3>
                    <p style="font-size: 12px; margin: 0; color: var(--rok-text-muted);">
                        Kelola multi-turnamen, kustomisasi logo tim, kontak WhatsApp kapten, dan bagikan tautan live bracket.
                    </p>
                </div>
                <div class="studio-portal-banner__actions" style="margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
                    @if(Auth::check() && Auth::user()->hasBracketAccess())
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex;align-items:center;gap:5px;">
                            <i data-lucide="swords" style="width:13px;height:13px;"></i> Buka Bracket Studio
                        </a>
                    @elseif(Auth::check())
                        <a href="{{ route('membership.payment', ['plan' => 'immortal']) }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex;align-items:center;gap:5px;">
                            <i data-lucide="crown" style="width:13px;height:13px;"></i> Aktifkan Immortal
                        </a>
                    @else
                        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex;align-items:center;gap:5px;">
                            <i data-lucide="key" style="width:13px;height:13px;"></i> Masuk Studio
                        </a>
                    @endif
                    @if ($activeTournament)
                        <a href="{{ route('bracket.live', $activeTournament->slug) }}" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; display:inline-flex;align-items:center;gap:5px;">
                            <i data-lucide="external-link" style="width:13px;height:13px;"></i> Live View
                        </a>
                    @endif
                    <a href="{{ route('bracket.free') }}" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; border-color: rgba(251,191,36,0.6); color: var(--rok-gold); display:inline-flex; align-items:center; gap:5px;">
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

    {{-- Membership Tiers Section for Organizers --}}
    <section class="membership-section" id="membership-tiers" style="padding: 60px 0;">
        <div class="section-inner">
            <header class="section-header" style="text-align: center; margin-bottom: 40px;">
                <span class="sr-only" aria-hidden="true">ORGANIZER MEMBERSHIP TIERS</span>
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
                                            <span style="color: #4ade80; font-weight: bold; flex-shrink: 0;"><i data-lucide="check" style="width:14px;height:14px;"></i></span>
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
