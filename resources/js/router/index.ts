import { createRouter, createWebHistory } from 'vue-router';
import AdminLogin from '../pages/backend/AdminLogin.vue';
import AdminDashboard from '../pages/backend/Dashboard.vue';
import Home from '../pages/frontend/Home.vue';
import ProductUpdate from '../pages/backend/ProductUpdate.vue';
import Inventory from '../pages/backend/Inventory.vue';
import PurchaseOrder from '../pages/backend/PurchaseOrder.vue';
import Profile from '../pages/backend/Profile.vue';
import Report from '../pages/backend/Report.vue';   
import Cashier from '../pages/backend/Cashier.vue';
import Notification



from '../pages/backend/Notification.vue';
const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/admin/login', name: 'admin-login', component: AdminLogin },
  { path: '/admin/dashboard', name: 'admin-dashboard', component: AdminDashboard },
  { path: '/admin/product/product-update', name: 'admin-product-product-update', component: ProductUpdate },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
