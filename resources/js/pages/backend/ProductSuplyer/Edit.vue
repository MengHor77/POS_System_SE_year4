<template>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
            <button
                @click="$emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl"
            >
                ✕
            </button>

            <h2 class="text-xl font-bold text-primary mb-6">
                Edit Supplier
            </h2>

            <form @submit.prevent="updateProduct" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Product Name
                    </label>
                    <input
                        v-model="form.name"
                        class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                        disabled
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Quantity
                    </label>
                    <input
                        type="number"
                        v-model.number="form.stock"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        v-model.number="form.price"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>

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
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, reactive, watch } from "vue";
import axios from "axios";

interface Product {
    id: number;
    name: string;
}

interface Supplier {
    id: number;
    product: Product;
    supplier_name: string;
    quantity: number;
    price: number;
}

export default defineComponent({
    name: "EditProduct",

    props: {
        supplier: {
            type: Object as () => Supplier,
            required: true,
        },
    },

    setup(props, { emit }) {
        const form = reactive({
            id: props.supplier.product.id,
            name: props.supplier.product.name,
            brand: "",
            barcode: 0,
            price: props.supplier.price,
            stock: props.supplier.quantity,
        });

        watch(
            () => props.supplier,
            (newVal) => {
                form.id = newVal.product.id;
                form.name = newVal.product.name;
                form.price = newVal.price;
                form.stock = newVal.quantity;
            },
        );

        const updateProduct = async () => {
            await axios.put(`/admin/product/${form.id}`, form);
            emit("updated");
            emit("close");
        };

        return { form, updateProduct };
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
