<template>
    <aside
        class="flex flex-col bg-dark text-white transition-all duration-300"
        :class="collapsed ? 'w-20' : 'w-64'"
    >
        <!-- Logo + Toggle -->
        <div
            class="relative flex items-center h-20 px-4 transition-all duration-300"
        >
            <transition name="fade">
                <span
                    v-if="!collapsed"
                    class="text-xl font-bold tracking-wide whitespace-nowrap ml-2"
                >
                    Phone Store
                </span>
            </transition>

            <button
                @click="toggleSidebar"
                class="absolute transition-all duration-300 px-3 py-2 rounded-full hover:bg-primary"
                :class="collapsed ? 'left-1/2 -translate-x-1/2' : 'right-4'"
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
                    class="flex items-center h-12 px-4 cursor-pointer hover:bg-darkSoft transition-all duration-300 rounded-lg overflow-hidden"
                    active-class="font-bold text-primary bg-infoSoft"
                >
                    <div
                        class="w-12 pr-4 flex-shrink-0 flex justify-center items-center"
                    >
                        <i :class="item.icon + ' text-lg'"></i>
                    </div>

                    <transition name="fade">
                        <span
                            v-if="!collapsed"
                            class="ml-2 flex-1 flex items-center justify-between whitespace-nowrap transition-opacity duration-300"
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
                    </transition>
                </router-link>
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
       
        const savedCount = parseInt(
            sessionStorage.getItem("notif-count") || "0",
        );
        const savedState = localStorage.getItem("sidebar-collapsed") === "true";
        const collapsed = ref(savedState);

       
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
                count: savedCount,
            },
            {
                icon: "fas fa-warehouse",
                label: "Inventory",
                route: "/admin/inventory",
            },
            {
                icon: "fas fa-truck",
                label: "Supplier",
                route: "/admin/supplier",
            },
            {
                icon: "fas fa-file-invoice",
                label: "Purchase Order",
                route: "/admin/purchase-order",
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
                const res = await axios.get("/admin/notification/count");
                const notification = menuItems.find(
                    (i) => i.label === "Notification",
                );
                if (notification) {
                    notification.count = res.data.total || 0;
                    sessionStorage.setItem(
                        "notif-count",
                        (res.data.total || 0).toString(),
                    );
                }
            } catch (error) {
                console.error("Failed to fetch notification count:", error);
            }
        };

        onMounted(() => {
            fetchNotificationCount();
            // Refresh every 30 seconds
            const interval = setInterval(fetchNotificationCount, 30000);

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
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
