<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
        >
            <!-- Close Button -->
            <button
                @click="$emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold"
            >
                &times;
            </button>

            <h2 class="text-2xl font-bold mb-4 text-primary">
                Add New Product
            </h2>

            <form @submit.prevent="saveProduct" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Product Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Enter product name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Category</label>
                    <select
                        v-model="form.category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    >
                        <option value="" disabled>Select category</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="Number(cat.id)"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Barcode</label>
                    <input
                        v-model.number="form.barcode"
                        type="number"
                        min="0"
                        placeholder="Enter barcode"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Price</label>
                    <input
                        v-model.number="form.price"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="Enter price"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Stock</label>
                    <input
                        v-model.number="form.stock"
                        type="number"
                        min="0"
                        placeholder="Enter stock quantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <div class="flex justify-end gap-4 mt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
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
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    name: "CreateProduct",
    setup(_, { emit }) {
        const form = reactive({
            name: "",
            category_id: null,
            barcode: null,
            price: null,
            stock: null,
        });

        const categories = ref<any[]>([]);

        const fetchCategories = async () => {
            const res = await axios.get("/admin/category"); // API to get all categories
            categories.value = res.data.data || res.data;
        };

        onMounted(fetchCategories);

        const saveProduct = async () => {
            try {
                await axios.post("/admin/product", form);
                emit("created");
                emit("close");
            } catch (error: any) {
                if (error.response && error.response.status === 409) {
                    emit("error", error.response.data.message);
                } else {
                    emit("error", "Failed to save product.");
                }
            }
        };

        return { form, saveProduct, categories };
    },
});
</script>
