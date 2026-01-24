import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";

// Backend Pages
import AdminLogin from "../pages/backend/AdminLogin/AdminLogin.vue";
import AdminDashboard from "../pages/backend/Dashboard/Index.vue";
import Notification from "../pages/backend/Notification/index.vue";
import Inventory from "../pages/backend/Inventory/Index.vue";
import PurchaseOrder from "../pages/backend/PurchaseOrder/Index.vue";
import Report from "../pages/backend/Report/Index.vue";
import CashierIndex from "../pages/backend/Cashier/Index.vue";
import CashierCreate from "../pages/backend/Cashier/Create.vue";
import CashierEdit from "../pages/backend/Cashier/Edit.vue";
import Profile from "../pages/backend/Profile/Index.vue";
import SaleIndex from "../pages/backend/Sale/Index.vue";
// Product Pages
import ProductIndex from "../pages/backend/Product/Index.vue";
import SupplierIndex from '../pages/backend/ProductSuplyer/Index.vue';
import CategoryIndex from '../pages/backend/Category/Index.vue'
// Frontend Pages
import Home from "../pages/frontend/Index.vue";
import GetStartPage from "../pages/frontend/GetStartPage/Index.vue";
import PosStore from "../pages/frontend/Index.vue";
import CashierLogin from "../pages/frontend/CashierLogin/CashierLogin.vue";


const routes = [
    // Supplier Page

    { path: '/admin/supplier', name: 'supplier.Index', component: SupplierIndex },

    {
        path: "/login/cashier",
        name: "cashier-pos",
        component: PosStore
    },
    {
        path: "/pos",        
        name: "pos.index",
        component: Home       
    },
    {
        path: "/",
        name: "get-start",
        component: GetStartPage
    },
    { path: "/cashier/login", name: "cashier-login", component: CashierLogin },

    { path: "/admin/login", name: "admin-login", component: AdminLogin },
    // { 
    //     path: "/login/admin", 
    //     name: "admin-login", 
    //     component: AdminLogin 
    // },
    {
        path: "/admin/dashboard",
        name: "admin-dashboard",
        component: AdminDashboard,
    },

    // Product routes
    {
        path: "/admin/product",
        name: "product.index",
        component: ProductIndex,
    },
    {
        path: "/admin/category",
        name: "category.index",
        component: CategoryIndex
    },

    {
        path: "/admin/notification",
        name: "notification",
        component: Notification,
    },
    { path: "/admin/inventory", name: "inventory", component: Inventory },
    {
        path: "/admin/purchase-order",
        name: "purchase-order.index",
        component: PurchaseOrder
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
