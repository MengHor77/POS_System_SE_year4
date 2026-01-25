<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6 bg-bgMain min-h-screen">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4"
                >
                    <h1 class="text-2xl font-bold text-primary">
                        Sales Management
                    </h1>

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

                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                >
                    <div
                        class="bg-bgCard rounded-xl2 w-full max-w-lg shadow-card overflow-hidden animate-in fade-in zoom-in duration-200 border border-border"
                    >
                        <div
                            class="p-5 border-b border-border flex justify-between items-center bg-tableHeader"
                        >
                            <h3 class="text-lg font-bold text-gray-800">
                                Sale Transaction Details
                            </h3>
                            <button
                                @click="showModal = false"
                                class="text-muted hover:text-danger transition"
                            >
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-3">
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Transaction ID:</span
                                        >
                                        <span class="font-bold text-gray-900"
                                            >#{{ selectedSale?.id }}</span
                                        >
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Product ID:</span
                                        >
                                        <span class="text-gray-700">{{
                                            selectedSale?.product_id
                                        }}</span>
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Product Name:</span
                                        >
                                        <span
                                            class="font-semibold text-primary"
                                            >{{
                                                selectedSale?.product_name
                                            }}</span
                                        >
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Category:</span
                                        >
                                        <span
                                            class="px-2 py-0.5 bg-infoSoft text-info rounded text-xs font-bold"
                                        >
                                            {{ selectedSale?.category }}
                                        </span>
                                    </p>
                                </div>
                                <div
                                    class="space-y-3 border-l border-border pl-4"
                                >
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Price per Unit:</span
                                        >
                                        <span class="text-gray-700"
                                            >${{
                                                Number(
                                                    selectedSale?.unit_price,
                                                ).toFixed(2)
                                            }}</span
                                        >
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Quantity Sold:</span
                                        >
                                        <span class="font-bold text-gray-900">{{
                                            selectedSale?.quantity
                                        }}</span>
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Total Amount:</span
                                        >
                                        <span
                                            class="text-xl font-bold text-success"
                                        >
                                            ${{
                                                Number(
                                                    selectedSale?.total_amount,
                                                ).toFixed(2)
                                            }}
                                        </span>
                                    </p>
                                    <p>
                                        <span
                                            class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                            >Date & Time:</span
                                        >
                                        <span class="text-gray-600">{{
                                            selectedSale?.created_at
                                        }}</span>
                                    </p>
                                </div>
                            </div>

                            <hr class="border-dashed border-border" />

                            <div
                                class="bg-infoSoft p-4 rounded-xl border border-info/20"
                            >
                                <p
                                    class="text-xxs font-bold text-info uppercase mb-2 tracking-widest"
                                >
                                    Processed By
                                </p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-soft"
                                    >
                                        {{
                                            selectedSale?.cashier_name?.charAt(
                                                0,
                                            )
                                        }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ selectedSale?.cashier_name }}
                                        </p>
                                        <p class="text-xxs text-muted">
                                            {{ selectedSale?.cashier_email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-tableHeader text-right border-t border-border"
                        >
                            <button
                                @click="showModal = false"
                                class="px-6 py-2 bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white rounded-lg transition font-medium shadow-soft text-sm"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
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
    components: { BackendLayout, Table, Pigination, SearchInput },
    setup() {
        const sales = ref<Sale[]>([]);
        const selectedSale = ref<Sale | null>(null);
        const showModal = ref(false);
        const searchQuery = ref("");

        // Pagination State
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
                    },
                });

                // Assuming Laravel lengthAwarePaginator structure
                // If your backend returns only an array, you'll need to paginate manually on frontend
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
        };
    },
});
</script>

<!-- 

