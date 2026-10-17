@extends('layouts.app')

@section('title', 'Membership Tiers & Paket Akses | Ruler Of Kings')
@section('description', 'Pilih paket keanggotaan ROK untuk membuka fitur turnamen Bracket Studio, emblem tim resmi, WhatsApp kapten lawan, dan akademi taktis esports.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge" style="letter-spacing: 2px;">ROK MEMBERSHIP TIERS</span>
            <h1 class="page-title">PAKET KEANGGOTAAN EKSKLUSIF</h1>
            <p class="page-subtitle-gold">
                Unlock Pro Features. Empower Your Team. Dominate Every Bracket.
            </p>
            <p class="page-subtitle">
                Tingkatkan pengalaman kompetisi esports Anda dengan akses ruang kerja <strong>ROK Bracket Studio</strong>, kustomisasi logo tim resmi dengan integrasi WhatsApp &amp; Poin Pertandingan, serta playbook strategi pro esports.
            </p>
        </div>
    </section>

    <section class="membership-section" style="padding: 40px 0 80px 0;">
        <div class="site-container" style="max-width: 1160px; margin: 0 auto; padding: 0 20px;">

            {{-- Membership Pricing Grid --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px; margin-bottom: 60px;">
                @foreach ($plans as $plan)
                    @php
                        $isCurrent = Auth::check() && Auth::user()->membershipTierSlug() === $plan->slug;
                    @endphp
                    <div class="pricing-card {{ $plan->is_featured ? 'pricing-card--featured' : '' }}" style="background: var(--rok-surface-card, #111622); border: 1px solid {{ $plan->is_featured ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: var(--radius-md); padding: 36px 30px; display: flex; flex-direction: column; justify-content: space-between; position: relative; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                        
                        @if ($plan->is_featured)
                            <span style="position: absolute; top: -13px; right: 24px; background: var(--rok-gold); color: #0b0f17; font-size: 10px; font-weight: 900; letter-spacing: 1.5px; padding: 4px 14px; border-radius: 999px; text-transform: uppercase;">
                                POPULAR CHOICE
                            </span>
                        @endif

                        <div>
                            <div class="pricing-header" style="margin-bottom: 18px;">
                                <span class="pricing-badge {{ $plan->is_featured ? 'pricing-badge--gold' : '' }}" style="font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: var(--rok-gold); display: inline-block; margin-bottom: 4px;">{{ $plan->badge_title }}</span>
                                <h3 class="pricing-title" style="font-size: 26px; font-weight: 800; margin: 4px 0 8px 0;">{{ $plan->name }}</h3>
                                <p class="pricing-sub" style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.5; margin: 0;">{{ $plan->description }}</p>
                            </div>

                            <div style="margin: 20px 0; padding: 14px 0; border-top: 1px solid var(--rok-surface-border); border-bottom: 1px solid var(--rok-surface-border);">
                                <div style="font-size: 30px; font-weight: 900; font-family: var(--font-sub);">
                                    {{ $plan->formatted_price }}
                                    @if ($plan->price > 0)
                                        <span style="font-size: 13px; font-weight: 600; color: var(--rok-text-muted); font-family: var(--font-main);">/ {{ $plan->billing_cycle }}</span>
                                    @endif
                                </div>
                            </div>

                            <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 32px 0; font-size: 13px; color: var(--rok-text-secondary); display: grid; gap: 12px;">
                                @if (is_array($plan->features))
                                    @foreach ($plan->features as $feature)
                                        <li style="display: flex; gap: 10px; align-items: flex-start;">
                                            <span style="color: #22c55e; font-weight: bold; flex-shrink: 0;">&#10003;</span>
                                            <span style="line-height: 1.45;">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            @if ($isCurrent)
                                <button type="button" class="btn-rok btn-rok--outline btn-rok--full" style="text-align: center; opacity: 0.7; cursor: default;" disabled>
                                    &#10003; Paket Anda Saat Ini
                                </button>
                            @elseif ($plan->price == 0)
                                <a href="{{ route('register') }}" class="btn-rok btn-rok--outline btn-rok--full" style="text-align: center; text-decoration: none;">
                                    Daftar Akun Gratis
                                </a>
                            @else
                                <a href="{{ route('membership.payment', ['plan' => $plan->slug]) }}" class="btn-rok {{ $plan->is_featured ? 'btn-rok--gold' : 'btn-rok--outline' }} btn-rok--full" style="text-align: center; text-decoration: none;">
                                    Pilih Paket {{ $plan->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Feature Highlights Grid --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 50px;">
                <div style="background: var(--rok-surface-card, #111622); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 26px;">
                    <div style="font-size: 32px; margin-bottom: 12px;">&#x1F3C6;</div>
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">Workspace ROK Bracket Studio</h3>
                    <p style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.6; margin: 0;">
                        Kelola turnamen tanpa batas, bagan Single/Double Elimination dan Swiss M7, dengan sinkronisasi skor real-time serta tautan live viewer untuk publik.
                    </p>
                </div>

                <div style="background: var(--rok-surface-card, #111622); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 26px;">
                    <div style="font-size: 32px; margin-bottom: 12px;">&#x1F4AC;</div>
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">Integrasi WhatsApp &amp; Poin Pertandingan</h3>
                    <p style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.6; margin: 0;">
                        Hubungkan logo tim ke nomor WhatsApp kapten agar mudah janjian lobby custom, dan pantau live match scoring beserta akumulasi poin liga otomatis.
                    </p>
                </div>

                <div style="background: var(--rok-surface-card, #111622); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 26px;">
                    <div style="font-size: 32px; margin-bottom: 12px;">&#x1F4DA;</div>
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">Akademi &amp; Taktik Playbook PDF</h3>
                    <p style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.6; margin: 0;">
                        Akses modul strategi pro esports Ruler of Kings: panduan drafting, makro manajemen, rotasi jungle, dan video breakdown eksklusif.
                    </p>
                </div>
            </div>

            {{-- Bottom Banner CTA --}}
            <div class="studio-portal-banner">
                <div class="studio-portal-banner__info">
                    <span class="studio-portal-badge">&#x1F451; SUDAH MEMILIKI MEMBERSHIP?</span>
                    <h3>LANGSUNG MASUK KE ROK BRACKET STUDIO</h3>
                    <p>
                        Akses workspace organizer Anda sekarang juga untuk mulai membuat bagan turnamen, mengunggah emblem tim resmi, dan membagikan link live bracket.
                    </p>
                </div>
                <div class="studio-portal-banner__actions">
                    <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--gold">
                        &#x2694; Buka Bracket Studio
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
