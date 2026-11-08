<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const { animateSplitText, bindSpringHover } = useRokAnimations();

const submit = () => {
    form.post('/register');
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
            <title>Daftar Akun Baru | Ruler Of Kings</title>
            <meta name="description" content="Daftarkan akun resmi Ruler Of Kings untuk membeli paket membership, membuka akses kurikulum taktis, dan mengelola turnamen." />
        </Head>

        <div class="page-container" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
            <div class="auth-card" style="width: 100%; max-width: 480px; background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 36px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
                <div style="text-align: center; margin-bottom: 28px;">
                    <h1 class="auth-title" style="font-size: 26px; font-weight: 800; color: var(--rok-text-bright); margin: 0 0 6px 0;">DAFTAR AKUN ROK</h1>
                    <p style="color: var(--rok-text-muted); font-size: 14px; margin: 0;">
                        Buat akun untuk mengakses paket membership, materi taktis, dan turnamen resmi.
                    </p>
                </div>

                <form @submit.prevent="submit" class="rok-form">
                    <div class="form-field" style="margin-bottom: 16px;">
                        <label for="name" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Nama Lengkap</label>
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

                    <div class="form-field" style="margin-bottom: 16px;">
                        <label for="username" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Username Unik</label>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            required
                            class="rok-input"
                            style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                        />
                        <span v-if="form.errors.username" style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ form.errors.username }}</span>
                    </div>

                    <div class="form-field" style="margin-bottom: 16px;">
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

                    <div class="form-field" style="margin-bottom: 16px;">
                        <label for="password" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Kata Sandi (Min. 8 Karakter)</label>
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

                    <div class="form-field" style="margin-bottom: 24px;">
                        <label for="password_confirmation" style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;">Konfirmasi Kata Sandi</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            class="rok-input"
                            style="width:100%;padding:10px 14px;background:var(--rok-bg);border:1px solid var(--rok-surface-border);border-radius:6px;color:var(--rok-text-bright);"
                        />
                    </div>

                    <button
                        type="submit"
                        class="btn-rok btn-rok--gold"
                        style="width: 100%; padding: 12px; font-weight: 700;"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Mendaftarkan...' : 'Daftar Akun Baru' }}
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--rok-text-muted);">
                    Sudah memiliki akun?
                    <Link href="/login" style="color: #d4af37; font-weight: 600;">Masuk di sini</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
