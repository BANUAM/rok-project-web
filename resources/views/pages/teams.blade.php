@extends('layouts.app')

@section('title', 'Teams & Organizational Structure | ROK Revamp')
@section('description', 'Struktur organisasi dan susunan roster pro player Ruler Of Kings (ROK) Revamp.')

@section('content')
<div class="page-container">
    {{-- Page Hero with dynamic Admin-managed banner --}}
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Teams & Roster' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'TEAMS & ROSTER' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Mengenal kepemimpinan, jajaran manajerial, pelatih, analis, serta atlet profesional di balik kejayaan Ruler Of Kings.' }}
            </p>
        </div>
    </section>

    {{-- 1. FOUNDERS SECTION (EXECUTIVE DOSSIER) --}}
    <section class="teams-section founders-section" id="founders">
        <div class="section-inner">
            <header class="section-header">
                <span class="teams-section-kicker">EXECUTIVE DOSSIER // 01</span>
                <h2 class="section-title">THE FOUNDERS</h2>
                <p class="section-description">
                    Para pendiri yang meletakkan visi dasar dan membangun fondasi kejayaan Ruler Of Kings.
                </p>
            </header>

            <div class="executive-dossier-grid">
                @foreach ($founders as $index => $founder)
                    <article class="executive-dossier-card">
                        <div class="executive-portrait-frame">
                            <img
                                src="{{ $founder->photo_url }}"
                                alt="Foto {{ $founder->name }}"
                                loading="lazy"
                            >
                        </div>
                        <div class="executive-dossier-body">
                            <span class="executive-kicker">DIR // 0{{ $index + 1 }}</span>
                            <h3 class="executive-name">{{ $founder->name }}</h3>
                            <div class="executive-role-title">{{ $founder->role_title }}</div>
                            <p class="executive-bio">{{ $founder->bio }}</p>
                            @if ($founder->instagram_url || $founder->linkedin_url)
                                <div class="executive-socials">
                                    @if ($founder->instagram_url)
                                        <a href="{{ $founder->instagram_url }}" target="_blank" rel="noopener noreferrer">
                                            Instagram &rarr;
                                        </a>
                                    @endif
                                    @if ($founder->linkedin_url)
                                        <a href="{{ $founder->linkedin_url }}" target="_blank" rel="noopener noreferrer">
                                            LinkedIn &rarr;
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 2. TEAMS & DIVISIONS SECTION --}}
    <section class="teams-section divisions-section" id="divisions">
        <div class="section-inner">
            <header class="section-header">
                <h2 class="section-title">STRUKTUR DIVISI &amp; ROSTER AKTIF</h2>
                <p class="section-description">
                    Hanya atlet yang telah lolos seleksi Trial, evaluasi LSM, dan kontrak resmi yang terdaftar sebagai roster aktif.
                </p>
            </header>

            {{-- Division Tabs --}}
            <div class="division-tabs" role="tablist">
                @foreach ($divisions as $index => $div)
                    <button
                        type="button"
                        class="tab-pill {{ $index === 0 ? 'is-active' : '' }}"
                        onclick="switchDivision('div-{{ $div->slug }}', this)"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                    >
                        <img src="{{ $div->icon_url }}" alt="" class="tab-pill__icon">
                        <span>{{ $div->name }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Division Content Panels --}}
            @foreach ($divisions as $index => $div)
                <div
                    class="division-panel {{ $index === 0 ? 'is-active' : '' }}"
                    id="div-{{ $div->slug }}"
                    role="tabpanel"
                >
                    <div class="division-info-strip">
                        <div>
                            <span class="badge-mini">{{ $div->game_name }}</span>
                            <h3 class="division-info-title">{{ $div->name }}</h3>
                            <p class="division-info-desc">{{ $div->description }}</p>
                        </div>
                        <div class="division-stats-pill">
                            <strong>{{ $div->activePlayers->count() }}</strong>
                            <span>Anggota Roster Aktif</span>
                        </div>
                    </div>

                    {{-- Staff Management Hierarchy --}}
                    @php
                        $staff = $div->activePlayers->filter(fn($p) => in_array($p->role, ['Manager', 'Coach', 'Analyst']));
                        $roster = $div->activePlayers->filter(fn($p) => !in_array($p->role, ['Manager', 'Coach', 'Analyst']));
                    @endphp

                    <div class="tactical-roster-ledger">
                        <div class="roster-ledger-header">
                            <span>No.</span>
                            <span>Atlet / IGN</span>
                            <span>Nama Lengkap</span>
                            <span>Peran Taktis</span>
                            <span style="text-align:right;">Status Roster</span>
                        </div>

                        @forelse ($div->activePlayers as $pIndex => $person)
                            <div class="roster-ledger-row">
                                <span class="roster-ledger-num">0{{ $pIndex + 1 }}</span>
                                <div class="roster-ledger-athlete">
                                    <img src="{{ $person->photo_url }}" alt="{{ $person->nickname }}" class="roster-athlete-avatar">
                                    <strong class="roster-athlete-ign">{{ $person->nickname }}</strong>
                                </div>
                                <span class="roster-ledger-realname">{{ $person->real_name }}</span>
                                <div>
                                    <span class="roster-role-badge">{{ $person->role }}</span>
                                </div>
                                <div class="roster-status-badge">CONTRACTED</div>
                            </div>
                        @empty
                            <div style="padding: 24px; text-align: center; color: #64748b; font-family: var(--font-mono, monospace); font-size: 13px;">
                                ROSTER IN FINALIZATION // PENDING CONTRACT REGISTRATION
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Bottom Trial Invite --}}
    <section class="section-bottom-cta">
        <div class="section-inner">
            <div class="cta-box-gold">
                <h3>INGIN MENJADI BAGIAN DARI ROSTER ROK?</h3>
                <p>Kami terus membuka pintu seleksi bagi talenta berbakat melalui proses evaluasi LSM yang transparan dan profesional.</p>
                <a href="{{ url('/trial') }}" class="btn-rok btn-rok--dark">
                    Daftar Trial Sekarang &rarr;
                </a>
            </div>
        </div>
    </section>
</div>

<script>
function switchDivision(panelId, btn) {
    document.querySelectorAll('.division-panel').forEach(p => p.classList.remove('is-active'));
    document.querySelectorAll('.tab-pill').forEach(b => {
        b.classList.remove('is-active');
        b.setAttribute('aria-selected', 'false');
    });

    const target = document.getElementById(panelId);
    if (target) {
        target.classList.add('is-active');
        btn.classList.add('is-active');
        btn.setAttribute('aria-selected', 'true');
    }
}
</script>
@endsection
