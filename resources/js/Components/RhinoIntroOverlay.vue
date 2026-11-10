<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import gsap from 'gsap';

const props = defineProps({
    autoPlay: { type: Boolean, default: true },
    forceShow: { type: Boolean, default: false },
});

const emit = defineEmits(['complete', 'skipped']);

const isVisible = ref(false);
const isDismissed = ref(false);
const overlayRef = ref(null);
const headRef = ref(null);
const wordmarkRef = ref(null);
const taglineRef = ref(null);
const lineRef = ref(null);

let tl = null;

const dismissIntro = (isSkip = false) => {
    if (isDismissed.value) return;
    isDismissed.value = true;
    if (tl) tl.kill();

    if (!overlayRef.value) {
        isVisible.value = false;
        window.__rokIntroAlreadyPlayed = true;
        return;
    }

    // Tirai naik ke atas, halaman terbuka dari bawah
    gsap.to(overlayRef.value, {
        yPercent: -100,
        duration: isSkip ? 0.6 : 0.9,
        ease: 'power4.inOut',
        onComplete: () => {
            isVisible.value = false;
            window.__rokIntroAlreadyPlayed = true;
            emit(isSkip ? 'skipped' : 'complete');
        },
    });
};

const playIntro = () => {
    isVisible.value = true;
    isDismissed.value = false;

    // Tunggu elemen ter-render sebelum dianimasikan
    requestAnimationFrame(() => {
        if (!overlayRef.value) return;

        gsap.set(overlayRef.value, { yPercent: 0 });
        gsap.set(headRef.value, { clipPath: 'inset(100% 0% 0% 0%)', y: 24 });
        gsap.set(wordmarkRef.value, { opacity: 0, y: 18 });
        gsap.set(taglineRef.value, { opacity: 0, y: 10 });
        gsap.set(lineRef.value, { scaleX: 0 });

        tl = gsap.timeline({
            onComplete: () => setTimeout(() => dismissIntro(false), 450),
        });

        tl.to(lineRef.value, { scaleX: 1, duration: 2.1, ease: 'power1.inOut' }, 0)
            .to(headRef.value, {
                clipPath: 'inset(0% 0% 0% 0%)',
                y: 0,
                duration: 1.1,
                ease: 'power3.inOut',
            }, 0.1)
            .to(wordmarkRef.value, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, 0.85)
            .to(taglineRef.value, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }, 1.15);
    });
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && isVisible.value) dismissIntro(true);
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);

    // Putar saat pertama membuka situs dan setiap kali halaman Home di-refresh
    if (props.autoPlay && !window.__rokIntroAlreadyPlayed) {
        playIntro();
    } else if (props.forceShow) {
        playIntro();
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown);
    if (tl) tl.kill();
});

defineExpose({ playIntro, dismissIntro });
</script>

<template>
    <teleport to="body">
        <aside
            v-if="isVisible"
            ref="overlayRef"
            class="rok-intro"
            role="dialog"
            aria-label="Pembuka Ruler Of Kings"
            aria-modal="true"
            @click="dismissIntro(true)"
        >
            <div class="rok-intro__stage">
                <img
                    ref="headRef"
                    src="/img/rok-rhino-head.png"
                    alt="Ruler Of Kings"
                    class="rok-intro__head"
                />
                <img
                    ref="wordmarkRef"
                    src="/img/rok-text-logo.png"
                    alt=""
                    aria-hidden="true"
                    class="rok-intro__wordmark"
                />
                <p ref="taglineRef" class="rok-intro__tagline">Ruler of Kings &middot; Esports Organization</p>
            </div>
            <span ref="lineRef" class="rok-intro__line" aria-hidden="true"></span>
        </aside>
    </teleport>
</template>

<style scoped>
.rok-intro {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: #f6f5f2;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    user-select: none;
}

.rok-intro__stage {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: min(420px, 78vw);
}

.rok-intro__head {
    width: 100%;
    max-width: 260px;
    height: auto;
}

.rok-intro__wordmark {
    width: 190px;
    max-width: 55vw;
    margin-top: 30px;
    height: auto;
}

.rok-intro__tagline {
    margin: 18px 0 0;
    font-family: 'Inter', system-ui, sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.06em;
    color: #7b818b;
}

.rok-intro__line {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 3px;
    background: #14161a;
    transform-origin: 0 50%;
}
</style>
