<script setup>
import { onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    pageHeader: { type: Object, default: () => null },
});

const form = useForm({
    name: '',
    email: '',
});

const { animateSplitText } = useRokAnimations();

const submit = () => {
    form.post('/trial/register');
};

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Pendaftaran Trial Pemain | Ruler Of Kings</title>
            <meta name="description" content="Pendaftaran seleksi terbuka calon atlet esports Ruler Of Kings." />
        </Head>

        <div class="page-container">
            <section class="page-hero">
                <div class="page-hero__inner">
                    <h1 class="page-title">{{ pageHeader?.title || 'Bergabung dengan ROK' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Seleksi terbuka untuk calon atlet. Daftarkan identitasmu untuk mendapat akses tes LSM dan evaluasi gameplay oleh manajemen ROK.' }}
                    </p>
                </div>
            </section>

            <section class="trial-section">
                <div class="section-inner">
                    <div class="trial-layout">
                        <div>
                            <span class="eyebrow">Alur seleksi</span>
                            <h2 class="section-title" style="font-size: clamp(28px, 3.4vw, 42px);">Tiga tahap menuju roster</h2>

                            <div class="trial-steps">
                                <div class="trial-step">
                                    <span class="trial-step__num">1</span>
                                    <div>
                                        <h3>Pendaftaran awal</h3>
                                        <p>Isi nama dan email aktif. Kamu akan menerima tautan pribadi untuk melengkapi profil dan berkas.</p>
                                    </div>
                                </div>
                                <div class="trial-step">
                                    <span class="trial-step__num">2</span>
                                    <div>
                                        <h3>Tes LSM</h3>
                                        <p>Ujian pilihan ganda tentang logika, strategi, dan makro permainan, dikerjakan sendiri tanpa bantuan.</p>
                                    </div>
                                </div>
                                <div class="trial-step">
                                    <span class="trial-step__num">3</span>
                                    <div>
                                        <h3>Evaluasi &amp; kontrak</h3>
                                        <p>Manajemen dan pelatih menilai rekaman gameplay serta hasil tes sebelum penawaran roster diberikan.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="trial-requirements">
                                <h3>Persyaratan minimal</h3>
                                <ul>
                                    <li>Rank kompetitif sesuai ketentuan divisi yang dituju.</li>
                                    <li>Siap mengikuti latihan, evaluasi taktik, dan coaching rutin.</li>
                                    <li>Menyiapkan tangkapan layar profil game dan statistik terbaru.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="trial-card">
                            <h2>Daftar trial</h2>
                            <p>
                                Belum perlu membuat akun. Cukup nama lengkap dan email aktif untuk membuat token trial pribadimu.
                            </p>

                            <form @submit.prevent="submit">
                                <div class="field">
                                    <label for="name">Nama lengkap</label>
                                    <input id="name" v-model="form.name" type="text" required autocomplete="name" placeholder="Sesuai identitas" />
                                    <span v-if="form.errors.name" class="field__error">{{ form.errors.name }}</span>
                                </div>

                                <div class="field">
                                    <label for="email">Email aktif</label>
                                    <input id="email" v-model="form.email" type="email" required autocomplete="email" placeholder="nama@email.com" />
                                    <span v-if="form.errors.email" class="field__error">{{ form.errors.email }}</span>
                                </div>

                                <button type="submit" class="btn-rok btn-rok--gold btn-rok--full" :disabled="form.processing" style="margin-top: 8px;">
                                    {{ form.processing ? 'Memproses...' : 'Lanjutkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
