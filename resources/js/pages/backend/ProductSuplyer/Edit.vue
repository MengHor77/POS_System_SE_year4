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
                Edit product Supplier
            </h2>

            <form @submit.prevent="updateSupplier" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Product</label>
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
                    <label class="block mb-1 font-semibold"
                        >Supplier Name</label
                    >
                    <input
                        v-model="form.supplier_name"
                        type="text"
                        placeholder="Enter supplier name"
                        class="input-field"
                        required
                    />
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Quantity</label>
                    <input
                        v-model.number="form.quantity"
                        type="number"
                        min="0"
                        placeholder="Enter quantity"
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
import { defineComponent, reactive, ref, watch, onMounted } from "vue";
import axios from "axios";

interface Product {
    id: number;
    name: string;
}
interface Supplier {
    id: number;
    product_id: number;
    supplier_name: string;
    quantity: number;
    price: number;
}

export default defineComponent({
    name: "EditSupplier",
    props: {
        supplier: { type: Object as () => Supplier, required: true },
    },
    emits: ["close", "updated"],
    setup(props, { emit }) {
        const form = reactive({ ...props.supplier });
        const products = ref<Product[]>([]);
        const loading = ref(false);

        const fetchProducts = async () => {
            if (products.value.length) return;
            const res = await axios.get("/admin/product/data");
            products.value = res.data.data;
        };

        watch(
            () => props.supplier,
            async (val) => {
                if (!val) return;

                // wait until products are loaded
                if (products.value.length === 0) {
                    await fetchProducts();
                }

                Object.assign(form, val);
            },
            { immediate: true },
        );

        const updateSupplier = async () => {
            try {
                loading.value = true;

                const res = await axios.put(`/admin/supplier/${form.id}`, form);

                // 🔥 SEND UPDATED SUPPLIER BACK
                emit("updated", res.data.data);
                emit("close");
            } catch (e) {
                alert("Update failed");
            } finally {
                loading.value = false;
            }
        };

        onMounted(fetchProducts);

        return { form, products, updateSupplier, loading };
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
