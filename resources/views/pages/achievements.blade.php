@extends('layouts.app')

@section('title', 'Prestasi & Hall of Fame | Ruler Of Kings')
@section('description', 'Daftar rekor prestasi dan penghargaan resmi turnamen Ruler Of Kings (ROK).')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Prestasi' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'ACHIEVEMENTS & TROPHIES' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Jejak penghargaan resmi yang diraih skuad Ruler Of Kings sepanjang kiprah kompetitif.' }}
            </p>
        </div>
    </section>

    <section class="achievements-page-section">
        <div class="section-inner">
            {{-- Ringkasan: dihitung dari data yang ditampilkan --}}
            @if ($achievements->isNotEmpty())
                <div class="stat-row" style="margin-top: 0; margin-bottom: 48px; border-top: none; padding-top: 0;">
                    <div class="stat-item">
                        <span class="stat-item__value">{{ $achievements->count() }}</span>
                        <span class="stat-item__label">Prestasi tercatat</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-item__value">{{ $achievements->pluck('event_year')->filter()->unique()->count() }}</span>
                        <span class="stat-item__label">Tahun kompetisi</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-item__value">{{ $achievements->where('is_highlight', true)->count() }}</span>
                        <span class="stat-item__label">Prestasi utama</span>
                    </div>
                </div>
            @endif

            {{-- Filter --}}
            <div class="filter-bar">
                <form method="GET" action="{{ url('/achievements') }}" class="filter-form">
                    <div class="filter-group">
                        <label for="filterYear" class="filter-label">Tahun</label>
                        <select name="year" id="filterYear" class="filter-select" onchange="this.form.submit()">
                            <option value="">Semua tahun</option>
                            @foreach ($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterDiv" class="filter-label">Divisi</label>
                        <select name="division_id" id="filterDiv" class="filter-select" onchange="this.form.submit()">
                            <option value="">Semua divisi</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (request()->hasAny(['year', 'division_id']))
                        <a href="{{ url('/achievements') }}" class="btn-clear-filter">Reset filter</a>
                    @endif
                </form>
            </div>

            {{-- Daftar prestasi --}}
            <div class="achv-list">
                <div class="achv-row achv-row--head">
                    <span>Tahun</span>
                    <span>Gelar</span>
                    <span>Tim</span>
                    <span>Keterangan</span>
                </div>

                @forelse ($achievements as $achievement)
                    <div class="achv-row" data-rok-animate="scroll-reveal">
                        <span class="achv-year">{{ $achievement->event_year }}</span>
                        <h3 class="achv-title">{{ $achievement->title }}</h3>
                        <span class="achv-team">
                            {{ $achievement->team_name ?? 'Ruler Of Kings' }}
                            @if ($achievement->division)
                                &middot; {{ $achievement->division->name }}
                            @endif
                        </span>
                        <span class="achv-note">{{ $achievement->caption }}</span>
                    </div>
                @empty
                    <div class="empty-state-box">
                        <p>Tidak ada prestasi yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ url('/achievements') }}" class="text-link" style="margin-top: 16px;">Tampilkan semua</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
