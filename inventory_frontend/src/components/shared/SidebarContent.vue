<template>
    <section class="flex flex-col gap-2">
        <!-- APP NAME -->
        <h1 class="uppercase tracking-tighter text-center">Inventory</h1>
        <!-- CREATE INVENTORY -->
        <CreateInventoryBtn />
        <!-- HOME -->
        <router-link to="/" class=" no-underline">Home</router-link>
        <!-- HELP -->
        <router-link to="/support" class=" no-underline">Help</router-link>
        <!-- DIVIDER -->
        <div class="divider divider-base-300 mb-0"></div>
        <!-- INVENTORIES -->
        <InventoriesDropdown />

    </section>

    <section class="flex flex-col gap-4">
        <!-- LANGUAGE SELECTOR -->
        <LanguageSelector />

        <button v-if="authStore.isActive" class="btn w-full btn-outline btn-xs sm:btn-sm md:btn-md" @click="authStore.logout">Log Out</button>

    </section>
</template>

<script setup>
import InventoriesDropdown from './InventoriesDropdown.vue';
import LanguageSelector from '../widgets/LanguageSelector.vue';
import CreateInventoryBtn from './CreateInventoryBtn.vue';
import { useAuthStore } from '../../stores/authStore';

const authStore = useAuthStore();
</script>