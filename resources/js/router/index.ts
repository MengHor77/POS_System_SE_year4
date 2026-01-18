import { createRouter, createWebHistory } from "vue-router";
import AdminLogin from "../pages/backend/AdminLogin.vue";
import AdminDashboard from "../pages/backend/Dashboard.vue";
import ProductUpdate from "../pages/backend/ProductUpdate.vue";
import Notification from "../pages/backend/Notification.vue";
import Inventory from "../pages/backend/Inventory.vue";
import PurchaseOrder from "../pages/backend/PurchaseOrder.vue";
import Report from "../pages/backend/Report.vue";
import Cashier from "../pages/backend/Cashier.vue";
import Profile from "../pages/backend/Profile.vue";

const routes = [
  { path: "/", name: "home", component: AdminLogin },
  { path: "/admin/login", name: "admin-login", component: AdminLogin },
  { path: "/admin/dashboard", name: "admin-dashboard", component: AdminDashboard },
  { path: "/admin/product", name: "product", component: ProductUpdate },
  { path: "/admin/notification", name: "notification", component: Notification },
  { path: "/admin/inventory", name: "inventory", component: Inventory },
  { path: "/admin/purchase-order", name: "purchase-order", component: PurchaseOrder },
  { path: "/admin/report", name: "report", component: Report },
  { path: "/admin/cashiers", name: "cashiers", component: Cashier },
  { path: "/admin/profile", name: "profile", component: Profile },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
