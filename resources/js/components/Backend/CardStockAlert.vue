<template>
    <div
        class="border border-dangerSoft bg-dangerSoft rounded-xl2 p-4 shadow-soft"
    >
        <div class="flex items-center mb-3">
            <i class="fa-solid fa-circle-exclamation text-danger"></i>

            <span class="text-danger font-semibold text-sm ml-2">
                Low Stock Alert
            </span>
        </div>

        <div
            class="bg-bgCard p-4 rounded-xl flex justify-between items-center shadow-card border border-border"
        >
            <div>
                <p class="font-medium text-darkSoft">
                    {{ product.name }}
                </p>
                <p class="text-sm text-muted">
                    {{ product.category }}
                </p>
                <p class="text-sm text-muted">
                    Stock:
                    <span class="text-danger font-bold">{{
                        product.stock
                    }}</span>
                    units
                </p>
            </div>

            <button
                @click="$emit('add-stock', product)"
                class="bg-darkSoft text-white text-sm px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-dark transition-colors shadow-soft"
            >
                <i class="fa-solid fa-plus"></i>
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
    name: "LowStockAlert",
    props: {
        product: {
            type: Object as PropType<Product>,
            required: true,
        },
    },
    emits: ["add-stock"],
});
</script>
