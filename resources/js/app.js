import './header.js';
import './hero-slider.js';
import './hero-section-two.js';

function applyRokTheme(theme) {
    const normalized = theme === 'light' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', normalized);
    localStorage.setItem('rok-theme', normalized);

    document.querySelectorAll('[data-theme-icon]').forEach((el) => {
        el.innerHTML = normalized === 'light' ? '&#x1F319;' : '&#x2600;&#xFE0F;';
    });
    document.querySelectorAll('[data-theme-label]').forEach((el) => {
        el.textContent = normalized === 'light' ? 'Gelap' : 'Terang';
    });
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
});
