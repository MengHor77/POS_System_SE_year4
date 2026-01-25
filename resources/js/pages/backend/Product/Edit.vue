<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
        >
            <h2 class="text-2xl font-bold mb-4 text-primary">Edit Product</h2>

            <form @submit.prevent="updateProduct" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Product Name</label>
                    <input
                        v-model="form.name"
                        placeholder="Enter product name"
                        class="input-field"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Category</label>
                    <select
                        v-model="form.category_id"
                        class="input-field"
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
                        class="input-field"
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
                        class="input-field"
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
                        class="input-field"
                        required
                    />
                </div>

                <div class="flex justify-end gap-4 mt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-bgBtnSave hover:bg-bgBtnSaveHover text-white"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    name: "EditProduct",
    props: {
        product: { type: Object, required: true },
    },
    setup(props, { emit }) {
        // ចម្លងទិន្នន័យពី props មកកាន់ form
        const form = reactive({ ...props.product });
        const categories = ref<any[]>([]);

        // ទាញយក Categories ទាំងអស់ (ប្រើ ?all=true)
        const fetchCategories = async () => {
            try {
                const res = await axios.get("/admin/category/data?all=true");
                // ដោយសារប្រើ get() ក្នុង Controller វានឹងបោះមកជា Array ផ្ទាល់
                categories.value = res.data;
            } catch (error) {
                console.error("Failed to fetch categories:", error);
            }
        };

        onMounted(fetchCategories);

        // តាមដានការផ្លាស់ប្តូរ props (ក្នុងករណីបើក Edit លើ Product ផ្សេងទៀត)
        watch(
            () => props.product,
            (newVal) => {
                if (newVal) {
                    Object.assign(form, newVal);
                }
            },
            { deep: true },
        );

        const updateProduct = async () => {
            try {
                // បញ្ជូនទិន្នន័យទៅកាន់ API Update
                await axios.put(`/admin/product/${form.id}`, form);
                emit("updated");
                emit("close");
            } catch (error: any) {
                console.error(error);
                if (error.response && error.response.status === 409) {
                    alert("This barcode already exists!");
                } else {
                    alert("Failed to update product.");
                }
            }
        };

        return { form, categories, updateProduct };
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
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}
</style>
