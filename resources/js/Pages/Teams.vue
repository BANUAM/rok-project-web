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
    animateStaggerFadeIn('.executive-dossier-card, .division-block', 0.05);

    document.querySelectorAll('.executive-dossier-card').forEach((el) => {
        bindSpringHover(el, 1.01, -2);
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
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Teams'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'STRUKTUR DIVISI & ROSTER AKTIF' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Pilar kepemimpinan dan dedikasi talenta terbaik yang mewakili panji kerajaan Ruler Of Kings di kancah nasional.' }}
                    </p>
                </div>
            </section>

            <!-- FOUNDERS & EXECUTIVE LEADERSHIP (CORPORATE DOSSIER) -->
            <section class="teams-section">
                <div class="section-inner">
                    <header class="section-header">
                        <span class="teams-section-kicker">EXECUTIVE DOSSIER // 01</span>
                        <h2 class="section-title">THE FOUNDERS</h2>
                        <p class="section-description">
                            Sosok di balik lahirnya Ruler Of Kings yang mengarahkan visi dan dedikasi organisasi.
                        </p>
                    </header>

                    <div class="executive-dossier-grid">
                        <article v-for="(founder, fIdx) in founders" :key="founder.id" class="executive-dossier-card">
                            <div class="executive-portrait-frame">
                                <img :src="founder.photo_url || '/img/dummy-division.svg'" :alt="founder.name" loading="lazy" />
                            </div>
                            <div class="executive-dossier-body">
                                <span class="executive-kicker">DIR // 0{{ fIdx + 1 }}</span>
                                <h3 class="executive-name">{{ founder.name }}</h3>
                                <div class="executive-role-title">{{ founder.role_title }}</div>
                                <p class="executive-bio">{{ founder.bio }}</p>
                                <div class="executive-socials">
                                    <a v-if="founder.instagram_url" :href="founder.instagram_url" target="_blank" rel="noopener noreferrer">Instagram &rarr;</a>
                                    <a v-if="founder.linkedin_url" :href="founder.linkedin_url" target="_blank" rel="noopener noreferrer">LinkedIn &rarr;</a>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- COMPETITIVE DIVISIONS & ROSTER (TACTICAL LEDGER) -->
            <section class="teams-section" style="padding-top: 0;">
                <div class="section-inner">
                    <header class="section-header">
                        <span class="teams-section-kicker">COMPETITIVE ROSTERS // 02</span>
                        <h2 class="section-title">STRUKTUR DIVISI &amp; ROSTER AKTIF</h2>
                        <p class="section-description">
                            Atlet pilihan yang menjalani latihan intensif dan strategi taktis tingkat tinggi.
                        </p>
                    </header>

                    <div class="division-blocks-list" style="display:flex; flex-direction:column; gap:48px;">
                        <article v-for="division in divisions" :key="division.id" class="division-block">
                            <div class="division-info-strip">
                                <div>
                                    <span class="badge-mini" style="font-family:var(--font-mono, monospace); font-size:11px; color:#f59e0b; text-transform:uppercase; letter-spacing:1px;">{{ division.game_name }}</span>
                                    <h3 class="division-info-title">{{ division.name }}</h3>
                                    <p class="division-info-desc">{{ division.description }}</p>
                                </div>
                                <div class="division-stats-pill">
                                    <strong>{{ (division.active_players || []).length }}</strong>
                                    <span>Anggota Roster Aktif</span>
                                </div>
                            </div>

                            <div class="tactical-roster-ledger">
                                <div class="roster-ledger-header">
                                    <span>No.</span>
                                    <span>Atlet / IGN</span>
                                    <span>Nama Lengkap</span>
                                    <span>Peran Taktis</span>
                                    <span style="text-align:right;">Status Roster</span>
                                </div>

                                <div
                                    v-for="(player, pIdx) in (division.active_players || [])"
                                    :key="player.id"
                                    class="roster-ledger-row"
                                >
                                    <span class="roster-ledger-num">0{{ pIdx + 1 }}</span>
                                    <div class="roster-ledger-athlete">
                                        <img :src="player.photo_url || '/img/dummy-division.svg'" :alt="player.in_game_name" class="roster-athlete-avatar" />
                                        <strong class="roster-athlete-ign">{{ player.in_game_name }}</strong>
                                    </div>
                                    <span class="roster-ledger-realname">{{ player.real_name }}</span>
                                    <div>
                                        <span class="roster-role-badge">{{ player.role }}</span>
                                    </div>
                                    <div class="roster-status-badge">CONTRACTED</div>
                                </div>

                                <div v-if="!division.active_players || !division.active_players.length" style="padding: 24px; text-align: center; color: #64748b; font-family: var(--font-mono, monospace); font-size: 13px;">
                                    ROSTER IN FINALIZATION // PENDING CONTRACT REGISTRATION
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
