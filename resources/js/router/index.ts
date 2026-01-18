import { createRouter, createWebHistory } from "vue-router";

// Backend Pages
import AdminLogin from "../pages/backend/AdminLogin.vue";
import AdminDashboard from "../pages/backend/Dashboard.vue";
import Notification from "../pages/backend/Notification.vue";
import Inventory from "../pages/backend/Inventory.vue";
import PurchaseOrder from "../pages/backend/PurchaseOrder.vue";
import Report from "../pages/backend/Report.vue";
import Cashier from "../pages/backend/Cashier.vue";
import Profile from "../pages/backend/Profile.vue";

// Product Pages
import ProductIndex from "../pages/backend/Product/Index.vue";
import ProductCreate from "../pages/backend/Product/Create.vue";
import ProductEdit from "../pages/backend/Product/Edit.vue";

const routes = [
    { path: "/", name: "home", component: AdminLogin },
    { path: "/admin/login", name: "admin-login", component: AdminLogin },
    { path: "/admin/dashboard", name: "admin-dashboard", component: AdminDashboard },

    // Product routes
    { path: "/admin/product", name: "product-index", component: ProductIndex },
    { path: "/admin/product/create", name: "product-create", component: ProductCreate },
    { path: "/admin/product/:id/edit", name: "product-edit", component: ProductEdit },

    { path: "/admin/notification", name: "notification", component: Notification },
    { path: "/admin/inventory", name: "inventory", component: Inventory },
    { path: "/admin/purchase-order", name: "purchase-order", component: PurchaseOrder },
    { path: "/admin/report", name: "report", component: Report },
    { path: "/admin/cashier", name: "cashiers", component: Cashier },
    { path: "/admin/profile", name: "profile", component: Profile },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
