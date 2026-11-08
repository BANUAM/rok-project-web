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
            <section class="page-hero">
                <div class="page-hero__inner">
                    <span class="sr-only" aria-hidden="true">FREE TOURNAMENT GENERATOR</span>
                    <h1 class="page-title">{{ tournamentName }}</h1>
                    <p class="page-subtitle">
                        Buat turnamen komunitas Anda sendiri secara gratis tanpa perlu registrasi akun. Bagikan link live bracket ke peserta secara instan.
                    </p>
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

                        <div style="text-align: right; margin-top: 24px;">
                            <button type="button" class="btn-rok btn-rok--gold" @click="currentStep = 2">
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div v-show="currentStep === 2" class="wizard-box" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <h3 style="margin-bottom: 18px;">Daftar Tim &amp; Kontak WhatsApp</h3>
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
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="btn-rok btn-rok--gold" @click="copyLiveLink">
                                    {{ liveLinkCopied ? 'Tersalin!' : 'Salin Link Live' }}
                                </button>
                                <button type="button" class="btn-rok btn-rok--outline" @click="currentStep = 2">
                                    Edit Tim
                                </button>
                            </div>
                        </div>

                        <!-- Bracket Tree Display -->
                        <div style="background: var(--rok-bg); border-radius: 8px; padding: 20px; border: 1px solid var(--rok-surface-border);">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                                <div>
                                    <h4 style="color: #d4af37; font-size: 12px; margin-bottom: 12px;">ROUND 1</h4>
                                    <div v-for="i in 4" :key="i" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 8px 12px; margin-bottom: 12px;">
                                        <div style="display:flex;justify-content:space-between;font-size:12px;">
                                            <span>{{ teams[(i - 1) * 2]?.name }}</span>
                                            <span style="color:#d4af37;font-weight:700;">0</span>
                                        </div>
                                        <div style="display:flex;justify-content:space-between;font-size:12px;border-top:1px solid var(--rok-surface-border);margin-top:4px;padding-top:4px;">
                                            <span>{{ teams[(i - 1) * 2 + 1]?.name }}</span>
                                            <span style="color:#d4af37;font-weight:700;">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 style="color: #cbd5e1; font-size: 12px; margin-bottom: 12px;">SEMIFINALS</h4>
                                    <div v-for="i in 2" :key="i" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 8px 12px; margin-bottom: 24px; margin-top: 16px;">
                                        <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--rok-silver);">
                                            <span>Pemenang M{{ (i - 1) * 2 + 1 }}</span>
                                            <span>-</span>
                                        </div>
                                        <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--rok-silver);border-top:1px solid var(--rok-surface-border);margin-top:4px;padding-top:4px;">
                                            <span>Pemenang M{{ (i - 1) * 2 + 2 }}</span>
                                            <span>-</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 style="color: #f59e0b; font-size: 12px; margin-bottom: 12px;">GRAND FINAL</h4>
                                    <div style="background: var(--rok-surface-card); border: 1px solid #d4af37; border-radius: 6px; padding: 10px 12px; margin-top: 40px;">
                                        <div style="display:flex;justify-content:space-between;font-size:12px;color:#d4af37;font-weight:700;">
                                            <span>Finalis 1</span>
                                            <span>-</span>
                                        </div>
                                        <div style="display:flex;justify-content:space-between;font-size:12px;color:#d4af37;font-weight:700;border-top:1px solid var(--rok-surface-border);margin-top:4px;padding-top:4px;">
                                            <span>Finalis 2</span>
                                            <span>-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
