<template>
    <div class="mt-8 bg-bgCard p-6 rounded-xl2 shadow-card h-96 flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-primary">Recent Sales</h2>

        <ul class="flex-1 overflow-y-auto custom-scrollbar pr-4">
            <li
                v-for="sale in sales"
                :key="sale.id"
                class="flex justify-between items-center py-3 border-b border-border hover:bg-tableRowHover transition-colors"
            >
                <div class="flex flex-col">
                    <span class="text-dark font-semibold">{{
                        sale.product_name
                    }}</span>
                    <span class="text-xxs text-muted uppercase">{{
                        sale.created_at
                    }}</span>
                </div>
                <div class="text-right">
                    <span class="block font-bold text-highlight">
                        ${{
                            Number(sale.total_amount).toLocaleString(
                                undefined,
                                { minimumFractionDigits: 2 },
                            )
                        }}
                    </span>
                    <span
                        class="text-xxs px-2 py-0.5 rounded-full bg-successSoft text-success font-bold uppercase tracking-wider"
                        >Completed</span
                    >
                </div>
            </li>
            <li
                v-if="sales.length === 0"
                class="text-center text-grayLight py-10"
            >
                <i class="fas fa-history block text-3xl mb-2 opacity-20"></i>
                No recent transactions found.
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
            required: true,
        },
    },
});
</script>

<style scoped>
/* Improved, visible scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* Increased width */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f8fafc; /* Light track using tableRowHover color */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1; /* Visible gray color */
    border-radius: 10px;
    border: 2px solid #f8fafc; /* This creates internal padding effect */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8; /* Darker when interacting */
}
</style>
