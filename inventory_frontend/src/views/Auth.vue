<template>
    <AnimatedContainer cssForContainer="prose prose-sm md:prose-base lg:prose-lg w-full mx-auto">

        <LoginForm v-if="isLogin" @chgForm="(value) => isLogin = value" />
        <SignupForm v-else @chgForm="(value) => isLogin = value" />

    </AnimatedContainer>
</template>

<script setup>
import { ref } from 'vue';
import AnimatedContainer from "../components/widgets/AnimatedContainer.vue";
import SignupForm from '../components/forms/SignupForm.vue';
import LoginForm from '../components/forms/LoginForm.vue';


const isLogin = ref(false);

</script>