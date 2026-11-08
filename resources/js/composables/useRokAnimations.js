import { onUnmounted, nextTick } from 'vue';
import gsap from 'gsap';
import { animate, scroll, inView, spring } from 'motion';
import SplitType from 'split-type';
import {
    initAllRokAnimations,
    initScrollProgressBar,
    initPageEntranceAnimation,
    initScrollDrivenAnimations,
    initSpringHoverEffects,
} from '@/animations/rokPageAnimations';

/**
 * Precision Animation Composable for Ruler Of Kings
 * Powered by GSAP, Motion One, and SplitType.
 */
export function useRokAnimations() {
    const activeInstances = [];

    /**
     * Splits text into chars/words and applies high-precision GSAP kinetic reveal.
     */
    const animateSplitText = (target, options = {}) => {
        nextTick(() => {
            const el = typeof target === 'string' ? document.querySelector(target) : target;
            if (!el) return;

            try {
                const split = new SplitType(el, {
                    types: options.types || 'lines,words,chars',
                    tagName: 'span',
                });
                activeInstances.push(split);

                const targets = options.splitBy === 'words' ? split.words : split.chars;
                if (!targets || targets.length === 0) return;

                gsap.fromTo(
                    targets,
                    {
                        opacity: 0,
                        y: options.y ?? 24,
                        rotateX: options.rotateX ?? -40,
                        transformOrigin: '50% 100%',
                    },
                    {
                        opacity: 1,
                        y: 0,
                        rotateX: 0,
                        duration: options.duration || 0.7,
                        stagger: options.stagger ?? 0.02,
                        ease: options.ease || 'power3.out',
                        delay: options.delay || 0.1,
                        clearProps: 'transform,opacity',
                    }
                );
            } catch (err) {
                console.warn('SplitType animation error:', err);
            }
        });
    };

    /**
     * Hero Title Grand Entrance Animation
     */
    const animateHeroEntrance = (titleSelector, subtitleSelector, ctaSelector) => {
        nextTick(() => {
            const tl = gsap.timeline({ defaults: { ease: 'power4.out' } });

            const titleEl = document.querySelector(titleSelector);
            if (titleEl) {
                const split = new SplitType(titleEl, { types: 'lines,words,chars' });
                activeInstances.push(split);

                tl.fromTo(
                    split.chars,
                    {
                        opacity: 0,
                        y: 40,
                        scale: 0.9,
                        filter: 'blur(4px)',
                    },
                    {
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        filter: 'blur(0px)',
                        duration: 0.8,
                        stagger: 0.025,
                    }
                );
            }

            if (subtitleSelector && document.querySelector(subtitleSelector)) {
                tl.fromTo(
                    subtitleSelector,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.6 },
                    '-=0.4'
                );
            }

            if (ctaSelector && document.querySelector(ctaSelector)) {
                tl.fromTo(
                    ctaSelector,
                    { opacity: 0, scale: 0.95, y: 15 },
                    { opacity: 1, scale: 1, y: 0, duration: 0.5 },
                    '-=0.3'
                );
            }
        });
    };

    /**
     * Ultra-fast physics spring button/card hover via Motion One
     */
    const bindSpringHover = (element, scale = 1.03, y = -3) => {
        if (!element) return;
        const handleEnter = () => {
            animate(element, { scale, y }, { duration: 0.3, easing: spring({ stiffness: 400, damping: 25 }) });
        };
        const handleLeave = () => {
            animate(element, { scale: 1, y: 0 }, { duration: 0.3, easing: spring({ stiffness: 400, damping: 25 }) });
        };

        element.addEventListener('mouseenter', handleEnter);
        element.addEventListener('mouseleave', handleLeave);

        activeInstances.push({
            revert: () => {
                element.removeEventListener('mouseenter', handleEnter);
                element.removeEventListener('mouseleave', handleLeave);
            },
        });
    };

    /**
     * Stagger reveal list/cards via Motion One
     */
    const animateStaggerFadeIn = (selectorOrElements, delayStep = 0.08) => {
        nextTick(() => {
            const elements = typeof selectorOrElements === 'string'
                ? document.querySelectorAll(selectorOrElements)
                : selectorOrElements;

            if (!elements || elements.length === 0) return;

            animate(
                elements,
                { opacity: [0, 1], y: [25, 0] },
                {
                    delay: (i) => i * delayStep,
                    duration: 0.55,
                    easing: [0.22, 1, 0.36, 1],
                }
            );
        });
    };

    /**
     * Scroll progress bar binding via Motion One
     */
    const initScrollProgress = (progressBarEl) => {
        if (!progressBarEl) return;
        return scroll(
            animate(progressBarEl, { scaleX: [0, 1] }, { ease: 'linear' })
        );
    };

    /**
     * Automatic cleanup on component unmount
     */
    onUnmounted(() => {
        activeInstances.forEach((inst) => {
            if (inst && typeof inst.revert === 'function') {
                inst.revert();
            }
        });
    });

    return {
        animateSplitText,
        animateHeroEntrance,
        bindSpringHover,
        animateStaggerFadeIn,
        initScrollProgress,
        initAllRokAnimations,
        initScrollProgressBar,
        initPageEntranceAnimation,
        initScrollDrivenAnimations,
        initSpringHoverEffects,
        gsap,
        animate,
        scroll,
        inView,
        spring,
        SplitType,
    };
}
