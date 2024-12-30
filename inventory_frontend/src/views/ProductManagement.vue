<template>
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center md:items-end gap-4 mb-6">
        <section class="prose">
            <h1>Inventory</h1>
            <p class="text-gray-500">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione, non.
            </p>
        </section>
        <button @click="uiStore.openModal(CreateProduct)" class="btn btn-neutral btn-wide">Create</button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-2 mb-4">

        <section class="flex gap-2 items-center w-full md:w-1/2">
            <!-- Search Input with Button -->
            <div class="relative flex items-center w-full md:grow">
                <input @keyup.enter="search" v-model="searchTerm" type="text" placeholder="Search"
                    class="w-full rounded-lg pl-4 pr-12 py-3 text-sm shadow bg-base-200 focus:outline-none focus:ring-1 focus:ring-base-content focus:border-base-content" />
                <button @click="search"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-base-content">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Filter Button -->

            <div class="dropdown dropdown-bottom dropdown-end">
                <div tabindex="0" role="button"
                    class="m-1 flex items-center gap-1 p-3 px-6 rounded-lg text-sm font-medium bg-base-200 hover:opacity-60"
                    :class="{ 'bg-base-300': filterParams }">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span v-if="!filterParams" class="hidden md:inline">Filter</span>
                    <span v-else class="hidden md:inline">Active</span>
                </div>
                <ul tabindex="0"
                    class="dropdown-content menu bg-base-200 rounded-box z-[1] w-72 md:w-[24rem] lg:w-[30rem] p-4 shadow space-y-4">

                    <!-- Filter Section Title -->
                    <div class="flex flex-col mb-4">
                        <h3 class="text-lg font-semibold">Filter by Updated Date</h3>
                        <p class="text-sm text-gray-500">Select a date range to filter by the last updated date.</p>
                    </div>

                    <!-- Start Date Input -->
                    <div class="flex flex-col">
                        <label class="text-sm mb-1">Start Date</label>
                        <input v-model="filterStartDate" type="date"
                            class="w-full rounded-lg pl-4 pr-4 py-3 text-sm shadow bg-base-200 focus:outline-none focus:ring-1 focus:ring-base-content focus:border-base-content" />
                    </div>

                    <!-- End Date Input -->
                    <div class="flex flex-col">
                        <label class="text-sm mb-1">End Date</label>
                        <input v-model="filterEndDate" type="date"
                            class="w-full rounded-lg pl-4 pr-4 py-3 text-sm shadow bg-base-200 focus:outline-none focus:ring-1 focus:ring-base-content focus:border-base-content" />
                    </div>

                    <!-- Button Group -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button @click="clearFilter" class="btn glass">Clear</button>
                        <button @click="applyFilter" class="btn glass">Apply</button>
                    </div>

                </ul>
            </div>

        </section>


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
            <tbody ref="animationParent">
                <tr v-for="each in appStore.paginatedProducts.products">
                    <th>{{ each.sku }}</th>
                    <td>{{ each.name }}</td>
                    <td>{{ each.stock.current_stock }}</td>
                    <td>{{ uiStore.formatDate(each.created_at) }}</td>
                    <td>{{ uiStore.formatDate(each.stock.updated_at) }}</td>
                    <td>
                        <section class="flex gap-2">

                            <div class="lg:tooltip lg:tooltip-left" data-tip="inbound">
                                <button @click="uiStore.openModal(StockAdjustment, {
                                    'name': each.name,
                                    'sku': each.sku,
                                    'currentQty': each.stock.current_stock,
                                    'id': each.id,
                                    'isOutbound': false
                                })" class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 15-6 6m0 0-6-6m6 6V9a6 6 0 0 1 12 0v3" />
                                    </svg>

                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="outbound">
                                <button @click="uiStore.openModal(StockAdjustment, {
                                    'name': each.name,
                                    'sku': each.sku,
                                    'currentQty': each.stock.current_stock,
                                    'id': each.id,
                                    'isOutbound': true
                                })" class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9 9 6-6m0 0 6 6m-6-6v12a6 6 0 0 1-12 0v-3" />
                                    </svg>

                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="edit">
                                <button
                                    @click="uiStore.openModal(UpdateProduct, { 'id': each.id, 'sku': each.sku, 'name': each.name })"
                                    class="btn btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>


                                </button>
                            </div>
                            <div class="lg:tooltip lg:tooltip-left" data-tip="delete">
                                <button @click="remove(each.name, each.id)" class="btn btn-circle">
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

                <tr v-if="appStore.paginatedProducts.products.length == 0">
                    <td colspan="6" class="text-center">No Product Found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useAppStore } from '../stores/appStore';
import { useUiStore } from '../stores/uiStore';
import CreateProduct from '../components/modals/CreateProduct.vue';
import UpdateProduct from '../components/modals/UpdateProduct.vue';
import Delete from '../components/modals/Delete.vue';
import autoAnimate from '@formkit/auto-animate';
import StockAdjustment from '../components/modals/StockAdjustment.vue';


const appStore = useAppStore();
const uiStore = useUiStore();
const filterParams = ref(null);
const filterStartDate = ref("");
const filterEndDate = ref("");
const searchTerm = ref("");
const animationParent = ref(null);


const applyFilter = () => {
    if (filterStartDate.value || filterEndDate.value) {
        filterParams.value = {
            startDate: filterStartDate.value,
            endDate: filterEndDate.value
        };

        search();
    } else {
        uiStore.addNotification("error", "enter one input value at least");
    }
}

const clearFilter = () => {
    filterStartDate.value = ""
    filterEndDate.value = ""
    filterParams.value = null;
    search();
};

const combinedParams = () => {
    let params = "";

    if (searchTerm.value) {
        params += `search=${searchTerm.value}`;
    }

    if (filterParams.value) {
        if (params) {
            params += "&";
        }
        params += `start=${filterParams.value.startDate}`;
        if (filterParams.value.endDate) {
            params += `&end=${filterParams.value.endDate}`;
        }
    }

    return params ? "?" + params : "";
};

const search = async () => {
    const temp = appStore.paginatedProducts.products;
    try {
        await appStore.loadInventoryProducts(null, combinedParams())
    } catch (error) {
        appStore.paginatedProducts.products = temp;
    }
}

const remove = async (name, id) => {
    uiStore.openModal(Delete, {
        entityToDelete: id,
        entityType: "product",
        entityName: name,
        onConfirm: appStore.removeProduct
    })
}

onMounted(async () => {

    await appStore.loadInventoryProducts();
    autoAnimate(animationParent.value);

})
</script>