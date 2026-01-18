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
                                class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 mr-2"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteProduct(product.id)"
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
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
    components: { BackendLayout },
    setup() {
        const products = ref<Product[]>([]);
        const router = useRouter();

        const fetchProducts = async () => {
            const res = await axios.get("/admin/product/data");
            products.value = res.data;
        };

        const goCreate = () => router.push("/admin/product/create");
        const goEdit = (id: number) => router.push(`/admin/product/${id}/edit`);

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure you want to delete this product?"))
                return;

            try {
                await axios.delete(`/admin/product/${id}`);

                window.dispatchEvent(
                    new CustomEvent("success", {
                        detail: "Product deleted successfully",
                    }),
                );

                fetchProducts();
            } catch (error) {
                console.error(error);
            }
        };

        onMounted(fetchProducts);

        return { products, goCreate, goEdit, deleteProduct };
    },
});
</script>
