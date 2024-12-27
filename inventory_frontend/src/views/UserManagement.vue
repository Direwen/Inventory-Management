<template>

    <section class="flex flex-col md:flex-row justify-between items-center md:items-end gap-4 mb-6">

        <section class="prose">
            <h1>User Management</h1>
            <p class="text-gray-500">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione, non.
            </p>
        </section>

        <div :class="{'lg:tooltip lg:tooltip-top': appStore.collabs?.length >= 5 }"
            :data-tip="appStore.collabs?.length >= 5 ? 'Max users reached' : ''">
            <button @click="uiStore.openModal(Invitation)" class="btn btn-neutral btn-wide" :disabled="appStore.collabs?.length >= 5">
                {{ appStore.collabs ? `Invite (${appStore.collabs.length}/5)` : 'Invite' }}
            </button>
        </div>
    </section>

    <div v-if="!appStore.collabs || uiStore.loading" class="flex w-full flex-col gap-4">
        <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width"
            class="skeleton h-4" :style="{ width: `${width * 100}%` }"></div>
    </div>

    <div v-else class="overflow-x-auto hide-scrollbar">
        <table class="table ">
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
            <tbody ref="animationParent">
                <tr v-for="each in appStore.collabs" :key="each.id" :recordId="each.id" :userId="each.user_id"
                    class="hover min-w-fit" :class="{ 'bg-base-200': each.user.email == authStore.user.email }">
                    <th>{{ each.user_id }}</th>
                    <td>{{ each.user.name ?? 'N/A' }}</td>
                    <td>{{ each.user.email }}</td>
                    <td>
                        <RoleSelector :userId="each.user_id" :currentRole="each.role" :email="each.user.email" />
                    </td>
                    <td class="">{{ uiStore.formatDate(each.created_at) }}</td>
                    <td class="">{{ uiStore.formatDate(each.updated_at) }}</td>
                    <td>
                        <RemoveUserBtn :recordId="each.id" :email="each.user.name ?? each.user.email" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useAppStore } from '../stores/appStore';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import RoleSelector from '../components/shared/RoleSelector.vue';
import RemoveUserBtn from '../components/shared/RemoveUserBtn.vue';
import autoAnimate from '@formkit/auto-animate';
import Invitation from '../components/modals/Invitation.vue';

const appStore = useAppStore();
const authStore = useAuthStore();
const uiStore = useUiStore();
const animationParent = ref(null);

onMounted(async () => {
    if (!appStore.collabs) {
        await appStore.loadCollabs();
    }
    autoAnimate(animationParent.value);
});
</script>
