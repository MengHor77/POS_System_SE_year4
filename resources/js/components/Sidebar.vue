<template>
  <aside
    class="h-screen bg-dark text-white transition-all duration-300 flex flex-col"
    :class="collapsed ? 'w-20' : 'w-64'"
  >
    <!-- Logo + Toggle -->
    <div class="flex items-center justify-between p-6 border-b border-darkSoft">
      <span v-if="!collapsed" class="text-xl font-bold tracking-wide">
        POS System
      </span>

      <button @click="$emit('toggle')" class="text-muted hover:text-white transition">
        <i class="fas fa-bars text-lg"></i>
      </button>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-4 space-y-1">
      <div
        v-for="item in menuItems"
        :key="item.label"
        class="flex items-center px-4 py-3 cursor-pointer hover:bg-darkSoft transition"
        :class="collapsed ? 'justify-center' : 'gap-3'"
      >
        <i :class="item.icon + ' text-lg'"></i>
        <span v-if="!collapsed" class="whitespace-nowrap">{{ item.label }}</span>
      </div>
    </nav>

    <!-- Footer -->
    <div class="mt-auto p-4 border-t border-darkSoft text-sm text-muted text-center">
      <span v-if="!collapsed">© POS System</span>
    </div>
  </aside>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "Sidebar",
  props: {
    collapsed: { type: Boolean, required: true },
  },
  emits: ["toggle"],
  setup() {
    const menuItems = [
      { icon: "fas fa-home", label: "Dashboard" },
      { icon: "fas fa-box-open", label: "Products" },
      { icon: "fas fa-warehouse", label: "Inventory" },
      { icon: "fas fa-file-invoice", label: "Purchase Order" },
      { icon: "fas fa-chart-line", label: "Report" },
      { icon: "fas fa-users", label: "Cashiers" },
      { icon: "fas fa-user", label: "Profile" },
    ];

    return { menuItems };
  },
});
</script>
