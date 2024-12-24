<template>
    <div class="">
        <h1 class="text-center">Login</h1>

        <form @submit.prevent="submitForm" class="flex flex-col gap-3">
            <label class="input input-bordered flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
                </svg>

                <input required v-model="email" type="email" class="grow placeholder:text-base-content/40" placeholder="Email" />
            </label>
            <label class="input input-bordered flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>

                <input required v-model="password" type="password" class="grow placeholder:text-base-content/40" placeholder="Password" />
            </label>

            <button type="submit" class="btn w-full my-3">Login</button>
        </form>

        <p class="text-center my-0 cursor-pointer select-none">New here? <span
                class="underline font-bold underline-offset-2" @click="toSignupForm">Sign Up</span></p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { defineEmits } from 'vue';
import { useAuthStore } from '../../stores/authStore';
import { useRouter } from 'vue-router';

const emit = defineEmits(['chgForm']);
const authStore = useAuthStore();
const email = ref('');
const password = ref('');
const router = useRouter();

const toSignupForm = () => {
    emit('chgForm', false);
};

const submitForm = async () => {
    try {
        
        await authStore.login(email.value, password.value);
        router.push('/');

    } catch (error) {

        console.log(error);

    }
};
</script>
