import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createIcons, icons } from 'lucide';
import gsap from 'gsap';
import { animate, scroll } from 'motion';
import SplitType from 'split-type';
import { initAllRokAnimations } from './animations/rokPageAnimations';

// Legacy script imports for Blade compatibility
import './header.js';
import './hero-slider.js';
import './hero-section-two.js';

// Global access to animation engines & helpers
window.gsap = gsap;
window.motionAnimate = animate;
window.motionScroll = scroll;
window.SplitType = SplitType;
window.initAllRokAnimations = initAllRokAnimations;

// Lucide Icons initialization
window.lucide = {
    createIcons: (options) => createIcons({ icons, ...options }),
    icons,
};

window.renderLucideIcons = function () {
    createIcons({
        icons,
        attrs: {
            'stroke-width': 2,
            class: 'lucide-icon',
        },
    });
};

export function applyRokTheme(theme) {
    const normalized = theme === 'light' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', normalized);
    try {
        localStorage.setItem('rok-theme', normalized);
    } catch (e) {}

    document.querySelectorAll('[data-theme-icon]').forEach((el) => {
        el.innerHTML = normalized === 'light'
            ? '<i data-lucide="moon" style="width:16px;height:16px;"></i>'
            : '<i data-lucide="sun" style="width:16px;height:16px;"></i>';
    });
    document.querySelectorAll('[data-theme-label]').forEach((el) => {
        el.textContent = normalized === 'light' ? 'Gelap' : 'Terang';
    });
    window.renderLucideIcons();
}

window.applyRokTheme = applyRokTheme;

document.addEventListener('DOMContentLoaded', () => {
    try {
        const initialTheme = localStorage.getItem('rok-theme') || document.documentElement.getAttribute('data-theme') || 'dark';
        applyRokTheme(initialTheme);
    } catch (e) {}

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            applyRokTheme(current === 'dark' ? 'light' : 'dark');
        });
    });

    window.renderLucideIcons();

    // Trigger animations on initial Blade / full-page load
    setTimeout(() => {
        initAllRokAnimations();
    }, 60);
});

// Re-initialize animations and icons on Inertia client-side page transitions
if (typeof router !== 'undefined' && router.on) {
    router.on('navigate', () => {
        window.renderLucideIcons();
        setTimeout(() => {
            initAllRokAnimations();
        }, 80);
    });
}

// Inertia Mount
const appEl = document.getElementById('app');
if (appEl && (appEl.dataset.page || appEl.getAttribute('data-page'))) {
    createInertiaApp({
        title: (title) => title ? `${title} | Ruler Of Kings` : 'ROK Revamp | Ruler Of Kings',
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            ),
        setup({ el, App, props, plugin }) {
            const vueApp = createApp({ render: () => h(App, props) });
            vueApp.use(plugin);
            vueApp.component('InertiaHead', Head);
            vueApp.component('InertiaLink', Link);
            vueApp.mount(el);
            window.renderLucideIcons();
        },
        progress: {
            color: '#d4af37',
            showSpinner: true,
        },
    });
}
