<template>
  <aside
    class="h-screen bg-dark text-white transition-all duration-300 flex flex-col"
    :class="collapsed ? 'w-20' : 'w-64'"
  >
    <!-- Logo + Toggle -->
    <div class="flex items-center justify-between p-6 border-b border-darkSoft">
      <span v-if="!collapsed" class="text-xl font-bold tracking-wide">Phone Store</span>
      <button @click="$emit('toggle')" class="text-muted hover:text-white transition">
        <i class="fas fa-bars text-lg"></i>
      </button>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-4 space-y-1 px-2">
      <div v-for="item in menuItems" :key="item.label" class="flex flex-col">
        <!-- Main menu item -->
        <div
          class="flex items-center px-4 py-3 cursor-pointer hover:bg-darkSoft transition rounded-lg"
          :class="collapsed ? 'justify-center' : 'gap-3'"
          @click="toggleSubmenu(item)"
        >
          <i :class="item.icon + ' text-lg'"></i>

          <!-- Main label or router-link -->
          <template v-if="item.route && !collapsed">
            <router-link
              :to="item.route"
              class="flex-1"
              active-class="font-bold text-primary"
            >
              {{ item.label }}
            </router-link>
          </template>
          <span v-else-if="!item.submenu && !collapsed">{{ item.label }}</span>

          <!-- Arrow for submenu -->
          <i
            v-if="item.submenu && !collapsed"
            :class="item.open ? 'fas fa-chevron-up ml-auto' : 'fas fa-chevron-down ml-auto'"
          ></i>
        </div>

        <!-- Submenu -->
        <div v-if="item.submenu && item.open && !collapsed" class="ml-6 flex flex-col">
          <router-link
            v-for="sub in item.submenu"
            :key="sub.label"
            :to="sub.route"
            class="px-4 py-2 cursor-pointer hover:bg-darkSoft rounded-lg text-sm"
            active-class="font-bold text-primary"
          >
            {{ sub.label }}
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Footer -->
    <div class="mt-auto p-4 border-t border-darkSoft text-sm text-muted text-center">
      <span v-if="!collapsed">© POS System</span>
    </div>
  </aside>
</template>

<script lang="ts">
import { defineComponent, reactive } from "vue";

export default defineComponent({
  name: "Sidebar",
  props: { collapsed: { type: Boolean, required: true } },
  emits: ["toggle"],
  setup() {
    // Menu with routes
    const menuItems = reactive([
      { icon: "fas fa-home", label: "Dashboard", route: "/admin/dashboard" },
      {
        icon: "fas fa-box-open",
        label: "Products",
        open: false,
        submenu: [
          { label: "Product Update", route: "/admin/product/product-update" },
          { label: "Notification", route: "/admin/product/notification" },
        ],
      },
      { icon: "fas fa-warehouse", label: "Inventory", route: "/admin/inventory" },
      { icon: "fas fa-file-invoice", label: "Purchase Order", route: "/admin/purchase-order" },
      { icon: "fas fa-chart-line", label: "Report", route: "/admin/report" },
      { icon: "fas fa-users", label: "Cashiers", route: "/admin/cashiers" },
      { icon: "fas fa-user", label: "Profile", route: "/admin/profile" },
    ]);

    const toggleSubmenu = (item: any) => {
      if (item.submenu) item.open = !item.open;
    };

    return { menuItems, toggleSubmenu };
  },
});
</script>
