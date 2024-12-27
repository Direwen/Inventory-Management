import { defineStore } from 'pinia'
import { useAuthStore } from './authStore'
import { useUiStore } from './uiStore'
import axiosInstance from '../axios/config';

export const useAppStore = defineStore('App', {
  state: () => ({
    noti: null,
    receivedInvitaions: null,
    inventories: null,
    activeInventory: null,
    collabs: null,
    paginatedInvitations: {
      invitations: null,
      previousUrl: null,
      nextUrl: null,
      currentPage: null
    },
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

        this.inventories = (res.data.data.length > 0) ? res.data.data : null;

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
        this.paginatedProducts.products = null;
        const res = await axiosInstance.get(url || `/inventories/${this.activeInventory}/products`);

        this.paginatedProducts.products = res.data.data.data;
        this.paginatedProducts.previousUrl = res.data.data.prev_page_url;
        this.paginatedProducts.nextUrl = res.data.data.next_page_url;
        this.paginatedProducts.currentPage = res.data.data.current_page;
      }, "Failed to load products", false);
    },

    async updateUserRole(userId, role) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.put(`/inventories/${this.activeInventory}/collabs`, {
          user_id: userId,
          role: role
        });

        this.collabs = res.data.data.collaborators;

      }, "Failed to Update", false);
    },

    async removeUser(id) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.delete(`/inventories/${this.activeInventory}/collabs/${id}`);

        this.collabs = this.collabs.filter(each => each.id !== id);
        // await this.loadCollabs();

      }, "Failed to Delete", false);
    },

    async sendInvitation(email, expiryDate = null) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.post(`/inventories/${this.activeInventory}/invitations`, {
          invitee_email: email,
          expires_at: expiryDate
        });

      }, "Failed to Delete", false);
    },

    async loadInvitations(url = null) {
      return await useUiStore().handleAsync(async () => {
        this.paginatedInvitations.invitations = null;
        const res = await axiosInstance.get(url || `/inventories/${this.activeInventory}/invitations`);

        this.paginatedInvitations.invitations = res.data.data.data;
        this.paginatedInvitations.previousUrl = res.data.data.prev_page_url;
        this.paginatedInvitations.nextUrl = res.data.data.next_page_url;
        this.paginatedInvitations.currentPage = res.data.data.current_page;
      }, "Failed to load invitations", false);
    },

    async cancelInvitation(id) {
      return await useUiStore().handleAsync(async () => {
        const res = await axiosInstance.put(`/inventories/${this.activeInventory}/invitations/${id}`, {
          status: "cancelled"
        });

        this.loadInvitations();

      }, "Failed to cancel the invitation", false);
    },

    async loadNotifications() {
      return await useUiStore().handleAsync(async () => {
        const [res, res2] = await Promise.all([
          axiosInstance.get(`/user/invitations`),
          axiosInstance.get(`/user/notifications`)
        ]);

        this.receivedInvitaions = (res.data.data.length != 0) ? res.data.data : null;
        this.noti = (res2.data.data.length != 0 ) ? res2.data.data : null;

      }, "Failed to get the notis", false, false);
    },

    async markNotificationsAsRead() {
      return useUiStore().handleAsync(async () => {
        const res = await axiosInstance.put(`/user/notifications`, {
          notification_ids: this.noti.map(each => each.id)
        });
      }, "Failed to mark noti as read", false, false);
    },

    async handleReceivedInvitation(invitationId, inventoryId, accept = false) {
      return await useUiStore().handleAsync(async () => {
        const res = await axiosInstance.put(`/inventories/${inventoryId}/invitations/${invitationId}`, {
          status: accept ? "accepted" : "declined"
        });

        if (accept) this.loadInventories();
        this.loadNotifications();

      }, "Failed", true, true);
    }

  },
})