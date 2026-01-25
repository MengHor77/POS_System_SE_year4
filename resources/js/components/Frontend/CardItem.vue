<template>
    <div
        class="flex flex-col p-4 rounded-2xl border border-gray-100 bg-gray-50 group hover:border-primary transition-all gap-3"
    >
        <div class="flex justify-between items-start">
            <div class="flex flex-col">
                <span
                    class="font-bold text-gray-800 text-lg group-hover:text-primary transition-colors leading-tight"
                >
                    {{ item.name }}
                </span>
                <span class="text-xs text-gray-600 font-medium mt-1">
                    ${{ Number(item.price).toFixed(2) }} / unit
                </span>
            </div>

            <button
                @click="$emit('remove', item.id)"
                class="text-gray-700 hover:text-red-500 transition-colors p-1"
                title="Remove Item"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <div class="flex justify-between items-center">
            <div
                class="flex items-center bg-white rounded-xl border border-gray-100 p-1 shadow-sm"
            >
                <button
                    @click="$emit('decrease', item.id)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition-colors"
                >
                    <i class="fas fa-minus text-xs"></i>
                </button>

                <span
                    class="px-4 font-bold text-gray-800 min-w-[40px] text-center"
                >
                    {{ item.qty }}
                </span>

                <button
                    @click="$emit('increase', item.id)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all"
                >
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>

            <div class="font-bold text-blue-600 text-xl">
                ${{ (Number(item.price) * item.qty).toFixed(2) }}
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface CartProduct {
    id: number;
    name: string;
    price: number | string;
    qty: number;
}

export default defineComponent({
    name: "CartItem",
    props: {
        item: {
            type: Object as PropType<CartProduct>,
            required: true,
        },
    },
    emits: ["increase", "decrease", "remove"],
});
</script>
