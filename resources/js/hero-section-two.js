
document.addEventListener('DOMContentLoaded', () => {
    const stack = document.getElementById('teamStack');
    const nameElement = document.getElementById('teamMemberName');
    const counterElement = document.getElementById('teamCounter');
    const previousButton = document.getElementById('teamPrevious');
    const nextButton = document.getElementById('teamNext');

    if (!stack || !nameElement || !counterElement) return;

    const cards = Array.from(stack.querySelectorAll('[data-member]'));
    const members = cards.map(card => card.dataset.memberName);

    if (cards.length === 0 || members.some(name => !name)) return;

    let activeIndex = 0;
    let autoplayInterval = null;
    let nameAnimation = null;

    const autoplayDelay = 5000;
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    function renderMember(index) {
        activeIndex = (index + members.length) % members.length;
        stack.dataset.active = String(activeIndex);

        cards.forEach((card, cardIndex) => {
            const isActive = cardIndex === activeIndex;

            card.classList.toggle('is-active', isActive);
            card.setAttribute('aria-pressed', String(isActive));
            card.tabIndex = isActive ? 0 : -1;
        });

        clearTimeout(nameAnimation);
        nameElement.style.opacity = '0';

        nameAnimation = setTimeout(() => {
            nameElement.textContent = members[activeIndex];
            nameElement.style.opacity = '1';
        }, 150);

        counterElement.textContent =
            `${String(activeIndex + 1).padStart(2, '0')} / ` +
            `${String(members.length).padStart(2, '0')}`;
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }

    function startAutoplay() {
        stopAutoplay();

        if (reducedMotion.matches || members.length < 2) return;
        if (document.hidden) return;

        autoplayInterval = setInterval(() => {
            renderMember(activeIndex + 1);
        }, autoplayDelay);
    }

    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            renderMember(index);
            startAutoplay();
        });
    });

    previousButton?.addEventListener('click', () => {
        renderMember(activeIndex - 1);
        startAutoplay();
    });

    nextButton?.addEventListener('click', () => {
        renderMember(activeIndex + 1);
        startAutoplay();
    });

    stack.addEventListener('mouseenter', stopAutoplay);
    stack.addEventListener('mouseleave', startAutoplay);
    stack.addEventListener('focusin', stopAutoplay);

    stack.addEventListener('focusout', event => {
        if (!stack.contains(event.relatedTarget)) {
            startAutoplay();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoplay();
        } else {
            startAutoplay();
        }
    });

    reducedMotion.addEventListener('change', startAutoplay);

    renderMember(0);
    startAutoplay();
});
