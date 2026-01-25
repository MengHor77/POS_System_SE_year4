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
                Add product Supplier
            </h2>

            <form @submit.prevent="save" class="space-y-4">
                <div>
                    <label>Product</label>
                    <select
                        v-model="form.product_id"
                        class="input-field"
                        required
                    >
                        <option v-for="p in products" :key="p.id" :value="p.id">
                            {{ p.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label>Supplier Name</label>
                    <input
                        v-model="form.supplier_name"
                        type="text"
                        class="input-field"
                        required
                    />
                </div>
                <div>
                    <label>Quantity</label>
                    <input
                        v-model.number="form.quantity"
                        type="number"
                        class="input-field"
                        min="0"
                        required
                    />
                </div>
                <div>
                    <label>Price</label>
                    <input
                        v-model.number="form.price"
                        type="number"
                        class="input-field"
                        min="0"
                        step="0.01"
                        required
                    />
                </div>

                <div class="flex justify-end gap-4">
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

interface Product {
    id: number;
    name: string;
}

export default defineComponent({
    name: "CreateProductSupplier",
    setup(_, { emit }) {
        const form = reactive({
            product_id: null as number | null,
            supplier_name: "",
            quantity: 0,
            price: 0,
        });

        const products = ref<Product[]>([]);

        const fetchProducts = async () => {
            try {
                // ១. បន្ថែម ?all=true ដើម្បីបានផលិតផលទាំងអស់
                const res = await axios.get("/admin/product/data?all=true");

                // ២. កែពី res.data.data មក res.data ព្រោះវាជា Array ផ្ទាល់
                products.value = res.data;
            } catch (error) {
                console.error("Failed to fetch products:", error);
            }
        };

        const save = async () => {
            try {
                await axios.post("/admin/supplier", form);
                emit("created");
                emit("close");
            } catch (error) {
                alert("Failed to save supplier info.");
            }
        };

        onMounted(fetchProducts);

        return { form, products, save };
    },
});
</script>
<style scoped>
.input-field {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
}
</style>
