<template>

    <LayoutModalContent>

        <template #header>
            <h3>Add New Product</h3>
            <div class="divider"></div>
        </template>

        <template #default>
            <section class="flex flex-col gap-2">
                <!-- Name (required, string, min 2, max 255) -->
                <label class="input input-bordered flex items-center gap-2">
                    Name
                    <input v-model="name" type="text" name="name" class="grow" required minlength="2" maxlength="255"
                        placeholder="Box" />
                </label>

                <!-- Prefix (optional, alphabetic, min 1, max 3) -->
                <label class="input input-bordered flex items-center gap-2">
                    Prefix
                    <input v-model="prefix"  type="text" name="prefix" class="grow" pattern="[A-Za-z]{1,3}" maxlength="3"
                        placeholder="SKU" />
                </label>

                <!-- Initial Quantity (optional, integer, min 0) -->
                <label class="input input-bordered flex items-center gap-2">
                    Initial Quantity
                    <input v-model="initialQty" type="number" name="initial_qty" class="grow" min="0" placeholder="0" />
                </label>
            </section>

        </template>

        <template #footer>
            <button :disabled="isLoading" class="btn" @click="add" aria-label="Confirm deletion of entity">
                <span v-if="!isLoading">Add</span>
                <span v-else class="loading loading-spinner loading-md"></span>
            </button>
        </template>

    </LayoutModalContent>

</template>

<script setup>
import { ref } from "vue";
import LayoutModalContent from "../../layouts/LayoutModalContent.vue"
import { useAppStore } from "../../stores/appStore";

const appStore = useAppStore();
const isLoading = ref(false);
const name = ref("");
const prefix = ref("");
const initialQty = ref("");

const add = async () => {
    isLoading.value = true;
    try {
        await appStore.addProduct(name.value, prefix.value, initialQty.value);
        name.value = "";
        prefix.value = "";
        initialQty.value = ""
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
} 
</script>