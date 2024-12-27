<template>
    <!-- HEADER -->
    <div class="prose mb-6 w-full">
        <h1>Invitations</h1>
        <p class="text-gray-500">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione, non.
        </p>
    </div>
    <!-- Search Bar and Pagination -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-2 mb-4">


        <label class="input input-bordered flex items-center gap-2 w-full md:grow">
            <input type="text" class="grow placeholder:text-base-300" placeholder="Search By Email" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <div class="join flex-none">
            <!-- Previous Page -->
            <button v-if="appStore.paginatedInvitations.currentPage > 1" class="join-item btn btn-square"
                @click="appStore.loadInvitations(appStore.paginatedInvitations.previousUrl)">
                {{ appStore.paginatedInvitations.currentPage - 1 }}
            </button>

            <!-- Current Page -->
            <button class="join-item btn btn-square btn-active">
                {{ appStore.paginatedInvitations.currentPage }}
            </button>

            <!-- Next Page -->
            <button :disabled="!appStore.paginatedInvitations.nextUrl" class="join-item btn btn-square"
                @click="appStore.loadInvitations(appStore.paginatedInvitations.nextUrl)">
                {{ appStore.paginatedInvitations.currentPage + 1 }}
            </button>
        </div>
    </div>
    <!-- TABLE -->
    <div v-if="!appStore.paginatedInvitations.invitations || uiStore.loading" class="flex w-full flex-col gap-4">
        <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width"
            class="skeleton h-4" :style="{ width: `${width * 100}%` }"></div>
    </div>

    <div v-else class="overflow-x-auto mb-4">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Expiry date</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Last Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="each in appStore.paginatedInvitations.invitations" :key="each">
                    <th>{{ each.invitee_email }}</th>
                    <td>{{ uiStore.formatDate(each.expires_at) }}</td>
                    <td>
                        <div class="tooltip" :data-tip="each.status">
                            <span class="px-2 py-1 w-8 h-3 inline-block rounded-full shadow animate-pulse" :class="{
                                'bg-yellow-400': each.status === 'pending',
                                'bg-green-400': each.status === 'accepted',
                                'bg-red-400': each.status === 'declined',
                                'bg-gray-300': each.status === 'expired',
                                'bg-blue-400': each.status === 'full',
                                'bg-gray-400': each.status === 'cancelled'
                            }"></span>
                        </div>


                    </td>
                    <td>{{ uiStore.formatDate(each.created_at) }}</td>
                    <td>{{ uiStore.formatDate(each.updated_at) }}</td>
                    <td>
                        <div class="lg:tooltip lg:tooltip-left" data-tip="cancel">
                            <button :disabled="each.status !== 'pending'" @click="appStore.cancelInvitation(each.id)" class="btn btn-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
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
import { onMounted } from 'vue';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/appStore';


const uiStore = useUiStore();
const appStore = useAppStore();


onMounted(async () => {

    if (!appStore.paginatedInvitations.invitations) await appStore.loadInvitations();

})
</script>