<template>
    <router-view v-if="isAuthPage" />
    <ContentWrapper v-else>
        <router-view />
    </ContentWrapper>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import ContentWrapper from './contentWrapper.vue';

const route = useRoute();
const isAuthPage = computed(() => route.name === 'admin.login');
let expiryTimer;

const scheduleExpiryRedirect = () => {
    if (!route.name || isAuthPage.value) return;

    const expiresAt = Number(localStorage.getItem('token_expires_at') || 0);
    const delay = expiresAt - Date.now();

    if (delay <= 0) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('token_expires_at');
        window.location.href = '/admin/login?unauthorized=true';
        return;
    }

    expiryTimer = window.setTimeout(() => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('token_expires_at');
        window.location.href = '/admin/login?unauthorized=true';
    }, delay);
};

onMounted(scheduleExpiryRedirect);
onUnmounted(() => window.clearTimeout(expiryTimer));
</script>