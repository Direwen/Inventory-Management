<template>
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center md:items-end gap-4 mb-6">
        <section class="prose">
            <h1>Inventory</h1>
            <p class="text-gray-500">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione, non.
            </p>
        </section>
        <button class="btn btn-neutral btn-wide">Create</button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-2 mb-4">

        <label class="input input-bordered flex items-center gap-2 w-full md:grow">
            <input type="text" class="grow placeholder:text-base-300" placeholder="Search" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <div class="join flex-none">
            <!-- Previous Page -->
            <button v-if="appStore.paginatedProducts.currentPage > 1" class="join-item btn btn-square"
                @click="appStore.loadInventoryProducts(appStore.paginatedProducts.previousUrl)">
                {{ appStore.paginatedProducts.currentPage - 1 }}
            </button>

            <!-- Current Page -->
            <button class="join-item btn btn-square btn-active">
                {{ appStore.paginatedProducts.currentPage }}
            </button>

            <!-- Next Page -->
            <button :disabled="!appStore.paginatedProducts.nextUrl" class="join-item btn btn-square"
                @click="appStore.loadInventoryProducts(appStore.paginatedProducts.nextUrl)">
                {{ appStore.paginatedProducts.currentPage + 1 }}
            </button>
        </div>

    </div>

    <div v-if="!appStore.paginatedProducts.products || uiStore.loading" class="flex w-full flex-col gap-4">
        <div v-for="width in [1 / 4, 1 / 2, 11 / 12, 3 / 4, 2 / 3, 5 / 12, 7 / 12, 9 / 12]" :key="width"
            class="skeleton h-4" :style="{ width: `${width * 100}%` }"></div>
    </div>

    <div v-else class="overflow-x-auto hide-scrollbar">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Added to Inventory At</th>
                    <th>Last Stock Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="each in appStore.paginatedProducts.products">
                    <th>{{ each.sku }}</th>
                    <td>{{ each.name }}</td>
                    <td>{{ each.stock.current_stock }}</td>
                    <td>{{ uiStore.formatDate(each.created_at) }}</td>
                    <td>{{ uiStore.formatDate(each.stock.updated_at) }}</td>
                    <td>
                        <section class="flex gap-2">

                            <div class="lg:tooltip lg:tooltip-left" data-tip="inbound">
                                <button class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 15-6 6m0 0-6-6m6 6V9a6 6 0 0 1 12 0v3" />
                                    </svg>

                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="outbound">
                                <button class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9 9 6-6m0 0 6 6m-6-6v12a6 6 0 0 1-12 0v-3" />
                                    </svg>

                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="edit">
                                <button class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>


                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="delete">
                                <button class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>


                                </button>
                            </div>

                        </section>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAppStore } from '../stores/appStore';
import { useUiStore } from '../stores/uiStore';

const appStore = useAppStore();
const uiStore = useUiStore();

onMounted(async () => {

    await appStore.loadInventoryProducts();

})
</script>