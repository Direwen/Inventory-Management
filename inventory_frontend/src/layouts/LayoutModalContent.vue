<template>

    <slot name="header"></slot>


    <slot name="default">
        <div>
            <p class="">No content provided for the modal body. This is the fallback content.</p>
        </div>
    </slot>

    <div class="flex justify-end items-center gap-2 mt-4">
        <button @click="useUiStore().closeModal" class="btn">Close</button>
        <slot name="footer">
        </slot>
    </div>
</template>

<script setup>
import { useUiStore } from '../stores/uiStore';
</script>