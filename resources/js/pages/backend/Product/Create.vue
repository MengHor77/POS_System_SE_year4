<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
        >
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
                        <option value="" disabled>
                            {{
                                categories.length
                                    ? "Select category"
                                    : "Loading categories..."
                            }}
                        </option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
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
            category_id: "",
            barcode: null,
            price: null,
            stock: null,
        });

        const categories = ref<any[]>([]);

        const fetchCategories = async () => {
            try {
                // បន្ថែម ?all=true ដើម្បីទាញយក Category ទាំងអស់មកបង្ហាញក្នុង Dropdown
                const res = await axios.get("/admin/category/data?all=true");

                // ដោយសារ get() ក្នុង Laravel បោះមកជា Array ផ្ទាល់ មិនមែនជា Object របស់ Paginate ទេ
                categories.value = res.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        };

        onMounted(fetchCategories);

        const saveProduct = async () => {
            try {
                const payload = {
                    ...form,
                    category_id: Number(form.category_id),
                };

                await axios.post("/admin/product", payload);
                emit("created");
                emit("close");
            } catch (error: any) {
                // ... រក្សាការឆែក Error ដូចកូដចាស់របស់អ្នក ...
                emit("error", "Failed to save product.");
            }
        };

        return { form, saveProduct, categories };
    },
});
</script>
