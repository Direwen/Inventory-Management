<template>

    <LayoutWrapper></LayoutWrapper>

</template>

<script setup>
import { onMounted } from 'vue';
import LayoutWrapper from './layouts/LayoutWrapper.vue';
import { useAppStore } from './stores/appStore';
import { useAuthStore } from './stores/authStore';


const appStore = useAppStore();
const authStore = useAuthStore();

onMounted(async () => {
    await authStore.loadUser();
    if (authStore.isActive) {
        appStore.loadInventories();
        appStore.loadNotifications();
    }
})
</script>