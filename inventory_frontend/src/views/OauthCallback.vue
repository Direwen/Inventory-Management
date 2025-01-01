<template>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

(async () => {
    try {
        await authStore.handleGoogleCallback(route.query);
    } catch (error) {
        
    } finally {
        router.push('/');
    }
})();

</script>