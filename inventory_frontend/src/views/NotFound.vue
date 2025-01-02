<template>
    <section class="flex flex-col items-center justify-center gap-4">

        <h1 class="text-4xl text-center md:text-8xl lg:text-9xl font-extrabold tracking-tighter uppercase animate-pulse">
        Page Not Found <span class="text-secondary-content break-keep">:_ (</span>
    </h1>
    <button @click="router.push('/')" class="btn btn-wide btn-outline mt-4 mx-auto uppercase tracking-wide">Return</button>

    </section>
</template>

<script setup>

import { useRouter } from 'vue-router';

const router = useRouter();
</script>