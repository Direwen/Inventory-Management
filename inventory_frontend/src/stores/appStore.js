import { defineStore } from 'pinia'
import { useAuthStore } from './authStore'
import { useUiStore } from './uiStore'
import axiosInstance from '../axios/config';

export const useAppStore = defineStore('App', {
    state: () => ({ 
        inventories: null,
        activeInventory: null,
        collabs: null,
        paginatedProducts: {
          products: null,
          previousUrl: null,
          nextUrl: null,
          currentPage: null
        },
    }),
    getters: {
    },
    actions: {
      
      async loadInventories() {
        
        return await useUiStore().handleAsync(async () => {

          const res = await axiosInstance.get("/inventories");

          this.inventories = res.data.data;

        }, "Failed to load the inventories");

      },

      async loadCollabs() {
        return await useUiStore().handleAsync(async () => {
          const res = await axiosInstance.get(`/inventories/${this.activeInventory}/collabs`);

          this.collabs = res.data.data;

        }, "Failed to load the collabs", false)
      },

      async loadInventoryProducts(url = null) {
        return await useUiStore().handleAsync(async () => {
            const res = await axiosInstance.get(url || `/inventories/${this.activeInventory}/products`);
            
            this.paginatedProducts.products = res.data.data.data;
            this.paginatedProducts.previousUrl = res.data.data.prev_page_url;
            this.paginatedProducts.nextUrl = res.data.data.next_page_url;
            this.paginatedProducts.currentPage = res.data.data.current_page;
        }, "Failed to load products", false);
    }
    

    },
})