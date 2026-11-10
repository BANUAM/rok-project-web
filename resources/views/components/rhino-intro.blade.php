{{-- ROK Rhino Motion Graphic Cinematic Preloader (Blade SSR) --}}
<aside
    id="rokRhinoIntroOverlay"
    class="rhino-intro-overlay"
    role="dialog"
    aria-label="ROK Motion Graphic Intro"
    aria-modal="true"
    style="display: none;"
>
    <!-- Technical Background Grid -->
    <div class="rhino-intro-overlay__grid" aria-hidden="true"></div>

    <div class="rhino-intro-telemetry" id="rhinoTelemetry">
        <div class="telemetry-top-left">
            <span class="telemetry-dot"></span>
            <span class="telemetry-code">SYS://ROK.ORGANIZATION.v26 // PROTOCOL ACTIVE</span>
        </div>
        <button
            type="button"
            class="telemetry-skip-btn"
            id="btnSkipRhinoIntro"
            title="Lewati Intro (Tekan ESC)"
        >
            <span>LEWATI</span>
            <kbd>[ESC]</kbd>
        </button>
    </div>

    <!-- Central Motion Stage -->
    <div class="rhino-stage">
        <div class="radar-ring" aria-hidden="true"></div>

        <div class="rhino-head-wrapper">
            <img
                id="rhinoHeadImg"
                src="{{ asset('img/rok-rhino-head-light.png') }}"
                alt="ROK Rhino Silhouette"
                class="rhino-head-img"
            >
            <img
                id="rhinoEyeImg"
                src="{{ asset('img/rok-rhino-eye.png') }}"
                alt="ROK Rhino Eye"
                class="rhino-eye-img"
            >
            <div id="rhinoScanLine" class="rhino-scan-line" aria-hidden="true"></div>
        </div>

        <div class="rhino-wordmark-wrapper">
            <img
                id="rokWordmarkImg"
                src="{{ asset('img/rok-text-logo-light.png') }}"
                alt="Ruler Of Kings"
                class="rok-wordmark-img"
            >
            <p id="rhinoTagline" class="rhino-editorial-tagline">
                RULER OF KINGS 
            </p>
        </div>
    </div>

    <div class="rhino-intro-footer">
        <span class="footer-meta">EST. 2026 // COMPETITIVE DIVISION SYSTEM</span>
        <span class="footer-meta footer-meta--status">STATUS: ONLINE</span>
    </div>
</aside>

<style>
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
    .rhino-head-wrapper { width: 220px; }
    .rok-wordmark-img { width: 240px; }
    .radar-ring { width: 320px; height: 320px; }
    .rhino-intro-telemetry, .rhino-intro-footer { left: 16px; right: 16px; }
}
</style>

<script>
window.initBladeRhinoIntro = function(force = false) {
    const overlay = document.getElementById('rokRhinoIntroOverlay');
    if (!overlay) return;

    overlay.style.display = 'flex';
    const head = document.getElementById('rhinoHeadImg');
    const eye = document.getElementById('rhinoEyeImg');
    const scan = document.getElementById('rhinoScanLine');
    const wordmark = document.getElementById('rokWordmarkImg');
    const tagline = document.getElementById('rhinoTagline');
    const telemetry = document.getElementById('rhinoTelemetry');
    const skipBtn = document.getElementById('btnSkipRhinoIntro');

    if (!window.gsap) {
        overlay.style.display = 'none';
        return;
    }

    const gsap = window.gsap;
    gsap.set(overlay, { opacity: 1, scale: 1 });
    gsap.set(head, { opacity: 0, scale: 0.88, filter: 'brightness(0.3) blur(6px)' });
    gsap.set(eye, { opacity: 0, scale: 0.5, filter: 'drop-shadow(0 0 0px #fbfbfb)' });
    gsap.set(wordmark, { opacity: 0, y: 30, scale: 1.15, filter: 'blur(8px)' });
    gsap.set(scan, { top: '0%', opacity: 0 });
    gsap.set(tagline, { opacity: 0, letterSpacing: '4px' });

    let dismissed = false;
    const dismiss = () => {
        if (dismissed) return;
        dismissed = true;
        gsap.to(overlay, {
            opacity: 0,
            scale: 1.05,
            duration: 0.55,
            ease: 'power3.inOut',
            onComplete: () => {
                overlay.style.display = 'none';
            }
        });
    };

    skipBtn?.addEventListener('click', dismiss);
    const escHandler = (e) => {
        if (e.key === 'Escape') {
            dismiss();
            window.removeEventListener('keydown', escHandler);
        }
    };
    window.addEventListener('keydown', escHandler);

    const tl = gsap.timeline({
        onComplete: () => {
            setTimeout(dismiss, 600);
        }
    });

    tl.to(telemetry, { opacity: 1, duration: 0.35, ease: 'power2.out' })
      .to(scan, { opacity: 1, duration: 0.2 }, '-=0.1')
      .to(head, { opacity: 0.95, scale: 1, filter: 'brightness(1) blur(0px)', duration: 0.8, ease: 'power3.out' }, '-=0.1')
      .to(scan, { top: '100%', duration: 0.85, ease: 'power2.inOut' }, '-=0.8')
      .to(scan, { opacity: 0, duration: 0.2 }, '-=0.1')
      .to(eye, { opacity: 1, scale: 1.15, filter: 'drop-shadow(0 0 25px #fbfbfb) drop-shadow(0 0 45px #f59e0b)', duration: 0.35, ease: 'back.out(2)' })
      .to(eye, { scale: 1, duration: 0.2, ease: 'power2.inOut' })
      .to(wordmark, { opacity: 1, y: 0, scale: 1, filter: 'blur(0px)', duration: 0.5, ease: 'power4.out' }, '-=0.1')
      .to(tagline, { opacity: 1, letterSpacing: '10px', duration: 0.55, ease: 'power2.out' }, '-=0.2');
};

document.addEventListener('DOMContentLoaded', () => {
    // Only run on homepage /
    if (window.location.pathname === '/' || window.location.pathname === '') {
        window.initBladeRhinoIntro();
    }
});
</script>
