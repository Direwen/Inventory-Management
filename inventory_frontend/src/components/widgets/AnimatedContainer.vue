<template>
    <div ref="animationParent" :class="cssForContainer">
        <slot></slot>
    </div>
</template>

<script setup>
import autoAnimate from "@formkit/auto-animate"
import { onMounted, ref } from "vue";

const props = defineProps(["cssForContainer"]);
const animationParent = ref(null);

onMounted(() => autoAnimate(animationParent.value))
</script>