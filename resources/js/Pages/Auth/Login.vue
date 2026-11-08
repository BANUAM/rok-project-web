<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const page = usePage();
const { animateSplitText, bindSpringHover } = useRokAnimations();

const submit = () => {
    form.post('/login');
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
            <title>Masuk Akun | Ruler Of Kings</title>
            <meta name="description" content="Masuk ke akun Ruler Of Kings untuk melanjutkan pembelian membership dan akses fitur komunitas." />
        </Head>

        <div class="page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
            <div class="auth-card" style="width: 100%; max-width: 480px; background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 36px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
                <div style="text-align: center; margin-bottom: 24px;">
                    <h1 class="auth-title" style="font-size: 26px; font-weight: 800; margin: 0 0 6px 0;">MASUK KE AKUN ROK</h1>
                    <p style="color: var(--rok-text-muted); font-size: 14px; margin: 0;">
                        Silakan masuk untuk mengakses <strong>ROK Bracket Studio</strong>, kurikulum akademi, atau mengelola membership Anda.
                    </p>
                </div>

                <div v-if="page.props.flash?.status" style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; padding: 12px 14px; color: #4ade80; font-size: 13px; margin-bottom: 20px;">
                    {{ page.props.flash.status }}
                </div>

                <form @submit.prevent="submit" class="rok-form">
                    <div class="form-field" style="margin-bottom: 18px;">
                        <label for="login" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Email atau Username</label>
                        <input
                            id="login"
                            v-model="form.login"
                            type="text"
                            required
                            autofocus
                            class="rok-input"
                            style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                        />
                        <span v-if="form.errors.login" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.login }}</span>
                    </div>

                    <div class="form-field" style="margin-bottom: 20px;">
                        <label for="password" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Kata Sandi</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="rok-input"
                            style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                        />
                        <span v-if="form.errors.password" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.password }}</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input v-model="form.remember" type="checkbox" />
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="btn-rok btn-rok--gold"
                        style="width: 100%; padding: 12px; font-weight: 700;"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Memproses...' : 'Masuk Sekarang' }}
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--rok-text-muted);">
                    Belum memiliki akun?
                    <Link href="/register" style="color: #d4af37; font-weight: 600;">Daftar di sini</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
