<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import RhinoIntroOverlay from '@/Components/RhinoIntroOverlay.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    heroSlides: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    achievements: { type: Array, default: () => [] },
    achievementsCount: { type: Number, default: 0 },
    modules: { type: Array, default: () => [] },
    partners: { type: Array, default: () => [] },
    demoTournament: { type: Object, default: () => null },
    leadership: { type: Array, default: () => [] },
    membershipPlans: { type: Array, default: () => [] },
});

const currentSlide = ref(0);
const totalMembers = computed(() =>
    props.divisions.reduce((sum, d) => sum + (d.active_players_count ?? (d.active_players ? d.active_players.length : 0)), 0)
);
const { animateSplitText, animateHeroEntrance, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

const nextSlide = () => {
    if (!props.heroSlides.length) return;
    currentSlide.value = (currentSlide.value + 1) % props.heroSlides.length;
    animateCurrentSlideHeadline();
};

const prevSlide = () => {
    if (!props.heroSlides.length) return;
    currentSlide.value = (currentSlide.value - 1 + props.heroSlides.length) % props.heroSlides.length;
    animateCurrentSlideHeadline();
};

const goToSlide = (idx) => {
    currentSlide.value = idx;
    animateCurrentSlideHeadline();
};

const animateCurrentSlideHeadline = () => {
    nextTick(() => {
        animateSplitText('.active-slide-headline', { y: 30, duration: 0.65, stagger: 0.03 });
    });
};

onMounted(() => {
    animateHeroEntrance('.hero-title-text', '.hero-subtitle-text', '.hero-action-buttons');

    
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <!-- Motion Graphic Badak Intro Overlay -->
        <RhinoIntroOverlay ref="rhinoIntroRef" :auto-play="true" />

        <Head>
            <title>ROK Revamp | Ruler Of Kings</title>
            <meta name="description" content="Ruler Of Kings (ROK) â€” Competitive Esports Organization focused on competition, development, discipline and unity." />
        </Head>

        <!-- HERO -->
        <section class="hero-slider" id="heroSlider" aria-roledescription="carousel">
            <div class="hero-slider__track">
                <article
                    v-for="(slide, idx) in heroSlides"
                    :key="slide.id || idx"
                    class="hero-slide"
                    :class="{ 'is-active': idx === currentSlide, 'hero-slide--plain': !slide.image_url }"
                    :aria-hidden="idx !== currentSlide"
                >
                    <div class="hero-slide__backdrop">
                        <img
                            v-if="slide.image_url"
                            :src="slide.image_url"
                            :alt="slide.title"
                            class="hero-slide__image"
                            :loading="idx === 0 ? 'eager' : 'lazy'"
                        />
                        <div v-if="slide.image_url" class="hero-slide__overlay"></div>
                    </div>

                    <div class="hero-slide__content">
                        <div class="site-container hero-slide__inner">
                            <h1 class="hero-slide__title hero-title-text active-slide-headline" v-html="slide.title"></h1>
                            <p class="hero-slide__subtitle hero-subtitle-text">{{ slide.subtitle }}</p>
                            <div class="hero-slide__cta hero-action-buttons">
                                <a
                                    v-if="slide.cta_primary_text"
                                    :href="slide.cta_primary_url || '/trial'"
                                    class="btn-rok btn-rok--gold"
                                >
                                    {{ slide.cta_primary_text }}
                                </a>
                                <a
                                    v-if="slide.cta_secondary_text"
                                    :href="slide.cta_secondary_url || '/bracket'"
                                    class="btn-rok btn-rok--outline"
                                >
                                    {{ slide.cta_secondary_text }}
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Slide cadangan jika database kosong -->
                <article v-if="!heroSlides.length" class="hero-slide hero-slide--plain is-active">
                    <div class="hero-slide__backdrop"></div>
                    <div class="hero-slide__content">
                        <div class="site-container hero-slide__inner">
                            <h1 class="hero-slide__title hero-title-text">Ruler Of Kings</h1>
                            <p class="hero-slide__subtitle hero-subtitle-text">
                                Organisasi esports kompetitif yang membangun atlet lewat disiplin, integritas, dan kerja sama tim.
                            </p>
                            <div class="hero-slide__cta hero-action-buttons">
                                <Link href="/trial" class="btn-rok btn-rok--gold">Daftar trial</Link>
                                <Link href="/teams" class="btn-rok btn-rok--outline">Kenali tim kami</Link>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <button
                v-if="heroSlides.length > 1"
                type="button"
                class="hero-slider__nav hero-slider__nav--prev"
                aria-label="Slide sebelumnya"
                @click="prevSlide"
            >
                &#x2039;
            </button>
            <button
                v-if="heroSlides.length > 1"
                type="button"
                class="hero-slider__nav hero-slider__nav--next"
                aria-label="Slide selanjutnya"
                @click="nextSlide"
            >
                &#x203A;
            </button>

            <div v-if="heroSlides.length > 1" class="hero-slider__indicators" role="tablist">
                <button
                    v-for="(slide, idx) in heroSlides"
                    :key="'ind-' + idx"
                    type="button"
                    class="hero-slider__dot"
                    :class="{ 'is-active': idx === currentSlide }"
                    :aria-label="'Pindah ke slide ' + (idx + 1)"
                    @click="goToSlide(idx)"
                ></button>
            </div>
        </section>

        <!-- TENTANG -->
        <section class="home-section" id="about" aria-labelledby="aboutTitle">
            <div class="home-section__inner">
                <div class="about-block">
                    <span class="eyebrow" id="aboutTitle">Tentang ROK</span>
                    <div>
                        <p class="about-statement" data-rok-animate="scroll-reveal">
                            Ruler Of Kings adalah organisasi esports yang membina atlet profesional,
                            <span>membangun karakter lewat disiplin, dan bersaing dengan integritas di panggung nasional.</span>
                        </p>
                        <Link href="/teams" class="text-link">Kenali tim kami <span aria-hidden="true">&rarr;</span></Link>
                    </div>
                </div>

                <div class="stat-row" data-rok-animate="scroll-reveal">
                    <div class="stat-item">
                        <span class="stat-item__value">{{ divisions.length }}</span>
                        <span class="stat-item__label">Divisi kompetitif</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-item__value">{{ totalMembers }}</span>
                        <span class="stat-item__label">Atlet &amp; staf aktif</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-item__value">{{ achievementsCount || achievements.length }}</span>
                        <span class="stat-item__label">Prestasi tercatat</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- DIVISI -->
        <section class="home-section home-section--alt teams-preview" id="teamsPreview" aria-labelledby="teamsPreviewTitle">
            <div class="home-section__inner">
                <div class="section-head-row">
                    <header class="section-header">
                        <span class="eyebrow">Divisi</span>
                        <h2 class="section-title" id="teamsPreviewTitle">Divisi kompetitif</h2>
                        <p class="section-description">
                            Setiap divisi dibina oleh staf pelatih dan diperkuat atlet pilihan yang membawa nama ROK di turnamen nasional.
                        </p>
                    </header>
                    <Link href="/teams" class="text-link">Lihat semua tim <span aria-hidden="true">&rarr;</span></Link>
                </div>

                <div class="division-list">
                    <Link
                        v-for="(div, idx) in divisions"
                        :key="div.id"
                        href="/teams"
                        class="division-row"
                        data-rok-animate="scroll-reveal"
                    >
                        <span class="division-row__index">{{ String(idx + 1).padStart(2, '0') }}</span>
                        <div>
                            <h3 class="division-row__name">{{ div.name }}</h3>
                            <span class="division-row__game">{{ div.game_name }}</span>
                        </div>
                        <div class="division-row__players">
                            <span v-for="player in (div.active_players || []).slice(0, 6)" :key="player.id">{{ player.nickname || player.in_game_name }}</span>
                            <span v-if="!div.active_players || !div.active_players.length">Roster segera diumumkan</span>
                        </div>
                        <span class="division-row__go" aria-hidden="true">&rarr;</span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- PRESTASI -->
        <section class="home-section achievements-preview" id="achievementsPreview" aria-labelledby="achievementsTitle">
            <div class="home-section__inner">
                <div class="section-head-row">
                    <header class="section-header">
                        <span class="eyebrow">Prestasi</span>
                        <h2 class="section-title" id="achievementsTitle">Jejak prestasi</h2>
                        <p class="section-description">
                            Pencapaian ROK di berbagai kompetisi, dari turnamen komunitas hingga kejuaraan resmi.
                        </p>
                    </header>
                    <Link href="/achievements" class="text-link">Semua prestasi <span aria-hidden="true">&rarr;</span></Link>
                </div>

                <div class="achv-list">
                    <div
                        v-for="ach in achievements"
                        :key="ach.id"
                        class="achv-row"
                        data-rok-animate="scroll-reveal"
                    >
                        <span class="achv-year">{{ ach.event_year || ach.year }}</span>
                        <h3 class="achv-title">{{ ach.title }}</h3>
                        <span class="achv-team">{{ ach.team_name || 'Ruler Of Kings' }}</span>
                        <span class="achv-note">{{ ach.caption }}</span>
                    </div>
                    <div v-if="!achievements.length" class="empty-state-box">Prestasi akan segera ditampilkan.</div>
                </div>
            </div>
        </section>

        <!-- LEARNING ACADEMY PREVIEW -->
        <section class="home-section learning-preview" id="learningSection" aria-labelledby="learningTitle">
            <div class="home-section__inner">
                <div class="learning-showcase">
                    <div class="learning-showcase__media">
                        <div class="video-container-dummy">
                            <iframe
                                src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"
                                title="ROK Strategy Masterclass"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                style="width: 100%; height: 100%; aspect-ratio: 16/9; border-radius: var(--radius-md);"
                            ></iframe>
                        </div>
                    </div>
                    <div class="learning-showcase__content">
                        <h2 class="section-title section-title--left" id="learningTitle">
                            LEARNING<br /><span class="text-gold">WITH US</span>
                        </h2>
                        <p class="section-description section-description--left">
                            Tingkatkan skill mikro, pemahaman makro, dan mentalitas berkompetisi melalui kurikulum terstruktur yang dirancang langsung oleh analis dan pelatih profesional ROK.
                        </p>
                        <div style="margin-top: 24px;">
                            <Link href="/learning-development" class="btn-rok btn-rok--gold">Mulai Belajar Sekarang &rarr;</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TOURNAMENT BRACKET PREVIEW -->
        <section class="home-section bracket-preview" id="bracketSection" aria-labelledby="bracketHeading">
            <div class="home-section__inner">
                <header class="section-header">
                    <h2 class="section-title" id="bracketHeading">BUILD. COMPETE. CONQUER.</h2>
                    <p class="section-subtitle-gold">Your Tournament. Your Bracket. Your Champion.</p>
                    <p class="section-description">
                        Platform generator bracket profesional di dalam website ROK Revamp. Kelola turnamen komunitas, single elimination, seeding otomatis, hingga live score tanpa ribet.
                    </p>
                </header>

                <div class="bracket-stage-card" style="margin-top: 30px;">
                    <div class="bracket-stage-header">
                        <div class="stage-info">
                            <span class="status-live-dot"></span>
                            <strong>LIVE BRACKET PREVIEW:</strong>
                            <span>{{ demoTournament ? demoTournament.name : 'ROK National Invitational' }}</span>
                        </div>
                        <div class="stage-badge">
                            <span>Single Elimination (8 Teams)</span>
                        </div>
                    </div>

                    <div class="bracket-stage-footer" style="padding: 24px; text-align: center;">
                        <Link href="/bracket" class="btn-rok btn-rok--gold">Buka Fitur Bracket Turnamen &rarr;</Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ORGANIZER MEMBERSHIP TIERS SECTION -->
        <section class="home-section home-section--alt membership-preview-section" id="membershipTiers" aria-labelledby="membershipHeading">
            <div class="home-section__inner">
                <header class="section-header">
                    <h2 class="section-title" id="membershipHeading">PAKET AKSES &amp; KEANGGOTAAN ORGANIZER</h2>
                    <p class="section-description">
                        Tingkatkan kualitas turnamen Anda dengan akses halaman khusus <strong>Bracket Studio</strong>, logo tim interaktif dengan tautan WhatsApp &amp; poin pertandingan, serta kurikulum taktik pro esports Ruler of Kings.
                    </p>
                </header>

                <div class="membership-pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
                    <div
                        v-for="plan in membershipPlans"
                        :key="plan.id"
                        class="pricing-card"
                        :class="{ 'pricing-card--featured': plan.slug === 'immortal' || plan.is_featured }"
                        style="background: var(--rok-card-bg, #111622); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 32px; display: flex; flex-direction: column; justify-content: space-between;"
                    >
                        <div>
                            <div class="pricing-header" style="margin-bottom: 16px;">
                                <span class="pricing-badge" style="font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: var(--rok-gold); display: inline-block; margin-bottom: 4px;">{{ plan.badge_title || plan.name }}</span>
                                <h3 class="pricing-title" style="font-size: 24px; font-weight: 800; margin: 6px 0;">{{ plan.name }}</h3>
                                <p class="pricing-sub" style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.5; margin: 0;">{{ plan.description }}</p>
                            </div>

                            <div style="margin: 16px 0; padding: 12px 0; border-top: 1px solid var(--rok-surface-border); border-bottom: 1px solid var(--rok-surface-border);">
                                <div style="font-size: 28px; font-weight: 900; font-family: var(--font-sub);">
                                    Rp {{ Number(plan.price).toLocaleString('id-ID') }}
                                    <span style="font-size: 13px; font-weight: 600; color: var(--rok-text-muted); font-family: var(--font-main);">/ {{ plan.duration_days ? plan.duration_days + ' hari' : 'bulan' }}</span>
                                </div>
                            </div>

                            <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 24px 0; font-size: 13px; color: var(--rok-text-secondary); display: grid; gap: 10px;">
                                <li
                                    v-for="(feature, fIdx) in (plan.features || [])"
                                    :key="fIdx"
                                    style="display: flex; gap: 8px; align-items: flex-start;"
                                >
                                    <span style="color: #4ade80; font-weight: bold; flex-shrink: 0;"><i data-lucide="check" style="width:14px;height:14px;"></i></span>
                                    <span>{{ feature }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            <Link
                                :href="'/membership/payment?plan=' + plan.slug"
                                class="btn-rok btn-rok--full"
                                :class="plan.slug === 'challenger' ? 'btn-rok--gold' : 'btn-rok--outline'"
                                style="text-align: center; text-decoration: none;"
                            >
                                {{ plan.slug === 'challenger' ? 'Buka Fitur Bracket' : (plan.slug === 'immortal' ? 'Pilih Paket Immortal' : 'Pelajari Akses') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TRIAL CTA SECTION -->
        <section class="home-section trial-cta-section" id="trialCta" aria-labelledby="trialCtaHeading">
            <div class="home-section__inner">
                <div class="trial-banner-card">
                    <div class="trial-banner-card__content">
                        <h2 class="trial-banner-title" id="trialCtaHeading">
                            SIAP JADI BAGIAN DARI<br />
                            <span class="text-gold">RULER OF KINGS?</span>
                        </h2>
                        <p class="trial-banner-desc">
                            Tunjukkan bakat terbaikmu! Jalani proses seleksi terstruktur berbasis tes LSM, evaluasi rekaman gameplay oleh manajemen, dan raih kesempatan bergabung dengan divisi roster kompetitif kami.
                        </p>
                        <div class="trial-actions" style="margin-top: 24px;">
                            <Link href="/trial" class="btn-rok btn-rok--gold">DAFTAR SELEKSI SEKARANG &rarr;</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PARTNERS SECTION -->
        <section class="home-section partners-preview" id="partnersSection" aria-labelledby="partnersHeading">
            <div class="home-section__inner">
                <header class="section-header">
                    <h2 class="section-title" id="partnersHeading">OFFICIAL PARTNERS &amp; SPONSORS</h2>
                    <p class="section-description">
                        Didukung oleh brand dan media partner terkemuka yang bersama-sama memajukan ekosistem Ruler Of Kings.
                    </p>
                </header>

                <div class="partners-grid">
                    <a
                        v-for="partner in partners"
                        :key="partner.id"
                        :href="partner.website_url || '#'"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="partner-card"
                    >
                        <img :src="partner.logo_url || '/img/dummy-division.svg'" :alt="partner.name" loading="lazy" />
                    </a>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
