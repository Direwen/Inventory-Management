import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Support from '../views/Support.vue';
import Auth from '../views/Auth.vue';
import Profile from '../views/Profile.vue';
import Inventory from '../views/Inventory.vue';
import UserManagement from '../views/UserManagement.vue';
import ProductManagement from '../views/ProductManagement.vue';
import Logs from '../views/Logs.vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/appStore';
import Invitations from '../views/Invitations.vue';
import ResetPassword from '../views/ResetPassword.vue';
import RestoreAccount from '../views/RestoreAccount.vue';

const requireUnauthenticated = async (to, from, next) => {
  const authStore = useAuthStore();
  const uiStore = useUiStore();

  await authStore.loadUser();

  if (authStore.isActive) {
    uiStore.addNotification("error", "You are already logged in.");
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

  const targetInventory = appStore.inventories.find(inv => inv.id == to.params.id);
  if (targetInventory) {
    appStore.activeInventory = targetInventory;
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
  { 
    path: '/inventory/:id', 
    name: 'Inventory', 
    redirect: to => {
      return { name: 'User-Management', params: to.params };
    },
    beforeEnter: validateInventory,
    children: [
      {
        path: 'user-management',
        name: 'User-Management',
        component: UserManagement, 
      },
      {
        path: 'product-management',
        name: 'Product-Management',
        component: ProductManagement, 
      },
      {
        path: 'logs',
        name: 'Logs',
        component: Logs, 
      },
      {
        path: 'invitations',
        name: 'Invitations',
        component: Invitations,
      }
    ]
  },
  {
    path: '/reset-password/:token',
    component: ResetPassword,
    beforeEnter: requireUnauthenticated
  },
  {
    path: '/restore-account',
    name: 'RestoreAccount',
    component: RestoreAccount,
    beforeEnter: requireUnauthenticated, // Ensure the user is not logged in
  },
];



const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global guard to clear active inventory when leaving inventory routes
router.beforeEach((to, from, next) => {
  const appStore = useAppStore();
  
  // If navigating away from inventory routes, clear activeInventory
  if (!to.path.startsWith('/inventory/')) {
    if (appStore.activeInventory) {
      appStore.resetActiveInventoryData();
    }
  }
  next();
});

export default router;