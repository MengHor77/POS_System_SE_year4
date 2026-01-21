<template>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
            <!-- Close -->
            <button
                @click="$emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl"
            >
                ✕
            </button>

            <h2 class="text-xl font-bold text-primary mb-6">
                Add Product Supplier
            </h2>

            <form @submit.prevent="save" class="space-y-4">
                <!-- Product -->
                <div>
                    <label class="block text-sm font-medium mb-1">Product</label>
                    <select
                        v-model="form.product_id"
                        required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                    >
                        <option disabled value="">Select product</option>
                        <option v-for="p in products" :key="p.id" :value="p.id">
                            {{ p.name }}
                        </option>
                    </select>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Supplier Name
                    </label>
                    <input
                        v-model="form.supplier_name"
                        class="w-full border rounded-lg px-3 py-2"
                        placeholder="Supplier name"
                        required
                    />
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Quantity
                    </label>
                    <input
                        type="number"
                        v-model.number="form.quantity"
                        min="0"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium mb-1">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        v-model.number="form.price"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, reactive, onMounted, ref } from "vue";
import axios from "axios";

export default defineComponent({
    name: "CreateProductSupplier",

    setup(_, { emit }) {
        const form = reactive({
            product_id: null as number | null,
            supplier_name: "",
            quantity: 0,
            price: null,
        });
        const save = async () => {
            await axios.post("/admin/supplier", form);
            emit("created");
            emit("close");
        };

        interface Product {
            id: number;
            name: string;
        }

        const products = ref<Product[]>([]);

        const fetchProducts = async () => {
            const res = await axios.get("/admin/product/data");
            products.value = res.data.data;
        };

        onMounted(fetchProducts);

        return { form, products, save };
    },
});
</script>
