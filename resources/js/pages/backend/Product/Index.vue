<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6">Products List</h1>

            <button
                @click="goCreate"
                class="mb-4 bg-primary text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Add New Product
            </button>

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
                        <td class="border px-4 py-2">
                            <button
                                @click="goEdit(product.id)"
                                class="bg-bgBtnEdit text-white px-2 py-1 rounded hover:bg-bgBtnEditHover mr-2"
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
            <Pigination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total="pagination.total"
                :per-page="pagination.per_page"
                @page-change="fetchProducts"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Pigination.vue";
import axios from "axios";
import { useRouter } from "vue-router";

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
    components: { BackendLayout, Pigination },
    setup() {
        const products = ref<Product[]>([]);
        const router = useRouter();

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0,
        });

        const fetchProducts = async (page = 1) => {
            const res = await axios.get("/admin/product/data", {
                params: { page },
            });

            products.value = res.data.data;
            pagination.value = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                per_page: res.data.per_page,
                total: res.data.total,
            };
        };

        const goCreate = () => router.push("/admin/product/create");
        const goEdit = (id: number) => router.push(`/admin/product/${id}/edit`);

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure you want to delete this product?"))
                return;

            await axios.delete(`/admin/product/${id}`);
            fetchProducts(pagination.value.current_page);
        };

        onMounted(() => fetchProducts());

        return {
            products,
            pagination,
            goCreate,
            goEdit,
            deleteProduct,
            fetchProducts,
        };
    },
});
</script>
