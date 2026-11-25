<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    tournament: { type: Object, default: () => null },
    pageHeader: { type: Object, default: () => null },
});

const currentStep = ref(1);
const tournamentName = ref(props.tournament?.name || 'ROK Free Community Tournament');
const teamCount = ref(props.tournament?.team_count || 8);
const teams = ref(
    Array.from({ length: 8 }, (_, i) => ({
        name: `Nama Tim ${i + 1}`,
        wa: '',
    }))
);
const liveLinkCopied = ref(false);
const praTourneyLinkCopied = ref(false);

const enableCustomBo = ref(false);
const semiBo = ref('BO3');
const finalBo = ref('BO5');
const enableBronzeMatch = ref(true);
const bronzeBo = ref('BO3');

const getWinTarget = (bo) => {
    if (bo === 'BO3') return 2;
    if (bo === 'BO5') return 3;
    if (bo === 'BO7') return 4;
    return 1;
};

// Edit match modal state
const editingMatch = ref(null);
const editScore1 = ref(0);
const editScore2 = ref(0);

const clampScores = () => {
    if (!editingMatch.value) return;
    const target = getWinTarget(editingMatch.value.bo);
    if (editScore1.value > target) editScore1.value = target;
    if (editScore1.value < 0) editScore1.value = 0;
    if (editScore2.value > target) editScore2.value = target;
    if (editScore2.value < 0) editScore2.value = 0;
    if (editScore1.value === target && editScore2.value === target) {
        editScore2.value = Math.max(0, target - 1);
    }
};

const openEditModal = (title, bo, teamA, teamB, s1 = 0, s2 = 0) => {
    editingMatch.value = { title, bo, teamA, teamB };
    const target = getWinTarget(bo);
    editScore1.value = Math.min(s1, target);
    editScore2.value = Math.min(s2, target);
    clampScores();
};

const closeEditModal = () => {
    editingMatch.value = null;
};

const { animateSplitText, bindSpringHover } = useRokAnimations();

const copyLiveLink = () => {
    const slug = props.tournament?.slug || 'rok-free-demo';
    const url = window.location.origin + '/bracket/live/' + slug;
    navigator.clipboard.writeText(url).then(() => {
        liveLinkCopied.value = true;
        setTimeout(() => {
            liveLinkCopied.value = false;
        }, 2000);
    });
};

const copyPraTourneyLink = () => {
    const slug = props.tournament?.slug || 'rok-free-demo';
    const url = window.location.origin + '/bracket/pra-tournament-free/' + slug;
    navigator.clipboard.writeText(url).then(() => {
        praTourneyLinkCopied.value = true;
        setTimeout(() => {
            praTourneyLinkCopied.value = false;
        }, 2000);
    });
};

