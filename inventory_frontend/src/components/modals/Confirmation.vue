<template>
    <LayoutModalContent>

        <template #header>
            <h3>Confirm Role Change</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <p class="">
                Are you sure you want to change the role from <strong>{{ currentRole }}</strong> to <strong>{{ newRole }}</strong>?
            </p>
            <p class="text-sm text-gray-500">
                This action will immediately update the user's permissions. Please ensure you have the correct role selected.
            </p>
        </template>

        <template #footer>
            <button class="btn" @click="confirm">Confirm</button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useAppStore } from '../../stores/appStore';
import { useUiStore } from '../../stores/uiStore';

const props = defineProps(["currentRole", "newRole", "Email", "userId"]);
const appStore = useAppStore();
const uiStore = useUiStore();

const confirm = () => {
    try {
        appStore.updateUserRole(props.userId, props.newRole);
        uiStore.closeModal();
    } catch (error) {
        
    }
}
</script>
