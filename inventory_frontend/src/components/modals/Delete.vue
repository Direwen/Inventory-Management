<template>
    <LayoutModalContent>
        <template #header>
            <h3>Confirm Deletion</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <p class="text-lg">
                Are you sure you want to permanently delete this {{ entityType }}: <strong>{{ entityName }}</strong>?
            </p>
            <p class="text-sm text-gray-500">
                This action cannot be undone. Please confirm to proceed.
            </p>
        </template>

        <template #footer>
            <button class="btn" @click="confirm" aria-label="Confirm deletion of entity">
                Delete
            </button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useAppStore } from '../../stores/appStore';
import { useUiStore } from '../../stores/uiStore';

const props = defineProps(["entityToDelete", "entityType", "entityName", "onConfirm"]);
const appStore = useAppStore();
const uiStore = useUiStore();

const confirm = () => {
    props.onConfirm(props.entityToDelete);
    // appStore.removeUser(props.entityToDelete);
    uiStore.closeModal();
}
</script>
