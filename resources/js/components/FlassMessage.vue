<template>
  <div
    v-if="message"
    :class="[
      'fixed top-5 right-5 z-50 px-4 py-3 rounded shadow-lg transition-all duration-300',
      type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    ]"
    v-show="visible"
  >
    {{ message }}
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
  props: {
    message: { type: String, required: true },
    type: { type: String as () => "success" | "error", default: "success" },
  },
  setup(props) {
    const visible = ref(false);

    // Watch for changes to the message prop
    watch(
      () => props.message,
      (newVal) => {
        if (newVal) {
          visible.value = true;

          // Hide after 3 seconds
          setTimeout(() => {
            visible.value = false;
          }, 3000);
        }
      },
      { immediate: true }
    );

    return { visible };
  },
});
</script>
