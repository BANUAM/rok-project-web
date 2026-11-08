<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    activeTournament: { type: Object, default: () => null },
    tournaments: { type: Array, default: () => [] },
    membershipPlans: { type: Array, default: () => [] },
    pageHeader: { type: Object, default: () => null },
});

const currentStep = ref(1);
const tournamentName = ref(props.activeTournament?.name || 'ROK Community Championship');
const format = ref('single_elimination');
const teamCount = ref(8);
const teams = ref(
    Array.from({ length: 8 }, (_, i) => ({
        name: `Nama Tim ${i + 1}`,
        wa: '',
    }))
);
const liveLinkCopied = ref(false);

const { animateSplitText, bindSpringHover } = useRokAnimations();

const updateTeamCount = (val) => {
    teamCount.value = Number(val);
    const count = teamCount.value;
    if (teams.value.length < count) {
        for (let i = teams.value.length; i < count; i++) {
            teams.value.push({ name: `Nama Tim ${i + 1}`, wa: '' });
        }
    } else {
        teams.value = teams.value.slice(0, count);
    }
};

const copyLiveLink = () => {
    const url = window.location.origin + '/bracket/live/' + (props.activeTournament?.slug || 'rok-invitational-season-1');
    navigator.clipboard.writeText(url).then(() => {
        liveLinkCopied.value = true;
        setTimeout(() => {
            liveLinkCopied.value = false;
        }, 2000);
    });
};

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateSplitText('.cyber-dock-title', { y: 16, duration: 0.6, delay: 0.25 });
    
    const dock = document.querySelector('.studio-cyber-dock');
    if (dock) bindSpringHover(dock, 1.015, -3);

    document.querySelectorAll('.btn-rok, .plan-card, .cyber-chip-btn').forEach((el) => {
        bindSpringHover(el, 1.03, -2);
    });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Tournament Bracket Engine | Ruler Of Kings</title>
            <meta name="description" content="Platform manajemen bracket turnamen esports profesional ROK. Buat bagan pertandingan, acak seeding tim, dan pantau live score." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Tournament Engine'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'BUILD. COMPETE. CONQUER.' }}</h1>
                    <p class="page-subtitle-gold">
                        Your Tournament. Your Bracket. Your Champion.
                    </p>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Kelola turnamen esports komunitasmu dengan bagan pertandingan otomatis, seeding tim berimbang, dan sistem live score real-time.' }}
                    </p>

                    <!-- Non-Rectangular Cyber Island Dock (Organizer Portal) -->
                    <div class="studio-portal-banner studio-cyber-dock">
                        <div class="studio-portal-banner__info">
                            <span class="studio-portal-badge">
                                <i data-lucide="crown" style="width:13px;height:13px;"></i> ORGANIZER PORTAL
                            </span>
                            <h3 class="portal-island-title cyber-dock-title">ROK BRACKET STUDIO</h3>
                            <p>
                                Kelola multi-turnamen, kustomisasi logo tim, kontak WhatsApp kapten, dan bagikan tautan live bracket.
                            </p>
                        </div>
                        <div class="studio-portal-banner__actions">
                            <Link href="/bracket/studio" class="cyber-chip-btn cyber-chip-btn--primary">
                                <i data-lucide="key" style="width:13px;height:13px;"></i> Masuk Studio
                            </Link>
                            <a
                                v-if="activeTournament?.slug"
                                :href="'/bracket/live/' + activeTournament.slug"
                                target="_blank"
                                class="cyber-chip-btn cyber-chip-btn--secondary"
                            >
                                <i data-lucide="external-link" style="width:13px;height:13px;"></i> Live View
                            </a>
                            <Link href="/bracket/free" class="cyber-chip-btn cyber-chip-btn--secondary">
                                <i data-lucide="gamepad-2" style="width:13px;height:13px;"></i> Versi Gratis
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- PAGINATED BRACKET GENERATOR WIZARD -->
            <section class="bracket-generator-section" style="padding-top: 10px;">
                <div class="site-container" style="max-width: 1000px; margin: 0 auto;">
                    <!-- Pagination Step Indicators (Minimal Modern Tabs) -->
                    <div class="wizard-steps-bar" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 24px;">
                        <button
                            type="button"
                            class="wizard-step-btn"
                            :class="{ 'is-active': currentStep === 1 }"
                            style="padding: 7px 16px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: var(--rok-text-secondary); cursor: pointer; font-size: 12px; font-weight: 700;"
                            @click="currentStep = 1"
                        >
                            1. Data Turnamen
                        </button>
                        <button
                            type="button"
                            class="wizard-step-btn"
                            :class="{ 'is-active': currentStep === 2 }"
                            style="padding: 7px 16px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: var(--rok-text-secondary); cursor: pointer; font-size: 12px; font-weight: 700;"
                            @click="currentStep = 2"
                        >
                            2. Daftar Tim &amp; Kontak
                        </button>
                        <button
                            type="button"
                            class="wizard-step-btn"
                            :class="{ 'is-active': currentStep === 3 }"
                            style="padding: 7px 16px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: var(--rok-text-secondary); cursor: pointer; font-size: 12px; font-weight: 700;"
                            @click="currentStep = 3"
                        >
                            3. Live Bagan Pertandingan
                        </button>
                    </div>

                    <!-- Step 1: Tournament Info -->
                    <div v-show="currentStep === 1" class="wizard-card" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <h3 style="margin-bottom: 20px;">Langkah 1: Tentukan Informasi Turnamen</h3>
                        <div class="wizard-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div>
                                <label class="form-label" style="display:block;margin-bottom:8px;font-size:12px;font-weight:700;color:var(--rok-silver);">NAMA TURNAMEN</label>
                                <input v-model="tournamentName" type="text" class="rok-input" style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);" />
                            </div>
                            <div>
                                <label class="form-label" style="display:block;margin-bottom:8px;font-size:12px;font-weight:700;color:var(--rok-silver);">FORMAT PERTANDINGAN</label>
                                <select v-model="format" class="rok-input" style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);">
                                    <option value="single_elimination">Single Elimination</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="display:block;margin-bottom:8px;font-size:12px;font-weight:700;color:var(--rok-silver);">JUMLAH TIM PESERTA</label>
                                <select :value="teamCount" @change="updateTeamCount($event.target.value)" class="rok-input" style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);">
                                    <option :value="4">4 Tim</option>
                                    <option :value="8">8 Tim</option>
                                    <option :value="16">16 Tim</option>
                                </select>
                            </div>
                        </div>

                        <div style="text-align: right; margin-top: 24px;">
                            <button type="button" class="btn-rok btn-rok--gold" @click="currentStep = 2">
                                Selanjutnya: Isi Tim Peserta &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Team Roster & Contact -->
                    <div v-show="currentStep === 2" class="wizard-card" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <h3 style="margin-bottom: 20px;">Langkah 2: Daftar Tim &amp; Kontak WhatsApp</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px;">
                            <div v-for="(t, idx) in teams" :key="idx" style="background: var(--rok-bg); padding: 12px 14px; border-radius: 6px; border: 1px solid var(--rok-surface-border); display: flex; align-items: center; gap: 8px;">
                                <span style="font-weight: 700; color: #d4af37; font-size: 12px; min-width: 24px;">#{{ idx + 1 }}</span>
                                <input v-model="t.name" type="text" placeholder="Nama Tim" style="flex: 1; background: transparent; border: none; color: var(--rok-text-bright); outline: none; font-size: 13px;" />
                                <input v-model="t.wa" type="text" placeholder="No WA (08...)" style="width: 110px; background: rgba(255,255,255,0.05); border: 1px solid var(--rok-surface-border); border-radius: 4px; padding: 4px 8px; color: var(--rok-text-bright); font-size: 11px;" />
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 24px;">
                            <button type="button" class="btn-rok btn-rok--outline" @click="currentStep = 1">
                                &larr; Kembali
                            </button>
                            <button type="button" class="btn-rok btn-rok--gold" @click="currentStep = 3">
                                Generate &amp; Lihat Bagan &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Bracket Tree & Live Link -->
                    <div v-show="currentStep === 3" class="wizard-card" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--rok-surface-border);">
                            <div>
                                <h3 style="margin: 0 0 4px 0;">{{ tournamentName }}</h3>
                                <span style="color: var(--rok-silver); font-size: 13px;">Format: Single Elimination &bull; {{ teamCount }} Tim</span>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="btn-rok btn-rok--outline" @click="copyLiveLink">
                                    {{ liveLinkCopied ? 'Tersalin!' : 'Salin Link Live' }}
                                </button>
                                <button type="button" class="btn-rok btn-rok--outline" @click="currentStep = 2">
                                    Edit Tim
                                </button>
                            </div>
                        </div>

                        <!-- Single Elimination Tree Visualizer -->
                        <div class="interactive-bracket-wrapper" style="overflow-x: auto; padding: 20px 0;">
                            <div style="display: flex; gap: 40px; min-width: 650px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #d4af37; font-size: 12px; margin-bottom: 12px; text-transform: uppercase;">ROUND 1 (QUARTERFINALS)</div>
                                    <div v-for="i in Math.ceil(teamCount / 2)" :key="i" style="background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 10px 14px; margin-bottom: 16px;">
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                                            <span>{{ teams[(i - 1) * 2]?.name || 'TBD' }}</span>
                                            <span style="font-weight: 700; color: #d4af37;">0</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid var(--rok-surface-border); padding-top: 4px;">
                                            <span>{{ teams[(i - 1) * 2 + 1]?.name || 'TBD' }}</span>
                                            <span style="font-weight: 700; color: #d4af37;">0</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #cbd5e1; font-size: 12px; margin-bottom: 12px; text-transform: uppercase;">ROUND 2 (SEMIFINALS)</div>
                                    <div v-for="i in Math.max(1, Math.ceil(teamCount / 4))" :key="i" style="background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 10px 14px; margin-bottom: 30px; margin-top: 20px;">
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                                            <span style="color: var(--rok-silver);">Pemenang M{{ (i - 1) * 2 + 1 }}</span>
                                            <span style="font-weight: 700;">-</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid var(--rok-surface-border); padding-top: 4px;">
                                            <span style="color: var(--rok-silver);">Pemenang M{{ (i - 1) * 2 + 2 }}</span>
                                            <span style="font-weight: 700;">-</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #f59e0b; font-size: 12px; margin-bottom: 12px; text-transform: uppercase;">GRAND FINAL</div>
                                    <div style="background: var(--rok-bg); border: 1px solid #d4af37; border-radius: 6px; padding: 12px 14px; margin-top: 50px;">
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                                            <span style="color: #d4af37; font-weight: 700;">Finalis 1</span>
                                            <span style="font-weight: 700;">-</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid var(--rok-surface-border); padding-top: 6px;">
                                            <span style="color: #d4af37; font-weight: 700;">Finalis 2</span>
                                            <span style="font-weight: 700;">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 20px;">
                            <Link href="/bracket/free" class="btn-rok btn-rok--outline">Buka Generator Edisi Gratis &rarr;</Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