const reshuffleTeams = () => {
    if (!confirm('Acak susunan tim / drawing bagan secara acak?')) {
        return;
    }
    const shuffled = [...teams.value];
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    teams.value = shuffled;
};

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    document.querySelectorAll('.btn-rok').forEach((el) => {
        bindSpringHover(el, 1.02, -2);
    });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Bagan Pertandingan Gratis | Ruler Of Kings</title>
            <meta name="description" content="Generator bracket turnamen gratis ROK dengan sinkronisasi live real-time dan format paginasi profesional." />
        </Head>

        <div class="page-container">
            <section class="page-hero" :class="{ 'page-hero--has-banner': pageHeader?.image_url }">
                <div v-if="pageHeader?.image_url" class="page-hero__bg">
                    <img :src="pageHeader.image_url" :alt="pageHeader.title || 'Tournament'" />
                    <div class="page-hero__overlay"></div>
                </div>
                <div class="page-hero__inner">
                    <span class="sr-only" aria-hidden="true">FREE TOURNAMENT GENERATOR</span>
                    <h1 class="page-title">{{ tournamentName }}</h1>
                    <p class="page-subtitle">
                        Buat turnamen komunitas Anda sendiri secara gratis tanpa perlu registrasi akun. Bagikan link live bracket ke peserta secara instan.
                    </p>
                    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 16px; flex-wrap: wrap;">
                        <a
                            :href="'/bracket/pra-tournament-free/' + (tournament?.slug || 'rok-free-demo')"
                            target="_blank"
                            class="cyber-chip-btn cyber-chip-btn--secondary"
                            title="Portal check-in mandiri tim peserta"
                        >
                            <i data-lucide="clipboard-list" style="width:13px;height:13px;"></i> Portal Pra-Turnamen Free &nearr;
                        </a>
                        <button
                            type="button"
                            class="cyber-chip-btn cyber-chip-btn--secondary"
                            @click="copyPraTourneyLink"
                            title="Salin Link Pra-Turnamen Free"
                        >
                            <i data-lucide="copy" style="width:13px;height:13px;"></i> {{ praTourneyLinkCopied ? 'Link Pra-Turnamen Tersalin!' : 'Salin Link Pra-Turnamen' }}
                        </button>
                    </div>
                </div>
            </section>

            <!-- Paginated Wizard -->
            <section class="bracket-generator-section" style="padding-top: 10px;">
                <div class="site-container" style="max-width: 1000px; margin: 0 auto;">
                    <!-- Wizard Steps Navigation -->
                    <div style="display: flex; justify-content: center; gap: 14px; margin-bottom: 24px;">
                        <button
                            type="button"
                            :class="{ 'btn-rok--gold': currentStep === 1, 'btn-rok--outline': currentStep !== 1 }"
                            class="btn-rok"
                            style="padding: 6px 16px; font-size: 13px;"
                            @click="currentStep = 1"
                        >
                            1. Data Turnamen
                        </button>
                        <button
                            type="button"
                            :class="{ 'btn-rok--gold': currentStep === 2, 'btn-rok--outline': currentStep !== 2 }"
                            class="btn-rok"
                            style="padding: 6px 16px; font-size: 13px;"
                            @click="currentStep = 2"
                        >
                            2. Daftar Tim &amp; Kontak
                        </button>
                        <button
                            type="button"
                            :class="{ 'btn-rok--gold': currentStep === 3, 'btn-rok--outline': currentStep !== 3 }"
                            class="btn-rok"
                            style="padding: 6px 16px; font-size: 13px;"
                            @click="currentStep = 3"
                        >
                            3. Live Bagan
                        </button>
                    </div>

                    <!-- Step 1 -->
                    <div v-show="currentStep === 1" class="wizard-box" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <h3 style="margin-bottom: 18px;">Informasi Turnamen</h3>
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 18px;">
                            <div>
                                <label style="display:block;margin-bottom:6px;font-size:12px;font-weight:700;color:var(--rok-silver);">NAMA TURNAMEN</label>
                                <input v-model="tournamentName" type="text" class="rok-input" style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);" />
                            </div>
                            <div>
                                <label style="display:block;margin-bottom:6px;font-size:12px;font-weight:700;color:var(--rok-silver);">JUMLAH TIM</label>
                                <select v-model="teamCount" class="rok-input" style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);">
                                    <option :value="4">4 Tim</option>
                                    <option :value="8">8 Tim</option>
                                    <option :value="16">16 Tim</option>
                                </select>
                            </div>
                        </div>

                        <!-- Single Elimination Best-of & Juara 3 Options -->
                        <div style="margin-top: 18px; padding: 14px 16px; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--rok-surface-border); border-radius: 8px;">
                            <div style="font-size: 12px; color: var(--rok-muted-light); margin-bottom: 8px;">
                                <strong style="color: var(--rok-silver);">Format Seri Default:</strong> Semua babak rata <strong>BO1</strong> dari babak penyisihan hingga Final.
                            </div>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700;">
                                <input type="checkbox" v-model="enableCustomBo" style="accent-color: var(--rok-gold);" />
                                <span>Kustomisasi Format Seri (BO3, BO5, BO7) &amp; Perebutan Juara 3</span>
                            </label>

                            <div v-if="enableCustomBo" style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--rok-surface-border); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                <div>
                                    <label class="form-label" style="display:block;margin-bottom:6px;font-size:11px;font-weight:700;color:var(--rok-silver);">FORMAT SEMI FINAL</label>
                                    <select v-model="semiBo" class="rok-input" style="width:100%;padding:8px 12px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);font-size:12px;">
                                        <option value="BO1">BO1 (Best of 1)</option>
                                        <option value="BO3">BO3 (Best of 3 - Rekomendasi)</option>
                                        <option value="BO5">BO5 (Best of 5)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="display:block;margin-bottom:6px;font-size:11px;font-weight:700;color:var(--rok-silver);">FORMAT GRAND FINAL</label>
                                    <select v-model="finalBo" class="rok-input" style="width:100%;padding:8px 12px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);font-size:12px;">
                                        <option value="BO1">BO1 (Best of 1)</option>
                                        <option value="BO3">BO3 (Best of 3)</option>
                                        <option value="BO5">BO5 (Best of 5 - Rekomendasi)</option>
                                        <option value="BO7">BO7 (Best of 7)</option>
                                    </select>
                                </div>
                                <div style="grid-column: 1 / -1; background: rgba(255, 255, 255, 0.02); padding: 10px 12px; border-radius: 6px;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; font-weight: 700;">
                                        <input type="checkbox" v-model="enableBronzeMatch" style="accent-color: var(--rok-gold);" />
                                        <span>Adakan Pertandingan Perebutan Juara 3 (Bronze Match)</span>
                                    </label>
                                    <div v-if="enableBronzeMatch" style="margin-top: 8px; display: flex; align-items: center; gap: 10px; padding-left: 20px;">
                                        <span style="font-size: 11px; color: var(--rok-silver);">Format Perebutan Juara 3:</span>
                                        <select v-model="bronzeBo" class="rok-input" style="padding: 4px 8px; background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 4px; color: var(--rok-text-bright); font-size: 11px;">
                                            <option value="BO1">BO1</option>
                                            <option value="BO3">BO3 (Rekomendasi)</option>
                                            <option value="BO5">BO5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right; margin-top: 24px;">
                            <button type="button" class="btn-rok btn-rok--gold" @click="currentStep = 2">
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div v-show="currentStep === 2" class="wizard-box" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 18px;">
                            <h3 style="margin: 0;">Daftar Tim &amp; Kontak WhatsApp</h3>
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <a
                                    :href="'/bracket/pra-tournament-free/' + (tournament?.slug || 'rok-free-demo')"
                                    target="_blank"
                                    class="btn-rok btn-rok--outline"
                                    style="padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;"
                                    title="Buka Portal Pra-Turnamen"
                                >
                                    <i data-lucide="clipboard-list" style="width:13px;height:13px;"></i> Portal Pra-Turnamen &nearr;
                                </a>
                                <button
                                    type="button"
                                    class="btn-rok btn-rok--outline"
                                    style="padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;"
                                    @click="reshuffleTeams"
                                    title="Acak Bagan / Drawing Tim"
                                >
                                    <i data-lucide="shuffle" style="width:13px;height:13px;"></i> Drawing Acak
                                </button>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px;">
                            <div v-for="(t, idx) in teams" :key="idx" style="background: var(--rok-bg); padding: 10px 12px; border-radius: 6px; border: 1px solid var(--rok-surface-border); display: flex; align-items: center; gap: 8px;">
                                <span style="font-weight: 700; color: #d4af37; font-size: 12px;">{{ idx + 1 }}.</span>
                                <input v-model="t.name" type="text" placeholder="Nama Tim" style="flex:1;background:transparent;border:none;color:var(--rok-text-bright);outline:none;font-size:13px;" />
                                <input v-model="t.wa" type="text" placeholder="No WA" style="width:100px;background:rgba(255,255,255,0.05);border:1px solid var(--rok-surface-border);border-radius:4px;padding:3px 6px;color:var(--rok-text-bright);font-size:11px;" />
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 24px;">
                            <button type="button" class="btn-rok btn-rok--outline" @click="currentStep = 1">
                                &larr; Kembali
                            </button>
                            <button type="button" class="btn-rok btn-rok--gold" @click="currentStep = 3">
                                Generate &amp; Simpan &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div v-show="currentStep === 3" class="wizard-box" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                            <div>
                                <h3 style="margin: 0;">{{ tournamentName }}</h3>
                                <p style="color: var(--rok-silver); font-size: 13px; margin: 4px 0 0 0;">Single Elimination &bull; {{ teamCount }} Tim</p>
                            </div>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a
                                    :href="'/bracket/pra-tournament-free/' + (tournament?.slug || 'rok-free-demo')"
                                    target="_blank"
                                    class="btn-rok btn-rok--outline"
                                    style="padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;"
                                    title="Buka Portal Pra-Turnamen"
                                >
                                    <i data-lucide="clipboard-list" style="width:13px;height:13px;"></i> Pra-Turnamen &nearr;
                                </a>
                                <button type="button" class="btn-rok btn-rok--outline" style="padding: 6px 12px; font-size: 12px;" @click="copyPraTourneyLink" title="Salin Link Pra-Turnamen">
                                    {{ praTourneyLinkCopied ? 'Tersalin!' : 'Salin Pra-Turnamen' }}
                                </button>
                                <button type="button" class="btn-rok btn-rok--gold" style="padding: 6px 12px; font-size: 12px;" @click="copyLiveLink">
                                    {{ liveLinkCopied ? 'Tersalin!' : 'Salin Link Live' }}
                                </button>
                                <button type="button" class="btn-rok btn-rok--outline" style="padding: 6px 12px; font-size: 12px;" @click="currentStep = 2">
                                    Edit Tim
                                </button>
                            </div>
                        </div>

                        <!-- Bracket Tree Display -->
                        <div style="background: var(--rok-bg); border-radius: 8px; padding: 20px; border: 1px solid var(--rok-surface-border);">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                                <!-- Round 1 -->
                                <div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                                        <h4 style="color: #d4af37; font-size: 12px; margin:0;">ROUND 1</h4>
                                        <span style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(212,175,55,0.15);color:#d4af37;">BO1</span>
                                    </div>
                                    <div v-for="i in 4" :key="i" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: 6px; overflow:hidden; margin-bottom: 12px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 10px;border-bottom:1px solid rgba(255,255,255,0.05);">
                                            <span style="font-size:10px;color:var(--rok-muted-light);">Match {{ i }}</span>
                                            <button type="button" @click="openEditModal('Round 1 Match ' + i, 'BO1', teams[(i-1)*2]?.name, teams[(i-1)*2+1]?.name)" style="background:transparent;border:1px solid rgba(212,175,55,0.3);color:#d4af37;font-size:9px;padding:1px 5px;border-radius:3px;cursor:pointer;">Ubah Hasil</button>
                                        </div>
                                        <div style="padding:6px 10px;">
                                            <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;">
                                                <span>{{ teams[(i - 1) * 2]?.name }}</span>
                                                <span style="color:#d4af37;font-weight:700;">0</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:12px;border-top:1px solid var(--rok-surface-border);padding:3px 0 0 0;">
                                                <span>{{ teams[(i - 1) * 2 + 1]?.name }}</span>
                                                <span style="color:#d4af37;font-weight:700;">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Semifinals -->
                                <div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                                        <h4 style="color: #cbd5e1; font-size: 12px; margin:0;">SEMI FINALS</h4>
                                        <span v-if="enableCustomBo" style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(99,102,241,0.15);color:#a5b4fc;">{{ semiBo }}</span>
                                        <span v-else style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(212,175,55,0.1);color:#d4af37;">BO1</span>
                                    </div>
                                    <div v-for="i in 2" :key="i" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: 6px; overflow:hidden; margin-bottom: 24px; margin-top: 16px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 10px;border-bottom:1px solid rgba(255,255,255,0.05);">
                                            <span style="font-size:10px;color:var(--rok-muted-light);">Semi Final {{ i }}</span>
                                            <button type="button" @click="openEditModal('Semi Final ' + i, enableCustomBo ? semiBo : 'BO1', 'Pemenang M' + ((i-1)*2+1), 'Pemenang M' + ((i-1)*2+2))" style="background:transparent;border:1px solid rgba(99,102,241,0.3);color:#a5b4fc;font-size:9px;padding:1px 5px;border-radius:3px;cursor:pointer;">Ubah Hasil</button>
                                        </div>
                                        <div style="padding:6px 10px;">
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--rok-silver);padding:3px 0;">
                                                <span>Pemenang M{{ (i - 1) * 2 + 1 }}</span>
                                                <span>-</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--rok-silver);border-top:1px solid var(--rok-surface-border);padding:3px 0 0 0;">
                                                <span>Pemenang M{{ (i - 1) * 2 + 2 }}</span>
                                                <span>-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Grand Final -->
                                <div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                                        <h4 style="color: #f59e0b; font-size: 12px; margin:0;">GRAND FINAL</h4>
                                        <span v-if="enableCustomBo" style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(245,158,11,0.2);color:#f59e0b;">{{ finalBo }}</span>
                                        <span v-else style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(212,175,55,0.1);color:#d4af37;">BO1</span>
                                    </div>
                                    <div style="background: var(--rok-surface-card); border: 1px solid #d4af37; border-radius: 6px; overflow:hidden; margin-top: 40px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 10px;border-bottom:1px solid rgba(212,175,55,0.2);">
                                            <span style="font-size:10px;color:#f59e0b;font-weight:700;">Grand Final</span>
                                            <button type="button" @click="openEditModal('Grand Final', enableCustomBo ? finalBo : 'BO1', 'Finalis 1', 'Finalis 2')" style="background:transparent;border:1px solid rgba(245,158,11,0.3);color:#f59e0b;font-size:9px;padding:1px 5px;border-radius:3px;cursor:pointer;">Ubah Hasil</button>
                                        </div>
                                        <div style="padding:6px 10px;">
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#d4af37;font-weight:700;padding:3px 0;">
                                                <span>Finalis 1</span>
                                                <span>-</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#d4af37;font-weight:700;border-top:1px solid var(--rok-surface-border);padding:3px 0 0 0;">
                                                <span>Finalis 2</span>
                                                <span>-</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bronze Match -->
                                    <div v-if="enableCustomBo && enableBronzeMatch" style="margin-top: 24px;">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                            <h4 style="color: #f59e0b; font-size: 11px; margin:0; opacity:0.7;">PEREBUTAN JUARA 3</h4>
                                            <span style="font-size:10px;font-weight:800;padding:1px 6px;border-radius:999px;background:rgba(245,158,11,0.15);color:#f59e0b;">{{ bronzeBo }}</span>
                                        </div>
                                        <div style="background: var(--rok-bg); border: 1px solid rgba(217,119,6,0.35); border-radius: 6px; overflow:hidden;">
                                            <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 10px;border-bottom:1px solid rgba(217,119,6,0.2);">
                                                <span style="font-size:10px;color:#f59e0b;">Kalah SF1 vs Kalah SF2</span>
                                                <button type="button" @click="openEditModal('Perebutan Juara 3', bronzeBo, 'Kalah Semi Final 1', 'Kalah Semi Final 2')" style="background:transparent;border:1px solid rgba(217,119,6,0.3);color:#f59e0b;font-size:9px;padding:1px 5px;border-radius:3px;cursor:pointer;">Ubah Hasil</button>
                                            </div>
                                            <div style="padding:6px 10px;">
                                                <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;">
                                                    <span style="color:var(--rok-silver);">Kalah SF 1</span>
                                                    <span style="font-weight:700;">-</span>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;font-size:12px;border-top:1px solid var(--rok-surface-border);padding:3px 0 0 0;">
                                                    <span style="color:var(--rok-silver);">Kalah SF 2</span>
                                                    <span style="font-weight:700;">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Match Modal -->
                        <div v-if="editingMatch" style="position: fixed; inset: 0; background: rgba(0,0,0,0.75); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 16px;" @click.self="closeEditModal">
                            <div style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: 12px; padding: 24px; max-width: 440px; width: 100%;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <h4 style="margin: 0; font-size: 16px;">Ubah Hasil — {{ editingMatch.title }}</h4>
                                    <span style="font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: rgba(245,158,11,0.15); color: #f59e0b;">{{ editingMatch.bo }}</span>
                                </div>
                                <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0 0 16px 0;">
                                    Format {{ editingMatch.bo }}: Pemenang adalah tim yang pertama kali meraih {{ getWinTarget(editingMatch.bo) }} poin (skor maksimal {{ getWinTarget(editingMatch.bo) }}).
                                </p>

                                <div style="display: grid; gap: 10px; margin-bottom: 20px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 10px 14px; border-radius: 8px;">
                                        <span style="font-weight: 700; font-size: 13px;">{{ editingMatch.teamA || 'Tim 1' }}</span>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <button type="button" @click="editScore1 = Math.max(0, editScore1 - 1); clampScores()" style="width:24px;height:24px;background:rgba(255,255,255,0.08);border:1px solid var(--rok-surface-border);color:#fff;border-radius:4px;cursor:pointer;font-weight:700;">-</button>
                                            <span style="font-size:16px;font-weight:800;min-width:24px;text-align:center;">{{ editScore1 }}</span>
                                            <button type="button" @click="if (editScore1 < getWinTarget(editingMatch.bo)) editScore1++; clampScores()" style="width:24px;height:24px;background:rgba(255,255,255,0.08);border:1px solid var(--rok-surface-border);color:#fff;border-radius:4px;cursor:pointer;font-weight:700;">+</button>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 10px 14px; border-radius: 8px;">
                                        <span style="font-weight: 700; font-size: 13px;">{{ editingMatch.teamB || 'Tim 2' }}</span>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <button type="button" @click="editScore2 = Math.max(0, editScore2 - 1); clampScores()" style="width:24px;height:24px;background:rgba(255,255,255,0.08);border:1px solid var(--rok-surface-border);color:#fff;border-radius:4px;cursor:pointer;font-weight:700;">-</button>
                                            <span style="font-size:16px;font-weight:800;min-width:24px;text-align:center;">{{ editScore2 }}</span>
                                            <button type="button" @click="if (editScore2 < getWinTarget(editingMatch.bo)) editScore2++; clampScores()" style="width:24px;height:24px;background:rgba(255,255,255,0.08);border:1px solid var(--rok-surface-border);color:#fff;border-radius:4px;cursor:pointer;font-weight:700;">+</button>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                    <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" @click="closeEditModal">Batal</button>
                                    <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" @click="closeEditModal">Simpan Skor</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
