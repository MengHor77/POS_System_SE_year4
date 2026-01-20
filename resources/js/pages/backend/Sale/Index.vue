<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold mb-4 text-primary">Sales Management</h1>

                <table class="min-w-full bg-white rounded-lg shadow">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Product</th>
                            <th class="px-4 py-2 border">Quantity</th>
                            <th class="px-4 py-2 border">Total Amount</th>
                            <th class="px-4 py-2 border">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Show sales if exist -->
                        <tr v-for="sale in sales" :key="sale.id">
                            <td class="px-4 py-2 border">
                                {{ sale.product_name }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ sale.quantity }}
                            </td>
                            <td class="px-4 py-2 border">
                                ${{ sale.total_amount }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ sale.created_at }}
                            </td>
                        </tr>

                        <!-- Show message if no sales -->
                        <tr v-if="sales.length === 0">
                            <td
                                class="px-4 py-4 border text-center text-gray-500"
                                colspan="4"
                            >
                                No sales to display.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";

interface Sale {
    id: number;
    product_name: string;
    quantity: number;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "SaleIndex",
    components: { BackendLayout },
    setup() {
        const sales = ref<Sale[]>([]);

        const fetchSales = async () => {
            try {
                const res = await axios.get("/admin/sale/data");
                sales.value = res.data;
            } catch (error) {
                console.error(error);
                sales.value = []; // fallback
            }
        };

        onMounted(() => fetchSales());

        return { sales };
    },
});
</script>

<style scoped>
table th {
    background-color: #f3f4f6;
    text-align: left;
}
</style>
