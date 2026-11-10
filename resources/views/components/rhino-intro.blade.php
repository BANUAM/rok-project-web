{{-- Pembuka Ruler Of Kings (Blade). Putar setiap membuka / refresh halaman Home. --}}
<aside
    id="rokRhinoIntroOverlay"
    class="rok-intro"
    role="dialog"
    aria-label="Pembuka Ruler Of Kings"
    aria-modal="true"
    style="display: none;"
>
    <div class="rok-intro__stage">
        <img id="rhinoHeadImg" src="{{ asset('img/rok-rhino-head.png') }}" alt="Ruler Of Kings" class="rok-intro__head">
        <img id="rokWordmarkImg" src="{{ asset('img/rok-text-logo.png') }}" alt="" aria-hidden="true" class="rok-intro__wordmark">
        <p id="rhinoTagline" class="rok-intro__tagline">Ruler of Kings &middot; Esports Organization</p>
    </div>
    <span id="rhinoLine" class="rok-intro__line" aria-hidden="true"></span>
</aside>

<style>
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
.rok-intro__stage { display: flex; flex-direction: column; align-items: center; width: min(420px, 78vw); }
.rok-intro__head { width: 100%; max-width: 260px; height: auto; }
.rok-intro__wordmark { width: 190px; max-width: 55vw; margin-top: 30px; height: auto; }
.rok-intro__tagline { margin: 18px 0 0; font-family: 'Inter', system-ui, sans-serif; font-size: 13px; font-weight: 500; letter-spacing: 0.06em; color: #7b818b; }
.rok-intro__line { position: absolute; left: 0; right: 0; bottom: 0; height: 3px; background: #14161a; transform-origin: 0 50%; }
</style>

<script>
window.initBladeRhinoIntro = function () {
    const overlay = document.getElementById('rokRhinoIntroOverlay');
    if (!overlay) return;

    const head = document.getElementById('rhinoHeadImg');
    const wordmark = document.getElementById('rokWordmarkImg');
    const tagline = document.getElementById('rhinoTagline');
    const line = document.getElementById('rhinoLine');

    if (!window.gsap) {
        overlay.style.display = 'none';
        return;
    }

    const gsap = window.gsap;
    overlay.style.display = 'flex';

    gsap.set(overlay, { yPercent: 0 });
    gsap.set(head, { clipPath: 'inset(100% 0% 0% 0%)', y: 24 });
    gsap.set(wordmark, { opacity: 0, y: 18 });
    gsap.set(tagline, { opacity: 0, y: 10 });
    gsap.set(line, { scaleX: 0 });

    let dismissed = false;
    let tl = null;
    const dismiss = (skip) => {
        if (dismissed) return;
        dismissed = true;
        if (tl) tl.kill();
        window.removeEventListener('keydown', onKey);
        gsap.to(overlay, {
            yPercent: -100,
            duration: skip ? 0.6 : 0.9,
            ease: 'power4.inOut',
            onComplete: () => { overlay.style.display = 'none'; },
        });
    };
    const onKey = (e) => { if (e.key === 'Escape') dismiss(true); };

    overlay.addEventListener('click', () => dismiss(true));
    window.addEventListener('keydown', onKey);

    tl = gsap.timeline({ onComplete: () => setTimeout(() => dismiss(false), 450) });
    tl.to(line, { scaleX: 1, duration: 2.1, ease: 'power1.inOut' }, 0)
      .to(head, { clipPath: 'inset(0% 0% 0% 0%)', y: 0, duration: 1.1, ease: 'power3.inOut' }, 0.1)
      .to(wordmark, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, 0.85)
      .to(tagline, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }, 1.15);
};

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname === '/' || window.location.pathname === '') {
        window.initBladeRhinoIntro();
    }
});
</script>
