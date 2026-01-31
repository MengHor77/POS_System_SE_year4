<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-primary">
                Products Management
            </h1>

            <div
                class="flex flex-col sm:flex-row gap-4 mb-6 items-stretch sm:items-center"
            >
                <button
                    @click="openCreateModal"
                    class="bg-dark text-white px-6 py-2 rounded-xl hover:bg-darkSoft shadow-md transition-all flex items-center justify-center gap-2 w-full sm:w-auto"
                >
                    <i class="fas fa-plus"></i> Add New Product
                </button>

                <div class="w-full sm:max-w-md">
                    <SearchInput
                        v-model="search"
                        @search="fetchProducts(1)"
                        placeholder="Search ID, Name, Barcode, category..."
                        containerClass="flex gap-2 w-full"
                        inputClass="border p-2 rounded-xl flex-1 min-w-0 w-full focus:ring-2 focus:ring-primary outline-none"
                    />
                </div>
            </div>

            <FlassMessage :message="flashMessage" :type="flashType" />

            <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
                <Table :columns="columns" :data="products" class="min-w-full">
                    <template #cell-category="{ row }">
                        <span
                            class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-medium whitespace-nowrap"
                        >
                            {{ row.category?.name || "N/A" }}
                        </span>
                    </template>

                    <template #cell-price="{ row }">
                        <span class="font-bold text-gray-700 whitespace-nowrap"
                            >${{ row.price }}</span
                        >
                    </template>

                    <template #cell-actions="{ row }">
                        <div class="flex gap-2">
                            <button
                                @click="openEditModal(row)"
                                class="px-3 py-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                            >
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <button
                                @click="deleteProduct(row.id)"
                                class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                            >
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <div class="flex justify-center sm:justify-end">
                <Pigination
                    class="mt-6"
                    :current-page="pagination.current_page"
                    :last-page="pagination.last_page"
                    :total="pagination.total"
                    :per-page="pagination.per_page"
                    @page-change="fetchProducts"
                />
            </div>

            <CreateProduct
                v-if="showCreateModal"
                @close="showCreateModal = false"
                @created="onSuccess('created')"
            />
            <EditProduct
                v-if="editingProduct"
                :product="editingProduct"
                @close="editingProduct = null"
                @updated="onSuccess('updated')"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import CreateProduct from "./Create.vue";
import EditProduct from "./Edit.vue";
import Table from "../../../components/Backend/Table.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";
import axios from "axios";

export default defineComponent({
    components: {
        BackendLayout,
        Pigination,
        SearchInput,
        CreateProduct,
        EditProduct,
        Table,
        FlassMessage,
    },
    setup() {
        const products = ref([]);
        const search = ref("");
        const showCreateModal = ref(false);
        const editingProduct = ref(null);
        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");
        const columns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Product Name" },
            { key: "category", label: "Category" },
            { key: "barcode", label: "Barcode" },
            { key: "price", label: "Price" },
            { key: "stock", label: "Stock" },
            { key: "actions", label: "Actions" },
        ];

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });

        const fetchProducts = async (page = 1) => {
            const res = await axios.get("/admin/product/data", {
                params: { page, search: search.value },
            });
            products.value = res.data.data;
            pagination.value = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                per_page: res.data.per_page,
                total: res.data.total,
            };
        };

        const onSuccess = (action: string) => {
            fetchProducts(pagination.value.current_page);
            flashMessage.value = `Product ${action} successfully!`;
            flashType.value = "success";
            setTimeout(() => (flashMessage.value = ""), 3000);
        };

        const deleteProduct = async (id: number) => {
            if (confirm("Delete this product?")) {
                await axios.delete(`/admin/product/${id}`);
                fetchProducts();
            }
        };

        onMounted(() => fetchProducts());

        return {
            products,
            search,
            columns,
            pagination,
            fetchProducts,
            showCreateModal,
            editingProduct,
            openCreateModal: () => (showCreateModal.value = true),
            openEditModal: (p: any) => (editingProduct.value = p),
            deleteProduct,
            flashMessage,
            flashType,
            onSuccess,
        };
    },
});
</script>

<style scoped>
/* Custom scrollbar for a cleaner mobile look */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
</style>
