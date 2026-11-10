@extends('layouts.app')

@section('title', 'Tim & Struktur Organisasi | Ruler Of Kings')
@section('description', 'Struktur organisasi, jajaran pendiri, dan susunan roster atlet Ruler Of Kings (ROK).')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Tim & roster' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'Tim & roster aktif' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Orang-orang di balik Ruler Of Kings: para pendiri, staf, dan atlet yang membawa nama ROK di kancah nasional.' }}
            </p>
        </div>
    </section>

    {{-- Pendiri --}}
    @if ($founders->isNotEmpty())
        <section class="teams-section founders-section" id="founders">
            <div class="section-inner">
                <header class="section-header">
                    <span class="eyebrow">Kepemimpinan</span>
                    <h2 class="section-title">THE FOUNDERS</h2>
                    <p class="section-description">Sosok yang meletakkan visi dan membangun fondasi Ruler Of Kings.</p>
                </header>

                <div class="leader-grid">
                    @foreach ($founders as $founder)
                        <article class="leader-card" data-rok-animate="scroll-reveal">
                            <div class="leader-card__photo">
                                <img src="{{ $founder->photo_url }}" alt="Foto {{ $founder->name }}" loading="lazy">
                            </div>
                            <h3 class="leader-card__name">{{ $founder->name }}</h3>
                            <div class="leader-card__role">{{ $founder->role_title }}</div>
                            <p class="leader-card__bio">{{ $founder->bio }}</p>
                            @if ($founder->instagram_url || $founder->linkedin_url)
                                <div class="leader-card__links">
                                    @if ($founder->instagram_url)
                                        <a href="{{ $founder->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram</a>
                                    @endif
                                    @if ($founder->linkedin_url)
                                        <a href="{{ $founder->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Divisi & roster --}}
    <section class="teams-section divisions-section" id="divisions" style="padding-top: 24px;">
        <div class="section-inner">
            <header class="section-header">
                <span class="eyebrow">Divisi</span>
                <h2 class="section-title">STRUKTUR DIVISI &amp; ROSTER AKTIF</h2>
                <p class="section-description">
                    Hanya atlet yang lolos seleksi trial, evaluasi LSM, dan kontrak resmi yang terdaftar sebagai roster aktif.
                </p>
            </header>

            @forelse ($divisions as $div)
                @php
                    $staffRoles = ['Manager', 'Coach', 'Analyst'];
                    $members = $div->activePlayers
                        ->sortBy(fn ($p) => in_array($p->role, $staffRoles) ? 1 : 0)
                        ->values();
                @endphp

                <article class="team-division" id="div-{{ $div->slug }}" data-rok-animate="scroll-reveal">
                    <div class="team-division__head">
                        <div>
                            <span class="team-division__game">{{ $div->game_name }}</span>
                            <h3 class="team-division__name">{{ $div->name }}</h3>
                        </div>
                        <p class="team-division__desc">{{ $div->description }}</p>
                    </div>

                    <div class="roster-table">
                        <div class="roster-row roster-row--head">
                            <span>Pemain</span>
                            <span>Nama</span>
                            <span>Peran</span>
                        </div>

                        @forelse ($members as $person)
                            <div class="roster-row">
                                <div class="roster-player">
                                    <img src="{{ $person->photo_url }}" alt="{{ $person->nickname }}" loading="lazy">
                                    <strong>{{ $person->nickname }}</strong>
                                </div>
                                <span class="roster-real">{{ $person->real_name }}</span>
                                <span class="roster-role">{{ $person->role }}</span>
                            </div>
                        @empty
                            <div class="roster-empty">Roster divisi ini sedang disiapkan. Ikuti trial untuk bergabung.</div>
                        @endforelse
                    </div>
                </article>
            @empty
                <div class="empty-state-box">Divisi belum ditambahkan.</div>
            @endforelse
        </div>
    </section>

    {{-- Ajakan trial --}}
    <section class="home-section home-section--alt">
        <div class="home-section__inner" style="text-align: left;">
            <span class="eyebrow">Bergabung</span>
            <h2 class="section-title" style="max-width: 18ch;">Ingin jadi bagian dari roster ROK?</h2>
            <p class="section-description" style="margin-bottom: 28px;">
                Kami membuka seleksi bagi talenta berbakat lewat proses evaluasi LSM yang transparan dan profesional.
            </p>
            <a href="{{ url('/trial') }}" class="btn-rok btn-rok--gold">Daftar trial</a>
        </div>
    </section>
</div>
@endsection
