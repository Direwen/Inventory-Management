<template>
    <!-- HEADER -->
    <div class="prose mb-6 w-full">
        <h1>Audit Logs</h1>
        <p class="text-gray-500">
            View recent actions and role assignments.
        </p>
    </div>
    <!-- Search Bar and Pagination -->
    <div class="flex justify-center items-center gap-2 mb-4">
        <div class="join flex-none">
            <!-- Previous Page -->
            <button v-if="appStore.paginatedLogs.currentPage > 1" class="join-item btn btn-square"
                @click="appStore.loadLogs(appStore.paginatedLogs.previousUrl)">
                {{ appStore.paginatedLogs.currentPage - 1 }}
            </button>

            <!-- Current Page -->
            <button class="join-item btn btn-square btn-active">
                {{ appStore.paginatedLogs.currentPage }}
            </button>

            <!-- Next Page -->
            <button :disabled="!appStore.paginatedLogs.nextUrl" class="join-item btn btn-square"
                @click="appStore.loadLogs(appStore.paginatedLogs.nextUrl)">
                {{ appStore.paginatedLogs.currentPage + 1 }}
            </button>
        </div>
    </div>
    <!-- TABLE -->
    <div v-if="!appStore.paginatedLogs.logs || uiStore.loading" class="flex w-full flex-col gap-4">
        <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width"
            class="skeleton h-4" :style="{ width: `${width * 100}%` }"></div>
    </div>

    <div v-else class="overflow-x-auto mb-4">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Created At</th>
                    <th>Last Updated At</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="log in appStore.paginatedLogs.logs" :key="log.id" class="hover">
                    <th>{{ log.id }}</th>
                    <td>{{ log.user?.name || log.user.email }}</td>
                    <td>{{ log.action }}</td>
                    <td>{{ uiStore.formatDate(log.created_at) }}</td>
                    <td>{{ uiStore.formatDate(log.updated_at) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/appStore';

const uiStore = useUiStore();
const appStore = useAppStore();

onMounted(async () => {
    await appStore.loadLogs();
});
</script>
