<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    pageHeader: { type: Object, default: () => null },
});

const form = useForm({
    name: '',
    email: '',
});

const { animateSplitText, bindSpringHover } = useRokAnimations();

const submit = () => {
    form.post('/trial/register');
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
            <title>Pendaftaran Trial Pemain | Ruler Of Kings</title>
            <meta name="description" content="Pendaftaran seleksi terbuka calon atlet esports Ruler Of Kings berbasis uji taktis LSM dan evaluasi rekaman gameplay." />
        </Head>

        <div class="page-container">
            <section class="page-hero">
                <div class="page-hero__inner">
                    <span class="teams-section-kicker" style="color: #f59e0b; margin-bottom: 8px;">SCOUTING PROTOCOL // TALENT INTAKE</span>
                    <h1 class="page-title">{{ pageHeader?.title || 'SELEKSI TERBUKA CALON ATLET' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Langkah awal pembuktian bakatmu. Daftarkan identitasmu untuk mendapatkan token akses tes LSM dan evaluasi gameplay oleh manajemen tim ROK.' }}
                    </p>
                </div>
            </section>

            <section class="trial-section" style="padding: 40px 0 80px 0;">
                <div class="section-inner" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                    <div class="trial-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 36px; align-items: start;">
                        <!-- LEFT COLUMN: SELECTION PROTOCOL & CRITERIA -->
                        <div class="trial-protocol-dossier" style="background: #090b10; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 36px;">
                            <span style="font-family: var(--font-mono, monospace); font-size: 11px; letter-spacing: 2px; color: #f59e0b; text-transform: uppercase;">SELECTION PROTOCOL</span>
                            <h2 style="font-size: 24px; font-weight: 800; color: #f8fafc; margin: 6px 0 20px 0;">TAHAPAN EVALUASI RESMI</h2>

                            <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 32px;">
                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                    <span style="font-family: var(--font-mono, monospace); font-size: 12px; font-weight: 800; color: #f59e0b; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 8px; border-radius: 4px;">PHASE 01</span>
                                    <div>
                                        <strong style="display: block; font-size: 15px; color: #f8fafc; margin-bottom: 2px;">Candidate Intake &amp; Token Verification</strong>
                                        <p style="font-size: 13px; color: #94a3b8; margin: 0; line-height: 1.5;">Pengisian identitas awal untuk menerbitkan token verifikasi unik dan link formulir portofolio atlet.</p>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                    <span style="font-family: var(--font-mono, monospace); font-size: 12px; font-weight: 800; color: #94a3b8; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); padding: 4px 8px; border-radius: 4px;">PHASE 02</span>
                                    <div>
                                        <strong style="display: block; font-size: 15px; color: #f8fafc; margin-bottom: 2px;">Tactical LSM &amp; Psychometric Test</strong>
                                        <p style="font-size: 13px; color: #94a3b8; margin: 0; line-height: 1.5;">Evaluasi pemahaman makro-mikro, pengambilan keputusan di bawah tekanan, dan mentalitas tim melalui sistem LSM.</p>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                    <span style="font-family: var(--font-mono, monospace); font-size: 12px; font-weight: 800; color: #94a3b8; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); padding: 4px 8px; border-radius: 4px;">PHASE 03</span>
                                    <div>
                                        <strong style="display: block; font-size: 15px; color: #f8fafc; margin-bottom: 2px;">Scrim Trials &amp; Head Coach Review</strong>
                                        <p style="font-size: 13px; color: #94a3b8; margin: 0; line-height: 1.5;">Uji tanding langsung bersama skuad utama dan penilaian gameplay oleh tim pelatih serta manajer divisi.</p>
                                    </div>
                                </div>
                            </div>

                            <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 20px;">
                                <span style="font-family: var(--font-mono, monospace); font-size: 11px; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">ELIGIBILITY BENCHMARKS:</span>
                                <ul style="margin: 10px 0 0 0; padding-left: 18px; font-size: 13px; color: #cbd5e1; line-height: 1.6;">
                                    <li>Minimal rank kompetitif: Mythical Glory / Radiant / Master ke atas.</li>
                                    <li>Disiplin tinggi, nol riwayat perilaku toksik (Zero Toxicity Policy).</li>
                                    <li>Kesiapan komunikasi suara (mic aktif) dan kehadiran sesi latihan rutin.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: INTAKE TERMINAL FORM -->
                        <div class="trial-form-box" style="background: #090b10; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 36px; position: relative;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <span style="font-family: var(--font-mono, monospace); font-size: 11px; letter-spacing: 1.5px; color: #22c55e; display: inline-flex; align-items: center; gap: 6px;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 8px #22c55e;"></span>
                                    TERMINAL ONLINE // BATCH 2026
                                </span>
                                <span style="font-family: var(--font-mono, monospace); font-size: 11px; color: #64748b;">STEP 01/03</span>
                            </div>

                            <h3 class="form-title" style="font-size: 22px; font-weight: 800; color: #f8fafc; margin: 0 0 8px 0;">PENDAFTARAN AWAL ATLET</h3>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0 0 24px 0; line-height: 1.5;">
                                Masukkan identitas resmi Anda. Sistem akan menggenerasikan token seleksi terenkripsi untuk pengisian berkas portofolio.
                            </p>

                            <form @submit.prevent="submit" class="rok-form">
                                <div class="form-field" style="margin-bottom: 20px;">
                                    <label for="name" style="display:block;margin-bottom:6px;font-family:var(--font-mono, monospace);font-size:12px;font-weight:700;letter-spacing:1px;color:#cbd5e1;text-transform:uppercase;">
                                        Nama Lengkap (Sesuai KTP / Identitas)
                                    </label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        placeholder="Contoh: Muhammad Farhan"
                                        class="rok-input"
                                        style="width:100%;padding:12px 14px;background:#040507;border:1px solid rgba(255,255,255,0.12);border-radius:4px;color:#f8fafc;font-size:14px;outline:none;"
                                    />
                                    <span v-if="form.errors.name" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.name }}</span>
                                </div>

                                <div class="form-field" style="margin-bottom: 28px;">
                                    <label for="email" style="display:block;margin-bottom:6px;font-family:var(--font-mono, monospace);font-size:12px;font-weight:700;letter-spacing:1px;color:#cbd5e1;text-transform:uppercase;">
                                        Alamat Email Aktif
                                    </label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        placeholder="nama@email.com"
                                        class="rok-input"
                                        style="width:100%;padding:12px 14px;background:#040507;border:1px solid rgba(255,255,255,0.12);border-radius:4px;color:#f8fafc;font-size:14px;outline:none;"
                                    />
                                    <span v-if="form.errors.email" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.email }}</span>
                                </div>

                                <button
                                    type="submit"
                                    class="btn-rok btn-rok--gold"
                                    style="width: 100%; padding: 14px; font-weight: 800; font-family: var(--font-sub, sans-serif); letter-spacing: 1.5px; text-transform: uppercase;"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'MEMPROSES TOKEN...' : 'GENERATE TRIAL TOKEN &amp; LANJUTKAN &rarr;' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
