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

            <table
                v-else
                class="w-full table-auto border-collapse border border-gray-300"
            >
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Brand</th>
                        <th class="border px-4 py-2">Barcode</th>
                        <th class="border px-4 py-2">Price</th>
                        <th class="border px-4 py-2">Stock</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products"
                        :key="product.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="border px-4 py-2">{{ product.id }}</td>
                        <td class="border px-4 py-2">{{ product.name }}</td>
                        <td class="border px-4 py-2">{{ product.brand }}</td>
                        <td class="border px-4 py-2">{{ product.barcode }}</td>
                        <td class="border px-4 py-2">{{ product.price }}</td>
                        <td
                            class="border px-4 py-2 font-bold"
                            :class="product.stock <= 5 ? 'text-red-500' : ''"
                        >
                            {{ product.stock }}
                        </td>
                        <td class="border px-4 py-2">
                            <span
                                class="px-2 py-1 text-xs rounded"
                                :class="
                                    product.stock <= 5
                                        ? 'bg-red-500 text-white'
                                        : 'bg-green-500 text-white'
                                "
                            >
                                {{
                                    product.stock <= 5 ? "LOW STOCK" : "ENOUGH"
                                }}
                            </span>
                        </td>
                        <td class="border px-4 py-2 flex gap-2">
                            <button
                                @click="editProduct(product)"
                                class="bg-bgBtnEdit text-white px-2 py-1 rounded"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteProduct(product.id)"
                                class="bg-bgBtnDelete text-white px-2 py-1 rounded"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

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
                    search: barcode.value, // <-- send search to backend
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
