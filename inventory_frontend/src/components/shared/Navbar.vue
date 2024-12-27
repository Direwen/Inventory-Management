<template>

    <div class="navbar">
        <div class="flex-none">
            <slot></slot>
        </div>

        <div class="flex-1 hidden md:inline-block lg:hidden">
            <a class="btn btn-ghost text-xl">Inventory</a>
        </div>

        <div class="flex-1 md:flex-none lg:flex-1 lg:flex justify-end gap-2">

            <ThemeToggle />

            <router-link v-if="!authStore.isActive" to="/auth" class="btn ml-2 lg:px-6">Log In</router-link>

            <div v-else class="flex items-center">
                

                <!-- profile or auth -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn m-1 hidden md:inline-flex">{{ authStore.user.email }}
                    </div>
                    <div tabindex="0" role="button" class="btn btn-circle m-1 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu bg-base-200 rounded-box z-[1] w-52 p-2 shadow">
                        <li><router-link to="/profile">Profile</router-link></li>
                        <li><a>Deactivate</a></li>
                    </ul>
                </div>

                <!-- Notification -->
                <Notification />
            </div>

        </div>
    </div>

</template>

<script setup>
import ThemeToggle from './ThemeToggle.vue';
import Notification from './Notification.vue';
import { useAuthStore } from '../../stores/authStore';

const authStore = useAuthStore();
</script>