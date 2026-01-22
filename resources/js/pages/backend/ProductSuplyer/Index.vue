<template>
    <BackendLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6 text-primary">
                Product Suppliers
            </h1>

            <button
                @click="showCreate = true"
                class="mb-4 bg-dark text-white px-4 py-2 rounded"
            >
                Add Supplier
            </button>

            <table class="w-full bg-bgCard rounded shadow-card">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-y text-start">Product</th>
                        <th class="p-3 border-y text-start">Supplier</th>
                        <th class="p-3 border-y text-start">Quantity</th>
                        <th class="p-3 border-y text-start">Price</th>
                        <th class="p-3 border-y text-start">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in suppliers" :key="item.id">
                        <td class="p-3 border-y text-start">
                            {{ item.product?.name || "Unknown" }}
                        </td>
                        <td class="p-3 border-y text-start">
                            {{ item.supplier_name }}
                        </td>
                        <td class="p-3 border-y text-start">
                            {{ item.quantity }}
                        </td>
                        <td class="p-3 border-y text-start">
                            {{ item.price }}
                        </td>
                        <td class="p-3 border-y text-start flex gap-2">
                            <button
                                @click="edit(item)"
                                class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                            >
                                <i class="fas fa-pen"></i>
                            </button>
                            <button
                                @click="remove(item.id)"
                                class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <CreateSupplier
                v-if="showCreate"
                @close="showCreate = false"
                @created="fetch"
            />
            <EditSupplier
                v-if="editing"
                :supplier="editing"
                @close="editing = null"
                @updated="fetch"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import CreateSupplier from "./Create.vue";
import EditSupplier from "./Edit.vue";

interface Product {
    id: number;
    name: string;
}

interface Supplier {
    id: number;
    product_id: number;
    product: Product;
    supplier_name: string;
    quantity: number;
    price: number;
}

export default {
    components: { BackendLayout, CreateSupplier, EditSupplier },
    setup() {
        const suppliers = ref<Supplier[]>([]);
        const showCreate = ref(false);
        const editing = ref<Supplier | null>(null);

        const fetch = async () => {
            const res = await axios.get("/admin/supplier/data");
            suppliers.value = res.data.data.map((s: any) => ({
                id: s.id,
                supplier_name: s.supplier_name,
                quantity: s.quantity,
                price: s.price,
                product: s.product ?? { id: 0, name: "Unknown" },
                product_id: s.product_id,
            }));
        };

        const edit = (item: Supplier) => {
            editing.value = { ...item };
        };

        const remove = async (id: number) => {
            if (!confirm("Delete?")) return;
            await axios.delete(`/admin/supplier/${id}`);
            fetch();
        };

        onMounted(fetch);

        return { suppliers, showCreate, editing, fetch, edit, remove };
    },
};
</script>
