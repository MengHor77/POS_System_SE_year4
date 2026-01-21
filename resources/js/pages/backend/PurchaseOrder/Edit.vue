<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-bgCard w-full max-w-2xl rounded-xl shadow-card p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-primary">
                    Edit Purchase Order
                </h2>

                <button
                    @click="$emit('close')"
                    class="text-muted hover:text-danger text-xl"
                >
                    &times;
                </button>
            </div>

            <form @submit.prevent="submit">
                <!-- Supplier -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Supplier Name
                    </label>
                    <input
                        v-model="form.supplier"
                        type="text"
                        class="w-full px-3 py-2 border rounded-lg"
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
                    >
                        <option
                            v-for="product in products"
                            :key="product.id"
                            :value="product.id"
                        >
                            {{ product.name }}
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
                        class="px-4 py-2 rounded-lg bg-bgBtnEdit text-white hover:bg-bgBtnEditHover"
                    >
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    name: "PurchaseOrderEdit",
    props: {
        orderId: {
            type: Number,
            required: true,
        },
    },
    emits: ["close", "updated"],
    setup(props, { emit }) {
        const products = ref<any[]>([]);

        const form = ref({
            supplier: "",
            product_id: "",
            quantity: 1,
        });

        const loadData = () => {
            // MOCK DATA (replace with API)
            products.value = [
                { id: 1, name: "iPhone 14" },
                { id: 2, name: "Galaxy S23" },
            ];

            form.value = {
                supplier: "Samsung Supplier",
                product_id: 2,
                quantity: 10,
            };
        };

        const submit = () => {
            console.log("UPDATE PO:", props.orderId, form.value);
            emit("updated");
            emit("close");
        };

        onMounted(loadData);

        return { products, form, submit };
    },
});
</script>
