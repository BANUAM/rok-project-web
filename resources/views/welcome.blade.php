@extends('layouts.app')

@section('title', 'ROK | Ruler Of Kings — Official Esports Organization')
@section('description', 'Selamat datang di Ruler Of Kings (ROK). Organisasi esports kompetitif yang berfokus pada prestasi, pengembangan atlet, trial pro-player, dan platform turnamen.')

@section('content')
    {{-- 01. HERO SECTION: Dynamic Hero Banner Slider --}}
    <section class="hero-section" id="homeHero" aria-label="ROK introduction">
        @if ($heroSlides->isNotEmpty())
            <div class="hero-slider" id="heroSlider">
                @foreach ($heroSlides as $index => $slide)
                    <article
                        class="hero-slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-slide="{{ $index }}"
                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    >
                        <div class="hero-slide__background">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($slide->image_path) }}"
                                alt="{{ $slide->title }}"
                                @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif
                            >
                        </div>

                        <div class="hero-slide__overlay"></div>

                        <div class="hero-slide__content">
                            <p class="hero-slide__eyebrow">
                                RULER OF KINGS 
                            </p>

                            <h1 class="hero-slide__title">
                                {{ $slide->title }}
                            </h1>

                            @if ($slide->description)
                                <p class="hero-slide__description">
                                    {{ $slide->description }}
                                </p>
                            @endif

                            <div class="hero-slide__actions">
                                <a
                                    href="{{ url($slide->cta_primary_url) }}"
                                    class="hero-button hero-button--primary"
                                >
                                    {{ $slide->cta_primary_text }}
                                </a>

                                <a
                                    href="{{ url($slide->cta_secondary_url) }}"
                                    class="hero-button hero-button--secondary"
                                >
                                    {{ $slide->cta_secondary_text }}
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach

                @if ($heroSlides->count() > 1)
                    <div class="hero-slider__controls">
                        <button type="button" id="heroPrevious" aria-label="Previous banner">
                            &#8592;
                        </button>

                        <div class="hero-slider__indicators" id="heroIndicators">
                            @foreach ($heroSlides as $index => $slide)
                                <button
                                    type="button"
                                    class="{{ $index === 0 ? 'is-active' : '' }}"
                                    data-go-to="{{ $index }}"
                                    aria-label="Go to banner {{ $index + 1 }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                ></button>
                            @endforeach
                        </div>

                        <button type="button" id="heroNext" aria-label="Next banner">
                            &#8594;
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="hero-empty">
                <p class="hero-empty__eyebrow">RULER OF KINGS</p>
                <h1>WELCOME TO NEW KINGDOM</h1>
                <p>ONE VISION. ONE TEAM. ONE KINGDOM.</p>

                <div class="hero-slide__actions">
                    <a href="{{ url('/trial') }}" class="hero-button hero-button--primary">
                        Join Our Trial
                    </a>
                    <a href="{{ url('/teams') }}" class="hero-button hero-button--secondary">
                        Explore Teams
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- 02. TEAMS PREVIEW SECTION --}}
    @include('components.home.teams-preview')

    {{-- 03. ACHIEVEMENTS SECTION --}}
    @include('components.home.achievements-preview')

    {{-- 04. LEARNING & DEVELOPMENT SECTION --}}
    @include('components.home.learning-preview')

    {{-- 05. BRACKET SECTION --}}
    @include('components.home.bracket-preview')

    {{-- 06. TRIAL CTA SECTION --}}
    @include('components.home.trial-cta')

    {{-- 07. PARTNERS & SPONSORS SECTION --}}
    @include('components.home.partners-preview')

@endsection
