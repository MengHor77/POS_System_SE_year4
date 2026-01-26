<template>
    <div
        class="bg-bgCard rounded-xl shadow-card p-5 flex justify-between items-center border border-border"
    >
        <div>
            <h2 class="text-lg font-bold text-darkSoft">
                {{ product.name }}
            </h2>

            <p class="text-sm text-muted">
                {{ product.category }}
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
            class="px-4 py-1 text-sm rounded-full font-bold shadow-soft transition-all duration-300"
            :class="dynamicStockClass"
        >
            {{ product.stock }} in stock
        </span>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType, ref, watch } from "vue";

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
        // Create a reactive ref to hold the CSS classes
        const dynamicStockClass = ref("");

        // Function to determine the styling based on current stock
        const updateClass = (currentStock: number) => {
            if (currentStock <= 5) {
                dynamicStockClass.value = "bg-dangerSoft text-danger";
            } else if (currentStock <= 10) {
                dynamicStockClass.value = "bg-warningSoft text-warning";
            } else {
                dynamicStockClass.value = "bg-successSoft text-success";
            }
        };

        // Watch the stock property of the product object
        watch(
            () => props.product.stock,
            (newStock) => {
                updateClass(newStock);
            },
            { immediate: true }, // Run immediately when component is created
        );

        return { dynamicStockClass };
    },
});
</script>