<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6 bg-bgMain min-h-screen">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-primary">
                        Sales Management
                    </h1>
                    <button
                        @click="fetchSales"
                        class="px-4 py-2 bg-bgCard hover:bg-bgHover text-muted border border-border rounded-lg text-sm transition shadow-soft flex items-center gap-2"
                    >
                        <i class="fas fa-sync-alt"></i> Refresh Table
                    </button>
                </div>

                <div class="bg-bgCard rounded-xl2 shadow-card border border-border overflow-hidden">
                    <Table :columns="columns" :data="sales" row-key="id">
                        <template #cell-cashier_name="{ row }">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ row.cashier_name }}</span>
                                <span class="text-xxs text-muted">{{ row.cashier_email }}</span>
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
                            <span class="text-sm text-muted">{{ row.created_at }}</span>
                        </template>
                    </Table>
                </div>

                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                >
                    <div
                        class="bg-bgCard rounded-xl2 w-full max-w-lg shadow-card overflow-hidden animate-in fade-in zoom-in duration-200 border border-border"
                    >
                        <div class="p-5 border-b border-border flex justify-between items-center bg-tableHeader">
                            <h3 class="text-lg font-bold text-gray-800">
                                Sale Transaction Details
                            </h3>
                            <button
                                @click="showModal = false"
                                class="text-muted hover:text-danger transition"
                            >
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-3">
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Transaction ID:</span>
                                        <span class="font-bold text-gray-900">#{{ selectedSale?.id }}</span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Product ID:</span>
                                        <span class="text-gray-700">{{ selectedSale?.product_id }}</span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Product Name:</span>
                                        <span class="font-semibold text-primary">{{ selectedSale?.product_name }}</span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Category:</span>
                                        <span class="px-2 py-0.5 bg-infoSoft text-info rounded text-xs font-bold">
                                            {{ selectedSale?.category }}
                                        </span>
                                    </p>
                                </div>
                                <div class="space-y-3 border-l border-border pl-4">
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Price per Unit:</span>
                                        <span class="text-gray-700">${{ Number(selectedSale?.unit_price).toFixed(2) }}</span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Quantity Sold:</span>
                                        <span class="font-bold text-gray-900">{{ selectedSale?.quantity }}</span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Total Amount:</span>
                                        <span class="text-xl font-bold text-success">
                                            ${{ Number(selectedSale?.total_amount).toFixed(2) }}
                                        </span>
                                    </p>
                                    <p>
                                        <span class="text-muted block text-xxs uppercase tracking-wider font-bold">Date & Time:</span>
                                        <span class="text-gray-600">{{ selectedSale?.created_at }}</span>
                                    </p>
                                </div>
                            </div>

                            <hr class="border-dashed border-border" />

                            <div class="bg-infoSoft p-4 rounded-xl border border-info/20">
                                <p class="text-xxs font-bold text-info uppercase mb-2 tracking-widest">
                                    Processed By
                                </p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-soft">
                                        {{ selectedSale?.cashier_name?.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ selectedSale?.cashier_name }}
                                        </p>
                                        <p class="text-xxs text-muted">
                                            {{ selectedSale?.cashier_email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-tableHeader text-right border-t border-border">
                            <button
                                @click="showModal = false"
                                class="px-6 py-2 bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white rounded-lg transition font-medium shadow-soft text-sm"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import Table from "../../../components/Backend/Table.vue";

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
    components: { BackendLayout, Table },
    setup() {
        const sales = ref<Sale[]>([]);
        const selectedSale = ref<Sale | null>(null);
        const showModal = ref(false);

        const columns = [
            { key: "id", label: "ID" },
            { key: "product_name", label: "Product" },
            { key: "quantity", label: "Qty" },
            { key: "total_amount", label: "Total Amount" },
            { key: "cashier_name", label: "Processed By" },
            { key: "actions", label: "Actions" },
            { key: "created_at", label: "Date & Time" },
        ];

        const fetchSales = async () => {
            try {
                const res = await axios.get("/admin/sale/data");
                sales.value = res.data;
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
        };
    },
});
</script> -->
