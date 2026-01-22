<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">
                Product Suppliers management
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
            <div class="flex flex-row gap-3 w-full">
                <div class="w-50">
                    <button
                        @click="showCreate = true"
                        class="bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
                    >
                        Add new product Supplier
                    </button>
                </div>
                <!-- Single Filter -->
                <div class="w-80">
                    <Filter
                        v-model="filterText"
                        placeholder="Search by Product or Supplier"
                        @filter="() => fetch(1)"
                        containerClass="px-2 flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                    />
                </div>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-bgCard rounded-xl shadow-card p-6">
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
            </div>

            <!-- Pagination -->
            <Pigination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total="pagination.total"
                :per-page="pagination.per_page"
                @page-change="fetch"
                class="mt-4"
            />

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
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import CreateSupplier from "./Create.vue";
import EditSupplier from "./Edit.vue";
import Pigination from "../../../components/Pigination.vue";
import Filter from "../../../components/Filter.vue";

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

interface Pagination {
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
}

export default {
    components: {
        BackendLayout,
        CreateSupplier,
        EditSupplier,
        Pigination,
        Filter,
    },
    setup() {
        const suppliers = ref<Supplier[]>([]);
        const showCreate = ref(false);
        const editing = ref<Supplier | null>(null);

        // Single filter input
        const filterText = ref("");

        // Pagination reactive object
        const pagination = reactive<Pagination>({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 5,
        });

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

        // Fetch suppliers with single search filter
        const fetch = async (page = 1) => {
            try {
                const res = await axios.get("/admin/supplier/data", {
                    params: {
                        page,
                        per_page: pagination.per_page,
                        search: filterText.value, // single filter for Product or Supplier
                    },
                });

                suppliers.value = res.data.data.map((s: any) => ({
                    id: s.id,
                    supplier_name: s.supplier_name,
                    quantity: s.quantity,
                    price: s.price,
                    product: s.product ?? { id: 0, name: "Unknown" },
                    product_id: s.product_id,
                }));

                // Update pagination
                pagination.current_page = res.data.current_page;
                pagination.last_page = res.data.last_page;
                pagination.total = res.data.total;
                pagination.per_page = res.data.per_page;
            } catch (err) {
                console.error(err);
                showFlashMessage("Failed to fetch suppliers.", "error");
            }
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
                fetch(pagination.current_page);
            } catch (err) {
                console.error(err);
                showFlashMessage("Failed to delete supplier.", "error");
            }
        };

        // Event handlers for Create/Edit
        const handleCreated = () => {
            fetch(1);
            showFlashMessage("Supplier created successfully!", "success");
            showCreate.value = false;
        };

        const handleUpdated = () => {
            fetch(pagination.current_page);
            showFlashMessage("Supplier updated successfully!", "success");
            editing.value = null;
        };

        const handleError = (msg: string) => {
            showFlashMessage(msg, "error");
        };

        onMounted(() => fetch());

        return {
            suppliers,
            showCreate,
            editing,
            filterText,
            pagination,
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
