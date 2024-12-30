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
    paginatedLogs: {
      logs: null,
      previousUrl: null,
      nextUrl: null,
      currentPage: null
    }
  }),
  getters: {
  },
  actions: {

    async loadEssentialData() {
      this.loadInventories();
      this.loadNotifications();
    },

    async loadInventories() {

      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.get("/inventories");

        this.inventories = (res.data.data.length > 0) ? res.data.data : null;

      }, "Failed to load the inventories");

    },

    async createInventory(name, description, stockThreshold) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.post(`/inventories`, {
          name: name,
          description: description,
          stock_threshold: stockThreshold
        });

        this.inventories.push(res.data.data);

      }, "Failed to create the inventory");
    },

    async updateInventory(id, name, description, stockThreshold) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.put(`/inventories/${id}`, {
          name: name,
          description: description,
          stock_threshold: stockThreshold
        });

        // Update inventories list
        const index = this.inventories.findIndex(inv => inv.id === id);
        if (index !== -1) {
          this.inventories[index]["name"] = name;
          this.inventories[index]["description"] = description;
          this.inventories[index]["stock_threshold"] = stockThreshold;
          this.activeInventory = this.inventories[index];
        }

      }, "Failed to update the inventory", false);
    },

    async removeInventory(id, router) {

      return await useUiStore().handleAsync(async () => {

        await axiosInstance.delete(`/inventories/${id}`);

        router.push('/');

        this.activeInventory = null;
        this.inventories = this.inventories.filter(inv => inv.id != id);


      }, "Failed to delete the inventory");

    },

    async loadCollabs() {
      return await useUiStore().handleAsync(async () => {
        const res = await axiosInstance.get(`/inventories/${this.activeInventory.id}/collabs`);

        this.collabs = res.data.data;

      }, "Failed to load the collabs", false)
    },

    async loadInventoryProducts(url = null, filterParams = "") {
      return await useUiStore().handleAsync(async () => {
        this.paginatedProducts.products = null;
        const res = await axiosInstance.get(url || `/inventories/${this.activeInventory.id}/products${filterParams}`);

        this.paginatedProducts.products = res.data.data.data;
        this.paginatedProducts.previousUrl = res.data.data.prev_page_url;
        this.paginatedProducts.nextUrl = res.data.data.next_page_url;
        this.paginatedProducts.currentPage = res.data.data.current_page;
      }, "Failed to load products", false);
    },

    async updateUserRole(userId, role) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.put(`/inventories/${this.activeInventory.id}/collabs`, {
          user_id: userId,
          role: role
        });

        this.collabs = res.data.data.collaborators;

      }, "Failed to Update", false);
    },

    async removeUser(id) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.delete(`/inventories/${this.activeInventory.id}/collabs/${id}`);

        this.collabs = this.collabs.filter(each => each.id !== id);
        // await this.loadCollabs();

      }, "Failed to Delete", false);
    },

    async sendInvitation(email, expiryDate = null) {
      return await useUiStore().handleAsync(async () => {

        const res = await axiosInstance.post(`/inventories/${this.activeInventory.id}/invitations`, {
          invitee_email: email,
          expires_at: expiryDate
        });

      }, "Failed to Delete", false);
    },

    async loadInvitations(url = null) {
      return await useUiStore().handleAsync(async () => {
        this.paginatedInvitations.invitations = null;
        const res = await axiosInstance.get(url || `/inventories/${this.activeInventory.id}/invitations`);

        this.paginatedInvitations.invitations = res.data.data.data;
        this.paginatedInvitations.previousUrl = res.data.data.prev_page_url;
        this.paginatedInvitations.nextUrl = res.data.data.next_page_url;
        this.paginatedInvitations.currentPage = res.data.data.current_page;
      }, "Failed to load invitations", false);
    },

    async cancelInvitation(id) {
      return await useUiStore().handleAsync(async () => {
        const res = await axiosInstance.put(`/inventories/${this.activeInventory.id}/invitations/${id}`, {
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
        this.noti = (res2.data.data.length != 0) ? res2.data.data : null;

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
        await axiosInstance.put(`/inventories/${inventoryId}/invitations/${invitationId}`, {
          status: accept ? "accepted" : "declined"
        });

        if (accept) this.loadInventories();
        this.loadNotifications();

      }, "Failed", true, true);
    },

    async loadLogs(url = null) {
      return await useUiStore().handleAsync(async () => {
        this.paginatedLogs.logs = null;
        const res = await axiosInstance.get(url || `/inventories/${this.activeInventory.id}/logs`);

        this.paginatedLogs.logs = res.data.data.data;
        this.paginatedLogs.previousUrl = res.data.data.prev_page_url;
        this.paginatedLogs.nextUrl = res.data.data.next_page_url;
        this.paginatedLogs.currentPage = res.data.data.current_page;
      }, "Failed to load logs", false, false);
    },

    async addProduct(name, prefix = null, initialQty = null) {
      return await useUiStore().handleAsync(async () => {

        await axiosInstance.post(`/inventories/${this.activeInventory.id}/products`, {
          name: name,
          prefix: prefix,
          initial_qty: initialQty
        });

        await this.loadInventoryProducts();

      }, "Failed to Add the product", false);
    },

    async updateProduct(id, name) {
      return await useUiStore().handleAsync(async () => {

        await axiosInstance.put(`/inventories/${this.activeInventory.id}/products/${id}`, {
          name: name,
        });

        await this.loadInventoryProducts();

      }, "Failed to update the product", false);
    },

    async removeProduct(id) {
      return await useUiStore().handleAsync(async () => {

        await axiosInstance.delete(`/inventories/${this.activeInventory.id}/products/${id}`);

        this.paginatedProducts.products = this.paginatedProducts.products.filter(each => each.id !== id);

      }, "Failed to Delete", false);
    },

    async adjustProductInbound(id, qty) {
      return await useUiStore().handleAsync(async () => {

        await axiosInstance.post(`/inventories/${this.activeInventory.id}/products/${id}/inbound`, {
          quantity: qty
        });
        await this.loadInventoryProducts();

      }, "Failed to Delete", false);
    },

    async adjustProductOutbound(id, qty) {
      return await useUiStore().handleAsync(async () => {

        await axiosInstance.post(`/inventories/${this.activeInventory.id}/products/${id}/outbound`, {
          quantity: qty
        });
        await this.loadInventoryProducts();

      }, "Failed to Delete", false);
    },

    resetActiveInventoryData() {
      this.activeInventory = null;
      this.collabs = null;
      this.paginatedInvitations = {
        invitations: null,
        previousUrl: null,
        nextUrl: null,
        currentPage: null
      };
      this.paginatedProducts = {
        products: null,
        previousUrl: null,
        nextUrl: null,
        currentPage: null
      };
      this.paginatedLogs = {
        logs: null,
        previousUrl: null,
        nextUrl: null,
        currentPage: null
      };
    },

    resetState() {
      this.$reset(); // Resets the entire state to initial values
    },

  },
})