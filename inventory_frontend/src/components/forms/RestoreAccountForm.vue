<template>

    <AnimatedContainer cssForContainer="max-w-sm mx-auto mt-10">

        <div v-if="step === 1">
            <h1 class="text-center text-2xl">Account Recovery</h1>
            <p class="text-center text-sm text-gray-500 mb-4">Please enter the email to receive the OTP.</p>
            <form @submit.prevent="sendOtp" class="flex flex-col gap-3">
                <label class="input input-bordered flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
                    </svg>
                    <input required v-model="email" type="email" :readonly="step === 2"
                        class="grow placeholder:text-base-content/40" placeholder="Email" />
                </label>
                <section class="flex justify-between items-center gap-2">
                    <button type="button" class="btn grow" @click="skip" :disabled="!email">
                        Skip
                    </button>
                    <button type="button" class="btn grow" @click="sendOtp" :disabled="otpSent">
                        Send OTP
                    </button>

                </section>
            </form>
        </div>

        <div v-if="step === 2">
            <h1 class="text-center text-2xl">Verify OTP</h1>
            <p class="text-center text-sm text-gray-500 mb-4">Please enter the OTP sent to your email.</p>
            <form @submit.prevent="reactivate" class="flex flex-col gap-3">
                <label class="input input-bordered flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    <input required v-model="otp" type="text" class="grow placeholder:text-base-content/40"
                        placeholder="OTP" />
                </label>
                <section class="flex justify-between items-center gap-2">
                    <button type="button" class="btn grow" @click="goBackToStep1">
                        Back
                    </button>
                    <button type="submit" class="btn grow">Reactivate</button>
                </section>
            </form>
        </div>

    </AnimatedContainer>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/authStore';
import { useUiStore } from '../../stores/uiStore';
import AnimatedContainer from '../widgets/AnimatedContainer.vue';

const authStore = useAuthStore();
const uiStore = useUiStore();
const email = ref('');
const otp = ref('');
const step = ref(1);
const otpSent = ref(false);

const sendOtp = async () => {
    if (!validateEmail(email.value)) {
        uiStore.addNotification("error", "Invalid email format");
        return;
    }
    try {
        await authStore.sendReactivationOtp(email.value);
        otpSent.value = true;
        step.value = 2;
    } catch (error) {
        console.error('Error sending OTP:', error);
    }
};

const reactivate = async () => {
    try {
        await authStore.reactivateAccount(email.value, otp.value);
        resetForm();
    } catch (error) {
        console.error('Error reactivating account:', error);
    }
};

const skip = () => {
    if (!validateEmail(email.value)) {
        uiStore.addNotification("error", "Invalid email format");
        return;
    }
    step.value = 2;
};

const goBackToStep1 = () => {
    resetForm();
};

const resetForm = () => {
    step.value = 1;
    otpSent.value = false;
    otp.value = '';
    email.value = '';
};

const validateEmail = (email) => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
};
</script>
