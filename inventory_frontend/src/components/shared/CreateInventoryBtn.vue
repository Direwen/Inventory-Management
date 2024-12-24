<template>

    <button @click="authStore.isActive ? uiStore.openModal(CreateInventoryForm) : uiStore.addNotification('error', 'Please log in to continue')" class="btn w-full btn-outline btn-xs sm:btn-sm md:btn-md uppercase mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        {{ $t('menu.create') }}
    </button>

</template>

<script setup>
import { useUiStore } from '../../stores/uiStore';
import { useAuthStore } from '../../stores/authStore';
import CreateInventoryForm from '../forms/CreateInventoryForm.vue';

const uiStore = useUiStore();
const authStore = useAuthStore();
</script>