<script setup>
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useRokAnimations } from '@/composables/useRokAnimations';

const props = defineProps({
    plans: { type: Array, default: () => [] },
    selectedPlan: { type: Object, default: () => null },
});

const { animateSplitText, bindSpringHover, animateStaggerFadeIn } = useRokAnimations();

onMounted(() => {
    animateSplitText('.page-title', { y: 24, duration: 0.7 });
    animateStaggerFadeIn('.plan-card', 0.08);
    document.querySelectorAll('.btn-rok, .plan-card').forEach((el) => {
        bindSpringHover(el, 1.02, -3);
    });
    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Paket Membership Resmi | Ruler Of Kings</title>
            <meta name="description" content="Pilih paket membership ROK Challenger atau ROK Immortal untuk membuka akses eksklusif kurikulum taktis dan ROK Bracket Studio." />
        </Head>

        <div class="page-container">
            <section class="page-hero">
                <div class="page-hero__inner">
                    <h1 class="page-title">ORGANIZER MEMBERSHIP TIERS</h1>
                    <p class="page-subtitle">
                        Buka potensi penuh manajemen kompetisi esport Anda dengan akses eksklusif ke <strong>ROK Bracket Studio</strong>, kurikulum akademi strategi tingkat lanjut, dan lencana resmi.
                    </p>
                </div>
            </section>

            <section class="membership-section">
                <div class="section-inner">
                    <div class="plans-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; max-width: 1000px; margin: 0 auto;">
                        <article
                            v-for="plan in plans"
                            :key="plan.id"
                            class="plan-card"
                            :class="{ 'plan-card--featured': plan.slug === 'immortal' }"
                            style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 32px; display: flex; flex-direction: column;"
                        >
                            <div class="plan-card__header" style="margin-bottom: 20px;">
                                <h3 style="font-size: 22px; margin: 0 0 8px 0; color: #d4af37;">{{ plan.name }}</h3>
                                <p style="color: var(--rok-text-muted); font-size: 13px; margin: 0;">{{ plan.description }}</p>
                            </div>

                            <div class="plan-card__price" style="margin-bottom: 24px;">
                                <span style="font-size: 32px; font-weight: 800; color: var(--rok-text-bright);">Rp {{ Number(plan.price).toLocaleString('id-ID') }}</span>
                                <span style="color: var(--rok-text-muted); font-size: 13px;"> / {{ plan.duration_days }} hari</span>
                            </div>

                            <ul class="plan-card__features" style="list-style: none; padding: 0; margin: 0 0 30px 0; flex: 1;">
                                <li
                                    v-for="(feat, idx) in (plan.features || [])"
                                    :key="idx"
                                    style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 10px; color: var(--rok-text-secondary);"
                                >
                                    <span style="color: #4ade80;"><i data-lucide="check" style="width:14px;height:14px;"></i></span>
                                    <span>{{ feat }}</span>
                                </li>
                            </ul>

                            <Link
                                :href="'/membership/payment?plan=' + plan.slug"
                                class="btn-rok btn-rok--gold"
                                style="width: 100%; text-align: center; padding: 12px; font-weight: 700;"
                            >
                                Pilih Paket {{ plan.name }} &rarr;
                            </Link>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
