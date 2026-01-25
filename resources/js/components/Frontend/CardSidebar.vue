<template>
  <aside class="w-[400px] bg-white rounded-[20px] shadow-lg flex flex-col overflow-hidden my-2 no-print">
    <div class="p-6 border-b flex justify-between items-center">
      <h2 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Current Items</h2>
      <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-1 rounded-full">
        {{ cart.length }} Items
      </span>
    </div>
    
    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
      <div v-if="cart.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400">
        <i class="fas fa-shopping-cart text-5xl mb-4 opacity-20"></i>
        <p class="font-medium">Your cart is empty</p>
      </div>
      
      <CardItem 
        v-for="item in cart" 
        :key="item.id" 
        :item="item" 
        @increase="$emit('increase', item.id)" 
        @decrease="$emit('decrease', item.id)" 
        @remove="$emit('remove', item.id)" 
      />
    </div>

    <div class="p-6 border-t bg-white">
      <div class="flex justify-between items-end mb-6">
        <span class="text-gray-500 uppercase text-xs tracking-widest">Total Amount</span>
        <span class="text-4xl font-black text-gray-900 tracking-tighter">
          ${{ total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
        </span>
      </div>
      <button 
        @click="$emit('submit')" 
        :disabled="cart.length === 0" 
        class="w-full bg-[#0a0a14] text-white py-5 rounded-2xl font-bold text-xl hover:bg-primary transition-all active:scale-95 disabled:bg-gray-200 disabled:cursor-not-allowed"
      >
        Submit
      </button>
    </div>
  </aside>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import CardItem from "./CardItem.vue";

export default defineComponent({
  name: "CartSidebar",
  components: { CardItem },
  props: {
    cart: { type: Array as PropType<any[]>, required: true },
    total: { type: Number, required: true }
  },
  emits: ["increase", "decrease", "remove", "submit"]
});
</script>
<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(128, 132, 131, 0.582);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
</style>