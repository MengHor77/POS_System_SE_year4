<template>
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold text-lg">{{ title }}</h2>
    <p class="text-gray-700 mt-2">{{ value }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';

export default defineComponent({
  name: 'Card',
  props: {
    title: { type: String as PropType<string>, required: true },
    value: { type: [String, Number] as PropType<string | number>, default: '' },
  },
});
</script>
