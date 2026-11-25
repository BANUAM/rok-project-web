@extends('layouts.app')

@section('title', 'Learning & Development | ROK Revamp Academy')
@section('description', 'Portal LMS ROK Academy: Akses kurikulum strategi, playbook turnamen, slide masterclass taktik, dan ujian kompetensi pro player.')

@section('content')
<div class="page-container lms-wrapper">
    {{-- PAGE HERO --}}
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Learning & Development' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'LEARNING &amp; DEVELOPMENT' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Portal Pembelajaran Taktik Esports & LMS Resmi ROK. Akses modul kurikulum, analisis replay, dan sertifikasi pro player.' }}
            </p>
        </div>
    </section>

    {{-- VIEW SWITCHER TABS --}}
    <div class="section-inner" style="padding-top: 24px; padding-bottom: 0;">
        <div class="lms-tabs-bar">
            <button type="button" class="lms-tab-btn is-active" id="btn-tab-lobby" onclick="switchLmsTab('lobby')">
                <i data-lucide="shield-check" style="width:16px;height:16px;"></i>
                Portal Masuk LMS
            </button>
            <button type="button" class="lms-tab-btn" id="btn-tab-player" onclick="switchLmsTab('player')">
                <i data-lucide="graduation-cap" style="width:16px;height:16px;"></i>
                Ruang Belajar &amp; LMS Player
            </button>
        </div>
    </div>

    {{-- ========================================================
         VIEW 1: LMS LOBBY (NeuronAcademy style from screenshot)
         ======================================================== --}}
    <section id="lms-view-lobby" class="lms-lobby-backdrop">
        <div class="lms-lobby-card">
            {{-- Left Column: Brand & Login Form --}}
            <div class="lms-lobby-left">
                <div class="lms-brand-header">
                    <img src="/img/rok-rhino-head.png" alt="ROK Academy" class="lms-brand-logo-img" onerror="this.src='/img/rok-logo.png'">
                    <h2 class="lms-brand-title">ROK<span>Academy</span></h2>
                </div>

                <form onsubmit="event.preventDefault(); switchLmsTab('player');">
                    <div class="lms-input-group">
                        <label for="lms-username">Email / Nama Pengguna</label>
                        <input
                            type="text"
                            id="lms-username"
                            class="lms-input-text"
                            value="{{ Auth::user()?->email ?? 'banuariefmuzaki@gmail.com' }}"
                            placeholder="nama@email.com"
                        >
                    </div>

                    <div class="lms-input-group">
                        <label for="lms-password">Kata Sandi</label>
                        <input
                            type="password"
                            id="lms-password"
                            class="lms-input-text"
                            value="secretpassword"
                            placeholder="••••••••••••"
                        >
                    </div>

                    <button type="submit" class="btn-lms-login">
                        Masuk ke LMS
                    </button>

                    <a href="#" onclick="alert('Silakan hubungi administrator ROK untuk pemulihan kata sandi.'); return false;" class="lms-forgot-link">
                        Lupa kata sandi?
                    </a>
                </form>
            </div>

            {{-- Right Column: Membership Verification (Replaces SSO) & Utilities --}}
            <div class="lms-lobby-right">
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Verifikasi Keanggotaan LMS
                    </div>

                    <div class="lms-access-status-card">
                        @if ($hasLearningAccess)
                            <div class="lms-access-badge lms-access-badge--active">
                                <i data-lucide="check-circle" style="width:13px;height:13px;"></i>
                                Akses Aktif ({{ strtoupper($userTier) }})
                            </div>
                            <p style="font-size: 13px; line-height: 1.5; margin: 0 0 14px 0; color: #475569;">
                                Selamat datang <strong>{{ $userName }}</strong>! Akun Anda memiliki izin penuh untuk membuka kurikulum taktik, slide SCORM interaktif, dan ujian evaluasi LMS.
                            </p>
                            <button type="button" class="btn-lms-action btn-lms-action--gold" onclick="switchLmsTab('player')">
                                <i data-lucide="external-link" style="width:15px;height:15px;"></i>
                                Buka LMS ROK Sekarang &rarr;
                            </button>
                        @else
                            <div class="lms-access-badge lms-access-badge--locked">
                                <i data-lucide="lock" style="width:13px;height:13px;"></i>
                                Akses Terbatas: Belum Terdaftar
                            </div>
                            <p style="font-size: 13px; line-height: 1.5; margin: 0 0 12px 0; color: #64748b;">
                                Modul taktik lengkap dikhususkan bagi pemegang paket <strong>ROK Learning</strong> atau <strong>ROK Immortal</strong>.
                            </p>
                            <a href="{{ route('membership.payment', ['plan' => 'learning']) }}" class="btn-lms-action btn-lms-action--gold">
                                <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                                Dapatkan Akses ROK Learning
                            </a>
                            <button type="button" class="btn-lms-action btn-lms-action--outline" onclick="switchLmsTab('player')">
                                <i data-lucide="eye" style="width:15px;height:15px;"></i>
                                Mode Tamu: Coba Demo LMS ROK
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Language Selector & Cookie Notice --}}
                <div class="lms-lobby-meta-bar">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <i data-lucide="globe" style="width:14px;height:14px;"></i>
                        <select style="border:none; background:transparent; font-size:12px; font-weight:600; color:inherit; cursor:pointer; outline:none;">
                            <option>Bahasa Indonesia (id)</option>
                            <option>English (en)</option>
                        </select>
                    </div>
                    <div style="display:flex; align-items:center; gap:4px; cursor:pointer;" title="Pemberitahuan kebijakan kuki platform">
                        <i data-lucide="help-circle" style="width:13px;height:13px;"></i>
                        <span>Pemberitahuan kuki</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Disclaimer Bar from screenshot --}}
        <div class="lms-disclaimer-footer">
            Jika Anda terus menjelajahi situs web ini, Anda menyetujui kebijakan kami:
            <a href="#">Kebijakan Privasi</a>, <a href="#">Syarat dan Ketentuan</a> &mdash;
            <span style="color:#f59e0b; cursor:pointer; font-weight:700;" onclick="switchLmsTab('player')">Lanjutkan</span>
        </div>
    </section>

    {{-- ========================================================
         VIEW 2: LMS INTERACTIVE LEARNING ENVIRONMENT (from video)
         ======================================================== --}}
    <section id="lms-view-player" style="display:none; padding: 20px 16px 60px 16px;">
        <div class="lms-app-view">
            {{-- 1. LMS Top App Bar --}}
            <div class="lms-top-bar">
                <div style="display:flex; align-items:center; gap:18px;">
                    <a href="#player" class="lms-logo-brand">
                        <img src="/img/rok-rhino-head.png" alt="ROK" style="width:28px;height:28px;object-fit:contain;" onerror="this.src='/img/rok-logo.png'">
                        <span>ROK Learning</span>
                    </a>
                    <nav class="lms-nav-links">
                        <a href="#kursus" class="lms-nav-link is-active">Kursus</a>
                        <a href="#nilai" class="lms-nav-link" onclick="alert('Nilai Rata-Rata Modul Anda: 94 / 100 (Predikat: Pro Ready)')">Nilai</a>
                        <a href="#kompetensi" class="lms-nav-link" onclick="alert('Kompetensi Utama: Makro Rotasi (95%), Vision River (92%), Retake Setup (90%)')">Kompetensi</a>
                    </nav>
                </div>
                <div class="lms-top-actions">
                    <button type="button" class="btn-lms-action btn-lms-action--outline" style="padding:6px 12px; font-size:12px; margin-bottom:0;" onclick="toggleLmsDrawer()">
                        <i data-lucide="menu" style="width:14px;height:14px;"></i>
                        <span>Menu Silabus</span>
                    </button>
                    <button type="button" class="btn-lms-action btn-lms-action--outline" style="padding:6px 12px; font-size:12px; margin-bottom:0;" onclick="switchLmsTab('lobby')">
                        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                        <span>Lobby LMS</span>
                    </button>
                </div>
            </div>

            {{-- 2. Breadcrumbs --}}
            <div class="lms-breadcrumbs">
                <a href="#" onclick="switchLmsTab('lobby')">Beranda</a>
                <span>/</span>
                <a href="#kursus">Kursus Saya</a>
                <span>/</span>
                <a href="#kursus">ROK Academy</a>
                <span>/</span>
                <span id="breadcrumb-active-topic" style="font-weight:700; color:#1e293b;">SMT-2026: Sistem Manajemen Taktik</span>
            </div>

            {{-- 3. Course Header Banner & Progress --}}
            <div class="lms-course-header-banner">
                <div style="flex: 1 1 500px;">
                    <h2 class="lms-course-info-h1">
                        <i data-lucide="book-open" style="width:22px;height:22px;color:#d97706;"></i>
                        Sistem Manajemen Taktik &amp; Analisis Replay Turnamen ROK (SMT)
                    </h2>
                    <p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
                        Pelatihan standar Pro Player ROK: Penguasaan Makro Rotasi, Vision River Control, Prosedur Retake Tertekan, dan SOP Komunikasi Turnamen.
                    </p>
                </div>
                <div class="lms-course-progress-box">
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:700; color:#475569;">
                        <span>Progres Kelulusan</span>
                        <span id="lms-progress-text">3 / 8 Aktivitas (38%)</span>
                    </div>
                    <div class="lms-progress-bar-track">
                        <div id="lms-progress-fill" class="lms-progress-bar-fill" style="width: 38%;"></div>
                    </div>
                </div>
            </div>

            {{-- 4. Interactive Slide Presentation Player (AlfaLearning SCORM Style) --}}
            <div style="padding: 20px 24px 0 24px;">
                <div class="lms-slide-player-card">
                    {{-- Activity Meta Box --}}
                    <div class="lms-player-meta-box">
                        <div>
                            <span class="badge-mini" style="background:rgba(217,119,6,0.15); color:#d97706; border:1px solid rgba(217,119,6,0.3); font-size:11px; padding:2px 8px;">
                                MODUL INTERAKTIF SCORM
                            </span>
                            <h3 id="player-active-title" style="margin:6px 0 2px 0; font-size:18px; font-weight:800;">
                                MACRO TEMPO: EARLY GAME DOMINATION
                            </h3>
                            <div style="font-size:12px; color:#64748b; display:flex; gap:14px; flex-wrap:wrap; margin-top:4px;">
                                <span><i data-lucide="repeat" style="width:12px;height:12px;display:inline;"></i> Upaya: Tidak terbatas</span>
                                <span><i data-lucide="award" style="width:12px;height:12px;display:inline;"></i> Metode Penilaian: Nilai Tertinggi</span>
                                <span><i data-lucide="check-circle" style="width:12px;height:12px;display:inline;"></i> Nilai Dilaporkan: 100 / 100</span>
                            </div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" onclick="goToSlide(1)">
                                <i data-lucide="rotate-ccw" style="width:14px;height:14px;"></i> Pratinjau Ulang
                            </button>
                            <button type="button" class="btn-lms-action btn-lms-action--gold" style="margin:0; padding:8px 16px; font-size:12px;" onclick="toggleFullscreenPlayer()">
                                <i data-lucide="maximize" style="width:14px;height:14px;"></i> Layar Penuh
                            </button>
                        </div>
                    </div>

                    {{-- 16:9 Interactive Slide Canvas with 12 Slides --}}
                    <div id="lms-slide-canvas" class="lms-slide-canvas">
                        {{-- Slide Header Tag --}}
                        <div class="lms-slide-header-tag">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span class="badge-mini" style="background:#d97706; color:#ffffff; font-weight:800; font-size:11px; padding:2px 8px;">
                                    ROK TACTICAL SMT
                                </span>
                                <span id="slide-stage-indicator" style="font-size:12px; font-weight:700; color:#64748b;">
                                    Tahap 1 dari 12
                                </span>
                            </div>
                            <div class="lms-slide-dots-container" id="slide-dots-bar">
                                @for ($i = 1; $i <= 12; $i++)
                                    <span class="lms-slide-dot {{ $i === 1 ? 'is-active' : '' }}" onclick="goToSlide({{ $i }})" title="Slide {{ $i }}"></span>
                                @endfor
                            </div>
                        </div>

                        {{-- Slide Body Container --}}
                        <div class="lms-slide-body" id="slide-body-content">
                            {{-- Slide contents dynamically toggled via JS below --}}
                        </div>

                        {{-- Slide Controls Bar --}}
                        <div class="lms-slide-controls-bar">
                            <button type="button" class="lms-slide-step-btn" id="btn-slide-prev" onclick="prevSlide()" disabled>
                                &larr; Sebelumnya
                            </button>
                            <div style="display:flex; align-items:center; gap:12px; font-size:13px; font-weight:700;">
                                <span id="slide-counter-text">Slide 1 / 12</span>
                                <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:4px 10px; font-size:11px;" onclick="toggleLmsDrawer()">
                                    <i data-lucide="list" style="width:12px;height:12px;"></i> Indeks Modul
                                </button>
                            </div>
                            <button type="button" class="lms-slide-step-btn" id="btn-slide-next" onclick="nextSlide()">
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>

                    {{-- Activity Jump Navigation Bar (from video) --}}
                    <div class="lms-activity-jump-nav">
                        <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" onclick="changeActivity('prev')">
                            &larr; Aktivitas Sebelumnya
                        </button>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:12px; font-weight:600; color:#64748b;">Lompat ke:</span>
                            <select id="activity-jump-select" onchange="jumpToActivity(this.value)" style="padding:6px 12px; font-size:12px; border-radius:6px; border:1px solid #cbd5e1; background:transparent; color:inherit; font-weight:600;">
                                <option value="1">1. Pengenalan Sistem Manajemen Taktik ROK</option>
                                <option value="2">2. Buku Panduan Makro &amp; Rotasi (Playbook PDF)</option>
                                <option value="3" selected>3. MACRO TEMPO: EARLY GAME DOMINATION</option>
                                <option value="4">4. Bedah Rotasi 90 Detik Awal &amp; River Control</option>
                                <option value="5">5. Prosedur Retake Setup &amp; Anti-Flank</option>
                                <option value="6">6. Ujian Taktik Makro &amp; Rotasi 90 Detik</option>
                                <option value="7">7. Tugas Analisis Replay Turnamen</option>
                                <option value="8">8. Sertifikat Resmi ROK Tactical Pro</option>
                            </select>
                        </div>
                        <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" onclick="changeActivity('next')">
                            Aktivitas Selanjutnya &rarr;
                        </button>
                    </div>
                </div>
            </div>

            {{-- 5. Course Syllabus Sections Accordion --}}
            <div class="lms-curriculum-container">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="margin:0; font-size:18px; font-weight:800;">Silabus Lengkap Kursus SMT</h3>
                    <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:6px 12px; font-size:12px;" onclick="toggleAllSections()">
                        <span id="btn-toggle-all-text">Ciutkan Semua Bagian</span>
                    </button>
                </div>

                {{-- SECTION 1: PANDUAN & PRA-SYARAT (OVERVIEW) --}}
                <div class="lms-section-block" id="sec-1">
                    <button type="button" class="lms-section-header-btn" onclick="toggleSection('sec-1')">
                        <span>Bagian 1: Panduan &amp; Pra-Syarat (Overview)</span>
                        <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                    </button>
                    <ul class="lms-activity-list">
                        <li class="lms-activity-item" onclick="jumpToActivity(1)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon"><i data-lucide="file-text" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Pengenalan Sistem Manajemen Taktik ROK</div>
                                    <p class="lms-activity-desc">Visi kurikulum, pembagian role analitis, dan standardisasi callout map.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--done"><i data-lucide="check" style="width:12px;height:12px;"></i> Selesai</span>
                        </li>
                        <li class="lms-activity-item" onclick="jumpToActivity(2)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon" style="background:rgba(239,68,68,0.15); color:#ef4444;"><i data-lucide="file" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Buku Panduan Makro &amp; Rotasi (Playbook PDF)</div>
                                    <p class="lms-activity-desc">Dokumen pegangan resmi 32 halaman untuk analisa setup turnamen.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--done"><i data-lucide="check" style="width:12px;height:12px;"></i> Selesai</span>
                        </li>
                    </ul>
                </div>

                {{-- SECTION 2: MODUL PEMBELAJARAN STRATEGIS (MATERI INTI) --}}
                <div class="lms-section-block" id="sec-2">
                    <button type="button" class="lms-section-header-btn" onclick="toggleSection('sec-2')">
                        <span>Bagian 2: MODUL PEMBELAJARAN STRATEGIS</span>
                        <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                    </button>
                    <ul class="lms-activity-list">
                        {{-- Featured Masterclass Item --}}
                        <li class="lms-activity-item is-selected" onclick="jumpToActivity(3)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon lms-activity-icon--scorm"><i data-lucide="play-circle" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">MACRO TEMPO: EARLY GAME DOMINATION</div>
                                    <p class="lms-activity-desc">Paket interaktif SCORM: Rotasi 90 detik, pincer setup, dan eksekusi turret dive.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--done"><i data-lucide="check" style="width:12px;height:12px;"></i> Sedang Aktif</span>
                        </li>

                        {{-- Database Modules & Lessons --}}
                        @foreach ($modules as $module)
                            @foreach ($module->lessons as $lesson)
                                <li class="lms-activity-item" onclick="loadLessonFromDb('{{ addslashes($lesson->title) }}', '{{ addslashes($lesson->summary ?? $module->description) }}')">
                                    <div class="lms-activity-left">
                                        <div class="lms-activity-icon {{ $lesson->isPdf() ? '' : 'lms-activity-icon--scorm' }}" style="{{ $lesson->isPdf() ? 'background:rgba(239,68,68,0.15);color:#ef4444;' : '' }}">
                                            <i data-lucide="{{ $lesson->isPdf() ? 'file-text' : 'video' }}" style="width:16px;height:16px;"></i>
                                        </div>
                                        <div>
                                            <div class="lms-activity-title" style="display:flex; align-items:center; gap:6px;">
                                                <span>{{ $lesson->title }}</span>
                                                @if ($lesson->isPdf())
                                                    <span class="badge-mini" style="font-size:10px; padding:1px 6px; background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.3);">PLAYBOOK PDF</span>
                                                @endif
                                            </div>
                                            <p class="lms-activity-desc">{{ $lesson->summary ?? $module->title }}</p>
                                        </div>
                                    </div>
                                    @if ($lesson->isPdf() && $lesson->pdf_url)
                                        <a href="{{ $lesson->pdf_url }}" target="_blank" class="lms-status-pill lms-status-pill--done" style="text-decoration:none;" onclick="event.stopPropagation();">
                                            <i data-lucide="download" style="width:12px;height:12px;"></i> Unduh PDF
                                        </a>
                                    @elseif ($lesson->is_free_preview)
                                        <span class="lms-status-pill lms-status-pill--todo">Preview Terbuka</span>
                                    @else
                                        <span class="lms-status-pill lms-status-pill--todo">Member Only</span>
                                    @endif
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>

                {{-- SECTION 3: EVALUASI & UJIAN KOMPETENSI --}}
                <div class="lms-section-block" id="sec-3">
                    <button type="button" class="lms-section-header-btn" onclick="toggleSection('sec-3')">
                        <span>Bagian 3: Evaluasi &amp; Ujian Kompetensi</span>
                        <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                    </button>
                    <ul class="lms-activity-list">
                        <li class="lms-activity-item" onclick="jumpToActivity(6)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon lms-activity-icon--quiz"><i data-lucide="help-circle" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Ujian Taktik Makro &amp; Rotasi 90 Detik</div>
                                    <p class="lms-activity-desc">15 Pertanyaan kasus skenario turnamen. Batas kelulusan skor 80.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--todo">Belum Dimulai</span>
                        </li>
                        <li class="lms-activity-item" onclick="jumpToActivity(7)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon" style="background:rgba(168,85,247,0.12); color:#a855f7;"><i data-lucide="upload" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Tugas Analisis Replay Turnamen</div>
                                    <p class="lms-activity-desc">Kirim analisis replay format MP4 / catatan heatmap tim kamu.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--todo">Batas: 7 Hari</span>
                        </li>
                    </ul>
                </div>

                {{-- SECTION 4: REAKTIF & SERTIFIKASI --}}
                <div class="lms-section-block" id="sec-4">
                    <button type="button" class="lms-section-header-btn" onclick="toggleSection('sec-4')">
                        <span>Bagian 4: Reaktif &amp; Sertifikasi</span>
                        <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                    </button>
                    <ul class="lms-activity-list">
                        <li class="lms-activity-item" onclick="alert('Kuesioner Evaluasi: Berikan ulasan mentor untuk perbaikan modul mendatang.')">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon lms-activity-icon--survey"><i data-lucide="message-square" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Kuesioner Evaluasi Materi &amp; Mentor ROK</div>
                                    <p class="lms-activity-desc">Umpan balik wajib sebelum penerbitan sertifikat digital.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--todo">Tersedia</span>
                        </li>
                        <li class="lms-activity-item" onclick="jumpToActivity(8)">
                            <div class="lms-activity-left">
                                <div class="lms-activity-icon" style="background:rgba(234,179,8,0.15); color:#eab308;"><i data-lucide="award" style="width:16px;height:16px;"></i></div>
                                <div>
                                    <div class="lms-activity-title">Sertifikat Resmi ROK Tactical Pro Player</div>
                                    <p class="lms-activity-desc">Sertifikasi terverifikasi blockchain ROK untuk portofolio pendaftaran tim pro.</p>
                                </div>
                            </div>
                            <span class="lms-status-pill lms-status-pill--todo"><i data-lucide="lock" style="width:12px;height:12px;"></i> Terkunci</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================
         CURRICULUM SIDEBAR DRAWER (as shown in AlfaLearning video)
         ======================================================== --}}
    <div id="lms-drawer-backdrop" class="lms-drawer-backdrop" style="display:none;" onclick="toggleLmsDrawer()"></div>
    <aside id="lms-drawer" class="lms-drawer" style="transform: translateX(-100%);">
        <div class="lms-drawer-header">
            <div style="display:flex; align-items:center; gap:8px;">
                <img src="/img/rok-rhino-head.png" alt="ROK" style="width:24px;height:24px;object-fit:contain;" onerror="this.src='/img/rok-logo.png'">
                <h4 style="margin:0; font-size:15px; font-weight:800;">Silabus Kursus SMT</h4>
            </div>
            <button type="button" class="lms-drawer-close-btn" onclick="toggleLmsDrawer()">&times;</button>
        </div>
        <ul class="lms-drawer-tree">
            <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:8px 14px 4px;">Bagian 1: Overview</li>
            <li class="lms-drawer-item" onclick="jumpToActivity(1); toggleLmsDrawer();">
                <i data-lucide="check-circle" style="width:14px;height:14px;color:#16a34a;"></i>
                <span>1. Pengenalan SMT ROK</span>
            </li>
            <li class="lms-drawer-item" onclick="jumpToActivity(2); toggleLmsDrawer();">
                <i data-lucide="check-circle" style="width:14px;height:14px;color:#16a34a;"></i>
                <span>2. Buku Panduan PDF</span>
            </li>

            <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 2: Materi Inti</li>
            <li class="lms-drawer-item is-active" onclick="jumpToActivity(3); toggleLmsDrawer();">
                <i data-lucide="play-circle" style="width:14px;height:14px;color:#d97706;"></i>
                <span>3. Macro Tempo SCORM</span>
            </li>
            <li class="lms-drawer-item" onclick="jumpToActivity(4); toggleLmsDrawer();">
                <i data-lucide="circle" style="width:14px;height:14px;color:#94a3b8;"></i>
                <span>4. Rotasi 90 Detik</span>
            </li>
            <li class="lms-drawer-item" onclick="jumpToActivity(5); toggleLmsDrawer();">
                <i data-lucide="circle" style="width:14px;height:14px;color:#94a3b8;"></i>
                <span>5. Retake Setup</span>
            </li>

            <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 3: Ujian</li>
            <li class="lms-drawer-item" onclick="jumpToActivity(6); toggleLmsDrawer();">
                <i data-lucide="help-circle" style="width:14px;height:14px;color:#f43f5e;"></i>
                <span>6. Ujian Taktik Makro</span>
            </li>
            <li class="lms-drawer-item" onclick="jumpToActivity(7); toggleLmsDrawer();">
                <i data-lucide="upload" style="width:14px;height:14px;color:#a855f7;"></i>
                <span>7. Tugas Analisis Replay</span>
            </li>

            <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 4: Kelulusan</li>
            <li class="lms-drawer-item" onclick="jumpToActivity(8); toggleLmsDrawer();">
                <i data-lucide="award" style="width:14px;height:14px;color:#eab308;"></i>
                <span>8. Sertifikat Digital ROK</span>
            </li>
        </ul>
    </aside>
