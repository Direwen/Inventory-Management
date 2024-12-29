<template>
    <LayoutModalContent>
        <template #header>
            <h3>{{ isOutbound ? 'Inventory Adjustment' : 'Inbound Adjustment' }}</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <div class="mb-4">
                <span class="block text-sm font-semibold">Product Details:</span>
                <div class="flex items-center justify-between mt-1">
                    <span class="font-medium">SKU:</span>
                    <span>{{ sku }}</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="font-medium">Name:</span>
                    <span>{{ name }}</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="font-medium">Current Quantity:</span>
                    <span>{{ currentQty }}</span>
                </div>
            </div>

            <!-- Quantity Input -->
            <label class="input input-bordered flex items-center gap-2 mb-4">
                <span class="font-semibold">Adjust by:</span>
                <input v-model="qty" type="number" name="adjust_qty" class="grow" :min="isOutbound ? -currentQty : 1"
                    placeholder="Enter quantity" />
            </label>

            <div class="mb-4">
                <div class="flex items-center justify-between mt-1">
                    <span class="font-medium text-gray-500">New Quantity:</span>
                    <span :class="{ 'text-red-600': isOutbound && newQuantity < 0, 'text-gray-500': newQuantity >= 0 }"
                        class="font-semibold">
                        {{ newQuantity }}
                    </span>
                </div>
                <p v-if="isOutbound && newQuantity < 0" class="text-red-500 text-sm mt-2">
                    ⚠️ Outbound quantity cannot exceed current inventory.
                </p>
            </div>

            <p class="text-sm text-gray-500">
                Please ensure the quantity is correct. <strong>Once adjusted, this action cannot be undone.</strong>
                The changes will immediately reflect in the inventory records.
            </p>
        </template>

        <template #footer>
            <button class="btn" @click="adjust" :disabled="isOutbound && newQuantity < 0"
                aria-label="Confirm adjustment">
                Adjust
            </button>
        </template>
    </LayoutModalContent>
</template>

<script setup>
import { ref, computed } from 'vue';
import LayoutModalContent from '../../layouts/LayoutModalContent.vue';
import { useUiStore } from '../../stores/uiStore';
import { useAppStore } from '../../stores/appStore';

const props = defineProps({
    name: { type: String, required: true },
    sku: { type: String, required: true },
    currentQty: { type: Number, required: true },
    id: { type: [String, Number], required: true },
    isOutbound: { type: Boolean, default: false }
});

const uiStore = useUiStore();
const appStore = useAppStore();
const qty = ref(0);

// Calculate the new quantity dynamically
const newQuantity = computed(() => {
    return props.currentQty + (props.isOutbound ? -Number(qty.value || 0) : Number(qty.value || 0));
});

const adjust = () => {
    if (qty.value < 1 || (props.isOutbound && newQuantity.value < 0)) {
        uiStore.addNotification("error", "Invalid quantity. Please check your entry.");
        return;
    }

    if (props.isOutbound) {
        appStore.adjustProductOutbound(props.id, qty.value);
    } else {
        appStore.adjustProductInbound(props.id, qty.value);
    }
    uiStore.closeModal();
}
</script>
