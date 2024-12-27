<template>
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="relative btn btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>

            <span v-if="notiCount > 0" class="absolute top-0 -right-1 bg-red-800 text-white px-2 py-1 rounded-full">{{
                notiCount }}</span>
        </div>
        <ul tabindex="0"
            class="dropdown-content menu flex-nowrap overflow-y-scroll hide-scrollbar bg-base-200 rounded-box z-[1] w-[19rem] sm:w-[32rem] p-2 shadow max-h-80">

            <li v-for="each in appStore.receivedInvitaions" :key="each.id">
                <section class="flex flex-col gap-2 justify-center items-start">
                    <p class="text-wrap">
                        <span class="font-semibold">{{ each.inviter.name }}</span> invited you to join <span
                            class="font-semibold">{{ each.inventory.name }}</span>.
                    </p>

                    <span class="text-xs md:text-sm text-gray-500 lowercase mb-2">{{
                        uiStore.formatRelativeDateTime(each.created_at) }}</span>

                    <section class="flex gap-2 justify-center items-center w-full">
                        <button @click="appStore.handleReceivedInvitation(each.id, each.inventory_id, true)"
                            class="btn bg-base-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span class="hidden lg:inline">Accept</span>
                        </button>
                        <button @click="appStore.handleReceivedInvitation(each.id, each.inventory_id, false)"
                            class="btn bg-base-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            <span class="hidden lg:inline">Reject</span>
                        </button>
                    </section>
                </section>
            </li>

            <li v-for="each in appStore.noti" :key="each">
                <section class="flex flex-col gap-2 justify-center items-start">
                    <p class="text-wrap">{{ each.message }}</p>
                    <span class="text-xs md:text-sm text-gray-500 lowercase">{{
                        uiStore.formatRelativeDateTime(each.created_at) }}</span>
                </section>
            </li>

            <li v-if="!appStore.receivedInvitaions && !appStore.noti">
                <p class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    No Notifications
                </p>
            </li>

            <li v-if="appStore.noti" class="flex items-end">
                <button class="btn text-gray-500" @click="appStore.markNotificationsAsRead()">
                    Mark As Read
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAppStore } from '../../stores/appStore';
import { useUiStore } from '../../stores/uiStore';

const appStore = useAppStore();
const uiStore = useUiStore();


const notiCount = computed(() =>
    (appStore.noti ? appStore.noti.length : 0) + (appStore.receivedInvitaions ? appStore.receivedInvitaions.length : 0)
);
</script>