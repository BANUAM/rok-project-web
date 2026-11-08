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
                    <h1 class="page-title">{{ pageHeader?.title || 'SELEKSI TERBUKA CALON ATLET' }}</h1>
                    <p class="page-subtitle">
                        {{ pageHeader?.subtitle || 'Langkah awal pembuktian bakatmu. Daftarkan identitasmu untuk mendapatkan token akses tes LSM dan evaluasi gameplay oleh manajemen tim ROK.' }}
                    </p>
                </div>
            </section>

            <section class="trial-section">
                <div class="section-inner" style="max-width: 600px; margin: 0 auto;">
                    <div class="trial-form-box" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 32px;">
                        <h3 class="form-title" style="font-size: 20px; margin: 0 0 8px 0;">PENDAFTARAN AWAL</h3>
                        <p style="color: var(--rok-text-muted); font-size: 13px; margin: 0 0 24px 0;">
                            Calon pemain belum perlu membuat akun permanen. Cukup masukkan nama lengkap dan email aktif untuk membuat token trial pribadimu.
                        </p>

                        <form @submit.prevent="submit" class="rok-form">
                            <div class="form-field" style="margin-bottom: 18px;">
                                <label for="name" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Nama Lengkap Sesuai KTP/Identitas</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="rok-input"
                                    style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                                />
                                <span v-if="form.errors.name" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.name }}</span>
                            </div>

                            <div class="form-field" style="margin-bottom: 24px;">
                                <label for="email" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Alamat Email Aktif</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="rok-input"
                                    style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                                />
                                <span v-if="form.errors.email" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.email }}</span>
                            </div>

                            <button
                                type="submit"
                                class="btn-rok btn-rok--gold"
                                style="width: 100%; padding: 12px; font-weight: 700;"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Memproses Token...' : 'Lanjutkan ke Formulir Profil &rarr;' }}
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
