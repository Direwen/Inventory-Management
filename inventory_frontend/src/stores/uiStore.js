import { formatDistanceToNow, isToday, isYesterday } from 'date-fns';
import { defineStore } from 'pinia';
import Toastify from 'toastify-js';

export const useUiStore = defineStore('Ui', {
    state: () => ({
        loading: false,
        error: null,
        modal: false,
        modalComponent: null,
        modalProps: {}
    }),
    getters: {

    },
    actions: {
        async handleAsync(task, errorMessage = "Something went wrong", loading = true, toast = true) {
            this.loading = loading;
            this.error = null;

            try {
                const result = await task();
                if (toast) this.addNotification("success", "Operation successful!");
                return result;
            } catch (error) {
                const message =
                    error.response?.data?.message || // Custom API error format
                    error.response?.data?.error ||   // Some APIs return error key
                    error.message ||                 // Fallback for generic errors
                    errorMessage;                    // Default message if nothing else works

                this.addNotification("error", message);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        addNotification(type, message) {
            const toastConfig = {
                text: message,
                duration: 2000,  // Adjust to your preference
                close: false,
                stopOnFocus: true,
                gravity: 'bottom',
                position: 'right',
                style: this.getToastStyle(type),
                onClick: function () {
                }
            };

            // Trigger Toastify notification
            Toastify(toastConfig).showToast();
        },

        getToastStyle(type) {
            switch (type) {
                case 'success':
                    return {
                        background: "linear-gradient(to right, #00b09b, #174701)",
                        boxShadow: "0 4px 6px rgba(0, 128, 0, 0.3)"  // Green shadow for success
                    };
                case 'error':
                    return {
                        borderRadius: ".2rem",
                        background: "linear-gradient(to right, #FF5F6D, #591803)",
                        boxShadow: "0 4px 6px rgba(255, 87, 34, 0.3)"  // Red shadow for error
                    };
                case 'info':
                    return {
                        background: "linear-gradient(to right, #2c2a24, #4f4f4f)", // Gradient for info
                        color: "#ffffff",
                        boxShadow: "0 4px 6px rgba(66, 66, 66, 0.3)"  // Dark gray shadow for info
                    };
                default:
                    return {
                        background: "linear-gradient(to right, #2c2a24, #4f4f4f)", // Gradient for default
                        color: "#ffffff",
                        boxShadow: "0 4px 6px rgba(100, 100, 255, 0.3)"  // Bluish gray shadow for default
                    };
            }
        },

        openModal(component, props = {}) {
            this.modal = true;
            this.modalComponent = component;
            this.modalProps = props;
        },

        closeModal() {
            this.modal = false;
            this.modalComponent = null;
            this.modalProps = {};
        },

        formatDate(date) {
            return new Date(date).toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            });
        },

        formatRelativeDate(timestamp) {
            const date = new Date(timestamp);

            if (isToday(date)) {
                return 'Today';
            } else if (isYesterday(date)) {
                return 'Yesterday';
            } else {
                return formatDistanceToNow(date, { addSuffix: true });
            }
        },

        formatRelativeDateTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();

            const isSameDay = (d1, d2) => d1.toDateString() === d2.toDateString();

            if (isSameDay(date, now)) {
                // Show time for today
                return `Today at ${date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                })}`;
            } else if (isYesterday(date)) {
                // Show time for yesterday
                return `Yesterday at ${date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                })}`;
            } else {
                const differenceInDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
                return `${differenceInDays} days ago`;
            }
        }

    }
});
