<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    heroSlides: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    achievements: { type: Array, default: () => [] },
    modules: { type: Array, default: () => [] },
    partners: { type: Array, default: () => [] },
    demoTournament: { type: Object, default: () => null },
    leadership: { type: Array, default: () => [] },
    membershipPlans: { type: Array, default: () => [] },
});

const currentSlide = ref(0);
const progressBarRef = ref(null);
const { animateSplitText, animateHeroEntrance, bindSpringHover, animateStaggerFadeIn, initScrollProgress } = useRokAnimations();

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
    if (progressBarRef.value) {
        initScrollProgress(progressBarRef.value);
    }

    animateHeroEntrance('.hero-title-text', '.hero-subtitle-text', '.hero-action-buttons');
    animateStaggerFadeIn('.division-card', 0.06);

    document.querySelectorAll('.btn-rok, .achievement-card, .division-card').forEach((el) => {
        bindSpringHover(el, 1.02, -3);
    });

    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>ROK Revamp | Ruler Of Kings</title>
            <meta name="description" content="Ruler Of Kings (ROK) — Competitive Esports Organization focused on competition, development, discipline and unity." />
        </Head>

        <!-- Kinetic Scroll Progress Indicator (Motion One) -->
        <div ref="progressBarRef" class="scroll-progress-bar" style="position: fixed; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #d4af37, #f59e0b, #cbd5e1); transform-origin: 0%; z-index: 9999;"></div>

        <!-- HERO SLIDER SECTION -->
        <section class="hero-slider" id="heroSlider" aria-roledescription="carousel">
            <div class="hero-slider__track">
                <article
                    v-for="(slide, idx) in heroSlides"
                    :key="slide.id || idx"
                    class="hero-slide"
                    :class="{ 'is-active': idx === currentSlide }"
                    :aria-hidden="idx !== currentSlide"
                >
                    <div class="hero-slide__backdrop">
                        <img
                            :src="slide.image_url || '/img/dummy-division.svg'"
                            :alt="slide.title"
                            class="hero-slide__image"
                            loading="lazy"
                        />
                        <div class="hero-slide__overlay"></div>
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

                <!-- Fallback slide if database is empty -->
                <article v-if="!heroSlides.length" class="hero-slide is-active">
                    <div class="hero-slide__backdrop">
                        <div class="hero-slide__overlay"></div>
                    </div>
                    <div class="hero-slide__content">
                        <div class="site-container hero-slide__inner">
                            <h1 class="hero-slide__title hero-title-text">RULER OF KINGS</h1>
                            <p class="hero-slide__subtitle hero-subtitle-text">
                                ONE VISION. ONE TEAM. ONE KINGDOM. Organisasi esports kompetitif berbasis disiplin, integritas, dan mental juara.
                            </p>
                            <div class="hero-slide__cta hero-action-buttons">
                                <Link href="/trial" class="btn-rok btn-rok--gold">GABUNG TRIAL SEKARANG</Link>
                                <Link href="/bracket" class="btn-rok btn-rok--outline">BUKA BRACKET ENGINE</Link>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Carousel Controls -->
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

            <!-- Indicators -->
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

        <!-- COMPETITIVE DIVISIONS SECTION -->
        <section class="home-section teams-preview" id="teamsPreview" aria-labelledby="teamsPreviewTitle">
            <div class="home-section__inner">
                <header class="section-header">
                    <h2 class="section-title" id="teamsPreviewTitle">DIVISI KOMPETITIF</h2>
                    <p class="section-description">
                        Kenali divisi dan atlet profesional Ruler Of Kings yang berjuang membawa panji kerajaan di panggung esports nasional.
                    </p>
                </header>

                <div class="divisions-grid">
                    <article v-for="div in divisions" :key="div.id" class="division-card">
                        <div class="division-card__visual">
                            <img :src="div.logo_url || '/img/dummy-division.svg'" :alt="div.name" loading="lazy" />
                            <span class="division-card__game-tag">{{ div.game_name }}</span>
                        </div>
                        <div class="division-card__body">
                            <h3 class="division-card__title">{{ div.name }}</h3>
                            <p class="division-card__desc">{{ div.description }}</p>
                            <div class="division-card__footer">
                                <span class="division-card__roster-count">{{ div.active_players_count || (div.active_players ? div.active_players.length : 0) }} Roster Aktif</span>
                                <Link href="/teams" class="division-card__link">Lihat Roster &rarr;</Link>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ACHIEVEMENTS SECTION -->
        <section class="home-section achievements-preview" id="achievementsPreview" aria-labelledby="achievementsTitle">
            <div class="home-section__inner">
                <header class="section-header">
                    <h2 class="section-title" id="achievementsTitle">PRESTASI &amp; KEJAYAAN</h2>
                    <p class="section-description">
                        Jejak pencapaian dan trofi yang membuktikan dedikasi serta konsistensi Ruler Of Kings.
                    </p>
                </header>

                <div class="achievements-grid">
                    <article v-for="ach in achievements" :key="ach.id" class="achievement-card">
                        <div class="achievement-card__media">
                            <img :src="ach.image_url || '/img/dummy-trophy.svg'" :alt="ach.title" loading="lazy" />
                            <span class="achievement-card__year">{{ ach.year }}</span>
                        </div>
                        <div class="achievement-card__info">
                            <span class="achievement-card__rank">{{ ach.placement_rank || 'CHAMPION' }}</span>
                            <h3 class="achievement-card__title">{{ ach.title }}</h3>
                            <p class="achievement-card__meta">{{ ach.team_name }} &bull; {{ ach.tournament_name }}</p>
                        </div>
                    </article>
                </div>

                <div style="text-align: center; margin-top: 36px;">
                    <Link href="/achievements" class="btn-rok btn-rok--outline">Lihat Seluruh Prestasi &rarr;</Link>
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
        <section class="home-section membership-preview-section" id="membershipTiers" aria-labelledby="membershipHeading">
            <div class="home-section__inner">
                <header class="section-header" style="text-align: center; margin-bottom: 40px;">
                    <h2 class="section-title" id="membershipHeading">PAKET AKSES &amp; KEANGGOTAAN ORGANIZER</h2>
                    <p class="section-description" style="max-width: 720px; margin: 0 auto;">
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
