<footer class="site-footer" id="community">
    <div class="site-footer__inner">

        {{-- Brand Column --}}
        <div class="site-footer__brand">
            <div class="site-footer__logo-row">
                <img
                    src="{{ asset('img/rok-logo.png') }}"
                    alt="ROK Logo"
                    class="site-footer__logo"
                    onerror="this.style.display='none'"
                >
                <div>
                    <h2 class="site-footer__title">ROK</h2>
                    <p class="site-footer__subtitle">Ruler of Kings</p>
                </div>
            </div>
            <p class="site-footer__description">
                Competitive esports organization focused on competition, development, discipline and unity.
            </p>
            <p class="site-footer__slogan">
                <span>ONE VISION.</span>
                <span>ONE TEAM.</span>
                <span>ONE KINGDOM.</span>
            </p>
        </div>

        {{-- Navigation Column --}}
        <div class="site-footer__links">
            <h3 class="site-footer__heading">Navigasi</h3>
            <ul class="site-footer__nav">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/teams') }}">Teams &amp; Structure</a></li>
                <li><a href="{{ url('/achievements') }}">Achievements</a></li>
                <li><a href="{{ url('/trial') }}">Join Trial</a></li>
                <li><a href="{{ url('/learning-development') }}">Learning &amp; Dev</a></li>
                <li><a href="{{ url('/bracket') }}">Tournament Bracket</a></li>
            </ul>
        </div>

        {{-- Community Column --}}
        <div class="site-footer__community">
            <h3 class="site-footer__heading">Community</h3>
            <div class="site-footer__social-list">
                <a href="https://discord.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                    <span class="social-badge__icon">⚡</span>
                    <span class="social-badge__name">Discord</span>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                    <span class="social-badge__icon">📸</span>
                    <span class="social-badge__name">Instagram</span>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                    <span class="social-badge__icon">▶</span>
                    <span class="social-badge__name">YouTube</span>
                </a>
            </div>
            <p class="site-footer__cta-text">
                Bergabunglah bersama ribuan pejuang esports lainnya di server resmi ROK.
            </p>
        </div>

    </div>

    <div class="site-footer__bottom">
        <div class="site-footer__bottom-inner">
            <p class="site-footer__copyright">
                &copy; {{ date('Y') }} Ruler Of Kings. All rights reserved.
            </p>
            <p class="site-footer__version">
                Official ROK Esports Portal
            </p>
        </div>
    </div>
</footer>
