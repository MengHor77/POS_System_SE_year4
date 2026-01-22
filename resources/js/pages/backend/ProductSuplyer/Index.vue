<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">
                Product Suppliers
            </h1>

            <!-- Flash Message -->
            <div
                v-if="flashMessage"
                :class="[
                    'p-4 rounded mb-4 text-white transition-all duration-300',
                    flashType === 'success' ? 'bg-success' : 'bg-danger',
                ]"
            >
                {{ flashMessage }}
            </div>

            <!-- Add Supplier Button -->
            <button
                @click="showCreate = true"
                class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
            >
                Add Supplier
            </button>

            <!-- Suppliers Table -->
            <table class="w-full bg-bgCard rounded shadow-card">
                <thead class="bg-tableHeader">
                    <tr>
                        <th class="p-3 border-y text-start">Product</th>
                        <th class="p-3 border-y text-start">Supplier</th>
                        <th class="p-3 border-y text-start">Quantity</th>
                        <th class="p-3 border-y text-start">Price</th>
                        <th class="p-3 border-y text-start">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in suppliers"
                        :key="item.id"
                        class="hover:bg-tableRowHover transition"
                    >
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

            <!-- Modals -->
            <CreateSupplier
                v-if="showCreate"
                @close="showCreate = false"
                @created="handleCreated"
                @error="handleError"
            />
            <EditSupplier
                v-if="editing"
                :supplier="editing"
                @close="editing = null"
                @updated="handleUpdated"
                @error="handleError"
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

        // Flash message
        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");
        const showFlashMessage = (
            message: string,
            type: "success" | "error" = "success",
        ) => {
            flashMessage.value = message;
            flashType.value = type;
            setTimeout(() => (flashMessage.value = ""), 3000);
        };

        // Fetch suppliers
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

        // Edit supplier
        const edit = (item: Supplier) => {
            editing.value = { ...item };
        };

        // Delete supplier
        const remove = async (id: number) => {
            if (!confirm("Are you sure you want to delete this supplier?"))
                return;
            try {
                await axios.delete(`/admin/supplier/${id}`);
                showFlashMessage("Supplier deleted successfully!", "success");
                fetch();
            } catch (err) {
                console.error(err);
                showFlashMessage("Failed to delete supplier.", "error");
            }
        };

        // Event handlers for Create/Edit
        const handleCreated = () => {
            fetch();
            showFlashMessage("Supplier created successfully!", "success");
            showCreate.value = false;
        };

        const handleUpdated = () => {
            fetch();
            showFlashMessage("Supplier updated successfully!", "success");
            editing.value = null;
        };

        const handleError = (msg: string) => {
            showFlashMessage(msg, "error");
        };

        onMounted(fetch);

        return {
            suppliers,
            showCreate,
            editing,
            fetch,
            edit,
            remove,
            flashMessage,
            flashType,
            showFlashMessage,
            handleCreated,
            handleUpdated,
            handleError,
        };
    },
};
</script>
