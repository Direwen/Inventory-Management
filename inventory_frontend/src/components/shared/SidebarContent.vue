<template>
    <section class="flex-1 overflow-y-scroll hide-scrollbar flex flex-col gap-2">
        <!-- APP NAME -->
        <h1 class="uppercase tracking-tighter text-center">Inventory</h1>
        <!-- CREATE INVENTORY -->
        <CreateInventoryBtn />
        <!-- HOME -->
        <router-link to="/" class=" no-underline flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Home
        </router-link>
        <!-- DIVIDER -->
        <div class="divider divider-base-300 mb-0"></div>
        <!-- INVENTORIES -->
        <InventoriesDropdown v-if="!appStore.activeInventory" />
        <ActiveInventoryNavigation v-else />
    </section>

    <section class="h-fit flex flex-col gap-4">
        <!-- LANGUAGE SELECTOR -->
        <LanguageSelector />

        <button v-if="authStore.isActive" class="btn w-full btn-outline btn-xs sm:btn-sm md:btn-md" @click="authStore.logout">Log Out</button>

    </section>
</template>

<script setup>
import InventoriesDropdown from './InventoriesDropdown.vue';
import LanguageSelector from '../widgets/LanguageSelector.vue';
import CreateInventoryBtn from './CreateInventoryBtn.vue';
import ActiveInventoryNavigation from './ActiveInventoryNavigation.vue';
import { useAuthStore } from '../../stores/authStore';
import { useAppStore } from '../../stores/appStore';

const authStore = useAuthStore();
const appStore = useAppStore();
</script>