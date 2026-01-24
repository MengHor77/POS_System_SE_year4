<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6">
                <h1 class="text-2xl font-bold mb-4 text-primary">
                    Sales Management
                </h1>

                <Table :columns="columns" :data="sales" row-key="id">
                    <!-- Optional: customize a cell with a slot -->
                    <template #cell-total_amount="{ row }">
                        ${{ row.total_amount }}
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

interface Sale {
    id: number;
    product_name: string;
    quantity: number;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "SaleIndex",
    components: { BackendLayout, Table },
    setup() {
        const sales = ref<Sale[]>([]);

        const columns = [
            { key: "id", label: "ID" },
            { key: "product_name", label: "Product" },
            { key: "quantity", label: "Quantity" },
            { key: "total_amount", label: "Total Amount" },
            { key: "created_at", label: "Date" },
        ];

        const fetchSales = async () => {
            try {
                const res = await axios.get("/admin/sale/data");
                sales.value = res.data;
            } catch (error) {
                console.error(error);
                sales.value = [];
            }
        };

        onMounted(() => fetchSales());

        return {
            sales,
            columns,
        };
    },
});
</script>

<style scoped>
table th {
    background-color: #f3f4f6;
    text-align: left;
}
</style>
