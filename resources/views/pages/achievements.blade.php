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
            <h1 class="page-title">{{ $pageHeader?->title ?? 'ACHIEVEMENTS & TROPHIES' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Jejak supremasi dan penghargaan resmi yang diraih skuad Ruler Of Kings sepanjang kiprah kompetitif esports.' }}
            </p>
        </div>
    </section>

    <section class="achievements-page-section">
        <div class="section-inner">
            {{-- Championship Metrics Banner --}}
            <div class="championship-metrics-banner">
                <div class="championship-metric-item">
                    <span class="championship-metric-val">18+</span>
                    <span class="championship-metric-lbl">Verified Championships</span>
                </div>
                <div class="championship-metric-item">
                    <span class="championship-metric-val">78.4%</span>
                    <span class="championship-metric-lbl">Career Match Win Rate</span>
                </div>
                <div class="championship-metric-item">
                    <span class="championship-metric-val">04</span>
                    <span class="championship-metric-lbl">Active Pro Divisions</span>
                </div>
                <div class="championship-metric-item">
                    <span class="championship-metric-val">100%</span>
                    <span class="championship-metric-lbl">Sanctioned Tournaments</span>
                </div>
            </div>

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

            {{-- Hall of Acclaim Ledger --}}
            <div class="hall-acclaim-ledger">
                <div class="hall-acclaim-header">
                    <span>Tahun</span>
                    <span>Gelar &amp; Kejuaraan</span>
                    <span>Divisi Skuad</span>
                    <span>Turnamen / Rekam Jejak</span>
                    <span style="text-align:right;">Status</span>
                </div>

                @forelse ($achievements as $achievement)
                    <div class="hall-acclaim-row">
                        <span class="hall-acclaim-year">{{ $achievement->event_year }}</span>
                        <div class="hall-acclaim-title-box">
                            <span class="hall-acclaim-rank-badge">{{ $achievement->placement_rank ?? 'CHAMPION' }}</span>
                            <h3>{{ $achievement->title }}</h3>
                        </div>
                        <span class="hall-acclaim-team">{{ $achievement->team_name ?? ($achievement->division?->name ?? 'Ruler Of Kings') }}</span>
                        <div class="hall-acclaim-desc">{{ $achievement->caption }}</div>
                        <div class="hall-acclaim-status">VERIFIED TITLE</div>
                    </div>
                @empty
                    <div class="empty-state-box" style="padding: 48px; text-align: center; color: #64748b; font-family: var(--font-mono, monospace);">
                        <p>TIDAK ADA DATA PRESTASI DITEMUKAN PADA FILTER INI.</p>
                        <a href="{{ url('/achievements') }}" class="btn-rok btn-rok--ghost" style="margin-top: 12px; display: inline-block;">Reset Filter &rarr;</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
