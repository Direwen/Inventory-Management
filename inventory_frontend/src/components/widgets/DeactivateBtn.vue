<template>
    <div>
        <h4 class="text-lg font-semibold mb-2">Deactivate Account</h4>
        <button class="btn btn-block btn-outline btn-error" @click="deactivate">Deactivate</button>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/authStore';

const authStore = useAuthStore();
const router = useRouter();

const deactivate = async () => {
    try {
        await authStore.deactivate();
        router.push('/');  // Redirect to home page after deactivation
    } catch (error) {
        console.error('Error deactivating account:', error);
    }
}
</script>
