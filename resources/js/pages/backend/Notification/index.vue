<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-warning">
                <i class="fas fa-bell text-2xl"></i>
                Low Stock Notifications
            </h1>

            <!-- Barcode Filter -->
            <Filter
                v-if="products.length > 0"
                v-model="barcode"
                placeholder="Filter by barcode or name"
                @filter="fetchLowStock(1)"
                containerClass="mb-4 flex gap-2 w-20"
                inputClass="border p-2 rounded flex-1"
                buttonClass="bg-darkSoft text-white px-4 py-2 rounded"
            />

            <div
                v-if="products.length === 0"
                class="text-center text-gray-500 mt-10"
            >
                🎉 All products have sufficient stock. the product less than
                amount 6 show here. You can
                <router-link
                    to="/admin/product"
                    class="text-blue-600 underline hover:text-blue-800"
                >
                    adjust stock here
                </router-link>
            </div>

            <div v-else class="bg-bgCard rounded-xl shadow-card p-6">
                <table class="w-full border-border rounded-lg overflow-hidden">
                    <thead class="bg-tableHeader text-sm">
                        <tr class="bg-gray-100 rounded-lg">
                            <th class="p-3 border-y text-start">ID</th>
                            <th class="p-3 border-y text-start">Name</th>
                            <th class="p-3 border-y text-start">Brand</th>
                            <th class="p-3 border-y text-start">Barcode</th>
                            <th class="p-3 border-y text-start">Price</th>
                            <th class="p-3 border-y text-start">Stock</th>
                            <th class="p-3 border-y text-start">Status</th>
                            <th class="p-3 border-y text-start">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="product in products"
                            :key="product.id"
                            class="text-sm hover:bg-tableRowHover transition"
                        >
                            <td class="p-3 border-y text-start">
                                {{ product.id }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ product.name }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ product.brand }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ product.barcode }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ product.price }}
                            </td>
                            <td
                                class="p-3 border-y text-start font-bold"
                                :class="
                                    product.stock <= 5 ? 'text-red-500' : ''
                                "
                            >
                                {{ product.stock }}
                            </td>
                            <td class="p-3 border-y text-start">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-red-500 text-white"
                                >
                                    Low Stock
                                </span>
                            </td>
                            <td class="p-3 border-y text-start flex gap-2">
                                <button
                                    @click="editProduct(product)"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pigination
                v-if="pagination.last_page > 1"
                class="mt-6"
                :currentPage="pagination.current_page"
                :lastPage="pagination.last_page"
                :total="pagination.total"
                :perPage="pagination.per_page"
                @page-change="fetchLowStock"
            />

            <!-- Edit Modal -->
            <EditProduct
                v-if="editingProduct"
                :product="editingProduct"
                @close="editingProduct = null"
                @updated="fetchLowStock(pagination.current_page)"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import EditProduct from "./Edit.vue";
import Pigination from "../../../components/Pigination.vue";
import Filter from "../../../components/Filter.vue";

interface Product {
    id: number;
    name: string;
    brand: string;
    barcode: string;
    price: number;
    stock: number;
}

export default defineComponent({
    name: "Notification",
    components: { BackendLayout, EditProduct, Pigination, Filter },
    setup() {
        const products = ref<Product[]>([]);
        const barcode = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });
        const editingProduct = ref<Product | null>(null);

        const fetchLowStock = async (page = 1) => {
            const res = await axios.get("/admin/notification/data", {
                params: {
                    page,
                    per_page: pagination.value.per_page,
                    search: barcode.value,
                },
            });
            products.value = res.data.data;
            pagination.value = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                per_page: res.data.per_page,
                total: res.data.total,
            };
        };

        const editProduct = (product: Product) => {
            editingProduct.value = { ...product };
        };

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure you want to delete this product?"))
                return;
            await axios.delete(`/admin/notification/${id}`);
            fetchLowStock(pagination.value.current_page);
        };

        onMounted(() => fetchLowStock());

        return {
            products,
            barcode,
            pagination,
            editingProduct,
            fetchLowStock,
            editProduct,
            deleteProduct,
        };
    },
});
</script>
