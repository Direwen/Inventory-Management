<template>

    <div>
        <h4 class="text-lg font-semibold mb-2">Deactivate Account</h4>
        <button class="btn btn-block btn-outline btn-error" @click="useUiStore().openModal(DeactivationConfirmation)">Deactivate</button>
    </div>
</template>

<script setup>
import { useUiStore } from '../../stores/uiStore';
import DeactivationConfirmation from '../modals/DeactivationConfirmation.vue';
</script>