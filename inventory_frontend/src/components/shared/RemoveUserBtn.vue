<template>
    <div class="lg:tooltip lg:tooltip-left" data-tip="remove the user">
        <button class="btn btn-circle" @click="handleDelete">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6 hover:text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { useUiStore } from '../../stores/uiStore';
import Delete from '../modals/Delete.vue';

const props = defineProps(["email", "recordId"]);
const uiStore = useUiStore();

const handleDelete = () => {
    uiStore.openModal(Delete, {
        entityName: props.email,
        entityToDelete: props.recordId,
        entityType: 'user'
    });
};
</script>
