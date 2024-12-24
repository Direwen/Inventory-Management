import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Support from '../views/Support.vue';
import Auth from '../views/Auth.vue';
import Profile from '../views/Profile.vue';
import Inventory from '../views/Inventory.vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/appStore';

const requireUnauthenticated = (to, from, next) => {
  const authStore = useAuthStore();
  const uiStore = useUiStore();

  if (authStore.isActive) {
    uiStore.addNotification("error", "Unauthorized Access");
    return next('/');
  }
  next();
};

const validateInventory = async (to, from, next) => {
  const appStore = useAppStore();
  const uiStore = useUiStore();

  if (!appStore.inventories) {
    await appStore.loadInventories();
  }
  const exists = appStore.inventories.some(inv => inv.id == to.params.id);

  if (exists) {
    appStore.activeInventory = to.params.id;
    next();
  } else {
    uiStore.addNotification("error", "Invalid Inventory");
    next('/');
  }
};

const routes = [
  { path: '/', component: Home },
  { path: '/support', component: Support },
  { path: '/auth', component: Auth, beforeEnter: requireUnauthenticated },
  { path: '/profile', component: Profile },
  { path: '/inventory/:id', name: 'Inventory', component: Inventory, beforeEnter: validateInventory }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;