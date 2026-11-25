<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    modules: { type: Array, default: () => [] },
    membershipPlans: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
    hasLearningAccess: { type: Boolean, default: false },
    userTier: { type: String, default: 'guest' },
    userName: { type: String, default: 'Tamu ROK' },
});

const { animateSplitText, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

// Active View Tab: 'lobby' (NeuronAcademy style) or 'player' (AlfaLearning style)
const activeTab = ref('lobby');

// Interactive LMS State
const currentSlide = ref(1);
const isDrawerOpen = ref(false);
const isFullscreen = ref(false);
const activeActivityIndex = ref(3);
const activeActivityTitle = ref('MACRO TEMPO: EARLY GAME DOMINATION');
const activeActivityTopic = ref('SMT-2026: Sistem Manajemen Taktik');

// Accordion sections
const expandedSections = ref({
    sec1: true,
    sec2: true,
    sec3: true,
    sec4: true,
});
const allExpanded = ref(true);

// Mini Quiz state
const quizSelectedOption = ref(null);
const quizResult = ref(null);

const activitiesList = [
    { id: 1, title: 'Pengenalan Sistem Manajemen Taktik ROK', type: 'doc', status: 'done', section: 1 },
    { id: 2, title: 'Buku Panduan Makro & Rotasi (Playbook PDF)', type: 'pdf', status: 'done', section: 1 },
    { id: 3, title: 'MACRO TEMPO: EARLY GAME DOMINATION', type: 'scorm', status: 'active', section: 2 },
    { id: 4, title: 'Bedah Rotasi 90 Detik Awal & River Control', type: 'scorm', status: 'todo', section: 2 },
    { id: 5, title: 'Prosedur Retake Setup & Anti-Flank', type: 'scorm', status: 'todo', section: 2 },
    { id: 6, title: 'Ujian Taktik Makro & Rotasi 90 Detik', type: 'quiz', status: 'todo', section: 3 },
    { id: 7, title: 'Tugas Analisis Replay Turnamen', type: 'upload', status: 'todo', section: 3 },
    { id: 8, title: 'Sertifikat Resmi ROK Tactical Pro', type: 'award', status: 'locked', section: 4 },
];

function switchTab(tab) {
    activeTab.value = tab;
    if (tab === 'player') {
        setTimeout(() => {
            window.renderLucideIcons && window.renderLucideIcons();
        }, 50);
    }
}

function nextSlide() {
    if (currentSlide.value < 12) {
        currentSlide.value++;
        refreshIcons();
    }
}

function prevSlide() {
    if (currentSlide.value > 1) {
        currentSlide.value--;
        refreshIcons();
    }
}

function goToSlide(n) {
    currentSlide.value = n;
    refreshIcons();
}

function toggleDrawer() {
    isDrawerOpen.value = !isDrawerOpen.value;
    refreshIcons();
}

function toggleFullscreen() {
    isFullscreen.value = !isFullscreen.value;
}

function toggleSection(sec) {
    expandedSections.value[sec] = !expandedSections.value[sec];
    refreshIcons();
}

function toggleAllSections() {
    allExpanded.value = !allExpanded.value;
    expandedSections.value.sec1 = allExpanded.value;
    expandedSections.value.sec2 = allExpanded.value;
    expandedSections.value.sec3 = allExpanded.value;
    expandedSections.value.sec4 = allExpanded.value;
    refreshIcons();
}

function selectActivity(actId) {
    activeActivityIndex.value = actId;
    const item = activitiesList.find(a => a.id === actId);
    if (item) {
        activeActivityTitle.value = item.title;
        activeActivityTopic.value = item.title;
    }
    if (actId === 3) {
        currentSlide.value = 1;
    } else if (actId === 6) {
        currentSlide.value = 11;
    } else if (actId === 8) {
        currentSlide.value = 12;
    } else {
        currentSlide.value = Math.min(actId * 2, 12);
    }
    if (activeTab.value !== 'player') {
        activeTab.value = 'player';
    }
    refreshIcons();
}

function changeActivity(dir) {
    let nextId = activeActivityIndex.value;
    if (dir === 'next' && nextId < 8) {
        nextId++;
    } else if (dir === 'prev' && nextId > 1) {
        nextId--;
    }
    selectActivity(nextId);
}

function checkQuiz(choiceIndex, isCorrect) {
    quizSelectedOption.value = choiceIndex;
    quizResult.value = isCorrect;
}

function refreshIcons() {
    setTimeout(() => {
        window.renderLucideIcons && window.renderLucideIcons();
    }, 40);
}

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateStaggerFadeIn('.module-card', 0.06);

    document.querySelectorAll('.module-card, .plan-card').forEach((el) => {
        bindSpringHover(el, 1.02, -3);
    });

    refreshIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>ROK Academy &amp; Learning Development | Ruler Of Kings</title>
            <meta name="description" content="Portal LMS ROK Academy: Akses modul taktik esports, slide masterclass SCORM interaktif, kurikulum turnamen resmi, dan evaluasi pro player." />
        </Head>

        <div class="page-container lms-wrapper">
            <!-- PAGE HERO -->
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Learning & Development'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'LEARNING & DEVELOPMENT' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Portal Pembelajaran Taktik Esports & LMS Resmi ROK. Akses modul kurikulum, analisis replay, dan sertifikasi pro player.' }}
                    </p>
                </div>
            </section>

            <!-- VIEW SWITCHER TABS -->
            <div class="section-inner" style="padding-top: 24px; padding-bottom: 0;">
                <div class="lms-tabs-bar">
                    <button
                        type="button"
                        class="lms-tab-btn"
                        :class="{ 'is-active': activeTab === 'lobby' }"
                        @click="switchTab('lobby')"
                    >
                        <i data-lucide="shield-check" style="width:16px;height:16px;"></i>
                        Portal Masuk LMS
                    </button>
                    <button
                        type="button"
                        class="lms-tab-btn"
                        :class="{ 'is-active': activeTab === 'player' }"
                        @click="switchTab('player')"
                    >
                        <i data-lucide="graduation-cap" style="width:16px;height:16px;"></i>
                        Ruang Belajar &amp; LMS Player
                    </button>
                </div>
            </div>

            <!-- ========================================================
                 VIEW 1: LMS LOBBY (NeuronAcademy style from screenshot)
                 ======================================================== -->
            <section v-show="activeTab === 'lobby'" class="lms-lobby-backdrop">
                <div class="lms-lobby-card">
                    <!-- Left Column: Brand & Login Form -->
                    <div class="lms-lobby-left">
                        <div class="lms-brand-header">
                            <img src="/img/rok-rhino-head.png" alt="ROK Academy" class="lms-brand-logo-img" />
                            <h2 class="lms-brand-title">ROK<span>Academy</span></h2>
                        </div>

                        <form @submit.prevent="switchTab('player')">
                            <div class="lms-input-group">
                                <label for="vue-lms-username">Email / Nama Pengguna</label>
                                <input
                                    type="text"
                                    id="vue-lms-username"
                                    class="lms-input-text"
                                    value="banuariefmuzaki@gmail.com"
                                    placeholder="nama@email.com"
                                />
                            </div>

                            <div class="lms-input-group">
                                <label for="vue-lms-password">Kata Sandi</label>
                                <input
                                    type="password"
                                    id="vue-lms-password"
                                    class="lms-input-text"
                                    value="secretpassword"
                                    placeholder="••••••••••••"
                                />
                            </div>

                            <button type="submit" class="btn-lms-login">
                                Masuk ke LMS
                            </button>

                            <a href="#" class="lms-forgot-link">
                                Lupa kata sandi?
                            </a>
                        </form>
                    </div>

                    <!-- Right Column: Membership Verification (Replaces SSO) & Utilities -->
                    <div class="lms-lobby-right">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Verifikasi Keanggotaan LMS
                            </div>

                            <div class="lms-access-status-card">
                                <template v-if="hasLearningAccess">
                                    <div class="lms-access-badge lms-access-badge--active">
                                        <i data-lucide="check-circle" style="width:13px;height:13px;"></i>
                                        Akses Aktif ({{ userTier.toUpperCase() }})
                                    </div>
                                    <p style="font-size: 13px; line-height: 1.5; margin: 0 0 14px 0; color: #475569;">
                                        Selamat datang <strong>{{ userName }}</strong>! Akun Anda memiliki izin penuh untuk membuka kurikulum taktik, slide SCORM interaktif, dan ujian evaluasi LMS.
                                    </p>
                                    <button type="button" class="btn-lms-action btn-lms-action--gold" @click="switchTab('player')">
                                        <i data-lucide="external-link" style="width:15px;height:15px;"></i>
                                        Buka LMS ROK Sekarang &rarr;
                                    </button>
                                </template>
                                <template v-else>
                                    <div class="lms-access-badge lms-access-badge--locked">
                                        <i data-lucide="lock" style="width:13px;height:13px;"></i>
                                        Akses Terbatas: Belum Terdaftar
                                    </div>
                                    <p style="font-size: 13px; line-height: 1.5; margin: 0 0 12px 0; color: #64748b;">
                                        Modul taktik lengkap dikhususkan bagi pemegang paket <strong>ROK Learning</strong> atau <strong>ROK Immortal</strong>.
                                    </p>
                                    <Link href="/membership" class="btn-lms-action btn-lms-action--gold">
                                        <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                                        Dapatkan Akses ROK Learning
                                    </Link>
                                    <button type="button" class="btn-lms-action btn-lms-action--outline" @click="switchTab('player')">
                                        <i data-lucide="eye" style="width:15px;height:15px;"></i>
                                        Mode Tamu: Coba Demo LMS ROK
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Language Selector & Cookie Notice -->
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

                <!-- Bottom Disclaimer Bar from screenshot -->
                <div class="lms-disclaimer-footer">
                    Jika Anda terus menjelajahi situs web ini, Anda menyetujui kebijakan kami:
                    <a href="#">Kebijakan Privasi</a>, <a href="#">Syarat dan Ketentuan</a> &mdash;
                    <span style="color:#f59e0b; cursor:pointer; font-weight:700;" @click="switchTab('player')">Lanjutkan</span>
                </div>
            </section>

            <!-- ========================================================
                 VIEW 2: LMS INTERACTIVE LEARNING ENVIRONMENT (from video)
                 ======================================================== -->
            <section v-show="activeTab === 'player'" style="padding: 20px 16px 60px 16px;">
                <div class="lms-app-view">
                    <!-- 1. LMS Top App Bar -->
                    <div class="lms-top-bar">
                        <div style="display:flex; align-items:center; gap:18px;">
                            <a href="#player" class="lms-logo-brand">
                                <img src="/img/rok-rhino-head.png" alt="ROK" style="width:28px;height:28px;object-fit:contain;" />
                                <span>ROK Learning</span>
                            </a>
                            <nav class="lms-nav-links">
                                <a href="#kursus" class="lms-nav-link is-active">Kursus</a>
                                <a href="#nilai" class="lms-nav-link" @click.prevent="alert('Nilai Rata-Rata Modul Anda: 94 / 100 (Predikat: Pro Ready)')">Nilai</a>
                                <a href="#kompetensi" class="lms-nav-link" @click.prevent="alert('Kompetensi Utama: Makro Rotasi (95%), Vision River (92%), Retake Setup (90%)')">Kompetensi</a>
                            </nav>
                        </div>
                        <div class="lms-top-actions">
                            <button type="button" class="btn-lms-action btn-lms-action--outline" style="padding:6px 12px; font-size:12px; margin-bottom:0;" @click="toggleDrawer">
                                <i data-lucide="menu" style="width:14px;height:14px;"></i>
                                <span>Menu Silabus</span>
                            </button>
                            <button type="button" class="btn-lms-action btn-lms-action--outline" style="padding:6px 12px; font-size:12px; margin-bottom:0;" @click="switchTab('lobby')">
                                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                                <span>Lobby LMS</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Breadcrumbs -->
                    <div class="lms-breadcrumbs">
                        <a href="#" @click.prevent="switchTab('lobby')">Beranda</a>
                        <span>/</span>
                        <a href="#kursus">Kursus Saya</a>
                        <span>/</span>
                        <a href="#kursus">ROK Academy</a>
                        <span>/</span>
                        <span style="font-weight:700; color:#1e293b;">{{ activeActivityTopic }}</span>
                    </div>

                    <!-- 3. Course Header Banner & Progress -->
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
                                <span>3 / 8 Aktivitas (38%)</span>
                            </div>
                            <div class="lms-progress-bar-track">
                                <div class="lms-progress-bar-fill" style="width: 38%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Interactive Slide Presentation Player (AlfaLearning SCORM Style) -->
                    <div style="padding: 20px 24px 0 24px;">
                        <div class="lms-slide-player-card">
                            <!-- Activity Meta Box -->
                            <div class="lms-player-meta-box">
                                <div>
                                    <span class="badge-mini" style="background:rgba(217,119,6,0.15); color:#d97706; border:1px solid rgba(217,119,6,0.3); font-size:11px; padding:2px 8px;">
                                        MODUL INTERAKTIF SCORM
                                    </span>
                                    <h3 style="margin:6px 0 2px 0; font-size:18px; font-weight:800;">
                                        {{ activeActivityTitle }}
                                    </h3>
                                    <div style="font-size:12px; color:#64748b; display:flex; gap:14px; flex-wrap:wrap; margin-top:4px;">
                                        <span><i data-lucide="repeat" style="width:12px;height:12px;display:inline;"></i> Upaya: Tidak terbatas</span>
                                        <span><i data-lucide="award" style="width:12px;height:12px;display:inline;"></i> Metode Penilaian: Nilai Tertinggi</span>
                                        <span><i data-lucide="check-circle" style="width:12px;height:12px;display:inline;"></i> Nilai Dilaporkan: 100 / 100</span>
                                    </div>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" @click="goToSlide(1)">
                                        <i data-lucide="rotate-ccw" style="width:14px;height:14px;"></i> Pratinjau Ulang
                                    </button>
                                    <button type="button" class="btn-lms-action btn-lms-action--gold" style="margin:0; padding:8px 16px; font-size:12px;" @click="toggleFullscreen">
                                        <i data-lucide="maximize" style="width:14px;height:14px;"></i> Layar Penuh
                                    </button>
                                </div>
                            </div>

                            <!-- 16:9 Interactive Slide Canvas -->
                            <div class="lms-slide-canvas" :class="{ 'is-fullscreen': isFullscreen }">
                                <!-- Slide Header Tag -->
                                <div class="lms-slide-header-tag">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="badge-mini" style="background:#d97706; color:#ffffff; font-weight:800; font-size:11px; padding:2px 8px;">
                                            ROK TACTICAL SMT
                                        </span>
                                        <span style="font-size:12px; font-weight:700; color:#64748b;">
                                            Tahap {{ currentSlide }} dari 12
                                        </span>
                                    </div>
                                    <div class="lms-slide-dots-container">
                                        <span
                                            v-for="i in 12"
                                            :key="i"
                                            class="lms-slide-dot"
                                            :class="{ 'is-active': currentSlide === i }"
                                            @click="goToSlide(i)"
                                            :title="'Slide ' + i"
                                        ></span>
                                    </div>
                                </div>

                                <!-- Slide Body Container -->
                                <div class="lms-slide-body">
                                    <!-- Slide 1: Cover -->
                                    <div v-if="currentSlide === 1" style="text-align:center; padding: 20px 0;">
                                        <span class="badge-mini" style="background:rgba(217,119,6,0.15); color:#d97706; border:1px solid rgba(217,119,6,0.3); font-size:12px; padding:3px 10px;">KURIKULUM PRO PLAYER</span>
                                        <h2 style="font-size:28px; font-weight:800; margin:14px 0; color:#d97706;">MACRO TEMPO: EARLY GAME DOMINATION</h2>
                                        <p style="max-width:620px; margin:0 auto 20px auto; color:#64748b; font-size:15px; line-height:1.6;">
                                            Selamat datang di modul interaktif SCORM ROK Academy. Modul ini membongkar protokol baku tim juara: rotasi 90 detik awal, kontrol river vision, dan eksekusi objektif Turtle pertama.
                                        </p>
                                        <button type="button" class="btn-lms-action btn-lms-action--gold" style="width:auto; padding:10px 24px; display:inline-flex;" @click="nextSlide">
                                            Mulai Modul Pembelajaran &rarr;
                                        </button>
                                    </div>

                                    <!-- Slide 2: Rotasi 90 Detik -->
                                    <div v-else-if="currentSlide === 2">
                                        <h2>1. Rotasi 90 Detik Pertama</h2>
                                        <p>Dalam 90 detik pertama, tempo ditentukan oleh efisiensi rute clearing jungler dan keberhasilan support/roamer membuka semak (bush) river.</p>
                                        <div class="lms-slide-grid">
                                            <div class="lms-slide-card">
                                                <h4><i data-lucide="compass" style="width:16px;height:16px;color:#d97706;"></i> Rute Blue Buff Start</h4>
                                                <p>Cocok untuk hero yang membutuhkan cooldown cepat. Prioritaskan jalur Lithowanderer pada detik 00:45 untuk mengamankan level 4 sebelum Turtle.</p>
                                            </div>
                                            <div class="lms-slide-card">
                                                <h4><i data-lucide="flame" style="width:16px;height:16px;color:#ef4444;"></i> Rute Red Buff Aggressive</h4>
                                                <p>Berfokus pada isolasi Gold Lane lawan sebelum menit ke-2. Memaksa roamer musuh terikat di satu sisi map.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Slide 3: Vision River -->
                                    <div v-else-if="currentSlide === 3">
                                        <h2>2. River Vision & Bush Control</h2>
                                        <p>Vision bukan hanya meletakkan ward atau hero di semak, melainkan memotong jalur rotasi lawan dan mencegah flanking pada lane prioritas.</p>
                                        <div class="lms-slide-grid">
                                            <div class="lms-slide-card">
                                                <h4><i data-lucide="eye" style="width:16px;height:16px;color:#0284c7;"></i> Mid River High Bush</h4>
                                                <p>Zona krusial pemantau rotasi Midlaner dan Roamer lawan. Jangan pernah melintas tanpa skill pengecekan semak (skill zoning).</p>
                                            </div>
                                            <div class="lms-slide-card">
                                                <h4><i data-lucide="shield" style="width:16px;height:16px;color:#16a34a;"></i> Defensive River Chokepoint</h4>
                                                <p>Titik aman mundur ketika jungler musuh memilih invasi counter-jungle. Berikan sinyal pincer kepada Exp Laner untuk segera cut wave.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Slide 4: Setup Turtle -->
                                    <div v-else-if="currentSlide === 4">
                                        <h2>3. Setup 30 Detik Pra-Turtle</h2>
                                        <p>Perebutan Turtle tidak dimulai saat monster muncul di menit ke-2, melainkan 30 detik sebelumnya (menit 01:30).</p>
                                        <div class="lms-slide-card" style="margin-top:12px;">
                                            <h4><i data-lucide="crosshair" style="width:16px;height:16px;color:#d97706;"></i> Formasi Pincer ROK Taktikal</h4>
                                            <p>1. Exp Laner menahan wave dan memblokir roamer lawan di semak belakang Turtle Pit.<br />
                                            2. Roamer berdiri di sisi river entrance untuk menembak info posisi jungler lawan.<br />
                                            3. Jungler dan Midlaner melakukan burst damage cepat dengan koordinasi Retribution pada HP Turtle 1.800.</p>
                                        </div>
                                    </div>

                                    <!-- Slide 5: Shoutcalling -->
                                    <div v-else-if="currentSlide === 5">
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
                                    </div>

                                    <!-- Slide 6: 4 Fase -->
                                    <div v-else-if="currentSlide === 6">
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
                                    </div>

                                    <!-- Slide 7: Distribusi Resource -->
                                    <div v-else-if="currentSlide === 7">
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
                                    </div>

                                    <!-- Slide 8: Retake Procedure -->
                                    <div v-else-if="currentSlide === 8">
                                        <h2>7. Prosedur Retake Setup saat Tertekan</h2>
                                        <p>Ketika tim kehilangan seluruh outer turret dan terkurung di base, lakukan SOP Retake:</p>
                                        <div class="lms-slide-card" style="margin-top:12px;">
                                            <h4><i data-lucide="shield-alert" style="width:16px;height:16px;color:#ef4444;"></i> Langkah Pertahanan Base ROK</h4>
                                            <p>1. Jangan keluar sembarangan sebelum wave super minion dibersihkan di dalam high ground.<br />
                                            2. Simpan skill crowd-control area (CC) untuk memotong inisiasi Lord musuh.<br />
                                            3. Siapkan satu flanker (Exp/Assassin) bersembunyi di semak base luar untuk menyergap marksman lawan saat mereka fokus menyerang inhibitor turret.</p>
                                        </div>
                                    </div>

                                    <!-- Slide 9: Bedah Replay -->
                                    <div v-else-if="currentSlide === 9">
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
                                    </div>

                                    <!-- Slide 10: Ketahanan BO5/BO7 -->
                                    <div v-else-if="currentSlide === 10">
                                        <h2>9. Ketahanan Mental Turnamen Panjang</h2>
                                        <p>Dalam format Best of 5 atau Best of 7, penurunan fokus 10% di game ke-4 dapat menyebabkan kekalahan telak.</p>
                                        <div class="lms-slide-card" style="margin-top:12px;">
                                            <h4><i data-lucide="zap" style="width:16px;height:16px;color:#d97706;"></i> SOP Jeda Game (Rest &amp; Reset)</h4>
                                            <p>&bull; <strong>Hidrasi:</strong> Minum air putih hangat 200ml setiap pergantian game untuk menjaga aliran oksigen ke otak.<br />
                                            &bull; <strong>Peregangan:</strong> Lakukan peregangan pergelangan tangan (wrist stretch) dan leher selama 2 menit.<br />
                                            &bull; <strong>Mindset Reset:</strong> Apapun hasil game sebelumnya (menang atau kalah), masuki game baru dengan mental 0 - 0.</p>
                                        </div>
                                    </div>

                                    <!-- Slide 11: Mini Quiz -->
                                    <div v-else-if="currentSlide === 11">
                                        <h2>10. Uji Pemahaman Makro 90 Detik</h2>
                                        <p>Jawab pertanyaan taktis berikut untuk memverifikasi pemahaman modul Anda:</p>
                                        <div class="lms-slide-card" style="margin-top:12px;">
                                            <p style="font-weight:700; color:#0f172a; margin-bottom:10px;">
                                                Pertanyaan: Pada menit 01:30, di mana seharusnya posisi Roamer saat tim merencanakan setup perebutan Turtle pertama?
                                            </p>
                                            <div class="lms-quiz-options">
                                                <button
                                                    type="button"
                                                    class="lms-quiz-opt"
                                                    :class="{
                                                        'is-wrong': quizSelectedOption === 1 && !quizResult,
                                                    }"
                                                    :disabled="quizSelectedOption !== null"
                                                    @click="checkQuiz(1, false)"
                                                >
                                                    A. Berada di Gold Lane membantu farming wave minion
                                                </button>
                                                <button
                                                    type="button"
                                                    class="lms-quiz-opt"
                                                    :class="{
                                                        'is-correct': quizSelectedOption !== null,
                                                    }"
                                                    :disabled="quizSelectedOption !== null"
                                                    @click="checkQuiz(2, true)"
                                                >
                                                    B. Di semak river/entrance jungle lawan untuk membuka vision dan memotong rotasi jungler musuh (Zoning)
                                                </button>
                                                <button
                                                    type="button"
                                                    class="lms-quiz-opt"
                                                    :class="{
                                                        'is-wrong': quizSelectedOption === 3 && !quizResult,
                                                    }"
                                                    :disabled="quizSelectedOption !== null"
                                                    @click="checkQuiz(3, false)"
                                                >
                                                    C. Diam di base membeli item pertahanan
                                                </button>
                                            </div>
                                            <div v-if="quizSelectedOption !== null" style="margin-top:12px; font-size:13px; font-weight:700;">
                                                <span v-if="quizResult" style="color:#16a34a;">
                                                    ✔ Jawaban Tepat! Roamer harus mengamankan vision choke point river untuk mengamankan Retribution Jungler.
                                                </span>
                                                <span v-else style="color:#ef4444;">
                                                    ✖ Kurang Tepat. Jawaban yang benar adalah B: Zoning pintu masuk river.
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Slide 12: Rangkuman & Selesai -->
                                    <div v-else-if="currentSlide === 12" style="text-align:center; padding: 24px 0;">
                                        <div style="width:64px; height:64px; border-radius:50%; background:rgba(34,197,94,0.15); color:#16a34a; display:inline-flex; align-items:center; justify-content:center; margin-bottom:14px;">
                                            <i data-lucide="check-circle" style="width:36px;height:36px;"></i>
                                        </div>
                                        <h2 style="font-size:26px; font-weight:800; color:#16a34a; margin:0 0 10px 0;">Selamat! Modul SCORM Selesai</h2>
                                        <p style="max-width:580px; margin:0 auto 20px auto; color:#64748b; font-size:14px; line-height:1.6;">
                                            Anda telah menyelesaikan seluruh 12 tahap materi <strong>MACRO TEMPO: EARLY GAME DOMINATION</strong>. Nilai 100/100 telah dicatat dalam buku nilai LMS ROK.
                                        </p>
                                        <div style="display:inline-flex; gap:12px;">
                                            <button type="button" class="btn-lms-action btn-lms-action--gold" style="width:auto; padding:10px 20px;" @click="selectActivity(6)">
                                                Lanjut ke Ujian Taktik Makro &rarr;
                                            </button>
                                            <button type="button" class="btn-lms-action btn-lms-action--outline" style="width:auto; padding:10px 18px;" @click="goToSlide(1)">
                                                Ulangi Modul
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide Controls Bar -->
                                <div class="lms-slide-controls-bar">
                                    <button
                                        type="button"
                                        class="lms-slide-step-btn"
                                        :disabled="currentSlide === 1"
                                        @click="prevSlide"
                                    >
                                        &larr; Sebelumnya
                                    </button>
                                    <div style="display:flex; align-items:center; gap:12px; font-size:13px; font-weight:700;">
                                        <span>Slide {{ currentSlide }} / 12</span>
                                        <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:4px 10px; font-size:11px;" @click="toggleDrawer">
                                            <i data-lucide="list" style="width:12px;height:12px;"></i> Indeks Modul
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        class="lms-slide-step-btn"
                                        :disabled="currentSlide === 12"
                                        @click="nextSlide"
                                    >
                                        Selanjutnya &rarr;
                                    </button>
                                </div>
                            </div>

                            <!-- Activity Jump Navigation Bar -->
                            <div class="lms-activity-jump-nav">
                                <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" @click="changeActivity('prev')">
                                    &larr; Aktivitas Sebelumnya
                                </button>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="font-size:12px; font-weight:600; color:#64748b;">Lompat ke:</span>
                                    <select
                                        :value="activeActivityIndex"
                                        @change="selectActivity(Number($event.target.value))"
                                        style="padding:6px 12px; font-size:12px; border-radius:6px; border:1px solid #cbd5e1; background:transparent; color:inherit; font-weight:600;"
                                    >
                                        <option v-for="act in activitiesList" :key="act.id" :value="act.id">
                                            {{ act.id }}. {{ act.title }}
                                        </option>
                                    </select>
                                </div>
                                <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:8px 14px; font-size:12px;" @click="changeActivity('next')">
                                    Aktivitas Selanjutnya &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Course Syllabus Sections Accordion -->
                    <div class="lms-curriculum-container">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                            <h3 style="margin:0; font-size:18px; font-weight:800;">Silabus Lengkap Kursus SMT</h3>
                            <button type="button" class="btn-lms-action btn-lms-action--outline" style="margin:0; padding:6px 12px; font-size:12px;" @click="toggleAllSections">
                                <span>{{ allExpanded ? 'Ciutkan Semua Bagian' : 'Buka Semua Bagian' }}</span>
                            </button>
                        </div>

                        <!-- SECTION 1: PANDUAN & PRA-SYARAT -->
                        <div class="lms-section-block">
                            <button type="button" class="lms-section-header-btn" @click="toggleSection('sec1')">
                                <span>Bagian 1: Panduan &amp; Pra-Syarat (Overview)</span>
                                <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                            </button>
                            <ul v-show="expandedSections.sec1" class="lms-activity-list">
                                <li class="lms-activity-item" @click="selectActivity(1)">
                                    <div class="lms-activity-left">
                                        <div class="lms-activity-icon"><i data-lucide="file-text" style="width:16px;height:16px;"></i></div>
                                        <div>
                                            <div class="lms-activity-title">Pengenalan Sistem Manajemen Taktik ROK</div>
                                            <p class="lms-activity-desc">Visi kurikulum, pembagian role analitis, dan standardisasi callout map.</p>
                                        </div>
                                    </div>
                                    <span class="lms-status-pill lms-status-pill--done"><i data-lucide="check" style="width:12px;height:12px;"></i> Selesai</span>
                                </li>
                                <li class="lms-activity-item" @click="selectActivity(2)">
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

                        <!-- SECTION 2: MODUL PEMBELAJARAN STRATEGIS -->
                        <div class="lms-section-block">
                            <button type="button" class="lms-section-header-btn" @click="toggleSection('sec2')">
                                <span>Bagian 2: MODUL PEMBELAJARAN STRATEGIS</span>
                                <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                            </button>
                            <ul v-show="expandedSections.sec2" class="lms-activity-list">
                                <li class="lms-activity-item is-selected" @click="selectActivity(3)">
                                    <div class="lms-activity-left">
                                        <div class="lms-activity-icon lms-activity-icon--scorm"><i data-lucide="play-circle" style="width:16px;height:16px;"></i></div>
                                        <div>
                                            <div class="lms-activity-title">MACRO TEMPO: EARLY GAME DOMINATION</div>
                                            <p class="lms-activity-desc">Paket interaktif SCORM: Rotasi 90 detik, pincer setup, dan eksekusi turret dive.</p>
                                        </div>
                                    </div>
                                    <span class="lms-status-pill lms-status-pill--done"><i data-lucide="check" style="width:12px;height:12px;"></i> Sedang Aktif</span>
                                </li>

                                <template v-for="mod in modules" :key="mod.id">
                                    <li
                                        v-for="les in (mod.lessons || [])"
                                        :key="les.id"
                                        class="lms-activity-item"
                                        @click="selectActivity(3)"
                                    >
                                        <div class="lms-activity-left">
                                            <div class="lms-activity-icon lms-activity-icon--scorm">
                                                <i data-lucide="play" style="width:16px;height:16px;"></i>
                                            </div>
                                            <div>
                                                <div class="lms-activity-title">{{ les.title }}</div>
                                                <p class="lms-activity-desc">{{ les.summary || mod.title }}</p>
                                            </div>
                                        </div>
                                        <span class="lms-status-pill lms-status-pill--todo">Materi Silabus</span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- SECTION 3: EVALUASI & UJIAN KOMPETENSI -->
                        <div class="lms-section-block">
                            <button type="button" class="lms-section-header-btn" @click="toggleSection('sec3')">
                                <span>Bagian 3: Evaluasi &amp; Ujian Kompetensi</span>
                                <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                            </button>
                            <ul v-show="expandedSections.sec3" class="lms-activity-list">
                                <li class="lms-activity-item" @click="selectActivity(6)">
                                    <div class="lms-activity-left">
                                        <div class="lms-activity-icon lms-activity-icon--quiz"><i data-lucide="help-circle" style="width:16px;height:16px;"></i></div>
                                        <div>
                                            <div class="lms-activity-title">Ujian Taktik Makro &amp; Rotasi 90 Detik</div>
                                            <p class="lms-activity-desc">15 Pertanyaan kasus skenario turnamen. Batas kelulusan skor 80.</p>
                                        </div>
                                    </div>
                                    <span class="lms-status-pill lms-status-pill--todo">Belum Dimulai</span>
                                </li>
                                <li class="lms-activity-item" @click="selectActivity(7)">
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

                        <!-- SECTION 4: REAKTIF & SERTIFIKASI -->
                        <div class="lms-section-block">
                            <button type="button" class="lms-section-header-btn" @click="toggleSection('sec4')">
                                <span>Bagian 4: Reaktif &amp; Sertifikasi</span>
                                <i data-lucide="chevron-down" style="width:16px;height:16px;"></i>
                            </button>
                            <ul v-show="expandedSections.sec4" class="lms-activity-list">
                                <li class="lms-activity-item" @click="selectActivity(8)">
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

            <!-- ========================================================
                 CURRICULUM SIDEBAR DRAWER (as shown in AlfaLearning video)
                 ======================================================== -->
            <div
                v-show="isDrawerOpen"
                class="lms-drawer-backdrop"
                @click="toggleDrawer"
            ></div>
            <aside
                class="lms-drawer"
                :style="{ transform: isDrawerOpen ? 'translateX(0)' : 'translateX(-100%)' }"
            >
                <div class="lms-drawer-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <img src="/img/rok-rhino-head.png" alt="ROK" style="width:24px;height:24px;object-fit:contain;" />
                        <h4 style="margin:0; font-size:15px; font-weight:800;">Silabus Kursus SMT</h4>
                    </div>
                    <button type="button" class="lms-drawer-close-btn" @click="toggleDrawer">&times;</button>
                </div>
                <ul class="lms-drawer-tree">
                    <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:8px 14px 4px;">Bagian 1: Overview</li>
                    <li class="lms-drawer-item" @click="selectActivity(1); toggleDrawer();">
                        <i data-lucide="check-circle" style="width:14px;height:14px;color:#16a34a;"></i>
                        <span>1. Pengenalan SMT ROK</span>
                    </li>
                    <li class="lms-drawer-item" @click="selectActivity(2); toggleDrawer();">
                        <i data-lucide="check-circle" style="width:14px;height:14px;color:#16a34a;"></i>
                        <span>2. Buku Panduan PDF</span>
                    </li>

                    <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 2: Materi Inti</li>
                    <li
                        class="lms-drawer-item"
                        :class="{ 'is-active': activeActivityIndex === 3 }"
                        @click="selectActivity(3); toggleDrawer();"
                    >
                        <i data-lucide="play-circle" style="width:14px;height:14px;color:#d97706;"></i>
                        <span>3. Macro Tempo SCORM</span>
                    </li>
                    <li class="lms-drawer-item" @click="selectActivity(4); toggleDrawer();">
                        <i data-lucide="circle" style="width:14px;height:14px;color:#94a3b8;"></i>
                        <span>4. Rotasi 90 Detik</span>
                    </li>
                    <li class="lms-drawer-item" @click="selectActivity(5); toggleDrawer();">
                        <i data-lucide="circle" style="width:14px;height:14px;color:#94a3b8;"></i>
                        <span>5. Retake Setup</span>
                    </li>

                    <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 3: Ujian</li>
                    <li class="lms-drawer-item" @click="selectActivity(6); toggleDrawer();">
                        <i data-lucide="help-circle" style="width:14px;height:14px;color:#f43f5e;"></i>
                        <span>6. Ujian Taktik Makro</span>
                    </li>
                    <li class="lms-drawer-item" @click="selectActivity(7); toggleDrawer();">
                        <i data-lucide="upload" style="width:14px;height:14px;color:#a855f7;"></i>
                        <span>7. Tugas Analisis Replay</span>
                    </li>

                    <li style="font-size:11px; font-weight:800; text-transform:uppercase; color:#94a3b8; padding:12px 14px 4px;">Bagian 4: Kelulusan</li>
                    <li class="lms-drawer-item" @click="selectActivity(8); toggleDrawer();">
                        <i data-lucide="award" style="width:14px;height:14px;color:#eab308;"></i>
                        <span>8. Sertifikat Digital ROK</span>
                    </li>
                </ul>
            </aside>
        </div>
    </AppLayout>
</template>
