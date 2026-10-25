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
            <span class="section-badge">ROK ACADEMY &amp; DEVELOPMENT</span>
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
                    <span class="badge-mini">FEATURED LESSON &bull; FREE PREVIEW</span>
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
                <span class="section-badge">CURRICULUM</span>
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

    {{-- 3. Membership Packages & Access Matrix --}}
    <section class="membership-section" id="membership">
        <div class="section-inner">
            <header class="section-header">
                <span class="section-badge">MEMBERSHIP TIERS</span>
                <h2 class="section-title">PAKET AKSES &amp; KEANGGOTAAN</h2>
                <p class="section-description">
                    Dapatkan akses penuh ke modul pembelajaran, fitur branding turnamen, dan dukungan komunitas eksklusif.
                </p>
            </header>

            <div class="membership-pricing-grid">
                {{-- Package 1: ROK Learning --}}
                <div class="pricing-card">
                    <div class="pricing-header">
                        <span class="pricing-badge">ACADEMY</span>
                        <h3 class="pricing-title">ROK Learning</h3>
                        <p class="pricing-sub">Fokus Pembelajaran Atlet</p>
                    </div>
                    <div class="pricing-body">
                        <ul class="pricing-features">
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Akses seluruh modul &amp; video strategi</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Unduh panduan kurikulum format PDF</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Tes evaluasi pengetahuan game (Quiz)</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Akses Discord Komunitas Diskusi</li>
                            <li><span class="cross">&times;</span> Tidak termasuk fitur turnamen branding</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="{{ route('membership.payment', ['plan' => 'learning']) }}" class="btn-rok btn-rok--outline btn-rok--full">
                            Pelajari Akses
                        </a>
                    </div>
                </div>

                {{-- Package 2: ROK Challenger --}}
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-header">
                        <span class="pricing-badge pricing-badge--gold">ORGANIZER PREMIUM</span>
                        <h3 class="pricing-title">ROK Challenger</h3>
                        <p class="pricing-sub">Tournament Branding</p>
                    </div>
                    <div class="pricing-body">
                        <ul class="pricing-features">
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Buat turnamen tanpa batas tim</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> <strong>Kustomisasi Logo Tim</strong> di Bracket</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Banner &amp; Tournament Branding resmi</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Tampilan Live Bracket publik</li>
                            <li><span class="cross">&times;</span> Tidak termasuk modul pembelajaran</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="btn-rok btn-rok--gold btn-rok--full">
                            Buka Fitur Bracket &rarr;
                        </a>
                    </div>
                </div>

                {{-- Package 3: ROK Immortal --}}
                <div class="pricing-card">
                    <div class="pricing-header">
                        <span class="pricing-badge">ALL ACCESS</span>
                        <h3 class="pricing-title">ROK Immortal</h3>
                        <p class="pricing-sub">Challenger + Learning</p>
                    </div>
                    <div class="pricing-body">
                        <ul class="pricing-features">
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> <strong>Semua fasilitas ROK Challenger</strong></li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> <strong>Semua fasilitas ROK Learning</strong></li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Prioritas konsultasi turnamen organizer</li>
                            <li><span class="check"><i data-lucide="check" style="width:13px;height:13px;"></i></span> Badge Immortal di portal komunitas</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="{{ route('membership.payment', ['plan' => 'immortal']) }}" class="btn-rok btn-rok--outline btn-rok--full">
                            Pilih Paket Immortal
                        </a>
                    </div>
                </div>
            </div>

            {{-- Matriks Hak Akses Membership --}}
            <div class="matrix-card" id="membership-matrix">
                <h3 class="matrix-title">MATRIKS HAK AKSES SISTEM</h3>
                <div class="table-responsive">
                    <table class="rok-table">
                        <thead>
                            <tr>
                                <th>Fitur Sistem</th>
                                <th>Free Organizer</th>
                                <th>ROK Learning</th>
                                <th>ROK Challenger</th>
                                <th class="col-highlight">ROK Immortal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Akses Website Publik</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                            <tr>
                                <td>Membuat Bracket Turnamen</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya (Dasar)</td>
                                <td>&times; Tidak Otomatis</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                            <tr>
                                <td>Generate &amp; Live Bracket</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td>&times; Tidak Otomatis</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                            <tr>
                                <td>Logo Tim Peserta</td>
                                <td>&times; Terkunci</td>
                                <td>&times; Terkunci</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                            <tr>
                                <td>Tournament Branding &amp; Banner</td>
                                <td>&times; Terkunci</td>
                                <td>&times; Terkunci</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                            <tr>
                                <td>Learning &amp; Development Penuh</td>
                                <td>&times; Terkunci</td>
                                <td><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                                <td>&times; Terkunci</td>
                                <td class="col-highlight"><i data-lucide="check" style="width:14px;height:14px;color:#22c55e;vertical-align:-2px;"></i> Ya</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="matrix-footnote">
                    <p><em>* Catatan: Atlet roster resmi ROK memperoleh akses penuh ke seluruh modul Learning &amp; Development tanpa kewajiban memiliki membership.</em></p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
