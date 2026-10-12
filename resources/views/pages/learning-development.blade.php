@extends('layouts.app')

@section('title', 'Learning & Development | ROK Revamp Academy')
@section('description', 'Pusat pembelajaran taktik esports, video strategi, modul kurikulum profesional, dan paket membership ROK Revamp.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">ROK ACADEMY &amp; DEVELOPMENT</span>
            <h1 class="page-title">LEARNING &amp; DEVELOPMENT</h1>
            <p class="page-subtitle">
                Tingkatkan penguasaan mikro dan strategi makro kamu melalui modul eksklusif, analisis pertandingan, dan materi kurikulum yang disusun oleh pelatih serta analis pro ROK.
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
                        <span>&#x23F1; Durasi: 14 Menit</span>
                        <span>&#x1F3AE; Mobile Legends: Bang Bang</span>
                        <span>&#x1F451; Coach Zen &amp; Analyst DataMind</span>
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
                                                <span class="lesson-play-icon">&#9658;</span>
                                                <span class="lesson-title">{{ $lesson->title }}</span>
                                            </div>
                                            <div class="lesson-item__right">
                                                @if ($lesson->is_free_preview)
                                                    <span class="badge-free">Free Preview</span>
                                                @else
                                                    <span class="badge-lock">&#x1F512; Member Only</span>
                                                @endif
                                                @if ($lesson->duration)
                                                    <span class="lesson-duration">{{ $lesson->duration }}</span>
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
                            <li><span class="check">&#10003;</span> Akses seluruh modul &amp; video strategi</li>
                            <li><span class="check">&#10003;</span> Tes evaluasi pengetahuan game (Quiz)</li>
                            <li><span class="check">&#10003;</span> Akses Discord Komunitas Diskusi</li>
                            <li><span class="cross">&times;</span> Tidak termasuk fitur turnamen branding</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#membership-matrix" class="btn-rok btn-rok--outline btn-rok--full">
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
                            <li><span class="check">&#10003;</span> Buat turnamen tanpa batas tim</li>
                            <li><span class="check">&#10003;</span> <strong>Kustomisasi Logo Tim</strong> di Bracket</li>
                            <li><span class="check">&#10003;</span> Banner &amp; Tournament Branding resmi</li>
                            <li><span class="check">&#10003;</span> Tampilan Live Bracket publik</li>
                            <li><span class="cross">&times;</span> Tidak termasuk modul pembelajaran</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="{{ url('/bracket') }}" class="btn-rok btn-rok--gold btn-rok--full">
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
                            <li><span class="check">&#10003;</span> <strong>Semua fasilitas ROK Challenger</strong></li>
                            <li><span class="check">&#10003;</span> <strong>Semua fasilitas ROK Learning</strong></li>
                            <li><span class="check">&#10003;</span> Prioritas konsultasi turnamen organizer</li>
                            <li><span class="check">&#10003;</span> Badge Immortal di portal komunitas</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#membership-matrix" class="btn-rok btn-rok--outline btn-rok--full">
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
                                <td>&#10003; Ya</td>
                                <td>&#10003; Ya</td>
                                <td>&#10003; Ya</td>
                                <td class="col-highlight">&#10003; Ya</td>
                            </tr>
                            <tr>
                                <td>Membuat Bracket Turnamen</td>
                                <td>&#10003; Ya (Dasar)</td>
                                <td>&times; Tidak Otomatis</td>
                                <td>&#10003; Ya</td>
                                <td class="col-highlight">&#10003; Ya</td>
                            </tr>
                            <tr>
                                <td>Generate &amp; Live Bracket</td>
                                <td>&#10003; Ya</td>
                                <td>&times; Tidak Otomatis</td>
                                <td>&#10003; Ya</td>
                                <td class="col-highlight">&#10003; Ya</td>
                            </tr>
                            <tr>
                                <td>Logo Tim Peserta</td>
                                <td>&times; Terkunci</td>
                                <td>&times; Terkunci</td>
                                <td>&#10003; Ya</td>
                                <td class="col-highlight">&#10003; Ya</td>
                            </tr>
                            <tr>
                                <td>Tournament Branding &amp; Banner</td>
                                <td>&times; Terkunci</td>
                                <td>&times; Terkunci</td>
                                <td>&#10003; Ya</td>
                                <td class="col-highlight">&#10003; Ya</td>
                            </tr>
                            <tr>
                                <td>Learning &amp; Development Penuh</td>
                                <td>&times; Terkunci</td>
                                <td>&#10003; Ya</td>
                                <td>&times; Terkunci</td>
                                <td class="col-highlight">&#10003; Ya</td>
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
