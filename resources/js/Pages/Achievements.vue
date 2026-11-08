<script setup>
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    achievements: { type: Array, default: () => [] },
    years: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const { animateSplitText, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateStaggerFadeIn('.achievement-card', 0.05);

    document.querySelectorAll('.achievement-card').forEach((el) => {
        bindSpringHover(el, 1.02, -3);
    });

    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Prestasi &amp; Hall of Fame | Ruler Of Kings</title>
            <meta name="description" content="Koleksi piala, rekam jejak juara, dan torehan sejarah Ruler Of Kings di berbagai kompetisi." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Achievements'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'ACHIEVEMENTS & TROPHIES' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Bukti konsistensi, kerja keras, dan dedikasi seluruh divisi atlet Ruler Of Kings di panggung turnamen prestisius.' }}
                    </p>
                </div>
            </section>

            <section class="achievements-section">
                <div class="section-inner">
                    <div class="achievements-grid">
                        <article v-for="ach in achievements" :key="ach.id" class="achievement-card">
                            <div class="achievement-card__media">
                                <img :src="ach.image_url || '/img/dummy-trophy.svg'" :alt="ach.title" loading="lazy" />
                                <span class="achievement-card__year">{{ ach.event_year || ach.year }}</span>
                            </div>
                            <div class="achievement-card__info">
                                <span class="achievement-card__rank">{{ ach.placement_rank || 'JUARA 1' }}</span>
                                <h3 class="achievement-card__title">{{ ach.title }}</h3>
                                <p class="achievement-card__meta">
                                    {{ ach.team_name || 'Ruler Of Kings' }} &bull; {{ ach.tournament_name }}
                                </p>
                            </div>
                        </article>
                    </div>

                    <div v-if="!achievements.length" style="text-align: center; padding: 40px; color: var(--rok-silver);">
                        Belum ada data prestasi yang ditampilkan.
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
