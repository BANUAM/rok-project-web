<script setup>
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    founders: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const { animateSplitText, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateStaggerFadeIn('.team-member-card, .division-block', 0.05);

    document.querySelectorAll('.team-member-card, .player-chip').forEach((el) => {
        bindSpringHover(el, 1.02, -2);
    });

    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Struktur Tim &amp; Roster Atlet | Ruler Of Kings</title>
            <meta name="description" content="Kenali jajaran founder, manajemen eksekutif, dan atlet profesional esports Ruler Of Kings." />
        </Head>

        <div class="page-container">
            <section class="page-hero">
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'STRUKTUR DIVISI & ROSTER AKTIF' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Pilar kepemimpinan dan dedikasi talenta terbaik yang mewakili panji kerajaan Ruler Of Kings di kancah nasional.' }}
                    </p>
                </div>
            </section>

            <!-- FOUNDERS & EXECUTIVE LEADERSHIP -->
            <section class="teams-section">
                <div class="section-inner">
                    <header class="section-header">
                        <h2 class="section-title">THE FOUNDERS</h2>
                        <p class="section-description">
                            Sosok di balik lahirnya Ruler Of Kings yang mengarahkan visi dan dedikasi organisasi.
                        </p>
                    </header>

                    <div class="founders-grid">
                        <article v-for="founder in founders" :key="founder.id" class="team-member-card">
                            <div class="team-member-card__avatar">
                                <img :src="founder.photo_url || '/img/dummy-division.svg'" :alt="founder.name" loading="lazy" />
                            </div>
                            <div class="team-member-card__info">
                                <h3 class="team-member-card__name">{{ founder.name }}</h3>
                                <p class="team-member-card__role">{{ founder.role_title }}</p>
                                <p class="team-member-card__bio">{{ founder.bio }}</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- COMPETITIVE DIVISIONS & ROSTER -->
            <section class="teams-section" style="padding-top: 0;">
                <div class="section-inner">
                    <header class="section-header">
                        <h2 class="section-title">STRUKTUR DIVISI &amp; ROSTER AKTIF</h2>
                        <p class="section-description">
                            Atlet pilihan yang menjalani latihan intensif dan strategi taktis tingkat tinggi.
                        </p>
                    </header>

                    <div class="division-blocks-list">
                        <article v-for="division in divisions" :key="division.id" class="division-block">
                            <div class="division-block__header">
                                <img :src="division.logo_url || '/img/dummy-division.svg'" :alt="division.name" class="division-block__logo" />
                                <div>
                                    <h3 class="division-block__title">{{ division.name }}</h3>
                                    <span class="division-block__game">{{ division.game_name }}</span>
                                </div>
                            </div>
                            <p class="division-block__desc">{{ division.description }}</p>

                            <div class="roster-grid">
                                <div
                                    v-for="player in (division.active_players || [])"
                                    :key="player.id"
                                    class="player-chip"
                                >
                                    <img :src="player.photo_url || '/img/dummy-division.svg'" :alt="player.in_game_name" class="player-chip__photo" />
                                    <div class="player-chip__info">
                                        <strong class="player-chip__ign">{{ player.in_game_name }}</strong>
                                        <span class="player-chip__name">{{ player.real_name }}</span>
                                        <span class="player-chip__role">{{ player.role }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
