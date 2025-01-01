<template>
    <div>
        <h4 class="text-lg font-semibold mb-2">{{ $t("profile.security") }}</h4>

        <form @submit.prevent="submit">
            <!-- Current Password -->
            <label class="input input-bordered flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                <input v-model="currentPassword" type="password" class="grow placeholder:text-base-content/40"
                    placeholder="Current Password" required minlength="8" maxlength="12" />
            </label>

            <!-- New Password -->
            <label class="input input-bordered flex items-center gap-2 mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                <input v-model="newPassword" type="password" class="grow placeholder:text-base-content/40"
                    placeholder="New Password" required minlength="8" maxlength="12"
                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,12}$"
                    title="Password must be 8-12 characters long and contain at least one letter and one number" />
            </label>

            <!-- Confirm New Password -->
            <label class="input input-bordered flex items-center gap-2 mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                <input v-model="confirmNewPassword" type="password" class="grow placeholder:text-base-content/40"
                    placeholder="Confirm New Password" required minlength="8" maxlength="12"
                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,12}$"
                    title="Password must match the new password and contain at least one letter and one number" />
            </label>

            <!-- Static validation rules message -->
            <p class="text-xs lg:text-sm text-secondary-content mt-1">
                * Password must be between 8 to 12 characters.
            </p>

            <section class="flex justify-end">
                <button type="submit" class="btn mt-4" :disabled="!canSave">{{ $t("buttons.save_changes") }}</button>
            </section>
        </form>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useAuthStore } from '../../stores/authStore';

const currentPassword = ref('');
const newPassword = ref('');
const confirmNewPassword = ref('');
const canSave = ref(false);

// Watch for changes in all three password fields
watch([newPassword, confirmNewPassword], () => {
    validateForm();
});

// Function to validate the form
const validateForm = () => {
    const passwordLengthValid = newPassword.value.length >= 8 && newPassword.value.length <= 12;
    const passwordsMatch = newPassword.value === confirmNewPassword.value;
    const currentPasswordValid = currentPassword.value.trim() !== '';
    canSave.value = passwordLengthValid && passwordsMatch && currentPasswordValid;
};

const submit = async () => {
    
    try {
        await useAuthStore().updatePsw(currentPassword.value, newPassword.value, confirmNewPassword.value);
        currentPassword.value = ""
        newPassword.value = ""
        confirmNewPassword.value = ""
    } catch (error) {
    }
}
</script>