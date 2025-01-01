<template>
    <div>
        <h4 class="text-lg font-semibold mb-2">{{ $t('profile.deactivate_account') }}</h4>
        <button class="btn btn-block bg-red-900 text-white tracking-wider uppercase hover:bg-red-700" @click="deactivate">
            {{ $t('deactivate') }}
        </button>
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
