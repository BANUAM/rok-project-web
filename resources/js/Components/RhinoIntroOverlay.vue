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
const rhinoHeadRef = ref(null);
const rhinoEyeRef = ref(null);
const rokTextRef = ref(null);
const scanLineRef = ref(null);
const telemetryRef = ref(null);
const taglineRef = ref(null);

let tl = null;

const dismissIntro = (isSkip = false) => {
    if (isDismissed.value) return;
    isDismissed.value = true;

    if (tl) {
        tl.kill();
    }

    if (overlayRef.value) {
        gsap.to(overlayRef.value, {
            opacity: 0,
            scale: 1.05,
            duration: 0.55,
            ease: 'power3.inOut',
            onComplete: () => {
                isVisible.value = false;
                try {
                    sessionStorage.setItem('rok_rhino_intro_seen', '1');
                } catch (e) {}
                if (isSkip) {
                    emit('skipped');
                } else {
                    emit('complete');
                }
            },
        });
    } else {
        isVisible.value = false;
    }
};

const playIntro = () => {
    isVisible.value = true;
    isDismissed.value = false;

    // Reset initial states
    gsap.set(overlayRef.value, { opacity: 1, scale: 1 });
    gsap.set(rhinoHeadRef.value, { opacity: 0, scale: 0.88, filter: 'brightness(0.3) blur(6px)' });
    gsap.set(rhinoEyeRef.value, { opacity: 0, scale: 0.5, filter: 'drop-shadow(0 0 0px #fbbf24)' });
    gsap.set(rokTextRef.value, { opacity: 0, y: 30, scale: 1.15, filter: 'blur(8px)' });
    gsap.set(scanLineRef.value, { top: '0%', opacity: 0 });
    gsap.set(taglineRef.value, { opacity: 0, letterSpacing: '4px' });

    tl = gsap.timeline({
        onComplete: () => {
            setTimeout(() => {
                dismissIntro(false);
            }, 600);
        },
    });

    // 1. Telemetry & ambient grid activation
    tl.to(telemetryRef.value, { opacity: 1, duration: 0.35, ease: 'power2.out' })

    // 2. Scan line sweep & Rhino silhouette reveal
    .to(scanLineRef.value, { opacity: 1, duration: 0.2 }, '-=0.1')
    .to(rhinoHeadRef.value, {
        opacity: 0.95,
        scale: 1,
        filter: 'brightness(1) blur(0px)',
        duration: 0.8,
        ease: 'power3.out',
    }, '-=0.1')
    .to(scanLineRef.value, {
        top: '100%',
        duration: 0.85,
        ease: 'power2.inOut',
    }, '-=0.8')
    .to(scanLineRef.value, { opacity: 0, duration: 0.2 }, '-=0.1')

    // 3. Fierce Eye Ignition Pulse
    .to(rhinoEyeRef.value, {
        opacity: 1,
        scale: 1.15,
        filter: 'drop-shadow(0 0 25px #fbbf24) drop-shadow(0 0 45px #f59e0b)',
        duration: 0.35,
        ease: 'back.out(2)',
    })
    .to(rhinoEyeRef.value, {
        scale: 1,
        duration: 0.2,
        ease: 'power2.inOut',
    })

    // 4. Kinetic ROK Wordmark Slam
    .to(rokTextRef.value, {
        opacity: 1,
        y: 0,
        scale: 1,
        filter: 'blur(0px)',
        duration: 0.5,
        ease: 'power4.out',
    }, '-=0.1')

    // 5. Editorial Tagline reveal
    .to(taglineRef.value, {
        opacity: 1,
        letterSpacing: '10px',
        duration: 0.55,
        ease: 'power2.out',
    }, '-=0.2');
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && isVisible.value) {
        dismissIntro(true);
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);

    const hasSeen = !props.forceShow && sessionStorage.getItem('rok_rhino_intro_seen');
    if (props.autoPlay && !hasSeen) {
        playIntro();
    } else if (props.forceShow) {
        playIntro();
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown);
    if (tl) tl.kill();
});

defineExpose({
    playIntro,
    dismissIntro,
});
</script>

