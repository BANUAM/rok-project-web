import { createIcons, icons } from 'lucide';
import './header.js';
import './hero-slider.js';
import './hero-section-two.js';

// Global Lucide Icons initialization
window.lucide = {
    createIcons: (options) => createIcons({ icons, ...options }),
    icons
};

window.renderLucideIcons = function() {
    createIcons({
        icons,
        attrs: {
            'stroke-width': 2,
            class: 'lucide-icon'
        }
    });
};

function applyRokTheme(theme) {
    const normalized = theme === 'light' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', normalized);
    localStorage.setItem('rok-theme', normalized);

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

document.addEventListener('DOMContentLoaded', () => {
    const initialTheme = localStorage.getItem('rok-theme') || document.documentElement.getAttribute('data-theme') || 'dark';
    applyRokTheme(initialTheme);

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            applyRokTheme(current === 'dark' ? 'light' : 'dark');
        });
    });

    window.renderLucideIcons();
});
