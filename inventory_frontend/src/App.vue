<template>

    <LayoutWrapper></LayoutWrapper>

</template>

<script setup>
import LayoutWrapper from './layouts/LayoutWrapper.vue';
import { useAppStore } from './stores/appStore';
import { useAuthStore } from './stores/authStore';


useAuthStore().loadUser();
useAppStore().loadInventories();
</script>