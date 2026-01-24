<template>
  <div class="receipt-outer">
    <div class="receipt-inner text-gray-800 bg-white mx-auto">
      
      <div class="text-center mb-4">
        <h1 class="text-xl font-bold uppercase tracking-widest">Aroma Cafe Style POS</h1>
        <p class="text-xs font-medium">1211 Green Street</p>
        <p class="text-xs font-medium">New York, NY 10005</p>
      </div>

      <div class="text-[10px] space-y-1 mb-3">
        <div class="flex justify-between">
          <span>{{ currentDateTime }}</span>
          <span>Order: #{{ orderNumber }}</span>
        </div>
        <div class="flex justify-between uppercase">
          <span>TAB 8</span>
          <span>HOST MAGGIE</span>
        </div>
      </div>

      <table class="w-full text-[11px] mb-4">
        <thead>
          <tr class="border-t border-b border-black border-dashed uppercase">
            <th class="text-left py-1 w-12">QTY</th>
            <th class="text-left py-1 pl-2">DESC</th>
            <th class="text-right py-1">AMT</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td class="py-1 align-top">{{ item.qty }}</td>
            <td class="py-1 pl-2 uppercase font-medium">{{ item.name }}</td>
            <td class="py-1 text-right align-top font-mono tracking-tighter">
              ${{ (Number(item.price) * item.qty).toFixed(2) }}
            </td>
          </tr>
        </tbody>
      </table>

      <div class="border-t border-black border-dashed pt-3 space-y-2">
        <div class="flex justify-between items-center text-sm font-bold">
          <span>AMT</span>
          <span>${{ total.toFixed(2) }}</span>
        </div>
        
        <div class="text-[10px] space-y-1 uppercase font-semibold text-gray-700">
          <div class="flex justify-between">
            <span>SUBTOTAL</span>
            <span>${{ (total * 0.9).toFixed(2) }}</span>
          </div>
          <div class="flex justify-between">
            <span>TAX</span>
            <span>${{ (total * 0.1).toFixed(2) }}</span>
          </div>
          <div class="flex justify-between text-black font-bold border-t border-gray-200 pt-1">
            <span>BALANCE</span>
            <span>${{ total.toFixed(2) }}</span>
          </div>
        </div>
      </div>

      <div class="mt-8 flex flex-col items-center">
        <div class="barcode-lines flex items-end justify-center h-10 w-full overflow-hidden opacity-80">
          <div v-for="n in 60" :key="n" 
            :class="['bg-black mr-[1px]', n % 4 === 0 ? 'w-[1px]' : 'w-[2px]']"
            :style="{ height: (Math.random() * 50 + 50) + '%' }">
          </div>
        </div>
        <span class="text-[9px] font-mono mt-1 tracking-[4px]">#{{ orderNumber }}</span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType, computed } from "vue";

export default defineComponent({
  name: "Receipt",
  props: {
    items: { type: Array as PropType<any[]>, required: true },
    total: { type: Number, required: true },
  },
  setup() {
    const orderNumber = Math.floor(100000 + Math.random() * 900000);
    const currentDateTime = computed(() => {
      return new Date().toLocaleString('en-US', { 
        month: '2-digit', day: '2-digit', year: 'numeric', 
        hour: '2-digit', minute: '2-digit', hour12: true 
      });
    });
    return { currentDateTime, orderNumber };
  }
});
</script>

<style scoped>
.receipt-inner { width: 80mm; padding: 10mm 5mm; font-family: 'Courier New', Courier, monospace; }
@media screen { .receipt-outer { display: none; } }
@media print { 
  .receipt-outer { display: block !important; width: 80mm; background: white; }
  @page { margin: 0; }
}
</style>