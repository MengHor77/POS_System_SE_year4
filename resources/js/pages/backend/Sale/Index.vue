<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold mb-4">Sales Page</h1>

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

export default defineComponent({
    name: "SaleIndex",
    components: { BackendLayout },
    setup() {
        const sales = ref([]);

        const fetchSales = async () => {
            try {
                const res = await axios.get("/admin/sales"); // your controller route
                sales.value = res.data;
            } catch (error) {
                console.error(error);
            }
        };

        onMounted(() => {
            fetchSales();
        });

        return { sales };
    },
});
</script>