</div>

{{-- CLIENT-SIDE JAVASCRIPT FOR LMS INTERACTION --}}
<script>
    // 12 Interactive Slides Definitions
    const lmsSlides = [
        {
            num: 1,
            title: "ROK TACTICAL SMT 2026 - Macro Tempo & Early Game Domination",
            html: `
                <div style="text-align:center; padding: 20px 0;">
                    <span class="badge-mini" style="background:rgba(217,119,6,0.15); color:#d97706; border:1px solid rgba(217,119,6,0.3); font-size:12px; padding:3px 10px;">KURIKULUM PRO PLAYER</span>
                    <h2 style="font-size:28px; font-weight:800; margin:14px 0; color:#d97706;">MACRO TEMPO: EARLY GAME DOMINATION</h2>
                    <p style="max-width:620px; margin:0 auto 20px auto; color:#64748b; font-size:15px; line-height:1.6;">
                        Selamat datang di modul interaktif SCORM ROK Academy. Modul ini membongkar protokol baku tim juara: rotasi 90 detik awal, kontrol river vision, dan eksekusi objektif Turtle pertama.
                    </p>
                    <div style="display:inline-flex; gap:16px; margin-top:10px;">
                        <button type="button" class="btn-lms-action btn-lms-action--gold" style="width:auto; padding:10px 24px;" onclick="nextSlide()">
                            Mulai Modul Pembelajaran &rarr;
                        </button>
                    </div>
                </div>
            `
        },
        {
            num: 2,
            title: "Fundamental: Rotasi 90 Detik Pertama & Jalur Netral",
            html: `
                <h2>1. Rotasi 90 Detik Pertama</h2>
                <p>Dalam 90 detik pertama, penentuan tempo ditentukan oleh efisiensi rute clearing jungler dan keberhasilan support/roamer membuka area semak (bush) river.</p>
                <div class="lms-slide-grid">
                    <div class="lms-slide-card">
                        <h4><i data-lucide="compass" style="width:16px;height:16px;color:#d97706;"></i> Rute Blue Buff Start</h4>
                        <p>Cocok untuk hero yang membutuhkan cooldown cepat. Prioritaskan jalur Lithowanderer pada detik 00:45 untuk mengamankan keunggulan level 4 sebelum perebutan Turtle.</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4><i data-lucide="flame" style="width:16px;height:16px;color:#ef4444;"></i> Rute Red Buff Aggressive</h4>
                        <p>Berfokus pada isolasi Gold Lane lawan sebelum menit ke-2. Memaksa roamer lawan terikat di satu sisi map dan membuka celah invasi jungle musuh.</p>
                    </div>
                </div>
            `
        },
        {
            num: 3,
            title: "Vision River & Penguasaan Ambush Bush",
            html: `
                <h2>2. River Vision & Bush Control</h2>
                <p>Vision bukan hanya meletakkan ward atau hero di semak, melainkan memotong jalur rotasi lawan dan mencegah flanking pada lane prioritas.</p>
                <div class="lms-slide-grid">
                    <div class="lms-slide-card">
                        <h4><i data-lucide="eye" style="width:16px;height:16px;color:#0284c7;"></i> Mid River High Bush</h4>
                        <p>Zona krusial pemantau rotasi Midlaner dan Roamer lawan. Jangan pernah melintas tanpa skill pengecekan semak (skill zoning).</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4><i data-lucide="shield" style="width:16px;height:16px;color:#16a34a;"></i> Defensive River Chokepoint</h4>
                        <p>Titik aman mundur ketika jungler lawan memilih invasi counter-jungle. Berikan sinyal pincer kepada Exp Laner untuk segera cut wave.</p>
                    </div>
                </div>
            `
        },
        {
            num: 4,
            title: "Turtle Objective Setup & Formasi Pincer",
            html: `
                <h2>3. Setup 30 Detik Pra-Turtle</h2>
                <p>Perebutan Turtle tidak dimulai saat monster muncul di menit ke-2, melainkan 30 detik sebelumnya (menit 01:30).</p>
                <div class="lms-slide-card" style="margin-top:12px;">
                    <h4><i data-lucide="crosshair" style="width:16px;height:16px;color:#d97706;"></i> Formasi Pincer ROK Taktikal</h4>
                    <p>1. Exp Laner menahan wave dan memblokir roamer lawan di semak belakang Turtle Pit.<br>
                    2. Roamer berdiri di sisi river entrance untuk menembak info posisi jungler lawan.<br>
                    3. Jungler dan Midlaner melakukan burst damage cepat dengan koordinasi Retribution pada HP Turtle 1.800.</p>
                </div>
            `
        },
        {
            num: 5,
            title: "Komunikasi Tim & Protokol Shoutcalling",
            html: `
                <h2>4. Protokol Shoutcalling Standar ROK</h2>
                <p>Komunikasi turnamen harus terbebas dari noise (reaksi emosional atau keluhan). Hanya ada 3 jenis komunikasi resmi:</p>
                <table class="lms-table-compact">
                    <thead>
                        <tr>
                            <th>Tipe Call</th>
                            <th>Tujuan</th>
                            <th>Contoh Callout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Target Call</strong></td>
                            <td>Fokus target utama teamfight</td>
                            <td><em>"Flicker Beatrix off, all-in Beatrix!"</em></td>
                        </tr>
                        <tr>
                            <td><strong>Resource Call</strong></td>
                            <td>Status cooldown spell &amp; ultimate lawan</td>
                            <td><em>"Mage no purify, retribution cooldown 5 detik"</em></td>
                        </tr>
                        <tr>
                            <td><strong>Tempo Call</strong></td>
                            <td>Perintah mundur atau reset objektif</td>
                            <td><em>"Disengage, reset river, jangan overcommit!"</em></td>
                        </tr>
                    </tbody>
                </table>
            `
        },
        {
            num: 6,
            title: "4 Fase Utama Pertandingan Esports",
            html: `
                <h2>5. Matriks 4 Fase Pertandingan</h2>
                <div class="lms-slide-grid">
                    <div class="lms-slide-card">
                        <h4>Fase 1: Early (00:00 - 05:00)</h4>
                        <p>Prioritas: Level 4, Gold plating turret luar, dan 2x Turtle.</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4>Fase 2: Mid (05:00 - 12:00)</h4>
                        <p>Prioritas: Runtuhkan outer turret, trap semak jungle lawan, dan kontrol Lord 1.</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4>Fase 3: Late (12:00 - 18:00)</h4>
                        <p>Prioritas: Evolved Lord setup, siege high-ground 3 lane serentak.</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4>Fase 4: End Game (18:00+)</h4>
                        <p>Prioritas: Enhanced Lord, split push bait, dan eliminasi instant carry lawan.</p>
                    </div>
                </div>
            `
        },
        {
            num: 7,
            title: "Distribusi Resource & Prioritas Farming",
            html: `
                <h2>6. Alokasi Resource &amp; Prioritas Gold</h2>
                <p>Keseimbangan ekonomi tim menentukan kesiapan teamfight besar di late-game.</p>
                <table class="lms-table-compact">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Prioritas Gold (1-5)</th>
                            <th>Tugas Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gold Laner</td>
                            <td>Prioritas 1 (Maksimal)</td>
                            <td>Scaling damage inti, full clearing safe wave</td>
                        </tr>
                        <tr>
                            <td>Jungler</td>
                            <td>Prioritas 2</td>
                            <td>Objektif monster netral &amp; burst pick-off</td>
                        </tr>
                        <tr>
                            <td>Midlaner</td>
                            <td>Prioritas 3</td>
                            <td>Wave clearing cepat &amp; rotasi bantuan side</td>
                        </tr>
                        <tr>
                            <td>Exp Laner</td>
                            <td>Prioritas 4</td>
                            <td>Zoning pertahanan &amp; penahan frontline</td>
                        </tr>
                        <tr>
                            <td>Roamer</td>
                            <td>Prioritas 5 (Support Item)</td>
                            <td>Buka map, zoning informasi, dan penahan spell</td>
                        </tr>
                    </tbody>
                </table>
            `
        },
        {
            num: 8,
            title: "Prosedur Retake Setup & Anti-Flank Defense",
            html: `
                <h2>7. Prosedur Retake Setup saat Tertekan</h2>
                <p>Ketika tim kehilangan seluruh outer turret dan terkurung di base, lakukan SOP Retake:</p>
                <div class="lms-slide-card" style="margin-top:12px;">
                    <h4><i data-lucide="shield-alert" style="width:16px;height:16px;color:#ef4444;"></i> Langkah Pertahanan Base ROK</h4>
                    <p>1. Jangan keluar sembarangan sebelum wave super minion dibersihkan di dalam high ground.<br>
                    2. Simpan skill crowd-control area (CC) untuk memotong inisiasi Lord musuh.<br>
                    3. Siapkan satu flanker (Exp/Assassin) bersembunyi di semak base luar untuk menyergap marksman lawan saat mereka fokus menyerang inhibitor turret.</p>
                </div>
            `
        },
        {
            num: 9,
            title: "Metodologi Bedah Replay & Heatmap Analysis",
            html: `
                <h2>8. Bedah Replay via Data & Heatmap</h2>
                <p>Kemenangan bukan sekadar kebetulan mekanik, melainkan eksekusi kalkulatif.</p>
                <div class="lms-slide-grid">
                    <div class="lms-slide-card">
                        <h4><i data-lucide="pause-circle" style="width:16px;height:16px;color:#0284c7;"></i> Freeze-Frame Analisis</h4>
                        <p>Hentikan replay 5 detik sebelum teamfight terjadi. Evaluasi posisi setiap pemain, ketersediaan spell, dan sudut pelarian lawan.</p>
                    </div>
                    <div class="lms-slide-card">
                        <h4><i data-lucide="activity" style="width:16px;height:16px;color:#16a34a;"></i> Heatmap Positioning</h4>
                        <p>Lacak jalur pergerakan roamer lawan untuk mendeteksi kebiasaan rotasi yang berulang di setiap menit ganjil.</p>
                    </div>
                </div>
            `
        },
        {
            num: 10,
            title: "Stamina Fisik, Kebugaran & Mental BO5 / BO7",
            html: `
                <h2>9. Ketahanan Mental Turnamen Panjang</h2>
                <p>Dalam format Best of 5 atau Best of 7, penurunan fokus 10% di game ke-4 dapat menyebabkan kekalahan telak.</p>
                <div class="lms-slide-card" style="margin-top:12px;">
                    <h4><i data-lucide="zap" style="width:16px;height:16px;color:#d97706;"></i> SOP Jeda Game (Rest &amp; Reset)</h4>
                    <p>&bull; <strong>Hidrasi:</strong> Minum air putih hangat 200ml setiap pergantian game untuk menjaga aliran oksigen ke otak.<br>
                    &bull; <strong>Peregangan:</strong> Lakukan peregangan pergelangan tangan (wrist stretch) dan leher selama 2 menit.<br>
                    &bull; <strong>Mindset Reset:</strong> Apapun hasil game sebelumnya (menang atau kalah), masuki game baru dengan mental 0 - 0.</p>
                </div>
            `
        },
        {
            num: 11,
            title: "Mini Quiz Interaktif: Uji Pemahaman Cepat",
            html: `
                <h2>10. Uji Pemahaman Makro 90 Detik</h2>
                <p>Jawab pertanyaan taktis berikut untuk memverifikasi pemahaman modul Anda:</p>
                <div class="lms-slide-card" style="margin-top:12px;">
                    <p style="font-weight:700; color:#0f172a; margin-bottom:10px;">
                        Pertanyaan: Pada menit 01:30, di mana seharusnya posisi Roamer saat tim merencanakan setup perebutan Turtle pertama?
                    </p>
                    <div class="lms-quiz-options">
                        <button type="button" class="lms-quiz-opt" onclick="checkQuizAnswer(this, false)">
                            A. Berada di Gold Lane membantu farming wave minion
                        </button>
                        <button type="button" class="lms-quiz-opt" onclick="checkQuizAnswer(this, true)">
                            B. Di semak river/entrance jungle lawan untuk membuka vision dan memotong rotasi jungler musuh (Zoning)
                        </button>
                        <button type="button" class="lms-quiz-opt" onclick="checkQuizAnswer(this, false)">
                            C. Diam di base membeli item pertahanan
                        </button>
                    </div>
                    <div id="quiz-feedback-box" style="margin-top:12px; font-size:13px; font-weight:700; display:none;"></div>
                </div>
            `
        },
        {
            num: 12,
            title: "Rangkuman Modul & Sertifikasi SMT Selesai",
            html: `
                <div style="text-align:center; padding: 24px 0;">
                    <div style="width:64px; height:64px; border-radius:50%; background:rgba(34,197,94,0.15); color:#16a34a; display:inline-flex; align-items:center; justify-content:center; margin-bottom:14px;">
                        <i data-lucide="check-circle" style="width:36px;height:36px;"></i>
                    </div>
                    <h2 style="font-size:26px; font-weight:800; color:#16a34a; margin:0 0 10px 0;">Selamat! Modul SCORM Selesai</h2>
                    <p style="max-width:580px; margin:0 auto 20px auto; color:#64748b; font-size:14px; line-height:1.6;">
                        Anda telah menyelesaikan seluruh 12 tahap materi <strong>MACRO TEMPO: EARLY GAME DOMINATION</strong>. Nilai 100/100 telah dicatat dalam buku nilai LMS ROK.
                    </p>
                    <div style="display:inline-flex; gap:12px;">
                        <button type="button" class="btn-lms-action btn-lms-action--gold" style="width:auto; padding:10px 20px;" onclick="jumpToActivity(6)">
                            Lanjut ke Ujian Taktik Makro &rarr;
                        </button>
                        <button type="button" class="btn-lms-action btn-lms-action--outline" style="width:auto; padding:10px 18px;" onclick="goToSlide(1)">
                            Ulangi Modul
                        </button>
                    </div>
                </div>
            `
        }
    ];

    let currentSlideIndex = 1;

    function renderSlide(index) {
        if (index < 1) index = 1;
        if (index > lmsSlides.length) index = lmsSlides.length;
        currentSlideIndex = index;

        const slide = lmsSlides[index - 1];
        document.getElementById('slide-body-content').innerHTML = slide.html;
        document.getElementById('slide-counter-text').innerText = 'Slide ' + index + ' / ' + lmsSlides.length;
        document.getElementById('slide-stage-indicator').innerText = 'Tahap ' + index + ' dari ' + lmsSlides.length;

        // Update Prev / Next button state
        document.getElementById('btn-slide-prev').disabled = (index === 1);
        document.getElementById('btn-slide-next').disabled = (index === lmsSlides.length);

        // Update dots
        const dots = document.querySelectorAll('.lms-slide-dot');
        dots.forEach((dot, idx) => {
            if (idx === (index - 1)) {
                dot.classList.add('is-active');
            } else {
                dot.classList.remove('is-active');
            }
        });

        // Re-render lucide icons if library available
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    }

    function prevSlide() {
        if (currentSlideIndex > 1) {
            renderSlide(currentSlideIndex - 1);
        }
    }

    function nextSlide() {
        if (currentSlideIndex < lmsSlides.length) {
            renderSlide(currentSlideIndex + 1);
        }
    }

    function goToSlide(num) {
        renderSlide(num);
    }

    function checkQuizAnswer(btn, isCorrect) {
        const container = btn.parentElement;
        const allBtns = container.querySelectorAll('.lms-quiz-opt');
        allBtns.forEach(b => {
            b.classList.remove('is-correct', 'is-wrong');
            b.disabled = true;
        });

        const feedbackBox = document.getElementById('quiz-feedback-box');
        feedbackBox.style.display = 'block';

        if (isCorrect) {
            btn.classList.add('is-correct');
            feedbackBox.style.color = '#16a34a';
            feedbackBox.innerHTML = '✔ Jawaban Tepat! Roamer harus mengamankan vision choke point river untuk mengamankan Retribution Jungler.';
        } else {
            btn.classList.add('is-wrong');
            feedbackBox.style.color = '#ef4444';
            feedbackBox.innerHTML = '✖ Kurang Tepat. Jawaban yang benar adalah B: Zoning pintu masuk river.';
        }
    }

    function switchLmsTab(tabName) {
        const lobbyView = document.getElementById('lms-view-lobby');
        const playerView = document.getElementById('lms-view-player');
        const btnLobby = document.getElementById('btn-tab-lobby');
        const btnPlayer = document.getElementById('btn-tab-player');

        if (tabName === 'player') {
            lobbyView.style.display = 'none';
            playerView.style.display = 'block';
            btnLobby.classList.remove('is-active');
            btnPlayer.classList.add('is-active');
            renderSlide(currentSlideIndex);
            window.scrollTo({ top: playerView.offsetTop - 80, behavior: 'smooth' });
        } else {
            lobbyView.style.display = 'flex';
            playerView.style.display = 'none';
            btnLobby.classList.add('is-active');
            btnPlayer.classList.remove('is-active');
            window.scrollTo({ top: lobbyView.offsetTop - 80, behavior: 'smooth' });
        }
    }

    function toggleLmsDrawer() {
        const drawer = document.getElementById('lms-drawer');
        const backdrop = document.getElementById('lms-drawer-backdrop');
        const isOpen = drawer.style.transform === 'translateX(0px)';

        if (isOpen) {
            drawer.style.transform = 'translateX(-100%)';
            backdrop.style.display = 'none';
        } else {
            drawer.style.transform = 'translateX(0px)';
            backdrop.style.display = 'block';
        }
    }

    function toggleFullscreenPlayer() {
        const canvas = document.getElementById('lms-slide-canvas');
        canvas.classList.toggle('is-fullscreen');
    }

    function toggleSection(secId) {
        const sec = document.getElementById(secId);
        const list = sec.querySelector('.lms-activity-list');
        const icon = sec.querySelector('.lms-section-header-btn i');
        if (list.style.display === 'none') {
            list.style.display = 'block';
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            list.style.display = 'none';
            if (icon) icon.style.transform = 'rotate(-90deg)';
        }
    }

    let allSectionsExpanded = true;
    function toggleAllSections() {
        allSectionsExpanded = !allSectionsExpanded;
        const lists = document.querySelectorAll('.lms-activity-list');
        const btnText = document.getElementById('btn-toggle-all-text');
        lists.forEach(l => {
            l.style.display = allSectionsExpanded ? 'block' : 'none';
        });
        btnText.innerText = allSectionsExpanded ? 'Ciutkan Semua Bagian' : 'Buka Semua Bagian';
    }

    const activityNames = [
        "Pengenalan Sistem Manajemen Taktik ROK",
        "Buku Panduan Makro & Rotasi (Playbook PDF)",
        "MACRO TEMPO: EARLY GAME DOMINATION",
        "Bedah Rotasi 90 Detik Awal & River Control",
        "Prosedur Retake Setup & Anti-Flank",
        "Ujian Taktik Makro & Rotasi 90 Detik",
        "Tugas Analisis Replay Turnamen",
        "Sertifikat Resmi ROK Tactical Pro"
    ];

    function jumpToActivity(index) {
        const idx = parseInt(index, 10);
        document.getElementById('activity-jump-select').value = idx;
        const title = activityNames[idx - 1] || "Aktivitas LMS";
        document.getElementById('player-active-title').innerText = title;
        document.getElementById('breadcrumb-active-topic').innerText = title;

        if (idx === 3) {
            goToSlide(1);
        } else if (idx === 6) {
            goToSlide(11); // Jump to quiz
        } else if (idx === 8) {
            goToSlide(12); // Certificate completion
        } else {
            goToSlide(Math.min(idx * 2, 12));
        }
    }

    function changeActivity(dir) {
        const select = document.getElementById('activity-jump-select');
        let current = parseInt(select.value, 10);
        if (dir === 'next' && current < 8) {
            current++;
        } else if (dir === 'prev' && current > 1) {
            current--;
        }
        jumpToActivity(current);
    }

    function loadLessonFromDb(title, summary) {
        document.getElementById('player-active-title').innerText = title;
        document.getElementById('breadcrumb-active-topic').innerText = title;
        lmsSlides[1].title = title;
        lmsSlides[1].html = `
            <h2>${title}</h2>
            <p>${summary}</p>
            <div class="lms-slide-card" style="margin-top:14px;">
                <h4><i data-lucide="play-circle" style="width:16px;height:16px;color:#d97706;"></i> Materi Terbuka</h4>
                <p>Materi pembelajaran ini dimuat langsung dari kurikulum database ROK Revamp. Perhatikan detail taktik sebelum melanjutkan ke ujian kompetensi.</p>
            </div>
        `;
        goToSlide(2);
        window.scrollTo({ top: document.getElementById('lms-slide-canvas').offsetTop - 60, behavior: 'smooth' });
    }

    // Initialize slide 1 on ready
    document.addEventListener('DOMContentLoaded', function() {
        renderSlide(1);
    });
</script>
@endsection
