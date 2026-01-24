<template>
  <div class="relative">
    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
    
    <input
      type="text"
      :value="modelValue"
      @input="handleInput"
      placeholder="Search barcode or name..."
      class="border rounded-xl px-10 py-2 w-80 focus:ring-2 focus:ring-primary outline-none border-gray-200 transition-all shadow-sm"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "SearchProduct",
  props: {
    // modelValue is the standard prop for v-model in Vue 3
    modelValue: {
      type: String,
      default: "",
    },
  },
  emits: ["update:modelValue", "search"],
  setup(props, { emit }) {
    const handleInput = (event: Event) => {
      const target = event.target as HTMLInputElement;
      // Update the v-model value
      emit("update:modelValue", target.value);
      // Trigger the search fetch in parent
      emit("search");
    };

    return { handleInput };
  },
});
</script>