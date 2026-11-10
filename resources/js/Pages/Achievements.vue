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
    animateStaggerFadeIn('.hall-acclaim-row', 0.04);

    document.querySelectorAll('.hall-acclaim-row').forEach((el) => {
        bindSpringHover(el, 1.01, -2);
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

            <section class="achievements-page-section">
                <div class="section-inner">
                    <!-- CHAMPIONSHIP METRICS BANNER -->
                    <div class="championship-metrics-banner">
                        <div class="championship-metric-item">
                            <span class="championship-metric-val">18+</span>
                            <span class="championship-metric-lbl">Verified Championships</span>
                        </div>
                        <div class="championship-metric-item">
                            <span class="championship-metric-val">78.4%</span>
                            <span class="championship-metric-lbl">Career Match Win Rate</span>
                        </div>
                        <div class="championship-metric-item">
                            <span class="championship-metric-val">04</span>
                            <span class="championship-metric-lbl">Active Pro Divisions</span>
                        </div>
                        <div class="championship-metric-item">
                            <span class="championship-metric-val">100%</span>
                            <span class="championship-metric-lbl">Sanctioned Tournaments</span>
                        </div>
                    </div>

                    <!-- HALL OF ACCLAIM LEDGER -->
                    <div class="hall-acclaim-ledger">
                        <div class="hall-acclaim-header">
                            <span>Tahun</span>
                            <span>Gelar &amp; Kejuaraan</span>
                            <span>Divisi Skuad</span>
                            <span>Turnamen / Rekam Jejak</span>
                            <span style="text-align:right;">Status</span>
                        </div>

                        <div v-for="ach in achievements" :key="ach.id" class="hall-acclaim-row">
                            <span class="hall-acclaim-year">{{ ach.event_year || ach.year }}</span>
                            <div class="hall-acclaim-title-box">
                                <span class="hall-acclaim-rank-badge">{{ ach.placement_rank || 'CHAMPION' }}</span>
                                <h3>{{ ach.title }}</h3>
                            </div>
                            <span class="hall-acclaim-team">{{ ach.team_name || 'Ruler Of Kings' }}</span>
                            <div class="hall-acclaim-desc">{{ ach.tournament_name || ach.caption }}</div>
                            <div class="hall-acclaim-status">VERIFIED TITLE</div>
                        </div>

                        <div v-if="!achievements.length" style="text-align: center; padding: 48px; color: #64748b; font-family: var(--font-mono, monospace);">
                            TIDAK ADA DATA PRESTASI DITEMUKAN PADA FILTER INI.
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
