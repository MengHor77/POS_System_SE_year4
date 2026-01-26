<template>
    <div class="mt-8 bg-bgCard p-6 rounded-2xl shadow-md h-96 flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-primary">Recent Sales</h2>
        <ul class="flex-1 overflow-y-auto custom-scrollbar">
            <li v-for="sale in sales" :key="sale.id" 
                class="flex justify-between py-2 border-b border-border">
                <span class="text-gray-700">{{ sale.product_name }}</span>
                <div class="flex gap-4 items-center">
                    <span class="font-semibold text-secondary">
                        ${{ Number(sale.total_amount).toFixed(2) }}
                    </span>
                    <span class="text-gray-500 text-sm">{{ sale.created_at }}</span>
                </div>
            </li>
            <li v-if="sales.length === 0" class="text-center text-gray-400 py-4">
                No recent sales yet.
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface Sale {
    id: number;
    product_name: string;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "RecentSales",
    props: {
        sales: {
            type: Array as PropType<Sale[]>,
            required: true
        }
    }
});
</script>