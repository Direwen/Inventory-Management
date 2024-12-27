<template>
    <div class="collapse">
        <input type="checkbox" />
        <div class="collapse-title p-0 flex justify-between items-center font-bold">
            <span>Inventories</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

        </div>
        <div class="collapse-content px-1 overflow-hidden">
            <p v-if="!useAuthStore().isActive" class="truncate my-0 text-center">Please log in to continue</p>

            <section v-else-if="useAuthStore().isActive && useAppStore().inventories">
                <section v-for="each in useAppStore().inventories" class="flex flex-col gap-1">
                    <span class="text-xs lg:text-sm font-semibold tracking-tighter">{{ uiStore.formatRelativeDate(each.updated_at) }}</span>
                    <router-link :to="{name: 'Inventory', params: {id: each.id}}" class="truncate no-underline cursor-pointer hover:bg-base-100 px-3 py-1 rounded transition-all ease-in-out duration-300">{{each.name}}</router-link>
                </section>
            </section>
            
            <p v-else class="truncate my-0 text-center">No Inventories</p>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '../../stores/authStore';
import { useAppStore } from '../../stores/appStore';
import { formatDistanceToNow, isToday, isYesterday } from 'date-fns';
import { useUiStore } from '../../stores/uiStore';

const uiStore = useUiStore();
</script>