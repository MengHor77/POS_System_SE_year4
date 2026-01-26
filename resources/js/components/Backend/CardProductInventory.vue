<template>
    <div
        class="bg-bgCard rounded-xl shadow-card p-5 flex justify-between items-center border border-border"
    >
        <div>
            <h2 class="text-lg font-bold text-darkSoft">
                {{ product.name }}
            </h2>

            <p class="text-sm text-muted">
                {{ product.brand }}
            </p>

            <div class="mt-4 flex gap-3">
                <button
                    @click="$emit('stock-in', product)"
                    class="px-4 py-2 border border-border rounded-lg hover:bg-bgHover text-primary font-medium transition-colors"
                >
                    <i class="fas fa-plus-circle mr-1"></i> Stock In
                </button>

                <button
                    @click="$emit('stock-out', product)"
                    class="px-4 py-2 border border-border rounded-lg hover:bg-bgHover text-danger font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="product.stock <= 0"
                >
                    <i class="fas fa-minus-circle mr-1"></i> Stock Out
                </button>
            </div>
        </div>

        <span
            class="px-4 py-1 text-sm rounded-full font-bold shadow-soft"
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
            // Using your Soft Status colors from tailwind.config.js
            if (props.product.stock <= 5) {
                return "bg-dangerSoft text-danger";
            }
            if (props.product.stock <= 10) {
                return "bg-warningSoft text-warning";
            }
            // Default to your Brand primary/success colors
            return "bg-successSoft text-success";
        });

        return { stockClass };
    },
});
</script>
