<template>
    <div class="bg-bgCard p-6 rounded-xl2 shadow-card h-96 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-primary">
                Best Selling Products
            </h2>
            <button
                class="text-xxs font-semibold text-primary hover:text-darkSoft uppercase tracking-wider"
            >
                View All
            </button>
        </div>

        <ul class="flex-1 overflow-y-auto custom-scrollbar pr-4">
            <li
                v-for="product in products"
                :key="product.id"
                class="flex justify-between items-center py-3 border-b border-border hover:bg-tableRowHover transition-colors"
            >
                <div class="flex flex-col gap-1">
                    <span class="text-dark font-semibold leading-tight">{{
                        product.name
                    }}</span>
                    <span
                        class="inline-block w-fit px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-infoSoft text-info border border-info/10"
                    >
                        {{ product.category_name }}
                    </span>
                </div>

                <div class="text-right">
                    <span
                        class="block font-bold text-secondary text-lg leading-none"
                    >
                        {{ product.sales_count }}
                    </span>
                    <span class="text-[10px] text-muted uppercase font-medium"
                        >Units Sold</span
                    >
                </div>
            </li>

            <li
                v-if="products.length === 0"
                class="text-center text-grayLight py-10"
            >
                <i class="fas fa-box-open block text-3xl mb-2"></i>
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
    category_name: string;
    sales_count: number;
}

export default defineComponent({
    name: "BestSelling",
    props: {
        products: {
            type: Array as PropType<Product[]>,
            required: true,
        },
    },
});
</script>

<style scoped>
/* Improved Scrollbar Styling */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* Slightly wider for easier viewing */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; /* Light track background */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #d1d5db; /* grayLight from your config */
    border-radius: 10px;
    border: 2px solid #f1f1f1; /* Creates a padding effect inside the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #9ca3af; /* Darker on hover */
}
</style>
