@extends('layouts.app')

@section('title', 'ROK | Ruler Of Kings — Organisasi Esports')
@section('description', 'Ruler Of Kings (ROK) adalah organisasi esports kompetitif yang berfokus pada prestasi, pengembangan atlet, seleksi pemain, dan platform turnamen.')

@section('content')
    {{-- 01. HERO --}}
    <section class="hero-section" id="homeHero" aria-label="Ruler Of Kings">
        @if ($heroSlides->isNotEmpty())
            <div class="hero-slider" id="heroSlider">
                @foreach ($heroSlides as $index => $slide)
                    <article
                        class="hero-slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-slide="{{ $index }}"
                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    >
                        <div class="hero-slide__backdrop">
                            <img
                                class="hero-slide__image"
                                src="{{ \Illuminate\Support\Facades\Storage::url($slide->image_path) }}"
                                alt="{{ $slide->title }}"
                                @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif
                            >
                            <div class="hero-slide__overlay"></div>
                        </div>

                        <div class="hero-slide__content">
                            <div class="site-container hero-slide__inner">
                                <h1 class="hero-slide__title">{{ $slide->title }}</h1>

                                @if ($slide->description)
                                    <p class="hero-slide__description">{{ $slide->description }}</p>
                                @endif

                                <div class="hero-slide__actions">
                                    <a href="{{ url($slide->cta_primary_url) }}" class="hero-button hero-button--primary">
                                        {{ $slide->cta_primary_text }}
                                    </a>
                                    <a href="{{ url($slide->cta_secondary_url) }}" class="hero-button hero-button--secondary">
                                        {{ $slide->cta_secondary_text }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                @if ($heroSlides->count() > 1)
                    <div class="hero-slider__controls">
                        <button type="button" id="heroPrevious" aria-label="Banner sebelumnya" style="display:inline-flex;align-items:center;justify-content:center;">
                            <i data-lucide="chevron-left" style="width:20px;height:20px;"></i>
                        </button>

                        <div class="hero-slider__indicators" id="heroIndicators">
                            @foreach ($heroSlides as $index => $slide)
                                <button
                                    type="button"
                                    class="{{ $index === 0 ? 'is-active' : '' }}"
                                    data-go-to="{{ $index }}"
                                    aria-label="Ke banner {{ $index + 1 }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                ></button>
                            @endforeach
                        </div>

                        <button type="button" id="heroNext" aria-label="Banner berikutnya" style="display:inline-flex;align-items:center;justify-content:center;">
                            <i data-lucide="chevron-right" style="width:20px;height:20px;"></i>
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="hero-slider" id="heroSlider">
                <article class="hero-slide hero-slide--plain is-active">
                    <div class="hero-slide__backdrop"></div>
                    <div class="hero-slide__content">
                        <div class="site-container hero-slide__inner">
                            <h1 class="hero-slide__title">Ruler Of Kings</h1>
                            <p class="hero-slide__description">
                                Organisasi esports kompetitif yang membangun atlet lewat disiplin, integritas, dan kerja sama tim.
                            </p>
                            <div class="hero-slide__actions">
                                <a href="{{ url('/trial') }}" class="hero-button hero-button--primary">Daftar trial</a>
                                <a href="{{ url('/teams') }}" class="hero-button hero-button--secondary">Kenali tim kami</a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        @endif
    </section>

    {{-- 02. TENTANG + STATISTIK (angka dihitung dari data sebenarnya) --}}
    <section class="home-section" id="about" aria-labelledby="aboutTitle">
        <div class="home-section__inner">
            <div class="about-block">
                <span class="eyebrow" id="aboutTitle">Tentang ROK</span>
                <div>
                    <p class="about-statement" data-rok-animate="scroll-reveal">
                        Ruler Of Kings adalah organisasi esports yang membina atlet profesional,
                        <span>membangun karakter lewat disiplin, dan bersaing dengan integritas di panggung nasional.</span>
                    </p>
                    <a href="{{ url('/teams') }}" class="text-link">Kenali tim kami <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>

            <div class="stat-row" data-rok-animate="scroll-reveal">
                <div class="stat-item">
                    <span class="stat-item__value">{{ $divisions->count() }}</span>
                    <span class="stat-item__label">Divisi kompetitif</span>
                </div>
                <div class="stat-item">
                    <span class="stat-item__value">{{ $divisions->sum(fn ($d) => $d->activePlayers->count()) }}</span>
                    <span class="stat-item__label">Atlet &amp; staf aktif</span>
                </div>
                <div class="stat-item">
                    <span class="stat-item__value">{{ $achievementsCount ?? $achievements->count() }}</span>
                    <span class="stat-item__label">Prestasi tercatat</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 03. DIVISI --}}
    @include('components.home.teams-preview')

    {{-- 04. PRESTASI --}}
    @include('components.home.achievements-preview')

    {{-- 05. LEARNING & DEVELOPMENT --}}
    @include('components.home.learning-preview')

    {{-- 06. BRACKET --}}
    @include('components.home.bracket-preview')

    {{-- 07. MEMBERSHIP --}}
    @include('components.home.membership-preview')

    {{-- 08. TRIAL CTA --}}
    @include('components.home.trial-cta')

    {{-- 09. PARTNER & SPONSOR --}}
    @include('components.home.partners-preview')

@endsection
