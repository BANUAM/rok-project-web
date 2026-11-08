<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="site-footer" id="community">
        <div class="site-footer__inner">
            <!-- Brand Column -->
            <div class="site-footer__brand">
                <div class="site-footer__logo-row">
                    <img
                        src="/img/rok-logo.png"
                        alt="ROK Logo"
                        class="site-footer__logo"
                        onerror="this.style.display='none'"
                    />
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

            <!-- Navigation Column -->
            <div class="site-footer__links">
                <h3 class="site-footer__heading">Navigasi</h3>
                <ul class="site-footer__nav">
                    <li><Link href="/">Home</Link></li>
                    <li><Link href="/teams">Teams &amp; Structure</Link></li>
                    <li><Link href="/achievements">Achievements</Link></li>
                    <li><Link href="/trial">Join Trial</Link></li>
                    <li><Link href="/learning-development">Learning &amp; Dev</Link></li>
                    <li><Link href="/bracket">Tournament Bracket</Link></li>
                    <li><Link href="/bracket/free">Free Bracket Generator</Link></li>
                </ul>
            </div>

            <!-- Community Column -->
            <div class="site-footer__community">
                <h3 class="site-footer__heading">Community</h3>
                <div class="site-footer__social-list">
                    <a href="https://discord.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                        <span class="social-badge__icon"><i data-lucide="zap" style="width:14px;height:14px;"></i></span>
                        <span class="social-badge__name">Discord</span>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                        <span class="social-badge__icon"><i data-lucide="instagram" style="width:14px;height:14px;"></i></span>
                        <span class="social-badge__name">Instagram</span>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="social-badge">
                        <span class="social-badge__icon"><i data-lucide="youtube" style="width:14px;height:14px;"></i></span>
                        <span class="social-badge__name">YouTube</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p>&copy; 2026 Ruler Of Kings (ROK) Revamp. Hak cipta dilindungi.</p>
        </div>
    </footer>
</template>
