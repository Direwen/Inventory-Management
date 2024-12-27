<template>
    <LayoutModalContent>
        
        <template #header>
            <h3>Deactivate Your Account</h3>
            <div class="divider"></div>
        </template>
        
        <template #default>
            <p class="text-red-600">
                Are you sure you want to deactivate your account? This action will disable your access to all services, but your account data will be preserved. You can always recover your account by reaching out to our support team.
            </p>
            <p class="text-sm text-gray-500">
                Please note: If you decide to reactivate your account in the future, we will assist you with the process.
            </p>
        </template>

        <template #footer>
            <button class="btn">Confirm Deactivation</button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import LayoutModalContent from "../../layouts/LayoutModalContent.vue"
</script>
