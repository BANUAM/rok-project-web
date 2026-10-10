document.addEventListener('DOMContentLoaded', () => {
    const headerWrapper = document.getElementById('siteHeaderWrapper');
    const headerReveal = document.getElementById('headerReveal');

    if (!headerWrapper || !headerReveal) {
        return;
    }

    // Header mulai dalam keadaan tersembunyi.
    headerWrapper.classList.add('is-hidden');
    headerReveal.setAttribute('aria-expanded', 'false');

    // Klik dapat digunakan untuk membuka atau menutup header.
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

    // Saat keyboard masuk ke area header, tampilkan header.
    headerWrapper.addEventListener('focusin', () => {
        headerWrapper.classList.remove('is-hidden');
        headerReveal.setAttribute('aria-expanded', 'true');
    });

    // Kembalikan keadaan tersembunyi saat fokus meninggalkan area.
    headerWrapper.addEventListener('focusout', (event) => {
        if (!headerWrapper.contains(event.relatedTarget)) {
            headerWrapper.classList.add('is-hidden');
            headerReveal.setAttribute('aria-expanded', 'false');
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('heroSlider');

    if (!slider) {
        return;
    }

    const slides = Array.from(slider.querySelectorAll('.hero-slide'));
    const indicators = Array.from(
        slider.querySelectorAll('[data-go-to]')
    );

    const previousButton = document.getElementById('heroPrevious');
    const nextButton = document.getElementById('heroNext');

    if (slides.length <= 1) {
        return;
    }

    let currentIndex = 0;
    let autoplayInterval = null;

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    const showSlide = (index) => {
        currentIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === currentIndex;

            slide.classList.toggle('is-active', isActive);
            slide.setAttribute('aria-hidden', String(!isActive));
        });

        indicators.forEach((indicator, indicatorIndex) => {
            const isActive = indicatorIndex === currentIndex;

            indicator.classList.toggle('is-active', isActive);
            indicator.setAttribute('aria-current', String(isActive));
        });
    };

    const nextSlide = () => showSlide(currentIndex + 1);
    const previousSlide = () => showSlide(currentIndex - 1);

    const stopAutoplay = () => {
        if (autoplayInterval !== null) {
            window.clearInterval(autoplayInterval);
            autoplayInterval = null;
        }
    };

    const startAutoplay = () => {
        stopAutoplay();

        if (reducedMotion.matches || document.hidden) {
            return;
        }

        autoplayInterval = window.setInterval(nextSlide, 6000);
    };

    previousButton?.addEventListener('click', () => {
        previousSlide();
        startAutoplay();
    });

    nextButton?.addEventListener('click', () => {
        nextSlide();
        startAutoplay();
    });

    indicators.forEach((indicator) => {
        indicator.addEventListener('click', () => {
            const index = Number(indicator.dataset.goTo);

            if (Number.isInteger(index)) {
                showSlide(index);
                startAutoplay();
            }
        });
    });

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);
    slider.addEventListener('focusin', stopAutoplay);
    slider.addEventListener('focusout', (event) => {
        if (!slider.contains(event.relatedTarget)) {
            startAutoplay();
        }
    });

    document.addEventListener('visibilitychange', startAutoplay);
    reducedMotion.addEventListener?.('change', startAutoplay);

    showSlide(0);
    startAutoplay();
});
