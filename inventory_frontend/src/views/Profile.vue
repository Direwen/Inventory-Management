<template>
    <h3 class="text-2xl font-bold mb-4">Account Settings</h3>

    <div class="w-full md:w-10/12 lg:w-8/12 mx-auto rounded p-6 space-y-8">

        <div v-if="useUiStore().loading && !useAuthStore().user" class="flex w-full md:w-10/12 lg:w-8/12 mx-auto  flex-col gap-4">
            <div class="skeleton h-32 w-full"></div>
            <div class="skeleton h-4 w-28"></div>
            <div class="skeleton h-4 w-full"></div>
            <div class="skeleton h-4 w-full"></div>
        </div>

        <section v-else>
            <!-- Personal Information Section -->
            <PersonalInfoForm :email="authStore?.user.email ?? ''" :name="authStore?.user.name ?? ''" />

            <div class="divider"></div>

            <!-- Security Section -->
            <SecurityForm />


            <div class="divider"></div>

            <!-- Connected Accounts Section -->
            <div>
                <h4 class="text-lg font-semibold mb-2">Connected Accounts</h4>
                <label class="input input-bordered flex items-center gap-2">
                    Discord
                    <input type="text" class="grow" placeholder="Connected" disabled />
                </label>
            </div>

            <div class="divider"></div>

            <!-- Deactivate Section -->
            <DeactivateBtn />
        </section>

    </div>
</template>

<script setup>
import PersonalInfoForm from '../components/forms/PersonalInfoForm.vue';
import SecurityForm from '../components/forms/SecurityForm.vue';
import DeactivateBtn from '../components/widgets/DeactivateBtn.vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';

const authStore = useAuthStore();
</script>
