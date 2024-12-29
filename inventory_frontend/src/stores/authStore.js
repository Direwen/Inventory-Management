import { defineStore } from 'pinia';
import axiosInstance from '../axios/config';
import { useUiStore } from './uiStore';
import { useAppStore } from './appStore';

export const useAuthStore = defineStore('Auth', {
    state: () => ({
        user: null,
        isActive: false
    }),
    actions: {
        async login(email, password) {

            return await useUiStore().handleAsync(async () => {
                const res = await axiosInstance.post("/auth/login", {
                    "email": email,
                    "password": password
                });

                this.saveUserData(res.data.data.user, res.data.data.token);
                useAppStore().loadEssentialData();

            }, "Failed to login");
        },

        async signup(email, password, confirmPassword) {
            const uiStore = useUiStore();

            return await uiStore.handleAsync(async () => {
                await axiosInstance.post("/auth/signup", {
                    "email": email,
                    "password": password,
                    "password_confirmation": confirmPassword
                });

            }, "Failed to signup");
        },

        async resendVerificationLink(email) {
            const uiStore = useUiStore();

            return await uiStore.handleAsync(async () => {
                await axiosInstance.post("/email/resend", {
                    "email": email,
                });

            }, "Failed to resend the verification link");
        },

        async loadUser() {

            if (localStorage.getItem('token') && !this.isActive) {
                const uiStore = useUiStore();
    
                return await uiStore.handleAsync(async () => {
                    const res = await axiosInstance.get("/user");
    
                    this.saveUserData(res.data.data);
    
                }, "failed to load user");
            }
        },

        async updateName(name) {
            const uiStore = useUiStore();

            return await uiStore.handleAsync(async () => {
                await axiosInstance.put("/user/update", {
                    "name": name
                });
                this.user.name = name;
            }, "Failed to update the name");
        },
        
        async updatePsw(currentPsw, newPsw, newPswConfirmation) {
            const uiStore = useUiStore();

            return await uiStore.handleAsync(async () => {
                await axiosInstance.put("/user/update-password", {
                    "old_password": currentPsw,
                    "password": newPsw,
                    "password_confirmation": newPswConfirmation
                });
            }, "Failed to update the password");
        },

        async logout() {
            const uiStore = useUiStore();

            return await uiStore.handleAsync(async () => {
                await axiosInstance.post("/auth/logout");
                this.removeUserData();
                useAppStore().resetState();
            }, "Failed to logout");
        },

        saveUserData(user, token = null) {
            this.user = user;
            if (token) localStorage.setItem('token', token);
            this.isActive = true;
        },

        removeUserData() {
            this.user = null;
            localStorage.removeItem('token');
            this.isActive = false;
        }
    }
});
