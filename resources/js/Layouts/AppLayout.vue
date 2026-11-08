<script setup>
import { onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();

onMounted(() => {
    window.renderLucideIcons && window.renderLucideIcons();
});

watch(
    () => page.url,
    () => {
        setTimeout(() => {
            window.renderLucideIcons && window.renderLucideIcons();
        }, 50);
    }
);
</script>

<template>
    <div class="rok-app-wrapper">
        <Navbar />

        <!-- Flash notifications -->
        <div v-if="page.props.flash && page.props.flash.success" class="flash-toast flash-toast--success" role="status">
            <span class="flash-toast__icon"><i data-lucide="check" style="width:16px;height:16px;"></i></span>
            <div class="flash-toast__content">
                <strong>Berhasil!</strong>
                <p>{{ page.props.flash.success }}</p>
            </div>
            <button type="button" class="flash-toast__close" @click="page.props.flash.success = null">&times;</button>
        </div>

        <div v-if="page.props.flash && page.props.flash.error" class="flash-toast flash-toast--error" role="alert">
            <span class="flash-toast__icon"><i data-lucide="alert-circle" style="width:16px;height:16px;"></i></span>
            <div class="flash-toast__content">
                <strong>Perhatian!</strong>
                <p>{{ page.props.flash.error }}</p>
            </div>
            <button type="button" class="flash-toast__close" @click="page.props.flash.error = null">&times;</button>
        </div>

        <main id="main-content">
            <slot />
        </main>

        <Footer />
    </div>
</template>
