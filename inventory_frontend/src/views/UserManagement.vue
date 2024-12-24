<template>
    <div ref="animationParent" class="overflow-x-auto">
        <section class="flex justify-end items-center mb-6">
            <button class="btn btn-neutral btn-wide">Invite</button>
        </section>

        <div v-if="!appStore.collabs || uiStore.loading" class="flex w-full flex-col gap-4">
            <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width" class="skeleton h-4"
                :style="{ width: `${width * 100}%` }"></div>
        </div>

        <table v-else class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined At</th>
                    <th>Last Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="each in appStore.collabs" :key="each.id" :recordId="each.id" :userId="each.user_id" class="hover min-w-fit" :class="{'bg-base-200': each.user.email == authStore.user.email}">
                    <th>{{ each.user_id }}</th>
                    <td>{{ each.user.name ?? 'N/A' }}</td>
                    <td>{{ each.user.email }}</td>
                    <td>
                        <select class="select select-ghost min-w-fit">
                            <option v-for="x in ['admin', 'manager', 'employee']" :key="x"
                                :disabled="x === each.role.toLowerCase()" :selected="each.role.toLowerCase() === x">
                                {{ x }}
                            </option>
                        </select>
                    </td>
                    <!-- Format the date and time with AM/PM -->
                    <td class="">{{ uiStore.formatDate(each.created_at) }}</td>
                    <td class="">{{ uiStore.formatDate(each.updated_at) }}</td>
                    <td>
                        <div class="lg:tooltip lg:tooltip-left" data-tip="remove the user">
                            <button class="btn btn-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 hover:text-red-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useAppStore } from '../stores/appStore';
import autoAnimate from '@formkit/auto-animate';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';

const appStore = useAppStore();
const authStore = useAuthStore();
const uiStore = useUiStore();
const animationParent = ref(null);


// Format the date to a more concise format (e.g., YYYY-MM-DD HH:MM AM/PM)


onMounted(() => {
    if (!appStore.collabs) appStore.loadCollabs();
    autoAnimate(animationParent.value);
});
</script>