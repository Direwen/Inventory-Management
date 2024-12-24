<template>

    <LayoutModalContent>

        <template #default>
            <div class="flex flex-col gap-2 prose-sm md:prose-md items-center">

                <svg v-if="status === 'success'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-24 bg-green-900 text-primary rounded-full p-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>

                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 bg-red-900 text-primary rounded-full p-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>

                <h3 class="m-0">Email Verification</h3>

                <p v-if="status === 'success'" class="text-center">
                    Your email has been successfully verified. Please log in to continue.
                </p>
                <p v-else class="text-center">
                    Verification failed due to an invalid or expired link. Please try again.
                </p>
            </div>
        </template>
    </LayoutModalContent>


</template>

<script setup>
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';

const route = useRoute();
const status = ref(route.query.status || 'failed');
const props = defineProps(["status"]);
</script>
