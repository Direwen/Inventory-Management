<template>
  <div class="">

    <section v-if="!authStore.isActive" class="flex flex-col gap-4">
      <h1 class="text-4xl text-center md:text-8xl lg:text-9xl font-extrabold tracking-tighter uppercase">
        {{ $t("welcome") }}
      </h1>
    </section>

    <section v-else>

      <h1 class="text-2xl lg:text-4xl font-semibold tracking-tighter mb-2">{{ $t(`greetings.${currentGreeting}`) }}, {{ authStore.user.name
        ??
        authStore.user.email }}
      </h1>

      <p v-if="authStore.isActive && appStore.inventories?.length > 0"
        class="text-lg lg:text-xl tracking-tighter text-gray-500 mb-4">
        {{ $t('continue_your_work') }}
      </p>

      <button @click="uiStore.openModal(CreateInventory)" class="btn btn-block">+ {{ $t("create_inventory") }}</button>

      <div v-if="!appStore.inventories || uiStore.loading" class="flex w-full flex-col gap-4">
        <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width"
          class="skeleton h-4" :style="{ width: `${width * 100}%` }"></div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th class="text-center">{{ $t("tables.name") }}</th>
              <th class="text-center">{{ $t("tables.last_modified") }}</th>
            </tr>
          </thead>
          <tbody>

            <tr v-for="each in appStore.inventories" :key="each.id" @click="navigateToInventory(each.id)"
              class="cursor-pointer transition-all duration-100 ease-in-out hover:bg-base-200">
              <td class="text-center">
                {{ each.name }}
              </td>
              <td class="text-center">{{ uiStore.formatRelativeDateTime(each.updated_at) }}</td>
            </tr>

            <tr v-if="appStore.inventories && appStore.inventories.length == 0">
              <td colspan="2" class="text-center text-gray-500">No Inventory Found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/AppStore';
import { useRoute, useRouter } from 'vue-router';
import Verification from '../components/modals/Verification.vue';
import CreateInventory from "../components/modals/CreateInventory.vue";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const uiStore = useUiStore();
const appStore = useAppStore();

const currentGreeting = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'morning';
  else if (hour < 18) return 'afternoon';
  else return 'evening';
});

onMounted(() => {
  if (route.query.status == 'success' || route.query.status == 'failed') {
    uiStore.openModal(Verification, { status: route.query.status });
  }
});

// Navigate to inventory
const navigateToInventory = (id) => {
  router.push({ name: 'Inventory', params: { id } });
};
</script>
