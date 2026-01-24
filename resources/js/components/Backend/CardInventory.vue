<template>
    <div class="bg-white rounded-xl shadow p-5 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-800">
                {{ product.name }}
            </h2>

            <p class="text-sm text-gray-500">
                {{ product.brand }} - {{ product.model ?? "-" }}
            </p>

            <div class="mt-4 flex gap-3">
                <button
                    @click="$emit('stock-in', product)"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                >
                    ➕ Stock In
                </button>

                <button
                    @click="$emit('stock-out', product)"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                    :disabled="product.stock <= 0"
                >
                    ➖ Stock Out
                </button>
            </div>
        </div>

        <span
            class="px-4 py-1 text-sm rounded-full font-medium"
            :class="stockClass"
        >
            {{ product.stock }} in stock
        </span>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed, PropType } from "vue";

/* ✅ LOCAL type */
interface Product {
    id: number;
    name: string;
    brand: string;
    model?: string;
    barcode: string;
    price: number;
    stock: number;
}

export default defineComponent({
    name: "CardInventory",

    props: {
        product: {
            type: Object as PropType<Product>,
            required: true,
        },
    },

    emits: {
        "stock-in": (_product: Product) => true,
        "stock-out": (_product: Product) => true,
    },

    setup(props) {
        const stockClass = computed(() => {
            if (props.product.stock <= 5) return "bg-red-100 text-red-700";
            if (props.product.stock <= 10) return "bg-yellow-100 text-yellow-700";
            return "bg-black text-white";
        });

        return { stockClass };
    },
});
</script>
