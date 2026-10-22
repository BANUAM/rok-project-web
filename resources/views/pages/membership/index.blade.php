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
                    <div style="margin-bottom: 12px; display: flex; align-items: center;">
                        <svg viewBox="0 0 24 24" width="34" height="34" fill="currentColor" style="color:#25D366;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                    </div>
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
