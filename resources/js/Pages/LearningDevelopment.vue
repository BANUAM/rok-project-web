<script setup>
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    modules: { type: Array, default: () => [] },
    plans: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const { animateSplitText, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateStaggerFadeIn('.module-card', 0.06);

    document.querySelectorAll('.module-card, .plan-card').forEach((el) => {
        bindSpringHover(el, 1.02, -3);
    });

    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>ROK Academy &amp; Learning Development | Ruler Of Kings</title>
            <meta name="description" content="Modul pembelajaran strategi esports, playbook makro, dan pelatihan mental bertanding resmi ROK." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Learning'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'ROK ACADEMY & DEVELOPMENT' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Pusat pembelajaran taktik, manajemen makro, dan analisa replay game profesional yang dipandu langsung oleh analis ROK.' }}
                    </p>
                </div>
            </section>

            <!-- FEATURED VIDEO LESSON -->
            <section class="featured-video-section">
                <div class="section-inner">
                    <div class="featured-video-card">
                        <div class="featured-video-player">
                            <iframe
                                src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"
                                title="Macro Tempo Masterclass"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                style="width:100%;height:100%;min-height:360px;border-radius:var(--radius-md);"
                            ></iframe>
                        </div>
                        <div class="featured-video-desc">
                            <h2>MACRO TEMPO: EARLY GAME DOMINATION</h2>
                            <p>
                                Bedah detail rotasi 90 detik awal dan kontrol vision river. Trik membaca rute jungler lawan dan mengeksekusi dive turret dengan aman.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CURRICULUM MODULES -->
            <section class="curriculum-section">
                <div class="section-inner">
                    <header class="section-header">
                        <h2 class="section-title">KURIKULUM TAKTIK RESMI</h2>
                        <p class="section-description">
                            Materi terstruktur mulai dari fundamental mekanik hingga analisis meta turnamen tingkat tinggi.
                        </p>
                    </header>

                    <div class="modules-grid">
                        <article v-for="mod in modules" :key="mod.id" class="module-card">
                            <div class="module-card__header">
                                <span class="module-card__level">{{ mod.level_badge || 'MODUL' }}</span>
                                <h3 class="module-card__title">{{ mod.title }}</h3>
                            </div>
                            <p class="module-card__desc">{{ mod.description }}</p>

                            <ul class="lesson-list">
                                <li v-for="lesson in (mod.lessons || [])" :key="lesson.id" class="lesson-item">
                                    <span class="lesson-item__icon"><i data-lucide="play-circle" style="width:14px;height:14px;"></i></span>
                                    <span class="lesson-item__title">{{ lesson.title }}</span>
                                    <span class="lesson-item__duration">{{ lesson.duration_minutes }}m</span>
                                </li>
                            </ul>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
