<template>
    <h3 class="text-2xl font-bold mb-4">{{ $t('profile.account_settings') }}</h3>

    <div class="w-full md:w-10/12 lg:w-8/12 mx-auto rounded p-6 space-y-8">

        <div v-if="useUiStore().loading && !useAuthStore().user"
            class="flex w-full md:w-10/12 lg:w-8/12 mx-auto  flex-col gap-4">
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
                <h4 class="text-lg font-semibold mb-2">{{ $t('profile.connected_accounts') }}</h4>
                <p>{{ authStore.user }}</p>
                <section v-if="authStore.user.google_id" class="relative flex md:justify-center items-center py-3 px-2 rounded bg-base-200">
                    <span class="uppercase font-medium">Google</span>
                    <button class="absolute btn top-0 right-0 hover:text-red-900 px-1 md:px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                            <path fill-rule="evenodd"
                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </section>

                <button v-else class="btn" @click="authStore.linkToGoogleAccount()">Connect</button>
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
