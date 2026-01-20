<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">Products List</h1>

            <!-- Add New Product Button -->
            <button
                @click="openCreateModal"
                class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
            >
                Add New Product
            </button>
            <!-- Filter Component -->
            <Filter
                v-model="search"
                placeholder="Filter by barcode or name"
                @filter="fetchProducts(1)"
                containerClass="mb-4 flex gap-2 w-20"
                inputClass="border p-2 rounded flex-1"
                buttonClass="bg-darkSoft text-white px-4 py-2 rounded"
            />

            <!-- Products Table -->
            <table
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
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td class="border px-4 py-2">{{ product.id }}</td>
                        <td class="border px-4 py-2">{{ product.name }}</td>
                        <td class="border px-4 py-2">{{ product.brand }}</td>
                        <td class="border px-4 py-2">{{ product.barcode }}</td>
                        <td class="border px-4 py-2">{{ product.price }}</td>
                        <td class="border px-4 py-2">{{ product.stock }}</td>
                        <td class="border px-4 py-2 flex gap-2">
                            <button
                                @click="openEditModal(product)"
                                class="bg-bgBtnEdit text-white px-2 py-1 rounded hover:bg-bgBtnEditHover"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteProduct(product.id)"
                                class="bg-bgBtnDelete text-white px-2 py-1 rounded hover:bg-bgBtnDeleteHover"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pigination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total="pagination.total"
                :per-page="pagination.per_page"
                @page-change="fetchProducts"
            />

            <!-- Modals -->
            <CreateProduct
                v-if="showCreateModal"
                @close="showCreateModal = false"
                @created="fetchProducts(pagination.current_page)"
            />
            <EditProduct
                v-if="editingProduct"
                :product="editingProduct"
                @close="editingProduct = null"
                @updated="fetchProducts(pagination.current_page)"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Pigination.vue";
import CreateProduct from "./Create.vue";
import EditProduct from "./Edit.vue";
import axios from "axios";
import Filter from "../../../components/Filter.vue";

interface Product {
    id: number;
    name: string;
    brand: string;
    barcode: number;
    price: number;
    stock: number;
}

export default defineComponent({
    name: "ProductIndex",
    components: {
        BackendLayout,
        Pigination,
        CreateProduct,
        EditProduct,
        Filter,
    },
    setup() {
        const products = ref<Product[]>([]);
        const editingProduct = ref<Product | null>(null);
        const showCreateModal = ref(false);

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });

        const search = ref("");

        const fetchProducts = async (page = 1) => {
            const res = await axios.get("/admin/product/data", {
                params: {
                    page,
                    per_page: pagination.value.per_page,
                    search: search.value, // <-- send the filter value
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

        const openCreateModal = () => {
            showCreateModal.value = true;
        };

        const openEditModal = (product: Product) => {
            editingProduct.value = { ...product };
        };

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure you want to delete this product?"))
                return;
            await axios.delete(`/admin/product/${id}`);
            fetchProducts(pagination.value.current_page);
        };

        onMounted(() => fetchProducts());

        return {
            products,
            editingProduct,
            showCreateModal,
            pagination,
            search,
            fetchProducts,
            openCreateModal,
            openEditModal,
            deleteProduct,
        };
    },
});
</script>
