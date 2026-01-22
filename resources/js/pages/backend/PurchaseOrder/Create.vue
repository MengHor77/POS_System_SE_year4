<template>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <!-- Modal -->
        <div class="bg-bgCard w-full max-w-2xl rounded-xl shadow-card p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-primary">
                    Create Purchase Order
                </h2>

                <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-danger text-2xl"
                >
                    &times;
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit">

                <!-- Product -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">
                        Product
                    </label>

                    <select
                        v-model.number="form.product_id"
                        @change="onProductChange"
                        class="w-full px-3 py-2 border rounded-lg"
                        required
                    >
                        <option :value="null">-- Select Product --</option>

                        <option
                            v-for="item in supplierProducts"
                            :key="item.id"
                            :value="item.product.id"
                        >
                            {{ item.product.name }}
                        </option>
                    </select>
                </div>

                <!-- Supplier -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">
                        Supplier Name
                    </label>
                    <input
                        type="text"
                        v-model="form.supplier"
                        readonly
                        class="w-full px-3 py-2 border rounded-lg bg-gray-100"
                    />
                </div>

                <!-- Quantity -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">
                        Quantity
                    </label>
                    <input
                        type="number"
                        min="1"
                        v-model.number="form.quantity"
                        class="w-full px-3 py-2 border rounded-lg"
                        required
                    />
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 mt-6">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90"
                    >
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

interface SupplierProduct {
    id: number;
    supplier_name: string;
    product: {
        id: number;
        name: string;
    };
}

export default defineComponent({
    name: "PurchaseOrderCreate",
    emits: ["close", "saved"],

    setup(_, { emit }) {
        const supplierProducts = ref<SupplierProduct[]>([]);

        const form = ref<{
            product_id: number | null;
            supplier: string;
            quantity: number;
        }>({
            product_id: null,
            supplier: "",
            quantity: 1,
        });

        const loadSupplierProducts = async () => {
            const res = await axios.get("/admin/supplier/data");
            supplierProducts.value = res.data.data;
        };

        const onProductChange = () => {
            const selected = supplierProducts.value.find(
                (s) => s.product.id === form.value.product_id
            );

            form.value.supplier = selected
                ? selected.supplier_name
                : "";
        };

        const submit = async () => {
            console.log("Purchase Order:", form.value);

            // Example API call
            // await axios.post("/admin/purchase-orders", form.value)

            emit("saved");
            emit("close");
        };

        onMounted(loadSupplierProducts);

        return {
            supplierProducts,
            form,
            onProductChange,
            submit,
        };
    },
});
</script>
