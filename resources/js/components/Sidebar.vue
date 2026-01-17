<template>
    <aside
        class="fixed top-0 left-0 h-screen bg-dark text-white transition-all duration-300 flex flex-col"
        :class="collapsed ? 'w-20' : 'w-64'"
    >
        <!-- Logo + Toggle -->
        <div
            class="flex items-center justify-between p-4 border-b border-darkSoft"
        >
            <span v-if="!collapsed" class="text-xl font-bold tracking-wide">
                POS System
            </span>

            <button
                @click="$emit('toggle')"
                class="text-muted hover:text-white transition ml-2"
            >
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 mt-4 space-y-1">
            <SidebarItem
                icon="fas fa-home"
                label="Dashboard"
                :collapsed="collapsed"
            />
            <SidebarItem
                icon="fas fa-box"
                label="Products"
                :collapsed="collapsed"
            />
            <SidebarItem
                icon="fas fa-receipt"
                label="Orders"
                :collapsed="collapsed"
            />
            <SidebarItem
                icon="fas fa-users"
                label="Users"
                :collapsed="collapsed"
            />
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-darkSoft text-sm text-muted">
            <span v-if="!collapsed">© POS System</span>
        </div>
    </aside>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import SidebarItem from "./SidebarItem.vue";

export default defineComponent({
    name: "Sidebar",
    components: { SidebarItem },
    props: {
        collapsed: {
            type: Boolean,
            required: true,
        },
    },
});
</script>
