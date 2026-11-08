<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { animate, spring } from 'motion';

const page = usePage();
const mobileOpen = ref(false);
const theme = ref('dark');

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme.value);
    try {
        localStorage.setItem('rok-theme', theme.value);
    } catch (e) {}
    nextTick(() => {
        window.renderLucideIcons && window.renderLucideIcons();
    });
};

const toggleMobile = () => {
    mobileOpen.value = !mobileOpen.value;
};

const closeMobile = () => {
    mobileOpen.value = false;
};

const handleLogout = () => {
    router.post('/logout');
};

const isActive = (path) => {
    const url = page.url;
    if (path === '/') return url === '/';
    return url.startsWith(path);
};

onMounted(() => {
    try {
        const saved = localStorage.getItem('rok-theme') || 'dark';
        theme.value = saved;
        document.documentElement.setAttribute('data-theme', saved);
    } catch (e) {}

    window.renderLucideIcons && window.renderLucideIcons();
});
</script>

<template>
    <nav class="site-nav" aria-label="Main navigation">
        <div class="site-nav__inner">
            <!-- Brand / Logo -->
            <Link href="/" class="site-nav__brand" aria-label="Ruler Of Kings Home" @click="closeMobile">
                <img
                    src="/img/rok-logo.png"
                    alt="ROK Logo"
                    class="site-nav__logo"
                    onerror="this.src='/img/dummy-division.svg'"
                />
                <span class="site-nav__brand-text">
                    <span class="site-nav__brand-name">ROK</span>
                    <span class="site-nav__brand-sub">Ruler of Kings</span>
                </span>
            </Link>

            <!-- Nav Links -->
            <div class="site-nav__links" :class="{ 'is-open': mobileOpen }">
                <Link href="/" class="site-nav__link" :class="{ 'is-active': isActive('/') }" @click="closeMobile">Home</Link>
                <Link href="/teams" class="site-nav__link" :class="{ 'is-active': isActive('/teams') }" @click="closeMobile">Teams</Link>
                <Link href="/achievements" class="site-nav__link" :class="{ 'is-active': isActive('/achievements') }" @click="closeMobile">Achievements</Link>
                <Link href="/trial" class="site-nav__link" :class="{ 'is-active': isActive('/trial') }" @click="closeMobile">Trial</Link>
                <Link href="/learning-development" class="site-nav__link" :class="{ 'is-active': isActive('/learning-development') }" @click="closeMobile">Learning &amp; Dev</Link>
                <Link href="/bracket" class="site-nav__link" :class="{ 'is-active': isActive('/bracket') }" @click="closeMobile">Bracket</Link>
                <Link href="/membership" class="site-nav__link" :class="{ 'is-active': isActive('/membership') }" @click="closeMobile">Membership</Link>

                <template v-if="page.props.auth && page.props.auth.user">
                    <Link
                        v-if="page.props.auth.user.has_bracket_access"
                        href="/bracket-studio"
                        class="site-nav__link site-nav__link--studio"
                        :class="{ 'is-active': isActive('/bracket-studio') }"
                        style="display:inline-flex;align-items:center;gap:5px;"
                        @click="closeMobile"
                    >
                        <i data-lucide="crown" style="width:14px;height:14px;"></i> Bracket Studio
                    </Link>
                    <span
                        class="site-nav__link site-nav__user-pill"
                        style="display:inline-flex;align-items:center;gap:5px;"
                    >
                        <i data-lucide="user" style="width:13px;height:13px;"></i> {{ page.props.auth.user.name }}
                    </span>
                    <button type="button" class="site-nav__link site-nav__logout-btn" @click="handleLogout">
                        Keluar
                    </button>
                </template>
                <template v-else>
                    <Link href="/login" class="site-nav__link site-nav__link--login" :class="{ 'is-active': isActive('/login') }" @click="closeMobile">
                        Masuk
                    </Link>
                </template>

                <!-- Global Dark / Light Theme Toggle -->
                <button
                    type="button"
                    class="theme-toggle-btn"
                    title="Ubah Tema Gelap / Terang"
                    aria-label="Toggle Tema Gelap atau Terang"
                    @click="toggleTheme"
                >
                    <span class="theme-toggle-icon">
                        <i :data-lucide="theme === 'light' ? 'moon' : 'sun'" style="width:14px;height:14px;"></i>
                    </span>
                    <span class="theme-toggle-label">{{ theme === 'light' ? 'Gelap' : 'Terang' }}</span>
                </button>
            </div>

            <!-- Mobile Hamburger -->
            <button
                class="site-nav__hamburger"
                :class="{ 'is-active': mobileOpen }"
                aria-label="Toggle menu"
                :aria-expanded="mobileOpen"
                @click="toggleMobile"
            >
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
</template>
