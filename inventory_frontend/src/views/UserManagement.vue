<template>

    <section class="prose mb-6 max-w-full">
        <section class="w-full flex justify-between items-center">
            <h1>
                {{ appStore.activeInventory?.name }}
                <div class="tooltip" :data-tip="$t('tooltip.stockThresholdLimit')">
                    <div class="badge badge-warning">{{ appStore.activeInventory?.stock_threshold }}</div>
                </div>
            </h1>
            <section>
                <span @click="uiStore.openModal(UpdateInventory, {
                    'name': appStore.activeInventory?.name,
                    'stockThreshold': appStore.activeInventory?.stock_threshold,
                    'description': appStore.activeInventory?.description
                })" class="btn btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                </span>
                <span @click="deleteInventory" class="btn btn-circle ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>

                </span>
            </section>
        </section>
        <p class="text-gray-500 indent-8 text-justify">
            {{ appStore.activeInventory?.description }}
        </p>
        <p class="text-gray-500 indent-8 text-justify">
            {{ $t('user_management.guide') }}
        </p>
    </section>

    <section class="flex flex-col md:flex-row justify-between items-center md:items-end gap-4 mb-6">

        <section class="prose">
            <h1>{{ $t('headers.userManagement') }}</h1>
        </section>

        <div :class="{ 'lg:tooltip lg:tooltip-top': appStore.collabs?.length >= 5 }"
            :data-tip="appStore.collabs?.length >= 5 ? $t('tooltip.maxUsers') : ''">
            <button @click="uiStore.openModal(Invitation)" class="btn btn-neutral btn-wide"
                :disabled="appStore.collabs?.length >= 5">
                {{ appStore.collabs ? `${$t('buttons.invite')} (${appStore.collabs.length}/5)` : $t('buttons.invite') }}
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
                    <th>{{ $t('tables.user_id') }}</th>
                    <th>{{ $t('tables.name') }}</th>
                    <th>{{ $t('tables.email') }}</th>
                    <th>{{ $t('tables.role') }}</th>
                    <th>{{ $t('tables.joined_at') }}</th>
                    <th>{{ $t('tables.last_updated_at') }}</th>
                    <th>{{ $t('tables.action') }}</th>
                </tr>
            </thead>
            <tbody ref="animationParent">
                <tr v-for="each in appStore.collabs" :key="each.id" :recordId="each.id" :userId="each.user_id"
                    class="hover min-w-fit" :class="{ 'bg-base-200': each.user.email == authStore.user.email }">
                    <th>{{ each.user_id }}</th>
                    <td>{{ each.user?.name || $t('defaults.notAvailable') }}</td>
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
import { useRouter } from 'vue-router';
import RoleSelector from '../components/shared/RoleSelector.vue';
import RemoveUserBtn from '../components/shared/RemoveUserBtn.vue';
import autoAnimate from '@formkit/auto-animate';
import Invitation from '../components/modals/Invitation.vue';
import UpdateInventory from '../components/modals/UpdateInventory.vue';
import Delete from '../components/modals/Delete.vue';

const appStore = useAppStore();
const authStore = useAuthStore();
const uiStore = useUiStore();
const router = useRouter();
const animationParent = ref(null);

const deleteInventory = async () => {
    try {
        uiStore.openModal(Delete, {
            'entityToDelete': appStore.activeInventory?.id,
            'entityType': 'inventory',
            'entityName': appStore.activeInventory?.name,
            'onConfirm': appStore.removeInventory,
            'routerRequired': true
        });
    } catch (err) {
        console.log(err)
    }
}

onMounted(async () => {
    if (!appStore.collabs) {
        await appStore.loadCollabs();
    }
    autoAnimate(animationParent.value);
});
</script>
