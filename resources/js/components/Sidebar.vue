<template>
    <aside
        class="flex flex-col bg-dark text-white transition-all duration-300"
        :class="collapsed ? 'w-20' : 'w-64'"
    >
        <!-- Logo + Toggle -->
        <div
            class="flex items-center justify-between p-6 border-b border-darkSoft"
        >
            <span v-if="!collapsed" class="text-xl font-bold tracking-wide"
                >Phone Store</span
            >

            <button
                @click="toggleSidebar"
                class="px-3 rounded-full bg-bgHover hover:bg-primary text-darkSoft hover:text-white transition"
            >
                <i
                    :class="
                        collapsed
                            ? 'fas fa-angle-double-right'
                            : 'fas fa-angle-double-left'
                    "
                    class="text-lg"
                ></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 mt-4 space-y-1 px-2">
            <div
                v-for="item in menuItems"
                :key="item.label"
                class="flex flex-col"
            >
                <router-link
                    v-if="item.route"
                    :to="item.route"
                    class="flex items-center px-4 py-3 cursor-pointer hover:bg-darkSoft transition rounded-lg"
                    active-class="font-bold text-primary"
                    :class="collapsed ? 'justify-center' : 'gap-3'"
                >
                    <i :class="item.icon + ' text-lg'"></i>

                    <span
                        v-if="!collapsed"
                        class="ml-3 flex-1 flex items-center justify-between"
                    >
                        {{ item.label }}
                        <span
                            v-if="
                                item.label === 'Notification' &&
                                (item.count ?? 0) > 0
                            "
                            class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"
                        >
                            {{ item.count }}
                        </span>
                    </span>
                </router-link>

                <!-- For items without a route -->
                <div
                    v-else
                    class="flex items-center px-4 py-3 cursor-pointer hover:bg-darkSoft transition rounded-lg"
                    :class="collapsed ? 'justify-center' : 'gap-3'"
                >
                    <i :class="item.icon + ' text-lg'"></i>
                    <span v-if="!collapsed" class="ml-3">{{ item.label }}</span>
                </div>
            </div>
        </nav>

        <!-- Footer -->
        <div
            class="mt-auto p-4 border-t border-darkSoft text-sm text-muted text-center"
        >
            <span v-if="!collapsed">© POS System</span>
        </div>
    </aside>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    name: "Sidebar",
    setup() {
        /**
         * 1. PERSISTENCE LOGIC
         * Check LocalStorage for a saved state.
         * We compare the string "true" because LocalStorage only stores strings.
         */
        const savedState = localStorage.getItem("sidebar-collapsed") === "true";
        const collapsed = ref(savedState);

        /**
         * 2. TOGGLE LOGIC
         * Update both the reactive ref and the LocalStorage record.
         */
        const toggleSidebar = () => {
            collapsed.value = !collapsed.value;
            localStorage.setItem(
                "sidebar-collapsed",
                collapsed.value.toString(),
            );
        };

        // Menu items with routes
        const menuItems = reactive([
            {
                icon: "fas fa-home",
                label: "Dashboard",
                route: "/admin/dashboard",
            },
            {
                icon: "fas fa-folder",
                label: "Category",
                route: "/admin/category",
            },
            {
                icon: "fas fa-box-open",
                label: "Products",
                route: "/admin/product",
            },
            {
                icon: "fas fa-bell",
                label: "Notification",
                route: "/admin/notification",
                count: 0,
            },
            {
                icon: "fas fa-warehouse",
                label: "Inventory",
                route: "/admin/inventory",
            },
            {
                icon: "fas fa-file-invoice",
                label: "Purchase Order",
                route: "/admin/purchase-order",
            },
            {
                icon: "fas fa-truck",
                label: "Supplier",
                route: "/admin/supplier",
            },
            {
                icon: "fas fa-chart-line",
                label: "Report",
                route: "/admin/report",
            },
            {
                icon: "fas fa-users",
                label: "Cashiers",
                route: "/admin/cashier",
            },
            {
                icon: "fas fa-money-bill-wave",
                label: "Sales",
                route: "/admin/sale",
            },
            {
                icon: "fas fa-user",
                label: "Profile",
                route: "/admin/profile",
            },
        ]);

        // Fetch low-stock notification count
        const fetchNotificationCount = async () => {
            try {
                // Adjust this URL if you changed your API routes in web.php
                const res = await axios.get("/admin/notification/count");
                const notification = menuItems.find(
                    (i) => i.label === "Notification",
                );
                if (notification) {
                    // Ensure we access the count from your specific API response structure
                    notification.count = res.data.total || 0;
                }
            } catch (error) {
                console.error("Failed to fetch notification count:", error);
            }
        };

        onMounted(() => {
            fetchNotificationCount();
            // Refresh every 30 seconds
            const interval = setInterval(fetchNotificationCount, 30000);

            // Clean up interval if the component is unmounted
            return () => clearInterval(interval);
        });

        return {
            menuItems,
            collapsed,
            toggleSidebar,
        };
    },
});
</script>
