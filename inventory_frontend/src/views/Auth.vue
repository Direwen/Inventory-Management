<template>
    <AnimatedContainer cssForContainer="prose prose-sm md:prose-base lg:prose-lg w-full mx-auto">

        <LoginForm v-if="isLogin" @chgForm="(value) => isLogin = value" />
        <SignupForm v-else @chgForm="(value) => isLogin = value" />

    </AnimatedContainer>
    <div class="divider"> OR </div>

        <button class="btn md:btn-wide py-2 mx-auto uppercase tracking-wider overflow-hidden flex justify-center items-center" @click="useAuthStore().getGoogleRedirectLink">
            <img src="https://static-00.iconduck.com/assets.00/google-icon-2048x673-w3o7skkh.png" alt="google" class="w-24 m-0 contrast-50">
        </button>

</template>

<script setup>
import { ref } from 'vue';
import AnimatedContainer from "../components/widgets/AnimatedContainer.vue";
import SignupForm from '../components/forms/SignupForm.vue';
import LoginForm from '../components/forms/LoginForm.vue';
import { useAuthStore } from '../stores/authStore';


const isLogin = ref(false);

</script>