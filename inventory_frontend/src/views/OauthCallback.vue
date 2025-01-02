<template>
    <h1 class="text-4xl text-center md:text-8xl lg:text-9xl font-extrabold tracking-tighter uppercase">
        Processing ...
    </h1>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { onMounted } from 'vue';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const uiStore = useUiStore();

onMounted(() => uiStore.loading = true);

(async () => {
    try {
        await authStore.handleGoogleCallback(route.query);
    } catch (error) {
        
    } finally {
        router.push('/');
    }
})();

</script>