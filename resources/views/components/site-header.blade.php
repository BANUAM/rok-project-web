<div class="site-header-wrapper" id="siteHeaderWrapper">
    <header class="site-header" id="siteHeader">
        <div class="site-header__inner">
            <a href="{{ url('/') }}"
               class="site-header__brand"
               aria-label="Ruler Of Kings Home">
                <img
                    src="{{ asset('img/rok-logo.png') }}"
                    alt="Ruler Of Kings Logo"
                    class="site-header__logo"
                    onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
                >
            </a>

            <div class="site-header__identity">
                <p class="site-header__tagline">
                    ONE VISION. ONE TEAM. ONE KINGDOM
                </p>

                <div class="site-header__socials" aria-label="Social media">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">IG</a>
                    <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" aria-label="TikTok">TT</a>
                    <a href="https://discord.com" target="_blank" rel="noopener noreferrer" aria-label="Discord">DC</a>
                </div>
            </div>
        </div>
    </header>

    <button
        type="button"
        class="site-header__reveal"
        id="headerReveal"
        aria-label="Tampilkan header"
        aria-controls="siteHeader"
        aria-expanded="true"
    >
        <span class="site-header__reveal-label">Ruler Of Kings</span>
        <span class="site-header__reveal-label" aria-hidden="true">&#x2304;</span>
    </button>
</div>

<nav class="site-nav" aria-label="Main navigation">
    <div class="site-nav__inner">
        <a href="{{ url('/') }}" class="site-nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a>
        <a href="{{ url('/teams') }}" class="site-nav__link {{ request()->is('teams*') ? 'is-active' : '' }}">Teams</a>
        <a href="{{ url('/achievements') }}" class="site-nav__link {{ request()->is('achievements*') ? 'is-active' : '' }}">Achievements</a>
        <a href="{{ url('/trial') }}" class="site-nav__link {{ request()->is('trial*') ? 'is-active' : '' }}">Trial</a>
        <a href="{{ url('/learning-development') }}" class="site-nav__link {{ request()->is('learning-development*') ? 'is-active' : '' }}">Learning &amp; Development</a>
        <a href="{{ url('/bracket') }}" class="site-nav__link {{ request()->is('bracket*') ? 'is-active' : '' }}">Bracket</a>
    </div>
</nav>
