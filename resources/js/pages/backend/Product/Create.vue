<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Add New Product
                </h1>
                <p class="text-gray-500">
                    Fill in the details below to add a new product.
                </p>
            </div>

            <!-- Form Container -->
            <form
                @submit.prevent="saveProduct"
                class="bg-white p-6 rounded-2xl shadow-md max-w-lg mx-auto space-y-4"
            >
                <!-- Name -->
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Product Name</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Enter product name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Brand -->
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Brand</label
                    >
                    <input
                        v-model="form.brand"
                        type="text"
                        placeholder="Enter brand"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Barcode -->
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Barcode</label
                    >
                    <input
                        v-model="form.barcode"
                        type="number"
                        placeholder="Enter barcode"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Price -->
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Price</label
                    >
                    <input
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        placeholder="Enter price"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Stock -->
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Stock</label
                    >
                    <input
                        v-model="form.stock"
                        type="number"
                        placeholder="Enter stock quantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 mt-4">
                    <button
                        type="button"
                        @click="router.back()"
                        class="px-4 py-2 rounded-lg bg-bgBtnCancel text-white hover:bg-bgBtnCancelHover"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-bgBtnSave text-white hover:bg-bgBtnSaveHover"
                    >
                        Save 
                    </button>
                </div>
            </form>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, reactive } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default defineComponent({
    name: "ProductCreate",
    components: { BackendLayout },
    setup() {
        const router = useRouter();

        const form = reactive({
            name: "",
            brand: "",
            barcode: 0,
            price: 0,
            stock: 0,
        });

        const saveProduct = async () => {
            try {
                await axios.post("/admin/product", form);

                window.dispatchEvent(
                    new CustomEvent("success", {
                        detail: "Product added successfully",
                    }),
                );

                router.push("/admin/product");
            } catch (error) {
                console.error(error);
                alert("Failed to save product.");
            }
        };

        return { form, saveProduct, router };
    },
});
</script>
