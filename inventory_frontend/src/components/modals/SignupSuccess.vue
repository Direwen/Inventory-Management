<template>
    <LayoutModalContent>
        <template #default>
            <section class="flex flex-col gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 animate-bounce">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>

                <h4 class="m-0">Email Verification Required</h4>

                <p class="m-0">
                    Please check your inbox at 
                    <span class="font-semibold">{{ props.email }}</span> 
                    and click the link to verify your email address.
                </p>
            </section>
        </template>

        <template #footer>
            <button class="btn" @click="authStore.resendVerificationLink(email)">Resend</button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useAuthStore } from '../../stores/authStore';

const props = defineProps(['email']);
const authStore = useAuthStore();
</script>
