<template>
  <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-[20px] w-full max-w-md shadow-2xl scale-in overflow-hidden border-t-[8px] border-primary flex flex-col max-h-[90vh]">
      
      <div class="p-6 pb-2 flex justify-between items-center shrink-0">
        <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">Payment Preview</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <div class="px-8 py-4 overflow-y-auto custom-scrollbar">
        <div class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300 font-mono text-gray-800">
          <div class="text-center mb-4">
            <p class="font-bold text-lg uppercase">POS PHONE STORE</p>
            <p class="text-[10px] text-gray-500">Order Preview - Not a Receipt</p>
          </div>

          <div class="space-y-2 mb-4 text-xs max-h-[200px] overflow-y-auto pr-2 custom-scrollbar border-b border-gray-200 pb-2">
            <div v-for="item in cartItems" :key="item.id" class="flex justify-between border-b border-dotted border-gray-300 pb-1">
              <span class="truncate w-40 tracking-tighter">{{ item.qty }}x {{ item.name }}</span>
              <span class="shrink-0 font-bold">${{ (Number(item.price) * item.qty).toFixed(2) }}</span>
            </div>
          </div>

          <div class="space-y-1 text-xs pt-2">
            <div class="flex justify-between">
              <span>Items Count:</span>
              <span>{{ cartItems.length }}</span>
            </div>
            <div class="flex justify-between">
              <span>Subtotal:</span>
              <span>${{ (total * 0.9).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between">
              <span>Tax (10%):</span>
              <span>${{ (total * 0.1).toFixed(2) }}</span>
            </div>
            <div class="border-t border-black border-dashed mt-2 pt-2 flex justify-between items-center">
              <span class="text-base font-bold">TOTAL PAY</span>
              <span class="text-2xl font-black text-primary">${{ total.toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-8 pt-2 grid grid-cols-2 gap-4 shrink-0 bg-white">
        <button 
          @click="$emit('close')" 
          class="py-4 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all uppercase text-xs tracking-widest"
        >
          Cancel
        </button>
        <button 
          @click="$emit('confirm')" 
          class="py-4 rounded-2xl font-bold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/30 transition-all active:scale-95 uppercase text-xs tracking-widest"
        >
          Confirm & Print
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

export default defineComponent({
  name: "PaymentModal",
  props: {
    total: { type: Number, required: true },
    cartItems: { type: Array as PropType<any[]>, required: true }
  },
  emits: ["close", "confirm"]
});
</script>

<style scoped>
.scale-in {
  animation: scaleIn 0.2s ease-out;
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.font-mono {
  font-family: 'Courier New', Courier, monospace;
}

/* Professional Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>