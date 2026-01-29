<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-primary">Edit Product</h2>
                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-700 text-2xl font-bold"
                >
                    &times;
                </button>
            </div>

            <form @submit.prevent="updateProduct" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Product Name</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        class="input-field"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Category</label
                    >
                    <select
                        v-model="form.category"
                        class="input-field"
                        required
                    >
                        <option value="">Select Category</option>
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
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Barcode</label
                    >
                    <input
                        v-model.number="form.barcode"
                        type="number"
                        class="input-field"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Price</label
                    >
                    <input
                        v-model.number="form.price"
                        type="number"
                        step="0.01"
                        class="input-field"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700"
                        >Stock</label
                    >
                    <input
                        v-model.number="form.stock"
                        type="number"
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
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import axios from "axios";

interface ProductForm {
    id?: number;
    name: string;
    category: string | number;
    barcode: number | string;
    price: number;
    stock: number;
}

export default defineComponent({
    name: "EditProduct",
    props: {
        product: { type: Object, required: true },
    },
    setup(props, { emit }) {
        const form = reactive<ProductForm>({
            id: props.product.id,
            name: props.product.name,
            barcode: props.product.barcode,
            price: props.product.price,
            stock: props.product.stock,
            category: props.product.category_id || props.product.category || "",
        });

        const categories = ref<any[]>([]);

        const fetchCategories = async () => {
            try {
                const res = await axios.get("/admin/category/data?all=true");
                categories.value = res.data;
            } catch (error) {
                console.error("Failed to fetch categories:", error);
            }
        };

        onMounted(fetchCategories);

        watch(
            () => props.product,
            (newVal) => {
                form.id = newVal.id;
                form.name = newVal.name;
                form.barcode = newVal.barcode;
                form.price = newVal.price;
                form.stock = newVal.stock;
                form.category = newVal.category_id || newVal.category || "";
            },
            { deep: true },
        );

        const updateProduct = async () => {
            try {
                // Pointing to your controller update route
                await axios.put(`/admin/notification/${form.id}`, form);
                emit("updated");
                emit("close");
            } catch (error: any) {
                if (error.response?.status === 422) {
                    alert(
                        error.response.data.message ||
                            "Validation Error (Check Barcode)",
                    );
                } else {
                    console.error(error);
                    alert("Failed to update product.");
                }
            }
        };

        return { form, updateProduct, categories };
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
    transition:
        border 0.2s,
        box-shadow 0.2s;
}
.input-field:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}
</style>
