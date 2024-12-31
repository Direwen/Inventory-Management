<template>
    <div @click="useUiStore().closeModal"
        class="fixed z-40 bg-primary-content/90 top-0 left-0 w-full h-screen flex justify-center items-center">


        <div @click.stop
            class="w-11/12 md:w-8/12 lg:w-1/2 bg-base-300 prose px-2 py-4 md:px-4 md:py-6 lg:px-6 lg:py-8 rounded overflow-y-scroll max-h-[90vh]">

            <slot name="default">
                <div>
                    <p class="">No content provided for the modal body. This is the fallback content.</p>
                </div>
            </slot>

        </div>

    </div>
</template>

<script setup>
import { useUiStore } from '../stores/uiStore';
</script>