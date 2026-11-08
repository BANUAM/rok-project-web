<script setup>
import { onMounted } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const form = useForm({});
const page = usePage();
const { animateSplitText, bindSpringHover } = useRokAnimations();

const resend = () => {
    form.post('/email/verification-notification');
};

onMounted(() => {
    animateSplitText('.auth-title', { y: 20, duration: 0.6 });
    document.querySelectorAll('.btn-rok').forEach((el) => {
        bindSpringHover(el, 1.02, -2);
    });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Verifikasi Alamat Email | Ruler Of Kings</title>
            <meta name="description" content="Verifikasi alamat email Anda untuk melanjutkan transaksi pembelian membership Ruler Of Kings." />
        </Head>

        <div class="page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
            <div class="auth-card" style="width: 100%; max-width: 500px; background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.4); color: #0284c7; font-size: 28px; margin-bottom: 20px;">
                    <i data-lucide="mail" style="width:30px;height:30px;"></i>
                </div>

                <h1 class="auth-title" style="font-size: 24px; font-weight: 800; color: var(--rok-text-bright); margin: 0 0 12px 0;">VERIFIKASI EMAIL ANDA</h1>

                <p style="color: var(--rok-text-secondary); font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                    Terima kasih telah mendaftar di <strong>Ruler Of Kings</strong>! Sebelum melanjutkan ke proses pembayaran membership, kami telah mengirimkan tautan verifikasi ke:
                </p>

                <div style="background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 12px; margin-bottom: 24px;">
                    <strong style="color: #0284c7; font-size: 15px;">{{ page.props.auth?.user?.email }}</strong>
                </div>

                <div v-if="page.props.flash?.status === 'verification-link-sent'" style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; padding: 12px 14px; color: #4ade80; font-size: 13px; margin-bottom: 20px;">
                    Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                </div>

                <form @submit.prevent="resend">
                    <button
                        type="submit"
                        class="btn-rok btn-rok--gold"
                        style="width: 100%; padding: 12px;"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Mengirim Ulang...' : 'Kirim Ulang Email Verifikasi' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
