<template>
    <aside
        class="flex flex-col bg-dark text-white transition-all duration-300 shadow-xl"
        :class="[
            collapsed
                ? 'w-20 -translate-x-full lg:translate-x-0 lg:w-20'
                : 'w-64 translate-x-0',
        ]"
    >
        <div
            class="relative border-b border-darkSoft flex items-center h-20 px-4"
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
                class="absolute transition-all duration-300 p-3 rounded-full hover:bg-darkSoft"
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

        <nav
            class="flex-1 mt-4 space-y-1 px-2 overflow-y-auto custom-scrollbar"
        >
            <div
                v-for="item in menuItems"
                :key="item.label"
                class="flex flex-col"
            >
                <router-link
                    v-if="item.route"
                    :to="item.route"
                    @click="handleItemClick"
                    class="flex items-center h-12 px-4 cursor-pointer hover:bg-darkSoft transition-all duration-300 rounded-lg overflow-hidden group"
                    active-class="font-bold text-primary bg-infoSoft"
                >
                    <div
                        class="w-12 pr-4 flex-shrink-0 flex justify-center items-center"
                    >
                        <i
                            :class="[
                                item.icon,
                                'text-lg group-hover:scale-110 transition-transform',
                            ]"
                        ></i>
                    </div>

                    <transition name="fade">
                        <span
                            v-if="!collapsed"
                            class="ml-2 flex-1 flex items-center justify-between whitespace-nowrap"
                        >
                            {{ item.label }}
                            <span
                                v-if="
                                    item.label === 'Notification' &&
                                    (item.count ?? 0) > 0
                                "
                                class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full"
                            >
                                {{ item.count }}
                            </span>
                        </span>
                    </transition>
                </router-link>
            </div>
        </nav>

        <div
            class="mt-auto p-4 border-t border-darkSoft text-xs text-center text-gray-500"
        >
            <span v-if="!collapsed">© POS System</span>
            <span v-else>©</span>
        </div>
    </aside>
</template>

<script lang="ts">
import { defineComponent, reactive, onMounted, onUnmounted } from "vue";
import axios from "axios";

export default defineComponent({
    name: "Sidebar",
    props: { collapsed: Boolean },
    emits: ["toggle"],
    setup(props, { emit }) {
        const toggleSidebar = () => {
            emit("toggle");
        };

        const handleItemClick = () => {
            const isMobile = window.innerWidth < 1024;
            if (isMobile && !props.collapsed) {
                emit("toggle");
            }
        };

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
            { icon: "fas fa-user", label: "Profile", route: "/admin/profile" },
        ]);

        const fetchNotificationCount = async () => {
            try {
                const res = await axios.get("/admin/notification/count");
                const notification = menuItems.find(
                    (i) => i.label === "Notification",
                );
                if (notification) notification.count = res.data.total || 0;
            } catch (e) {
                console.error(e);
            }
        };

        onMounted(() => {
            fetchNotificationCount();
            const interval = setInterval(fetchNotificationCount, 30000);
            onUnmounted(() => clearInterval(interval));
        });

        return { menuItems, toggleSidebar, handleItemClick };
    },
});
</script>
