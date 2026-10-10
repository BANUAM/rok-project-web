
document.addEventListener('DOMContentLoaded', () => {
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
