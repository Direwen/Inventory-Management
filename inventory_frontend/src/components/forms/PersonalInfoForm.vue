<template>
    <div>
        <h4 class="text-lg font-semibold mb-2">Personal Information</h4>
        <label class="input input-bordered flex items-center gap-2">
            Email
            <input type="text" class="grow" :placeholder="email" disabled />
        </label>

        <form @submit.prevent="saveChanges">
            <label class="input input-bordered flex items-center gap-2 pr-0 mt-4">
                Name
                <input
                    required
                    type="text"
                    class="grow"
                    v-model="editedName"
                    @input="checkChanges"
                    :minlength="5"
                    :maxlength="10"
                />
            </label>

            <!-- Static validation rules message -->
            <p class="text-xs lg:text-sm text-secondary-content mt-1">
                * Name must be 5-10 characters long and contain only ASCII letters.
            </p>

            <section class="flex justify-end">
                <button type="submit" class="btn mt-4" :disabled="!canSave">Save Changes</button>
            </section>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/authStore';

const props = defineProps(["email", "name"]);
const editedName = ref(props.name);
const canSave = ref(false);

const checkChanges = () => {
    // Check if the name is edited and its length is valid (between 5 and 10)
    canSave.value = editedName.value !== props.name && editedName.value.trim() !== "" && editedName.value.length >= 5 && editedName.value.length <= 10;
};

const saveChanges = async () => {
    console.log("Saving changes:", editedName.value);
    try {
        await useAuthStore().updateName(editedName.value);
        canSave.value = false;
    } catch (error) {
        console.log(error);
    }
};
</script>
