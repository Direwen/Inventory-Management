import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Auth from '../views/Auth.vue';
import Profile from '../views/Profile.vue';
import UserManagement from '../views/UserManagement.vue';
import ProductManagement from '../views/ProductManagement.vue';
import Logs from '../views/Logs.vue';
import { useAuthStore } from '../stores/authStore';
import { useUiStore } from '../stores/uiStore';
import { useAppStore } from '../stores/appStore';
import Invitations from '../views/Invitations.vue';
import ResetPassword from '../views/ResetPassword.vue';
import RestoreAccount from '../views/RestoreAccount.vue';
import OauthCallback from '../views/OauthCallback.vue';
import NotFound from '../views/NotFound.vue';

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

const requireOauthFlow = (to, from, next) => {
  // Check if the query has 'code' or 'state' (Google OAuth flow check)
  if (to.query.code && to.query.state) {
    // Proceed if the user is in the middle of the OAuth flow
    next();
  } else {
    // Redirect to the login page or a page where users should go if they try to access this route directly
    next('/');
  }
}

const routes = [
  { path: '/', component: Home },
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
  {
    path: '/callback/google',
    name: 'OauthCallback',
    component: OauthCallback,
    beforeEnter: requireOauthFlow
  },
  // Catch-all route for undefined paths
  { 
    path: '/:pathMatch(.*)*', 
    name: 'NotFound', 
    component: NotFound 
  }
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