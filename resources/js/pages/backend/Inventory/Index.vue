<template>
    <BackendLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6 text-primary">
                Inventory Management
            </h1>
            <div class="w-96 py-6">
                <Filter
                    v-model="search"
                    placeholder="Search products name or alert low stock "
                    @filter="fetchProducts(1)"
                    containerClass="flex gap-2 w-full "
                    inputClass="border p-2 rounded flex-1 w-full"
                    buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                />
            </div>

            <div class="space-y-20">
                <!-- LOW STOCK ALERTS -->
                <div
                    class="space-y-4 mb-6 bg-gray-100 p-6 rounded-xl shadow-sm"
                >
                    <h1 class="pb-9 font-semibold text-xl text-warning">
                        Low stock alert
                    </h1>
                    <CardStockAlert
                        v-for="product in lowStockProducts"
                        :key="product.id"
                        :product="product"
                        @add-stock="openStockIn"
                    />
                </div>
                <div
                    class="space-y-4 bg-gray-100 p-6 mb-6 rounded-xl shadow-sm"
                >
                    <h1 class="pb-9 font-semibold text-2xl text-primary">
                        Current Inventory
                    </h1>

                    <CardInventory
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        class="w-full"
                        @stock-in="openStockIn(product)"
                        @stock-out="openStockOut(product)"
                    />

                    <Pigination
                        :current-page="pagination.current_page"
                        :last-page="pagination.last_page"
                        :total="pagination.total"
                        :per-page="pagination.per_page"
                        @page-change="fetchProducts"
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
import { defineComponent, ref, onMounted, computed } from "vue";
import axios from "axios";

import BackendLayout from "../../../layouts/BackendLayout.vue";
import CardInventory from "../../../components//Backend/CardInventory.vue";
import CardStockAlert from "../../../components/Backend/CardStockAlert.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";
import Filter from "../../../components/Backend/Filter.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
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
    components: {
        BackendLayout,
        CardInventory,
        Create,
        Edit,
        CardStockAlert,
        Pigination,
        Filter,
        FlassMessage,
    },

    setup() {
        const products = ref<Product[]>([]);
        const selectedProduct = ref<Product | null>(null);
        const showStockIn = ref(false);
        const showStockOut = ref(false);

        const search = ref("");

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 4,
        });

        const fetchProducts = async (page = 1) => {
            const res = await axios.get("/admin/inventory/data", {
                params: {
                    page,
                    per_page: pagination.value.per_page,
                    search: search.value,
                },
            });

            products.value = res.data.data;

            pagination.value = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                total: res.data.total,
                per_page: res.data.per_page,
            };
        };

        const openStockIn = (product: Product) => {
            selectedProduct.value = product;
            showStockIn.value = true;
        };

        const openStockOut = (product: Product) => {
            selectedProduct.value = product;
            showStockOut.value = true;
        };
        const lowStockProducts = computed(() => {
            return products.value.filter((p) => p.stock <= 5); // alert for stock <= 5
        });
        onMounted(fetchProducts);

        return {
            products,
            selectedProduct,
            showStockIn,
            showStockOut,
            lowStockProducts,
            fetchProducts,
            openStockIn,
            openStockOut,
            search,
            pagination,
        };
    },
});
</script>
