@extends('layouts.app')

@section('title', 'Learning & Development | ROK Revamp Academy')
@section('description', 'Pusat pembelajaran taktik esports, video strategi, modul kurikulum profesional, dan paket membership ROK Revamp.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Learning & Development' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'LEARNING & DEVELOPMENT' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Tingkatkan penguasaan mikro dan strategi makro kamu melalui modul eksklusif, analisis pertandingan, dan materi kurikulum yang disusun oleh pelatih serta analis pro ROK.' }}
            </p>
        </div>
    </section>

    {{-- 1. Showcase Video Strategy Masterclass --}}
    <section class="learning-section-top">
        <div class="section-inner">
            <div class="learning-featured-card">
                <div class="featured-video-player">
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/qgc7QE04ELs?si=F44AvjCuX8zwD3Jo"
                        title="ROK Strategy Masterclass"
                        loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="featured-video-desc">
                    <h2>MACRO TEMPO: EARLY GAME DOMINATION</h2>
                    <p>
                        Bedah detail rotasi 90 detik awal dan kontrol vision river. Trik membaca rute jungler lawan dan mengeksekusi dive turret dengan aman.
                    </p>
                    <div class="video-meta-tags">
                        <span style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="clock" style="width:13px;height:13px;"></i> Durasi: 14 Menit</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="gamepad-2" style="width:13px;height:13px;"></i> Mobile Legends: Bang Bang</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="crown" style="width:13px;height:13px;"></i> Coach Zen &amp; Analyst DataMind</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Modules & Curriculum from Database --}}
    <section class="curriculum-section">
        <div class="section-inner">
            <header class="section-header">
                <h2 class="section-title">MODUL PEMBELAJARAN STRATEGIS</h2>
                <p class="section-description">
                    Materi terstruktur yang mengasah pemahaman taktik dari fase drafting hingga perebutan objektif late game.
                </p>
            </header>

            <div class="modules-grid">
                @forelse ($modules as $module)
                    <article class="module-card">
                        <div class="module-card__banner">
                            <img src="{{ $module->thumbnail_url }}" alt="{{ $module->title }}" loading="lazy">
                            <span class="category-pill">{{ $module->category }}</span>
                        </div>
                        <div class="module-card__body">
                            <h3 class="module-card__title">{{ $module->title }}</h3>
                            <p class="module-card__desc">{{ $module->description }}</p>

                            <div class="lessons-accordion">
                                <h4>DAFTAR LESSON ({{ $module->lessons->count() }} MATERI):</h4>
                                <ul class="lesson-list">
                                    @foreach ($module->lessons as $lesson)
                                        <li class="lesson-item">
                                            <div class="lesson-item__left">
                                                @if ($lesson->isPdf())
                                                    <span class="lesson-play-icon" style="background: rgba(239, 68, 68, 0.2); color: #f87171; display:flex; align-items:center; justify-content:center;"><i data-lucide="file-text" style="width:14px;height:14px;"></i></span>
                                                @else
                                                    <span class="lesson-play-icon" style="display:flex; align-items:center; justify-content:center;"><i data-lucide="play" style="width:14px;height:14px;"></i></span>
                                                @endif
                                                <div>
                                                    <span class="lesson-title">{{ $lesson->title }}</span>
                                                    @if ($lesson->isPdf())
                                                        <span class="badge-mini" style="margin-left: 6px; font-size: 10px; padding: 1px 6px; background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);">PLAYBOOK PDF</span>
                                                    @endif
                                                    @if ($lesson->summary)
                                                        <p class="text-xs text-muted" style="margin: 2px 0 0 0; line-height: 1.4;">{{ $lesson->summary }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="lesson-item__right">
                                                @if ($lesson->is_free_preview)
                                                    @if ($lesson->isPdf() && $lesson->pdf_url)
                                                        <a href="{{ $lesson->pdf_url }}" target="_blank" class="badge-free" style="text-decoration: none; display:inline-flex; align-items:center; gap:4px;">
                                                            <i data-lucide="download" style="width:13px;height:13px;"></i> Unduh PDF
                                                        </a>
                                                    @elseif ($lesson->video_url)
                                                        <a href="{{ $lesson->video_url }}" target="_blank" class="badge-free" style="text-decoration: none; display:inline-flex; align-items:center; gap:4px;">
                                                            <i data-lucide="play" style="width:13px;height:13px;"></i> Tonton Gratis
                                                        </a>
                                                    @else
                                                        <span class="badge-free">Free Preview</span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('membership.payment', ['plan' => 'learning']) }}" class="badge-lock" style="text-decoration: none; display:inline-flex; align-items:center; gap:4px;" title="Buka akses via Membership">
                                                        <i data-lucide="lock" style="width:13px;height:13px;"></i> Member Only
                                                    </a>
                                                @endif

                                                @if ($lesson->duration)
                                                    <span class="lesson-duration">{{ $lesson->duration }}</span>
                                                @elseif ($lesson->isPdf() && $lesson->formatted_file_size)
                                                    <span class="lesson-duration">{{ $lesson->formatted_file_size }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state-box">
                        <p>Modul pembelajaran sedang dipersiapkan oleh tim kurikulum ROK.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
