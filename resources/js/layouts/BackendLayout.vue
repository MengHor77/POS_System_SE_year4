<template>
  <div class="flex min-h-screen bg-bgMain">
    <!-- Sidebar -->
    <Sidebar
      :collapsed="collapsed"
      @toggle="toggleSidebar"
    />

    <!-- Main Content -->
    <div
      class="flex-1 flex flex-col transition-all duration-300"
      :class="collapsed ? 'ml-20' : 'ml-64'"
    >
      <Header @toggle-sidebar="toggleSidebar" />

      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Header from "../components/Header.vue";
import Sidebar from "../components/Sidebar.vue";

export default defineComponent({
  name: "BackendLayout",
  components: { Header, Sidebar },
  setup() {
    const collapsed = ref(false);

    const toggleSidebar = () => {
      collapsed.value = !collapsed.value;
    };

    return { collapsed, toggleSidebar };
  },
});
</script>
