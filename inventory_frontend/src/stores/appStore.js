import { defineStore } from 'pinia'
import { useAuthStore } from './authStore'
import { useUiStore } from './uiStore'
import axiosInstance from '../axios/config';

export const useAppStore = defineStore('App', {
    state: () => ({ 
        inventories: null,
        activeInventory: null,
        inventory: null
    }),
    getters: {
    },
    actions: {
      
      async loadInventories() {
        
        return await useUiStore().handleAsync(async () => {

          const res = await axiosInstance.get("/inventories");

          this.inventories = res.data.data;
          console.log(this.inventories)

        }, "Failed to load the inventories");

      },

      async loadActiveInventory() {
        return await useUiStore().handleAsync(async () => {
          const res = await axiosInstance.get("/inventories/" + this.activeInventory);

          console.log(res.data);

        }, "Failed to load the inventory")
      }

    },
})