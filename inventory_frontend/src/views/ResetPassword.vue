<template>
    <div class="prose prose-sm md:prose-base lg:prose-lg w-full mx-auto">
        <ResetPasswordForm :token="token" />
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import ResetPasswordForm from '../components/forms/ResetPasswordForm.vue';
import { onMounted, ref } from 'vue';
import { useAuthStore } from '../stores/authStore';

const route = useRoute();
const token = ref('');
const authStore = useAuthStore();

onMounted(async () => {
    token.value = route.params.token;
})
</script>