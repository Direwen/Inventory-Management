<template>
  <div>
    <!-- Trigger notification (e.g., on button click) -->
    <button class="btn btn-outline" @click="showSuccessToast">Show Success</button>
    <button class="btn btn-outline" @click="showErrorToast">Show Error</button>
    <button class="btn btn-outline" @click="uiStore.openModal(SignupSuccess, {'email' : 'bot1@gmail.com'})">Show Error</button>

    <div>
      {{ authStore.user ?? "empty" }}
    </div>


  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { useRoute } from 'vue-router';
import Verification from '../components/modals/Verification.vue';
import SignupSuccess from '../components/modals/SignupSuccess.vue';

const route = useRoute();
const authStore = useAuthStore();
const uiStore = useUiStore();

onMounted(() => (route.query.status == 'success' || route.query.status == 'failed') ? uiStore.openModal(Verification, {'status' : route.query.status}) : "" );

const showSuccessToast = () => {
  uiStore.addNotification("success", "Action was successful!");
};

const showErrorToast = () => {
  uiStore.addNotification("error", "An error occurred.");
};
</script>