<template>
    <div class="border border-red-200 bg-red-50 rounded-lg p-4">
        <!-- Alert Header -->
        <div class="flex items-center mb-3">
            <i class="fa-solid fa-circle-exclamation text-red-600"></i>

            <span class="text-red-600 font-semibold text-sm"
                >Low Stock Alert</span
            >
        </div>

        <!-- Product Info Box -->
        <div
            class="bg-white p-4 rounded-md flex justify-between items-center shadow-sm"
        >
            <div>
                <p class="font-medium text-gray-800">{{ product.name }}</p>
                <p class="text-sm text-gray-500">
                    Stock: {{ product.stock }} units
                </p>
            </div>
            <button
                @click="$emit('add-stock', product)"
                class="bg-black text-white text-sm px-3 py-1 rounded flex items-center space-x-1 hover:bg-gray-800"
            >
                <span>+</span>
                <span>Add Stock</span>
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface Product {
    id: number;
    name: string;
    stock: number;
    brand?: string;
    barcode?: string;
    price?: number;
}

export default defineComponent({
    props: {
        product: {
            type: Object as PropType<Product>,
            required: true,
        },
    },
});
</script>
