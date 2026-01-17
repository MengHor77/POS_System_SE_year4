<template>
  <div
    class="flex items-center gap-4 px-4 py-3
           cursor-pointer transition
           hover:bg-darkSoft"
  >
    <i :class="[icon, 'text-lg w-6 text-center']"></i>

    <span
      v-if="!collapsed"
      class="text-sm font-medium"
    >
      {{ label }}
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "SidebarItem",
  props: {
    icon: { type: String, required: true },
    label: { type: String, required: true },
    collapsed: { type: Boolean, required: true },
  },
});
</script>
