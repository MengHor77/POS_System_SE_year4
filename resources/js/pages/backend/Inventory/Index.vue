<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1 class="text-2xl font-bold mb-6 text-primary">
                Inventory Management
            </h1>

            <div class="w-full sm:w-80 py-4 md:py-6">
                <SearchInput
                    v-model="search"
                    placeholder="Search product name..."
                    @input="handleSearch"
                    @filter="handleSearch"
                    containerClass="flex items-center bg-white border border-primary rounded-full px-4 py-1.5 focus-within:ring-2 focus-within:ring-primary/20 transition-all w-full"
                    inputClass="flex-1 outline-none bg-transparent text-sm ml-2 w-full"
                    buttonClass="text-gray-400 hover:text-primary transition-colors"
                />
            </div>

            <div class="space-y-10 md:space-y-20">
                <div
                    v-if="lowStockProducts.length > 0"
                    class="space-y-4 mb-6 bg-gray-100 p-4 md:p-6 rounded-xl shadow-sm border-l-4 border-warning"
                >
                    <h1
                        class="pb-4 md:pb-9 font-semibold text-lg md:text-xl text-warning flex items-center gap-2"
                    >
                        <i class="fas fa-exclamation-triangle"></i> Low stock
                        alert
                    </h1>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <CardStockAlert
                            v-for="product in lowStockProducts"
                            :key="'alert-' + product.id"
                            :product="product"
                            @add-stock="openStockIn"
                        />
                    </div>
                </div>

                <div
                    class="space-y-4 bg-gray-100 p-4 md:p-6 mb-6 rounded-xl shadow-sm"
                >
                    <h1
                        class="pb-4 md:pb-9 font-semibold text-xl md:text-2xl text-primary"
                    >
                        Current Inventory
                    </h1>

                    <div class="space-y-4">
                        <CardProductInventory
                            v-for="product in products"
                            :key="'inv-' + product.id"
                            :product="product"
                            class="w-full"
                            @stock-in="openStockIn(product)"
                            @stock-out="openStockOut(product)"
                        />
                    </div>

                    <div
                        class="flex justify-center md:justify-end mt-6 overflow-x-auto"
                    >
                        <Pigination
                            v-if="pagination.last_page > 1"
                            :current-page="pagination.current_page"
                            :last-page="pagination.last_page"
                            :total="pagination.total"
                            :per-page="pagination.per_page"
                            @page-change="fetchProducts"
                        />
                    </div>
                </div>
            </div>

            <Create
                v-if="showStockIn && selectedProduct !== null"
                :product="selectedProduct"
                type="in"
                @close="showStockIn = false"
                @created="fetchProducts"
            />

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
import CardProductInventory from "../../../components/Backend/CardProductInventory.vue";
import CardStockAlert from "../../../components/Backend/CardStockAlert.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
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
        CardProductInventory,
        Create,
        Edit,
        CardStockAlert,
        Pigination,
        SearchInput,
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
            try {
                const res = await axios.get("/admin/inventory/data", {
                    params: {
                        page: page,
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
            } catch (err) {
                console.error("Search failed", err);
            }
        };

        const handleSearch = () => {
            pagination.value.current_page = 1;
            fetchProducts(1);
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
            return products.value.filter((p) => p.stock <= 5);
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
            handleSearch,
        };
    },
});
</script>
