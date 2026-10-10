
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
                >
            </a>

            <div class="site-header__identity">
                <p class="site-header__tagline">
                    ONE VISION. ONE TEAM. ONE KINGDOM
                </p>

                <div class="site-header__socials" aria-label="Social media">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="TikTok">TT</a>
                    <a href="#" aria-label="Discord">DC</a>
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
        <span class="site-header__reveal-label" aria-hidden="true">⌄</span>
    </button>

</div>

<nav class="site-nav" aria-label="Main navigation">
    <div class="site-nav__inner">
        <a href="{{ url('/') }}" class="site-nav__link">Home</a>
        <a href="{{ url('/teams') }}" class="site-nav__link">Teams</a>
        <a href="{{ url('/achievements') }}" class="site-nav__link">Achievement</a>
        <a href="{{ url('/trial') }}" class="site-nav__link">Trial</a>
        <a href="{{ url('/learning-development') }}" class="site-nav__link">
            Learn &amp; Development
        </a>
        <a href="{{ url('/bracket') }}" class="site-nav__link">Bracket</a>
    </div>
</nav>
