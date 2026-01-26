<template>
    <div class="bg-bgCard p-6 rounded-2xl shadow-md h-96 flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-primary">Best Selling Products</h2>
        <ul class="flex-1 overflow-y-auto custom-scrollbar">
            <li v-for="product in products" :key="product.id" 
                class="flex justify-between py-2 border-b border-border">
                <span class="text-gray-700">{{ product.name }}</span>
                <span class="font-semibold text-secondary">{{ product.sales_count }} units</span>
            </li>
            <li v-if="products.length === 0" class="text-center text-gray-400 py-4">
                No best selling products yet.
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface Product {
    id: number;
    name: string;
    sales_count: number;
}

export default defineComponent({
    name: "BestSelling",
    props: {
        products: {
            type: Array as PropType<Product[]>,
            required: true
        }
    }
});
</script>