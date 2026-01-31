<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-primary">
                Product Suppliers Management
            </h1>

            <FlassMessage :message="flashMessage" :type="flashType" />

            <div
                class="flex flex-col sm:flex-row gap-4 w-full pb-6 items-stretch sm:items-center"
            >
                <div class="w-full sm:w-auto">
                    <button
                        @click="showCreate = true"
                        class="w-full sm:w-auto bg-dark text-white px-4 py-2.5 rounded-xl hover:bg-darkSoft shadow-md transition-all flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-plus"></i> Add New Supplier
                    </button>
                </div>
                <div class="w-full sm:w-80">
                    <SearchInput
                        v-model="filterText"
                        placeholder="Search ID, Product, or Supplier..."
                        @search="fetch(1)"
                        containerClass="flex gap-2 w-full"
                        inputClass="border p-2 rounded-xl flex-1 min-w-0 w-full focus:ring-2 focus:ring-primary outline-none"
                    />
                </div>
            </div>

            <div
                class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100"
            >
                <div class="overflow-x-auto">
                    <Table
                        :columns="columns"
                        :data="suppliers"
                        class="min-w-full"
                    >
                        <template #cell-product="{ row }">
                            <span
                                class="font-medium text-gray-800 whitespace-nowrap"
                            >
                                {{ row.product?.name || "Unknown" }}
                            </span>
                        </template>

                        <template #cell-price="{ row }">
                            <span
                                class="font-bold text-gray-700 whitespace-nowrap"
                            >
                                ${{ row.price }}
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <div class="flex gap-2">
                                <button
                                    @click="edit(row)"
                                    class="px-3 py-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                >
                                    <i class="fas fa-pen text-sm"></i>
                                </button>
                                <button
                                    @click="remove(row.id)"
                                    class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <div class="flex justify-center sm:justify-start">
                <Pigination
                    class="mt-6"
                    :current-page="pagination.current_page"
                    :last-page="pagination.last_page"
                    :total="pagination.total"
                    :per-page="pagination.per_page"
                    @page-change="fetch"
                />
            </div>

            <CreateSupplier
                v-if="showCreate"
                @close="showCreate = false"
                @created="handleCreated"
                @error="handleError"
            />
            <EditSupplier
                v-if="editing"
                :key="editing.id"
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
import Pigination from "../../../components/Backend/Pigination.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";
import Table from "../../../components/Backend/Table.vue";

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
        SearchInput,
        FlassMessage,
        Table,
    },
    setup() {
        const suppliers = ref<Supplier[]>([]);
        const showCreate = ref(false);
        const editing = ref<Supplier | null>(null);
        const filterText = ref("");
        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");

        const columns = [
            { key: "id", label: "ID" },
            { key: "product", label: "Product Name" },
            { key: "supplier_name", label: "Supplier Name" },
            { key: "quantity", label: "Qty" },
            { key: "price", label: "Unit Price" },
            { key: "actions", label: "Actions" },
        ];

        const pagination = reactive<Pagination>({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 5,
        });

        const showFlashMessage = (
            message: string,
            type: "success" | "error" = "success",
        ) => {
            flashMessage.value = message;
            flashType.value = type;
            setTimeout(() => (flashMessage.value = ""), 3000);
        };

        const fetch = async (page = 1) => {
            try {
                const res = await axios.get("/admin/supplier/data", {
                    params: {
                        page,
                        per_page: pagination.per_page,
                        search: filterText.value,
                    },
                });

                suppliers.value = res.data.data;

                pagination.current_page = res.data.current_page;
                pagination.last_page = res.data.last_page;
                pagination.total = res.data.total;
                pagination.per_page = res.data.per_page;
            } catch (err) {
                showFlashMessage("Failed to fetch suppliers.", "error");
            }
        };

        const edit = (item: Supplier) => {
            editing.value = { ...item };
        };

        const remove = async (id: number) => {
            if (!confirm("Are you sure you want to delete this supplier?"))
                return;
            try {
                await axios.delete(`/admin/supplier/${id}`);
                showFlashMessage("Supplier deleted successfully!");
                fetch(pagination.current_page);
            } catch (err) {
                showFlashMessage("Failed to delete supplier.", "error");
            }
        };

        const handleCreated = () => {
            fetch(1);
            showFlashMessage("Supplier created successfully!");
            showCreate.value = false;
        };

        const handleUpdated = () => {
            fetch(pagination.current_page);
            showFlashMessage("Supplier updated successfully!");
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
            handleCreated,
            handleUpdated,
            handleError,
            columns,
        };
    },
};
</script>

<style scoped>
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}
</style>
