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
            <a href="{{ url('/bracket') }}" class="site-nav__link {{ request()->is('bracket') || request()->is('bracket/live*') ? 'is-active' : '' }}">Bracket</a>
            <a href="{{ url('/membership') }}" class="site-nav__link {{ request()->is('membership*') ? 'is-active' : '' }}">Membership</a>

            @auth
                @if(Auth::user()->hasBracketAccess())
                    <a href="{{ route('bracket.studio.index') }}" class="site-nav__link site-nav__link--studio {{ request()->is('bracket-studio*') ? 'is-active' : '' }}">
                        &#x1F451; Bracket Studio
                    </a>
                @endif
                <span class="site-nav__link site-nav__user-pill" title="{{ Auth::user()->activeMembershipPlan()?->name ?? 'ROK Member' }}">
                    &#x1F464; {{ Auth::user()->username ?? Str::words(Auth::user()->name, 1, '') }}
                    @if(Auth::user()->membershipTierSlug() !== 'free')
                        <span class="user-tier-tag">{{ strtoupper(Auth::user()->membershipTierSlug()) }}</span>
                    @endif
                </span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="site-nav__link site-nav__logout-btn">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="site-nav__link site-nav__link--login {{ request()->routeIs('login') ? 'is-active' : '' }}">Masuk</a>
            @endauth

            {{-- Global Dark / Light Theme Toggle --}}
            <button type="button" class="theme-toggle-btn" data-theme-toggle title="Ubah Tema Gelap / Terang" aria-label="Toggle Tema Gelap atau Terang">
                <span class="theme-toggle-icon" data-theme-icon>&#x2600;&#xFE0F;</span>
                <span class="theme-toggle-label" data-theme-label>Terang</span>
            </button>
        </div>

        {{-- Mobile Hamburger --}}
        <button class="site-nav__hamburger" id="navHamburger" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

    </div>
</nav>
