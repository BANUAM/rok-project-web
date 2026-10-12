@extends('layouts.app')

@section('title', 'Teams & Organizational Structure | ROK Revamp')
@section('description', 'Struktur organisasi dan susunan roster pro player Ruler Of Kings (ROK) Revamp.')

@section('content')
<div class="page-container">
    {{-- Page Hero --}}
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">ORGANIZATIONAL STRUCTURE</span>
            <h1 class="page-title">TEAMS &amp; ROSTER</h1>
            <p class="page-subtitle">
                Mengenal kepemimpinan, jajaran manajerial, pelatih, analis, serta atlet profesional di balik kejayaan Ruler Of Kings.
            </p>
        </div>
    </section>

    {{-- 1. FOUNDERS SECTION (Tersendiri, di luar divisi) --}}
    <section class="teams-section founders-section" id="founders">
        <div class="section-inner">
            <header class="section-header">
                <span class="section-badge">LEADERSHIP</span>
                <h2 class="section-title">THE FOUNDERS</h2>
                <p class="section-description">
                    Para pendiri yang meletakkan visi dasar dan membangun fondasi kejayaan Ruler Of Kings.
                </p>
            </header>

            <div class="founders-grid">
                @foreach ($founders as $founder)
                    <article class="founder-card">
                        <div class="founder-card__visual">
                            <img
                                src="{{ $founder->photo_url }}"
                                alt="Foto {{ $founder->name }}"
                                class="founder-card__photo"
                                loading="lazy"
                            >
                        </div>
                        <div class="founder-card__body">
                            <span class="founder-card__role">{{ $founder->role_title }}</span>
                            <h3 class="founder-card__name">{{ $founder->name }}</h3>
                            <p class="founder-card__bio">{{ $founder->bio }}</p>
                            @if ($founder->instagram_url || $founder->linkedin_url)
                                <div class="founder-card__links">
                                    @if ($founder->instagram_url)
                                        <a href="{{ $founder->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                            Instagram &rarr;
                                        </a>
                                    @endif
                                    @if ($founder->linkedin_url)
                                        <a href="{{ $founder->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
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
                <span class="section-badge">COMPETITIVE DIVISIONS</span>
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

                    @if ($staff->isNotEmpty())
                        <div class="roster-tier">
                            <h4 class="roster-tier__title">
                                <span>&#9881;</span> MANAGEMENT &amp; COACHING STAFF
                            </h4>
                            <div class="roster-grid roster-grid--staff">
                                @foreach ($staff as $person)
                                    <article class="player-card player-card--staff">
                                        <div class="player-card__avatar">
                                            <img src="{{ $person->photo_url }}" alt="{{ $person->nickname }}" loading="lazy">
                                        </div>
                                        <div class="player-card__info">
                                            <span class="player-card__role-badge">{{ $person->role }}</span>
                                            <h5 class="player-card__ign">{{ $person->nickname }}</h5>
                                            <p class="player-card__realname">{{ $person->real_name }}</p>
                                            <div class="player-card__meta">
                                                <span>{{ $person->domicile ?? 'Indonesia' }}</span>
                                                @if ($person->age)
                                                    <span>&bull; {{ $person->age }} Thn</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 5 Roles Players Roster --}}
                    <div class="roster-tier">
                        <h4 class="roster-tier__title">
                            <span>&#x1F3AE;</span> ACTIVE PLAYERS (LINEUP)
                        </h4>
                        @if ($roster->isNotEmpty())
                            <div class="roster-grid">
                                @foreach ($roster as $player)
                                    <article class="player-card">
                                        <div class="player-card__avatar">
                                            <img src="{{ $player->photo_url }}" alt="{{ $player->nickname }}" loading="lazy">
                                            <span class="player-card__role-tag player-card__role-tag--{{ Str::slug($player->role) }}">
                                                {{ $player->role }}
                                            </span>
                                        </div>
                                        <div class="player-card__info">
                                            <span class="player-card__sub">{{ $div->game_name }}</span>
                                            <h5 class="player-card__ign">{{ $player->nickname }}</h5>
                                            <p class="player-card__realname">{{ $player->real_name }}</p>
                                            <div class="player-card__meta">
                                                <span>{{ $player->domicile ?? 'Indonesia' }}</span>
                                                @if ($player->age)
                                                    <span>&bull; {{ $player->age }} Tahun</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state-box">
                                <p>Roster aktif untuk divisi ini belum ditetapkan. Ikuti proses trial untuk bergabung!</p>
                            </div>
                        @endif
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
