<script setup>
import { onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    founders: { type: Array, default: () => [] },
    divisions: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const { animateSplitText } = useRokAnimations();

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Struktur Tim &amp; Roster Atlet | Ruler Of Kings</title>
            <meta name="description" content="Kenali jajaran founder, manajemen, dan atlet profesional esports Ruler Of Kings." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Teams'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'Tim & roster aktif' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Orang-orang di balik Ruler Of Kings: para pendiri, staf, dan atlet yang membawa nama ROK di kancah nasional.' }}
                    </p>
                </div>
            </section>

            <!-- Pendiri -->
            <section v-if="founders.length" class="teams-section">
                <div class="section-inner">
                    <header class="section-header">
                        <span class="eyebrow">Kepemimpinan</span>
                        <h2 class="section-title">Para pendiri</h2>
                        <p class="section-description">
                            Sosok yang meletakkan visi dan membangun fondasi Ruler Of Kings.
                        </p>
                    </header>

                    <div class="leader-grid">
                        <article v-for="founder in founders" :key="founder.id" class="leader-card" data-rok-animate="scroll-reveal">
                            <div class="leader-card__photo">
                                <img :src="founder.photo_url || '/img/dummy-founder.svg'" :alt="founder.name" loading="lazy" />
                            </div>
                            <h3 class="leader-card__name">{{ founder.name }}</h3>
                            <div class="leader-card__role">{{ founder.role_title }}</div>
                            <p class="leader-card__bio">{{ founder.bio }}</p>
                            <div v-if="founder.instagram_url || founder.linkedin_url" class="leader-card__links">
                                <a v-if="founder.instagram_url" :href="founder.instagram_url" target="_blank" rel="noopener noreferrer">Instagram</a>
                                <a v-if="founder.linkedin_url" :href="founder.linkedin_url" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- Divisi & roster -->
            <section class="teams-section" style="padding-top: 24px;">
                <div class="section-inner">
                    <header class="section-header">
                        <span class="eyebrow">Divisi</span>
                        <h2 class="section-title">Roster aktif</h2>
                        <p class="section-description">
                            Hanya atlet yang lolos seleksi dan terdaftar resmi yang tampil sebagai roster aktif.
                        </p>
                    </header>

                    <article v-for="division in divisions" :key="division.id" class="team-division" data-rok-animate="scroll-reveal">
                        <div class="team-division__head">
                            <div>
                                <span class="team-division__game">{{ division.game_name }}</span>
                                <h3 class="team-division__name">{{ division.name }}</h3>
                            </div>
                            <p class="team-division__desc">{{ division.description }}</p>
                        </div>

                        <div class="roster-table">
                            <div class="roster-row roster-row--head">
                                <span>Pemain</span>
                                <span>Nama</span>
                                <span>Peran</span>
                            </div>

                            <div v-for="player in (division.active_players || [])" :key="player.id" class="roster-row">
                                <div class="roster-player">
                                    <img :src="player.photo_url || '/img/dummy-player.svg'" :alt="player.nickname || player.in_game_name" loading="lazy" />
                                    <strong>{{ player.nickname || player.in_game_name }}</strong>
                                </div>
                                <span class="roster-real">{{ player.real_name }}</span>
                                <span class="roster-role">{{ player.role }}</span>
                            </div>

                            <div v-if="!division.active_players || !division.active_players.length" class="roster-empty">
                                Roster divisi ini sedang disiapkan.
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
