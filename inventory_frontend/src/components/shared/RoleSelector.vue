<template>
    <div class="dropdown dropdown-bottom">
        <div tabindex="0" role="button" class="btn m-1">{{ currentRole }}</div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li v-for="each in definedRoles.filter(role => role !== currentRole.toLowerCase())" :key="each">
                <a @click="handleRoleClick(each)">
                    {{ each }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Confirmation from '../modals/Confirmation.vue';
import { useUiStore } from '../../stores/uiStore';

const definedRoles = ['admin', 'manager', 'employee'];
const props = defineProps(["currentRole", "email", "userId"]);
const selectedRole = ref(props.currentRole);
const uiStore = useUiStore();

const handleRoleClick = (newRole) => {
    if (newRole !== props.currentRole.toLowerCase()) {
        selectedRole.value = newRole;
        uiStore.openModal(Confirmation, {
            currentRole: props.currentRole,
            newRole: newRole,
            email: props.email,
            userId: props.userId
        });
        console.log(`Role changed to: ${newRole}`);
    }
};
</script>
