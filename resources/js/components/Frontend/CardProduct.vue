<template>
  <div 
    @click="product.stock > 0 && $emit('add-to-cart', product)"
    class="bg-bgCard rounded-[20px] border border-border p-6 shadow-card hover:shadow-soft transition-all cursor-pointer group flex flex-col items-center text-center relative overflow-hidden"
  >
    <div 
      v-if="product.stock <= 5"
      class="absolute top-4 right-4 px-2 py-1 rounded-lg text-xxs font-bold uppercase tracking-wider"
      :class="product.stock === 0 ? 'bg-dangerSoft text-danger' : 'bg-warningSoft text-warning'"
    >
      {{ product.stock === 0 ? 'Out of Stock' : 'Low Stock' }}
    </div>

    <h3 class="text-2xl font-semibold text-darkSoft mb-1 group-hover:text-primary transition-colors">
      {{ product.name }}
    </h3>

    <p class="text-xl text-muted mb-2 font-medium">
      {{ typeof product.category === 'object' ? product.category.name : product.category }}
    </p>

    <div class="text-4xl font-bold text-info mb-4">
      ${{ formatPrice(product.price) }}
    </div>

    <div 
      :class="[
        product.stock > 0 
          ? 'bg-dark text-white hover:bg-darkSoft' 
          : 'bg-grayLight text-muted cursor-not-allowed',
        'px-8 py-2 rounded-full text-lg font-bold inline-flex items-center shadow-soft transition-all active:scale-95'
      ]"
    >
      <i v-if="product.stock > 0" class="fas fa-plus-circle mr-2 text-sm"></i>
      Stock: {{ product.stock }}
    </div>

    <span class="mt-3 text-sm font-mono">
      Code: {{ product.barcode }}
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
interface Product {
  id: number;
  name: string;
  category: any;
  category_id: number;
  barcode: number;
  price: number | string;
  stock: number;
}

export default defineComponent({
  name: 'CardProduct',
  props: {
    product: {
      type: Object as PropType<Product>,
      required: true
    }
  },
  emits: ['add-to-cart'],
  setup() {
   
    const formatPrice = (value: number | string) => {
      const num = Number(value);
      if (isNaN(num)) return '0';
      return num % 1 === 0 ? num.toString() : num.toFixed(2);
    };

    return { formatPrice };
  }
});
</script>

<style scoped>
/* Optional: Adding a slight lift effect on hover */
.group:hover {
  transform: translateY(-4px);
}
</style>