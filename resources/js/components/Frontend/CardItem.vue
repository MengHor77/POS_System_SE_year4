<template>
  <div class="flex justify-between items-center p-4 rounded-2xl border border-gray-100 bg-gray-50 group hover:border-primary transition-all">
    <div class="flex flex-col">
      <span class="font-bold text-gray-800 text-lg group-hover:text-primary transition-colors">
        {{ item.name }}
      </span>
      <div class="flex items-center gap-2 mt-1">
        <span class="text-sm text-gray-500 font-medium bg-white px-2 py-0.5 rounded-lg border border-gray-100">
          Qty: {{ item.qty }}
        </span>
      </div>
    </div>
    
    <div class="text-right">
      <div class="font-bold text-info text-xl">
        ${{ (Number(item.price) * item.qty).toFixed(2) }}
      </div>
      <div v-if="item.qty > 1" class="text-xxs text-gray-400 font-medium">
        ${{ Number(item.price).toFixed(2) }} / unit
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface CartProduct {
  id: number;
  name: string;
  price: number | string;
  qty: number;
}

export default defineComponent({
  name: "CartItem",
  props: {
    item: {
      type: Object as PropType<CartProduct>,
      required: true,
    },
  },
});
</script>