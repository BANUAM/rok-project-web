<nav class="site-nav" aria-label="Main navigation">
    <div class="site-nav__inner">

        {{-- Brand / Logo --}}
        <a href="{{ url('/') }}" class="site-nav__brand" aria-label="Ruler Of Kings Home">
            <img
                src="{{ asset('img/rok-logo.png') }}"
                alt="ROK Logo"
                class="site-nav__logo"
                onerror="this.src='{{ asset('img/dummy-division.svg') }}'"
            >
            <span class="site-nav__brand-text">
                <span class="site-nav__brand-name">ROK</span>
                <span class="site-nav__brand-sub">Ruler of Kings</span>
            </span>
        </a>

        {{-- Nav Links --}}
        <div class="site-nav__links">
            <a href="{{ url('/') }}" class="site-nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a>
            <a href="{{ url('/teams') }}" class="site-nav__link {{ request()->is('teams*') ? 'is-active' : '' }}">Teams</a>
            <a href="{{ url('/achievements') }}" class="site-nav__link {{ request()->is('achievements*') ? 'is-active' : '' }}">Achievements</a>
            <a href="{{ url('/trial') }}" class="site-nav__link {{ request()->is('trial*') ? 'is-active' : '' }}">Trial</a>
            <a href="{{ url('/learning-development') }}" class="site-nav__link {{ request()->is('learning-development*') ? 'is-active' : '' }}">Learning &amp; Dev</a>
            <a href="{{ url('/bracket') }}" class="site-nav__link {{ request()->is('bracket*') ? 'is-active' : '' }}">Bracket</a>
            <a href="{{ url('/membership') }}" class="site-nav__link {{ request()->is('membership*') ? 'is-active' : '' }}">Membership</a>
            @auth
                <span class="site-nav__link" style="color: var(--rok-gold); font-weight: 700;">
                    &#x1F464; {{ Auth::user()->username ?? Str::words(Auth::user()->name, 1, '') }}
                </span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="site-nav__link" style="background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 13px;">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="site-nav__link {{ request()->routeIs('login') ? 'is-active' : '' }}" style="color: var(--rok-gold);">Masuk</a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <button class="site-nav__hamburger" id="navHamburger" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

    </div>
</nav>
