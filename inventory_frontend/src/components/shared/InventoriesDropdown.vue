<template>
    <div class="relative">
        <button @click="toggleDropdown" class="w-full flex justify-between items-center">
            <span class="font-semibold">Inventories</span>
            <svg :class="{ 'rotate-180': isOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 15l-7.5-7.5L4.5 15" />
            </svg>
        </button>

        <div v-show="isOpen" class="absolute w-full z-10">
            <p v-if="!authStore.isActive" class="py-2 text-center text-gray-500">Please log in to continue</p>

            <section v-else-if="appStore.inventories?.length">
                <div v-for="(group, key) in groupedInventories" :key="key" v-show="group.length != 0" class="mb-8">
                    <h4 class="text-xs font-semibold ">{{ key }}</h4>
                    <router-link v-for="each in group" :key="each.id"
                        :to="{ name: 'Inventory', params: { id: each.id } }"
                        class="block no-underline mb-[2px] truncate text-sm py-2 hover:bg-base-300 rounded px-4">
                        {{ each.name }}
                    </router-link>
                </div>
            </section>

            <p v-else class="py-2 text-center text-gray-500">No Inventories</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../../stores/authStore';
import { useAppStore } from '../../stores/appStore';
import { useUiStore } from '../../stores/uiStore';

const isOpen = ref(true);
const authStore = useAuthStore();
const appStore = useAppStore();
const uiStore = useUiStore();

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

// Group inventories by usage date
const groupedInventories = computed(() => {
    const today = [];
    const thisWeek = [];
    const older = [];

    const now = new Date();
    const oneDay = 1000 * 60 * 60 * 24;

    appStore.inventories.forEach((inventory) => {
        const updatedAt = new Date(inventory.updated_at);
        const diffDays = Math.floor((now - updatedAt) / oneDay);

        if (diffDays === 0) {
            today.push(inventory);
        } else if (diffDays <= 7) {
            thisWeek.push(inventory);
        } else {
            older.push(inventory);
        }
    });

    return {
        'Today': today,
        'This Week': thisWeek,
        'Older': older
    };
});
</script>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}
</style>