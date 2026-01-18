<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Product</h1>

            <form
                @submit.prevent="updateProduct"
                class="bg-white p-6 rounded-2xl shadow-md max-w-lg mx-auto space-y-4"
            >
                <div class="flex flex-col">
                    <label class="mb-1 font-medium text-gray-700"
                        >Product Name</label
                    >
                    <input
                        v-model="form.name"
                        placeholder="Enter product name"
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium text-gray-700">Brand</label>
                    <input
                        v-model="form.brand"
                        placeholder="Enter brand"
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium text-gray-700"
                        >Barcode</label
                    >
                    <input
                        v-model="form.barcode"
                        type="number"
                        placeholder="Enter barcode"
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium text-gray-700">Price</label>
                    <input
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        placeholder="Enter price"
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium text-gray-700">Stock</label>
                    <input
                        v-model="form.stock"
                        type="number"
                        placeholder="Enter stock quantity"
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex justify-between items-center mt-4">
                    <button
                        type="button"
                        @click="goBack"
                        class="px-4 py-2 rounded bg-gray-400 hover:bg-gray-500 text-white"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-primary hover:bg-blue-600 text-white"
                    >
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, reactive, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
    name: "ProductEdit",
    components: { BackendLayout },
    setup() {
        const router = useRouter();
        const route = useRoute();

        const form = reactive({
            name: "",
            brand: "",
            barcode: 0,
            price: 0,
            stock: 0,
        });

        // Fetch product data by ID
        const fetchProduct = async () => {
            try {
                const res = await axios.get(
                    `/admin/product/${route.params.id}`,
                );
                Object.assign(form, res.data);
            } catch (error) {
                console.error("Failed to fetch product:", error);
                alert("Failed to load product data.");
            }
        };

        // Update product
        const updateProduct = async () => {
            try {
                await axios.put(`/admin/product/${route.params.id}`, form);

                window.dispatchEvent(
                    new CustomEvent("success", {
                        detail: "Product updated successfully",
                    }),
                );

                router.push("/admin/product");
            } catch (error) {
                console.error("Update failed:", error);
                alert("Failed to update product. Please check the input.");
            }
        };

        const goBack = () => router.push("/admin/product");

        onMounted(fetchProduct);

        return { form, updateProduct, goBack };
    },
});
</script>

<style scoped>
.input-field {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
    outline: none;
    transition: border 0.2s;
}
.input-field:focus {
    border-color: #3b82f6; /* Tailwind blue-500 */
    box-shadow: 0 0 0 1px #3b82f6;
}
.bg-primary {
    background-color: #3b82f6; /* Tailwind blue-500 */
}
</style>
