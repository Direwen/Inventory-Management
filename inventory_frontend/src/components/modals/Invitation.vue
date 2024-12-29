<template>

    <LayoutModalContent>

        <template #header>
            <h3 class="text-xl font-semibold">Send Inventory Invitation</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <p class="text-sm text-gray-500 mb-4">Enter the email address to send an invitation for inventory access.
            </p>


            <section class="flex flex-col md:flex-row justify-between md:items-center gap-2">
                <label class="input input-bordered flex items-center gap-2 shadow-md rounded-lg grow ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-5 w-5">
                        <path
                            d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                        <path
                            d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
                    </svg>
                    <input required v-model="email" type="email" class="grow text-sm focus:outline-none"
                        placeholder="Enter email address" />
                </label>

                <input v-model="expiryDate" type="date" class="input input-bordered" required />
            </section>

        </template>

        <template #footer>

            <button :disabled="isLoading" type="submit" class="btn btn-wide" @click="send">
                <span v-if="!isLoading">Send Invitation </span>
                <span v-else class="loading loading-spinner loading-md"></span>
            </button>
        </template>

    </LayoutModalContent>

</template>

<script setup>
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useUiStore } from '../../stores/uiStore';
import { useAppStore } from '../../stores/appStore';
import { computed, ref } from 'vue';

const uiStore = useUiStore();
const appStore = useAppStore();
const isLoading = ref(false);
const email = ref('');
const expiryDate = ref('');

// Email validation pattern (basic regex)
const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

// Computed property for checking if the email is valid
const isEmailValid = computed(() => {
    return emailPattern.test(email.value);
});

// Method to send the invitation
const send = async () => {
    if (!isEmailValid.value) {
        uiStore.addNotification("error", "Invalid Email");
        return;
    }

    isLoading.value = true;
    try {
        await appStore.sendInvitation(email.value, expiryDate.value);
        email.value = ""
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
}
</script>
