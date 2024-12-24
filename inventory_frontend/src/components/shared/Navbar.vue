<template>

    <div class="navbar">
        <div class="flex-none">
            <slot></slot>
        </div>

        <div class="flex-1 lg:hidden">
            <a class="btn btn-ghost text-xl">Inventory</a>
        </div>

        <div class="flex-none lg:flex-1 lg:flex lg:justify-end gap-2">

            <ThemeToggle />

            <router-link v-if="!authStore.isActive" to="/auth" class="btn ml-2 lg:px-6">Log In</router-link>
            <div v-else class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn m-1">{{ authStore.user.email }}</div>
                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><router-link to="/profile">Profile</router-link></li>
                    <li><a>Deactivate</a></li>
                </ul>
            </div>
        </div>
    </div>

</template>

<script setup>
import ThemeToggle from './ThemeToggle.vue';
import { useAuthStore } from '../../stores/authStore';

const authStore = useAuthStore();
</script>