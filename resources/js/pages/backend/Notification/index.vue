<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1
                class="text-2xl md:text-3xl font-bold mb-6 text-warning flex items-center gap-3"
            >
                <i class="fas fa-bell"></i>
                Low Stock Notifications
            </h1>

            <div class="mb-6 w-full sm:w-80">
                <SearchInput
                    v-model="barcode"
                    placeholder="Search by barcode or name"
                    @search="fetchLowStock(1)"
                    containerClass="flex gap-2 w-full"
                    inputClass="border p-2 rounded-xl flex-1 min-w-0 w-full focus:ring-2 focus:ring-warning outline-none"
                />
            </div>

            <div
                v-if="products.length === 0"
                class="text-center text-gray-500 mt-10 px-4"
            >
                🎉 All products have sufficient stock. Products with less than
                amount 6 show here.
                <router-link
                    to="/admin/product"
                    class="block sm:inline text-blue-600 underline hover:text-blue-800 mt-2 sm:mt-0"
                >
                    adjust stock here
                </router-link>
            </div>

            <div v-else class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <Table
                        :columns="tableColumns"
                        :data="products"
                        rowKey="id"
                        class="min-w-full"
                    >
                        <template #cell-category="{ row }">
                            <span class="whitespace-nowrap">
                                {{ row.category?.name || row.category }}
                            </span>
                        </template>

                        <template #cell-stock="{ row }">
                            <span
                                :class="
                                    row.stock <= 5
                                        ? 'text-red-500 font-bold'
                                        : ''
                                "
                            >
                                {{ row.stock }}
                            </span>
                        </template>

                        <template #cell-status>
                            <span
                                class="px-2 py-1 text-xs rounded-full bg-red-500 text-white whitespace-nowrap"
                            >
                                Low Stock
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <div class="flex gap-2">
                                <button
                                    @click="editProduct(row)"
                                    class="px-3 py-2 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button
                                    @click="deleteProduct(row.id)"
                                    class="px-3 py-2 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <div class="mt-6 flex justify-center md:justify-end">
                <Pigination
                    v-if="pagination.last_page > 1"
                    class="mt-6"
                    :currentPage="pagination.current_page"
                    :lastPage="pagination.last_page"
                    :total="pagination.total"
                    :perPage="pagination.per_page"
                    @page-change="fetchLowStock"
                />
            </div>

            <EditProduct
                v-if="editingProduct"
                :product="editingProduct"
                @close="editingProduct = null"
                @updated="fetchLowStock(pagination.current_page)"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import EditProduct from "./Edit.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import Table from "../../../components/Backend/Table.vue";

export default defineComponent({
    name: "Notification",
    components: { BackendLayout, EditProduct, Pigination, SearchInput, Table },
    setup() {
        const products = ref([]);
        const barcode = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });
        const editingProduct = ref(null);

        const tableColumns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Name" },
            { key: "category", label: "Category" },
            { key: "barcode", label: "Barcode" },
            { key: "price", label: "Price" },
            { key: "stock", label: "Stock" },
            { key: "status", label: "Status" },
            { key: "actions", label: "Actions" },
        ];

        const fetchLowStock = async (page = 1) => {
            try {
                const res = await axios.get("/admin/notification/data", {
                    params: {
                        page,
                        search: barcode.value,
                        per_page: pagination.value.per_page,
                    },
                });
                products.value = res.data.data;
                pagination.value = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    per_page: res.data.per_page,
                    total: res.data.total,
                };
            } catch (error) {
                console.error("Error fetching low stock:", error);
            }
        };

        const editProduct = (product: any) => {
            editingProduct.value = { ...product };
        };

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure?")) return;
            await axios.delete(`/admin/notification/${id}`);
            window.dispatchEvent(new Event("stock-updated"));
            fetchLowStock(pagination.value.current_page);
        };

        onMounted(() => fetchLowStock());

        return {
            products,
            barcode,
            pagination,
            editingProduct,
            fetchLowStock,
            editProduct,
            deleteProduct,
            tableColumns,
        };
    },
});
</script>

<style scoped>
/* Ensure smooth horizontal scrolling on touch devices */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
</style>
