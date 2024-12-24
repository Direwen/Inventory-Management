<template>
    <div ref="animationParent" class="prose prose-sm md:prose-base lg:prose-lg w-full mx-auto">

        <LoginForm v-if="isLogin" @chgForm="(value) => isLogin = value" />
        <SignupForm v-else @chgForm="(value) => isLogin = value" />

    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import autoAnimate from "@formkit/auto-animate"
import SignupForm from '../components/forms/SignupForm.vue';
import LoginForm from '../components/forms/LoginForm.vue';


const isLogin = ref(false);
const animationParent = ref(null);

// Apply auto-animate to the parent container
onMounted(() => {
    autoAnimate(animationParent.value);
})

</script>