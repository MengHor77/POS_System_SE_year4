<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-primary">
                        Sales Management
                    </h1>
                    <button
                        @click="fetchSales"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition"
                    >
                        🔄 Refresh Table
                    </button>
                </div>

                <Table :columns="columns" :data="sales" row-key="id">
                    <template #cell-cashier_name="{ row }">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900">{{
                                row.cashier_name
                            }}</span>
                            <span class="text-xs text-gray-500">{{
                                row.cashier_email
                            }}</span>
                        </div>
                    </template>

                    <template #cell-total_amount="{ row }">
                        <span class="font-semibold text-green-600">
                            ${{ Number(row.total_amount).toFixed(2) }}
                        </span>
                    </template>

                    <template #cell-created_at="{ row }">
                        <span class="text-sm text-gray-600">{{
                            row.created_at
                        }}</span>
                    </template>
                </Table>
            </div>
        </template>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import Table from "../../../components/Backend/Table.vue";

// Updated Interface to include Cashier Details from your new migration
interface Sale {
    id: number;
    product_name: string;
    cashier_name: string;
    cashier_email: string;
    quantity: number;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "SaleIndex",
    components: { BackendLayout, Table },
    setup() {
        const sales = ref<Sale[]>([]);

        // Updated Columns to include Cashier
        const columns = [
            { key: "id", label: "ID" },
            { key: "product_name", label: "Product" },
            { key: "quantity", label: "Quantity" },
            { key: "total_amount", label: "Total Amount" },
            { key: "cashier_name", label: "Process by" }, 
            { key: "created_at", label: "Date & Time" },
        ];

        const fetchSales = async () => {
            try {
                const res = await axios.get("/admin/sale/data");
                sales.value = res.data;
            } catch (error) {
                console.error("Error fetching sales:", error);
                sales.value = [];
            }
        };

        onMounted(() => fetchSales());

        return {
            sales,
            columns,
            fetchSales,
        };
    },
});
</script>
