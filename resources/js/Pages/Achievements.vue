<script setup>
import { computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    achievements: { type: Array, default: () => [] },
    years: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const { animateSplitText } = useRokAnimations();

// Ringkasan dihitung dari data yang tampil, bukan angka karangan.
const yearsCount = computed(() => new Set(props.achievements.map((a) => a.event_year || a.year).filter(Boolean)).size);
const teamsCount = computed(() => new Set(props.achievements.map((a) => a.team_name).filter(Boolean)).size);

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Prestasi &amp; Hall of Fame | Ruler Of Kings</title>
            <meta name="description" content="Rekam jejak juara dan torehan sejarah Ruler Of Kings di berbagai kompetisi." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Achievements'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'Prestasi' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Bukti konsistensi, kerja keras, dan dedikasi seluruh divisi Ruler Of Kings di panggung turnamen.' }}
                    </p>
                </div>
            </section>

            <section class="achievements-page-section">
                <div class="section-inner">
                    <div v-if="achievements.length" class="stat-row" style="margin-top: 0; margin-bottom: 56px; border-top: none; padding-top: 0;">
                        <div class="stat-item">
                            <span class="stat-item__value">{{ achievements.length }}</span>
                            <span class="stat-item__label">Prestasi tercatat</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-item__value">{{ yearsCount }}</span>
                            <span class="stat-item__label">Tahun kompetisi</span>
                        </div>
                        <div v-if="teamsCount" class="stat-item">
                            <span class="stat-item__value">{{ teamsCount }}</span>
                            <span class="stat-item__label">Tim berprestasi</span>
                        </div>
                    </div>

                    <div class="achv-list">
                        <div class="achv-row achv-row--head">
                            <span>Tahun</span>
                            <span>Gelar</span>
                            <span>Tim</span>
                            <span>Keterangan</span>
                        </div>

                        <div v-for="ach in achievements" :key="ach.id" class="achv-row" data-rok-animate="scroll-reveal">
                            <span class="achv-year">{{ ach.event_year || ach.year }}</span>
                            <h3 class="achv-title">{{ ach.title }}</h3>
                            <span class="achv-team">{{ ach.team_name || 'Ruler Of Kings' }}</span>
                            <span class="achv-note">{{ ach.caption }}</span>
                        </div>

                        <div v-if="!achievements.length" class="empty-state-box">
                            Belum ada data prestasi yang ditampilkan.
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
