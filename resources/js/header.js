
document.addEventListener('DOMContentLoaded', () => {
    // Mobile hamburger menu toggle
    const hamburger = document.getElementById('navHamburger');
    const navLinks = document.querySelector('.site-nav__links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('is-open');
            hamburger.setAttribute('aria-expanded', String(isOpen));
        });

        // Close menu when a link is clicked
        navLinks.querySelectorAll('.site-nav__link').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // Legacy header reveal support if element exists
    const headerWrapper = document.getElementById('siteHeaderWrapper');
    const headerReveal = document.getElementById('headerReveal');

    if (!headerWrapper || !headerReveal) {
        return;
    }

    headerWrapper.classList.add('is-hidden');
    headerReveal.setAttribute('aria-expanded', 'false');

    headerReveal.addEventListener('click', () => {
        const isHidden = headerWrapper.classList.toggle('is-hidden');

        headerReveal.setAttribute(
            'aria-expanded',
            String(!isHidden)
        );

        headerReveal.setAttribute(
            'aria-label',
            isHidden ? 'Tampilkan header' : 'Sembunyikan header'
        );
    });

    headerWrapper.addEventListener('focusin', () => {
        headerWrapper.classList.remove('is-hidden');
        headerReveal.setAttribute('aria-expanded', 'true');
    });

    headerWrapper.addEventListener('focusout', (event) => {
        if (!headerWrapper.contains(event.relatedTarget)) {
            headerWrapper.classList.add('is-hidden');
            headerReveal.setAttribute('aria-expanded', 'false');
        }
    });
});
