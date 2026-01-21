<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative">
            <h2 class="text-2xl font-bold mb-4 text-primary">Edit Supplier</h2>

            <form @submit.prevent="updateSupplier" class="space-y-4">
                <div>
                    <label>Product</label>
                    <select v-model="form.product_id" class="input-field" required>
                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>
                <div>
                    <label>Supplier Name</label>
                    <input v-model="form.supplier_name" type="text" class="input-field" required />
                </div>
                <div>
                    <label>Quantity</label>
                    <input v-model.number="form.quantity" type="number" class="input-field" min="0" required />
                </div>
                <div>
                    <label>Price</label>
                    <input v-model.number="form.price" type="number" class="input-field" min="0" step="0.01" />
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, watch, onMounted } from 'vue';
import axios from 'axios';

interface Product { id: number; name: string; }
interface Supplier { id: number; product_id: number; supplier_name: string; quantity: number; price: number; }

export default defineComponent({
    name: 'EditProductSupplier',
    props: { supplier: { type: Object as () => Supplier, required: true } },
    setup(props, { emit }) {
        const form = reactive({ ...props.supplier });
        const products = ref<Product[]>([]);

        const fetchProducts = async () => {
            const res = await axios.get('/admin/product/data');
            products.value = res.data.data;
        };

        watch(() => props.supplier, (newVal) => Object.assign(form, newVal));

        const updateSupplier = async () => {
            await axios.put(`/admin/supplier/${form.id}`, form);
            emit('updated');
            emit('close');
        };

        onMounted(fetchProducts);
        return { form, products, updateSupplier };
    },
});
</script>
