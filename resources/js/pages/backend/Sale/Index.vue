<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6 bg-bgMain min-h-screen">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold mb-6 text-primary">
                        Sales Management
                    </h1>

                    <div
                        class="flex flex-wrap items-center justify-between gap-4"
                    >
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <SearchInput
                                v-model="searchQuery"
                                placeholder="Search ID, Product, or Cashier..."
                                @search="fetchSales(1)"
                            />

                            <button
                                @click="fetchSales(1)"
                                class="px-4 py-2 bg-bgCard hover:bg-bgHover text-muted border border-border rounded-lg text-sm transition shadow-soft flex items-center gap-2"
                            >
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>

                        <DateRangeFilter
                            v-model:startDate="startDate"
                            v-model:endDate="endDate"
                            @filter="fetchSales(1)"
                        />
                    </div>
                </div>

                <div
                    class="bg-bgCard rounded-xl2 shadow-card border border-border overflow-hidden"
                >
                    <Table :columns="columns" :data="sales" row-key="id">
                        <template #cell-cashier_name="{ row }">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{
                                    row.cashier_name
                                }}</span>
                                <span class="text-xxs text-muted">{{
                                    row.cashier_email
                                }}</span>
                            </div>
                        </template>

                        <template #cell-total_amount="{ row }">
                            <span class="font-semibold text-success">
                                ${{ Number(row.total_amount).toFixed(2) }}
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <button
                                @click="openDetail(row)"
                                class="px-3 py-1 bg-info text-white text-xs rounded-md hover:opacity-90 transition flex items-center gap-1 shadow-soft"
                            >
                                <i class="fas fa-eye text-xxs"></i> View
                            </button>
                        </template>

                        <template #cell-created_at="{ row }">
                            <span class="text-sm text-muted">{{
                                row.created_at
                            }}</span>
                        </template>
                    </Table>
                </div>

                <Pigination
                    v-if="pagination.total > 0"
                    :current-page="pagination.currentPage"
                    :last-page="pagination.lastPage"
                    :total="pagination.total"
                    :per-page="pagination.perPage"
                    @page-change="fetchSales"
                />

                <SaleTransactionDetails
                    v-if="showModal"
                    :sale="selectedSale"
                    @close="showModal = false"
                />
            </div>
        </template>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, reactive } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Table from "../../../components/Backend/Table.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import DateRangeFilter from "../../../components/Backend/DateRangeFilter.vue";
import SaleTransactionDetails from "./SaleTransactionDetails.vue";
import axios from "axios";

interface Sale {
    id: number;
    product_id: number;
    product_name: string;
    category: string;
    unit_price: number;
    cashier_name: string;
    cashier_email: string;
    quantity: number;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "SaleIndex",
    components: {
        BackendLayout,
        Table,
        Pigination,
        SearchInput,
        DateRangeFilter,
        SaleTransactionDetails,
    },
    setup() {
        const sales = ref<Sale[]>([]);
        const selectedSale = ref<Sale | null>(null);
        const showModal = ref(false);
        const searchQuery = ref("");

        const startDate = ref("");
        const endDate = ref("");

        const pagination = reactive({
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 5,
        });

        const columns = [
            { key: "id", label: "ID" },
            { key: "product_name", label: "Product" },
            { key: "quantity", label: "Qty" },
            { key: "total_amount", label: "Total Amount" },
            { key: "cashier_name", label: "Processed By" },
            { key: "actions", label: "Actions" },
            { key: "created_at", label: "Date & Time" },
        ];

        const fetchSales = async (page = 1) => {
            try {
                const res = await axios.get("/admin/sale/data", {
                    params: {
                        page: page,
                        search: searchQuery.value,
                        start_date: startDate.value,
                        end_date: endDate.value,
                    },
                });

                if (res.data.data) {
                    sales.value = res.data.data;
                    pagination.currentPage = res.data.current_page;
                    pagination.lastPage = res.data.last_page;
                    pagination.total = res.data.total;
                    pagination.perPage = res.data.per_page;
                } else {
                    sales.value = res.data;
                }
            } catch (error) {
                console.error("Error fetching sales:", error);
                sales.value = [];
            }
        };

        const openDetail = (sale: Sale) => {
            selectedSale.value = sale;
            showModal.value = true;
        };
        onMounted(() => fetchSales());

        return {
            sales,
            columns,
            fetchSales,
            showModal,
            selectedSale,
            openDetail,
            searchQuery,
            pagination,
            startDate,
            endDate,
        };
    },
});
</script>
