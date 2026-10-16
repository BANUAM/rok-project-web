@extends('layouts.app')

@section('title', 'Achievements & Hall of Fame | ROK Revamp')
@section('description', 'Daftar rekor prestasi, piala, dan sertifikat resmi turnamen Ruler Of Kings (ROK) Revamp.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Achievements & Trophies' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <span class="section-badge">HALL OF FAME</span>
            <h1 class="page-title">{{ $pageHeader?->title ?? 'ACHIEVEMENTS & TROPHIES' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Jejak supremasi dan penghargaan resmi yang diraih skuad Ruler Of Kings sepanjang kiprah kompetitif esports.' }}
            </p>
        </div>
    </section>

    <section class="achievements-page-section">
        <div class="section-inner">
            {{-- Filter Bar --}}
            <div class="filter-bar">
                <form method="GET" action="{{ url('/achievements') }}" class="filter-form">
                    <div class="filter-group">
                        <label for="filterYear" class="filter-label">Tahun:</label>
                        <select name="year" id="filterYear" class="filter-select" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach ($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                    {{ $yr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterDiv" class="filter-label">Divisi:</label>
                        <select name="division_id" id="filterDiv" class="filter-select" onchange="this.form.submit()">
                            <option value="">Semua Divisi</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (request()->hasAny(['year', 'division_id']))
                        <a href="{{ url('/achievements') }}" class="btn-clear-filter">Reset Filter</a>
                    @endif
                </form>
            </div>

            {{-- Achievements Grid --}}
            <div class="achievements-full-grid">
                @forelse ($achievements as $achievement)
                    <article class="achievement-full-card">
                        <div class="achievement-full-card__media">
                            <img
                                src="{{ $achievement->image_url }}"
                                alt="{{ $achievement->title }}"
                                loading="lazy"
                            >
                            <span class="year-badge">{{ $achievement->event_year }}</span>
                            @if ($achievement->is_highlight)
                                <span class="highlight-badge">&#x2B50; Major Title</span>
                            @endif
                        </div>

                        <div class="achievement-full-card__body">
                            <div class="achievement-tags">
                                <span class="tag-team">{{ $achievement->team_name ?? 'Ruler Of Kings' }}</span>
                                @if ($achievement->division)
                                    <span class="tag-division">{{ $achievement->division->name }}</span>
                                @endif
                                @if ($achievement->event_date)
                                    <span class="tag-date">{{ $achievement->event_date->format('d M Y') }}</span>
                                @endif
                            </div>

                            <h3 class="achievement-full-card__title">{{ $achievement->title }}</h3>
                            <p class="achievement-full-card__caption">{{ $achievement->caption }}</p>
                        </div>
                    </article>
                @empty
                    <div class="empty-state-box">
                        <p>Tidak ada prestasi yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ url('/achievements') }}" class="btn-rok btn-rok--ghost">Tampilkan Semua</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
