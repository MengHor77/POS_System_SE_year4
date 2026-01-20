<template>
    <BackendLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Inventory Management</h1>

            <div class="bg-gray-100 p-6 rounded-xl shadow-sm">
                <div class="mb-4">
                    <h2 class="font-semibold text-lg">Current Inventory</h2>
                </div>

                <div class="space-y-4">
                    <CardInventory
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        class="w-full"
                        @stock-in="openStockIn(product)"
                        @stock-out="openStockOut(product)"
                    />
                </div>
            </div>

            <!-- STOCK IN MODAL -->
            <Create
                v-if="showStockIn && selectedProduct !== null"
                :product="selectedProduct"
                type="in"
                @close="showStockIn = false"
                @created="fetchProducts"
            />

            <!-- STOCK OUT MODAL -->
            <Edit
                v-if="showStockOut && selectedProduct !== null"
                :product="selectedProduct"
                type="out"
                @close="showStockOut = false"
                @updated="fetchProducts"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

import BackendLayout from "../../../layouts/BackendLayout.vue";
import CardInventory from "../../../components/CardInventory.vue";

import Create from "./Create.vue";
import Edit from "./Edit.vue";

interface Product {
    id: number;
    name: string;
    brand: string;
    barcode: string;
    price: number;
    stock: number;
}

export default defineComponent({
    components: { BackendLayout, CardInventory, Create, Edit },

    setup() {
        const products = ref<Product[]>([]);
        const selectedProduct = ref<Product | null>(null);
        const showStockIn = ref(false);
        const showStockOut = ref(false);

        const fetchProducts = async () => {
            const res = await axios.get("/admin/inventory/data");
            products.value = res.data.data;
        };

        const openStockIn = (product: Product) => {
            selectedProduct.value = product;
            showStockIn.value = true;
        };

        const openStockOut = (product: Product) => {
            selectedProduct.value = product;
            showStockOut.value = true;
        };

        onMounted(fetchProducts);

        return {
            products,
            selectedProduct,
            showStockIn,
            showStockOut,
            fetchProducts,
            openStockIn,
            openStockOut,
        };
    },
});
</script>
