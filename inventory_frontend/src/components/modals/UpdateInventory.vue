<template>
    <LayoutModalContent>
        <template #header>
            <h3 class="text-xl font-semibold">Update Inventory Details</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <p class="text-sm text-gray-500 mb-4">Update the inventory information below.</p>

            <section class="flex flex-col gap-4">
                <!-- Name Field -->
                <label class="input input-bordered flex items-center gap-2 shadow-md rounded-lg grow">
                    <span class="text-gray-500">Name</span>
                    <input required v-model="inventory.name" type="text" class="grow text-sm focus:outline-none"
                        placeholder="Enter inventory name" />
                </label>

                <!-- Description Field -->
                <textarea v-model="inventory.description" rows="3" class="textarea"
                    placeholder="Optional: Add inventory description"></textarea>

                <!-- Stock Threshold Field -->
                <label class="input input-bordered flex items-center gap-2 shadow-md rounded-lg grow">
                    <span class="text-gray-500">Stock Threshold</span>
                    <input required v-model.number="inventory.stockThreshold" type="number" min="0" max="65535"
                        class="grow text-sm focus:outline-none" placeholder="Enter stock threshold" />
                </label>
            </section>
        </template>

        <template #footer>
            <button :disabled="!hasChanges || isLoading" type="submit" class="btn btn-wide" @click="update">
                <span v-if="!isLoading">Update Inventory</span>
                <span v-else class="loading loading-spinner loading-md"></span>
            </button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useUiStore } from '../../stores/uiStore';
import { useAppStore } from '../../stores/appStore';
import { computed, ref } from 'vue';

const uiStore = useUiStore();
const appStore = useAppStore();
const isLoading = ref(false);
const props = defineProps(["name", "stockThreshold", "description"]);

// Inventory fields
const inventory = ref({
    name: props.name,
    description: props.description,
    stockThreshold: props.stockThreshold
});

// Validation rules
const isNameValid = computed(() => {
    return inventory.value.name.length >= 3 && inventory.value.name.length <= 255;
});

const isDescriptionValid = computed(() => {
    return !inventory.value.description || inventory.value.description.length <= 1000;
});

const isThresholdValid = computed(() => {
    return inventory.value.stockThreshold >= 0 && inventory.value.stockThreshold <= 65535;
});

// Check if any changes have been made
const hasChanges = computed(() => {
    return inventory.value.name !== props.name ||
        inventory.value.description !== props.description ||
        inventory.value.stockThreshold !== props.stockThreshold;
});

// Method to update inventory
const update = async () => {
    if (!isNameValid.value) {
        uiStore.addNotification("error", "Name must be between 3 and 255 characters.");
        return;
    }
    if (!isDescriptionValid.value) {
        uiStore.addNotification("error", "Description cannot exceed 1000 characters.");
        return;
    }
    if (!isThresholdValid.value) {
        uiStore.addNotification("error", "Stock threshold must be between 0 and 65535.");
        return;
    }

    isLoading.value = true;
    try {
        await appStore.updateInventory(appStore.activeInventory.id, inventory.value.name, inventory.value.description, inventory.value.stockThreshold);
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
        uiStore.closeModal();
    }
}
</script>
