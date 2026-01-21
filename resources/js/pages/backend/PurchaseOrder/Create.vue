<template>
    <!-- MODAL BACKDROP -->
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <!-- MODAL -->
        <div class="bg-bgCard w-full max-w-2xl rounded-xl shadow-card p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-primary">
                    Create Purchase Order
                </h2>

                <button
                    @click="$emit('close')"
                    class="text-muted hover:text-danger text-xl"
                >
                    &times;
                </button>
            </div>

            <!-- FORM -->
            <form @submit.prevent="submit">
                <!-- Supplier -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Supplier Name
                    </label>
                    <input
                        v-model="form.supplier"
                        type="text"
                        class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-primary/30"
                        required
                    />
                </div>

                <!-- Product -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Product
                    </label>
                    <select
                        v-model="form.product_id"
                        class="w-full px-3 py-2 border rounded-lg"
                        required
                    >
                        <option value="">-- Select Product --</option>
                        <option
                            v-for="product in products"
                            :key="product.id"
                            :value="product.id"
                        >
                            {{ product.name }} ({{ product.brand }})
                        </option>
                    </select>
                </div>

                <!-- Quantity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Quantity
                    </label>
                    <input
                        v-model.number="form.quantity"
                        type="number"
                        min="1"
                        class="w-full px-3 py-2 border rounded-lg"
                        required
                    />
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 mt-6">
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
import { defineComponent, ref, onMounted } from "vue";

interface Product {
    id: number;
    name: string;
    brand: string;
}

export default defineComponent({
    name: "PurchaseOrderCreate",
    emits: ["close", "saved"],
    setup(_, { emit }) {
        const products = ref<Product[]>([]);

        const form = ref({
            supplier: "",
            product_id: "",
            quantity: 1,
        });

        const loadProducts = () => {
            // TEMP MOCK (replace with API)
            products.value = [
                { id: 1, name: "iPhone 14", brand: "Apple" },
                { id: 2, name: "Galaxy S23", brand: "Samsung" },
            ];
        };

        const submit = () => {
            console.log("CREATE PO:", form.value);
            emit("saved");
            emit("close");
        };

        onMounted(loadProducts);

        return { products, form, submit };
    },
});
</script>
