<template>

    <LayoutModalContent>

        <template #header>
            <h3>Edit Product {{ sku }}</h3>
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
            </section>

        </template>

        <template #footer>
            <button :disabled="isLoading || !isModified" class="btn" @click="update" aria-label="Confirm update of entity">
                <span v-if="!isLoading">Update</span>
                <span v-else class="loading loading-spinner loading-md"></span>
            </button>
        </template>

    </LayoutModalContent>

</template>

<script setup>
import { ref, computed } from "vue";
import LayoutModalContent from "../../layouts/LayoutModalContent.vue"
import { useAppStore } from "../../stores/appStore";
import { useUiStore } from "../../stores/uiStore";

const props = defineProps(["sku", "name", "id"])
const appStore = useAppStore();
const uiStore = useUiStore();
const isLoading = ref(false);
const name = ref(props.name);

// Track initial value
const initialName = ref(props.name);

// Computed property to check if modified
const isModified = computed(() => {
    return (name.value != "") && (name.value !== initialName.value);
});

const update = async () => {
    // console.log("updated", name.value)
    if (!isModified.value) return; // Prevent unnecessary calls
    isLoading.value = true;
    try {
        await appStore.updateProduct(props.id, name.value);
        uiStore.closeModal();
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
} 
</script>
