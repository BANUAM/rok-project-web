import gsap from 'gsap';
import { animate, scroll, inView, spring } from 'motion';
import SplitType from 'split-type';

/**
 * Global Animation Suite for Ruler Of Kings (ROK)
 * Powered by GSAP, Motion One, and SplitType.
 * Handles page entrance on load & route change, and scroll-triggered reveals across all pages.
 */

let scrollProgressCleanup = null;
let activeInViewCleanups = [];

/**
 * 1. Fixed Top Kinetic Scroll Progress Bar
 */
export function initScrollProgressBar() {
    if (typeof window === 'undefined') return;

    if (scrollProgressCleanup && typeof scrollProgressCleanup === 'function') {
        scrollProgressCleanup();
        scrollProgressCleanup = null;
    }

    let bar = document.getElementById('rokGlobalProgressBar');
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'rokGlobalProgressBar';
        bar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #d4af37 0%, #f59e0b 50%, #e2e8f0 100%);
            transform-origin: 0% 50%;
            transform: scaleX(0);
            z-index: 99999;
            pointer-events: none;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        `;
        document.body.appendChild(bar);
    }

    try {
        scrollProgressCleanup = scroll(
            animate(bar, { scaleX: [0, 1] }, { ease: 'linear' })
        );
    } catch (err) {
        // Fallback to standard scroll event listener
        const updateProgress = () => {
            const h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = h > 0 ? window.scrollY / h : 0;
            bar.style.transform = `scaleX(${Math.min(1, Math.max(0, progress))})`;
        };
        window.addEventListener('scroll', updateProgress, { passive: true });
        scrollProgressCleanup = () => window.removeEventListener('scroll', updateProgress);
    }
}

/**
 * 2. Page Entrance Animation (Executed when opening any page)
 */
export function initPageEntranceAnimation() {
    if (typeof window === 'undefined') return;

    const pageContainers = document.querySelectorAll(
        '.page-container, #main-content, .rok-main, .page-hero'
    );
    const mainContainer = document.querySelector('.page-container') || document.getElementById('main-content');

    if (mainContainer) {
        animate(
            mainContainer,
            { opacity: [0, 1], y: [16, 0] },
            { duration: 0.55, easing: [0.16, 1, 0.3, 1] }
        );
    }

    // Kinetic Typography for Main Page Title
    const titleEl = document.querySelector('.page-title, .hero-title, h1.section-title');
    if (titleEl && !titleEl.dataset.rokSplitAnimated) {
        titleEl.dataset.rokSplitAnimated = 'true';
        try {
            const split = new SplitType(titleEl, {
                types: 'words,chars',
                tagName: 'span',
            });

            if (split.chars && split.chars.length > 0) {
                gsap.fromTo(
                    split.chars,
                    {
                        opacity: 0,
                        y: 28,
                        rotateX: -25,
                        transformOrigin: '50% 100%',
                    },
                    {
                        opacity: 1,
                        y: 0,
                        rotateX: 0,
                        duration: 0.7,
                        stagger: 0.02,
                        ease: 'power3.out',
                        delay: 0.08,
                        clearProps: 'transform,opacity',
                    }
                );
            }
        } catch (e) {
            animate(
                titleEl,
                { opacity: [0, 1], y: [20, 0] },
                { duration: 0.6, easing: [0.16, 1, 0.3, 1] }
            );
        }
    }

    // Subtitle & Header Sub-elements Staggered Entrance
    const subElements = document.querySelectorAll(
        '.page-subtitle-gold, .page-subtitle, .hero-subtitle, .hero-description, .studio-portal-banner'
    );
    if (subElements.length > 0) {
        animate(
            subElements,
            { opacity: [0, 1], y: [14, 0] },
            {
                delay: (i) => 0.15 + i * 0.08,
                duration: 0.5,
                easing: [0.22, 1, 0.36, 1],
            }
        );
    }
}

/**
 * 3. Scroll-Driven Reveal Animations (Triggered as user scrolls down)
 */
export function initScrollDrivenAnimations() {
    if (typeof window === 'undefined') return;

    // Cleanup previous observer instances on page change
    activeInViewCleanups.forEach((cleanup) => {
        if (typeof cleanup === 'function') cleanup();
    });
    activeInViewCleanups = [];

    // Target elements to reveal on scroll
    const scrollTargets = [
        '.section-header',
        '.section-title:not(.page-title)',
        '.section-subtitle',
        '.team-card',
        '.team-member-card',
        '.achievement-card',
        '.plan-card',
        '.pricing-card',
        '.module-card',
        '.lesson-card',
        '.branding-card-box',
        '.generator-inputs',
        '.generated-bracket-output',
        '.wizard-card',
        '.mgmt-card',
        '.stat-card',
        '.hero-slide-preview',
        '.rules-card',
        '.featured-video-card',
        '.division-block',
        '.division-card',
        '.studio-cyber-dock',
        '[data-rok-animate="scroll-reveal"]',
    ];

    const observedElements = new Set();

    scrollTargets.forEach((selector) => {
        document.querySelectorAll(selector).forEach((el) => {
            if (observedElements.has(el)) return;
            observedElements.add(el);

            // Set initial state before inView fires
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.willChange = 'opacity, transform';

            const stopInView = inView(
                el,
                () => {
                    animate(
                        el,
                        { opacity: 1, y: 0 },
                        {
                            duration: 0.6,
                            easing: [0.22, 1, 0.36, 1],
                        }
                    );
                },
                { margin: '0px 0px -40px 0px' }
            );

            activeInViewCleanups.push(stopInView);
        });
    });

    // Stagger containers (e.g. grids of cards)
    const staggerContainers = [
        '.teams-grid',
        '.achievements-grid',
        '.pricing-cards-grid',
        '.membership-plans-grid',
        '.plans-grid',
        '.modules-grid',
        '.team-inputs-grid',
        '.founders-grid',
        '.founder-grid',
        '.divisions-grid',
    ];

    staggerContainers.forEach((containerSelector) => {
        document.querySelectorAll(containerSelector).forEach((container) => {
            const children = Array.from(container.children).filter(
                (c) => !c.classList.contains('no-animate')
            );
            if (children.length === 0) return;

            children.forEach((child) => {
                child.style.opacity = '0';
                child.style.transform = 'translateY(24px)';
                child.style.willChange = 'opacity, transform';
            });

            const stopContainer = inView(
                container,
                () => {
                    animate(
                        children,
                        { opacity: 1, y: 0 },
                        {
                            delay: (i) => i * 0.07,
                            duration: 0.55,
                            easing: [0.22, 1, 0.36, 1],
                        }
                    );
                },
                { margin: '0px 0px -30px 0px' }
            );

            activeInViewCleanups.push(stopContainer);
        });
    });
}

/**
 * 4. Spring Hover Micro-Interactions
 */
export function initSpringHoverEffects() {
    if (typeof window === 'undefined') return;

    const hoverElements = document.querySelectorAll(
        '.btn-rok, .plan-card, .team-card, .achievement-card, .cyber-chip-btn, .theme-toggle-btn'
    );

    hoverElements.forEach((el) => {
        if (el.dataset.rokHoverBound) return;
        el.dataset.rokHoverBound = 'true';

        el.addEventListener('mouseenter', () => {
            animate(
                el,
                { scale: 1.025, y: -2 },
                { duration: 0.25, easing: spring({ stiffness: 450, damping: 26 }) }
            );
        });

        el.addEventListener('mouseleave', () => {
            animate(
                el,
                { scale: 1, y: 0 },
                { duration: 0.25, easing: spring({ stiffness: 450, damping: 26 }) }
            );
        });
    });
}

/**
 * Master initializer: runs scroll progress, entrance, scroll reveals, and hover physics
 */
export function initAllRokAnimations() {
    initScrollProgressBar();
    initPageEntranceAnimation();
    initScrollDrivenAnimations();
    initSpringHoverEffects();
}
