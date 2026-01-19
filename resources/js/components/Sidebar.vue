<template>
    <aside
        class="flex flex-col bg-dark text-white transition-all duration-300"
        :class="collapsed ? 'w-20' : 'w-64'"
    >
        <!-- Logo + Toggle -->
        <div
            class="flex items-center justify-between p-6 border-b  border-darkSoft"
        >
            <span v-if="!collapsed" class="text-xl font-bold tracking-wide"
                >Phone Store</span
            >

            <button
                @click="$emit('toggle')"
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
                <div
                    class="flex items-center px-4 py-3 cursor-pointer hover:bg-darkSoft transition rounded-lg"
                    :class="collapsed ? 'justify-center' : 'gap-3'"
                >
                    <i :class="item.icon + ' text-lg'"></i>

                    <router-link
                        v-if="!collapsed && item.route"
                        :to="item.route"
                        class="flex-1 flex items-center justify-between"
                        active-class="font-bold text-primary"
                    >
                        {{ item.label }}
                        <!-- Notification Badge -->
                        <span
                            v-if="
                                item.label === 'Notification' &&
                                (item.count ?? 0) > 0
                            "
                            class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"
                        >
                            {{ item.count }}
                        </span>
                    </router-link>

                    <span v-else-if="!collapsed && !item.route">{{
                        item.label
                    }}</span>
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
import { defineComponent, reactive, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    name: "Sidebar",
    props: { collapsed: { type: Boolean, required: true } },
    emits: ["toggle"],
    setup() {
        // Menu items with routes
        const menuItems = reactive([
            {
                icon: "fas fa-home",
                label: "Dashboard",
                route: "/admin/dashboard",
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
                icon: "fas fa-chart-line",
                label: "Report",
                route: "/admin/report",
            },
            {
                icon: "fas fa-users",
                label: "Cashiers",
                route: "/admin/cashier",
            },
            { icon: "fas fa-user", label: "Profile", route: "/admin/profile" },
        ]);

        // Fetch low-stock count for notification badge
        const fetchNotificationCount = async () => {
            try {
                const res = await axios.get("/admin/notification/count");
                const notification = menuItems.find(
                    (i) => i.label === "Notification",
                );
                if (notification) notification.count = res.data.total;
            } catch (error) {
                console.error("Failed to fetch notification count:", error);
            }
        };

        onMounted(() => {
            fetchNotificationCount();
            // Optional: refresh count every 30 seconds
            setInterval(fetchNotificationCount, 30000);
        });

        return { menuItems };
    },
});
</script>

<style scoped>
/* Optional: smooth hover effect */
</style>
