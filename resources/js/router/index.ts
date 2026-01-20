import { createRouter, createWebHistory } from "vue-router";

// Backend Pages
import AdminLogin from "../pages/backend/AdminLogin.vue";
import AdminDashboard from "../pages/backend/Dashboard.vue";
import Notification from "../pages/backend/Notification/index.vue";
import Inventory from "../pages/backend/Inventory/Index.vue";
import PurchaseOrder from "../pages/backend/PurchaseOrder.vue";
import Report from "../pages/backend/Report.vue";
import CashierIndex from "../pages/backend/Cashier/Index.vue";
import CashierCreate from "../pages/backend/Cashier/Create.vue";
import CashierEdit from "../pages/backend/Cashier/Edit.vue";
import Profile from "../pages/backend/Profile/Index.vue";
import SaleIndex from "../pages/backend/Sale/Index.vue";
// Product Pages
import ProductIndex from "../pages/backend/Product/Index.vue";

// Frontend Pages
import Home from "../pages/frontend/Home.vue";

const routes = [
    { path: "/", name: "home", component: Home },

    { path: "/admin/login", name: "admin-login", component: AdminLogin },
    {
        path: "/admin/dashboard",
        name: "admin-dashboard",
        component: AdminDashboard,
    },
    {
        path: "/admin/profile",
        name: "Profile",
        component: Profile,
        meta: { requiresAuth: true },
    },
    
    // Product routes
    {
        path: "/admin/product",
        name: "product.index",
        component: ProductIndex,
        // no child routes for create/edit, will use modals
    },
    {
        path: "/admin/notification",
        name: "notification",
        component: Notification,
    },
    { path: "/admin/inventory", name: "inventory", component: Inventory },
    {
        path: "/admin/purchase-order",
        name: "purchase-order",
        component: PurchaseOrder,
    },
    { path: "/admin/sale", name: "sale", component: SaleIndex },
    { path: "/admin/report", name: "report", component: Report },
    {
        path: "/admin/cashier",
        component: CashierIndex,
    },
    { path: "/admin/cashier/create", component: CashierCreate },
    { path: "/admin/cashier/:id/edit", component: CashierEdit, props: true },
    { path: "/admin/profile", name: "profile", component: Profile },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