<template>
    <teleport to="body">
        <aside
            v-if="isVisible"
            ref="overlayRef"
            class="rhino-intro-overlay"
            role="dialog"
            aria-label="ROK Motion Graphic Intro"
            aria-modal="true"
        >
            <!-- Atmospheric Technical Grid & Telemetry -->
            <div class="rhino-intro-overlay__grid" aria-hidden="true"></div>

            <div ref="telemetryRef" class="rhino-intro-telemetry">
                <div class="telemetry-top-left">
                    <span class="telemetry-dot"></span>
                    <span class="telemetry-code">SYS://ROK.ORGANIZATION.v26 // PROTOCOL ACTIVE</span>
                </div>
                <button
                    type="button"
                    class="telemetry-skip-btn"
                    @click="dismissIntro(true)"
                    title="Lewati Intro (Tekan ESC)"
                >
                    <span>LEWATI</span>
                    <kbd>[ESC]</kbd>
                </button>
            </div>

            <!-- Central Motion Stage -->
            <div class="rhino-stage">
                <!-- Outer Telemetry Radar Ring -->
                <div class="radar-ring" aria-hidden="true">
                    <div class="radar-ring__ticks"></div>
                </div>

                <!-- Rhino Head Container with Scan Beam -->
                <div class="rhino-head-wrapper">
                    <img
                        ref="rhinoHeadRef"
                        src="/img/rok-rhino-head-light.png"
                        alt="ROK Rhino Silhouette"
                        class="rhino-head-img"
                    />

                    <!-- Dedicated High-Intensity Eye Glow -->
                    <img
                        ref="rhinoEyeRef"
                        src="/img/rok-rhino-eye.png"
                        alt="ROK Rhino Eye"
                        class="rhino-eye-img"
                    />

                    <!-- Sweeping Laser Beam -->
                    <div ref="scanLineRef" class="rhino-scan-line" aria-hidden="true"></div>
                </div>

                <!-- ROK Typography Wordmark -->
                <div class="rhino-wordmark-wrapper">
                    <img
                        ref="rokTextRef"
                        src="/img/rok-text-logo-light.png"
                        alt="Ruler Of Kings"
                        class="rok-wordmark-img"
                    />
                    <p ref="taglineRef" class="rhino-editorial-tagline">
                        RULER OF KINGS ESPORTS
                    </p>
                </div>
            </div>

            <!-- Bottom Telemetry Metadata -->
            <div class="rhino-intro-footer">
                <span class="footer-meta">EST. 2026 // COMPETITIVE DIVISION SYSTEM</span>
                <span class="footer-meta footer-meta--status">STATUS: ONLINE</span>
            </div>
        </aside>
    </teleport>
</template>

<style scoped>
.rhino-intro-overlay {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: #060709;
    color: #e2e8f0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    user-select: none;
}

.rhino-intro-overlay__grid {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(circle at center, black 40%, transparent 85%);
    pointer-events: none;
}

.rhino-intro-telemetry {
    position: absolute;
    top: 24px;
    left: 28px;
    right: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: var(--font-mono, monospace);
    font-size: 11px;
    letter-spacing: 1.5px;
    color: #94a3b8;
    z-index: 10;
}

.telemetry-top-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.telemetry-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 10px #22c55e;
    animation: pulseDot 1.5s infinite ease-in-out;
}

@keyframes pulseDot {
    0%, 100% { opacity: 0.4; transform: scale(0.9); }
    50% { opacity: 1; transform: scale(1.3); }
}

.telemetry-skip-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 999px;
    padding: 6px 14px;
    color: #cbd5e1;
    font-family: var(--font-mono, monospace);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.telemetry-skip-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: #cbd5e1;
    color: #ffffff;
    transform: translateY(-1px);
}

.telemetry-skip-btn kbd {
    background: rgba(0, 0, 0, 0.4);
    padding: 2px 5px;
    border-radius: 4px;
    font-size: 9px;
    color: #94a3b8;
}

.rhino-stage {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 620px;
    width: 90%;
    z-index: 5;
}

.radar-ring {
    position: absolute;
    width: 440px;
    height: 440px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -58%);
    border: 1px dashed rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    pointer-events: none;
    animation: rotateRadar 24s linear infinite;
}

@keyframes rotateRadar {
    from { transform: translate(-50%, -58%) rotate(0deg); }
    to { transform: translate(-50%, -58%) rotate(360deg); }
}

.rhino-head-wrapper {
    position: relative;
    width: 280px;
    max-width: 80vw;
    aspect-ratio: 575 / 513;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rhino-head-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.15));
}

.rhino-eye-img {
    position: absolute;
    left: 45.91%;
    top: 44.83%;
    width: 16.35%;
    height: 11.50%;
    object-fit: contain;
    pointer-events: none;
    z-index: 2;
}

.rhino-scan-line {
    position: absolute;
    left: -10%;
    right: -10%;
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #38bdf8 40%, #fbbf24 60%, transparent 100%);
    box-shadow: 0 0 16px rgba(56, 189, 248, 0.8), 0 0 30px rgba(251, 191, 36, 0.6);
    pointer-events: none;
    z-index: 3;
}

.rhino-wordmark-wrapper {
    margin-top: 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.rok-wordmark-img {
    width: 320px;
    max-width: 82vw;
    object-fit: contain;
    filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.8));
}

.rhino-editorial-tagline {
    margin-top: 14px;
    font-family: var(--font-heading, 'Teko', sans-serif);
    font-size: 15px;
    letter-spacing: 6px;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
}

.rhino-intro-footer {
    position: absolute;
    bottom: 24px;
    left: 28px;
    right: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: var(--font-mono, monospace);
    font-size: 10px;
    letter-spacing: 1.2px;
    color: #64748b;
    z-index: 10;
}

.footer-meta--status {
    color: #22c55e;
    font-weight: 700;
}

@media (max-width: 640px) {
    .rhino-head-wrapper {
        width: 220px;
    }
    .rok-wordmark-img {
        width: 240px;
    }
    .radar-ring {
        width: 320px;
        height: 320px;
    }
    .rhino-intro-telemetry,
    .rhino-intro-footer {
        left: 16px;
        right: 16px;
    }
}
</style>
