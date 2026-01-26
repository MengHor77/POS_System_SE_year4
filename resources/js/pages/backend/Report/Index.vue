<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <div
                class="flex justify-between items-center mb-8 border-b border-border pb-4"
            >
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                        Inventory Report
                    </h1>
                    <p class="text-muted text-sm">
                        Generated on {{ reportDate }}
                    </p>
                </div>
                <div class="flex items-center gap-4 no-print">
                    <SearchInput
                        v-model="searchQuery"
                        @search="fetchData(1)"
                        placeholder="Search orders..."
                    />
                    <button
                        @click="printReport"
                        class="bg-primary hover:bg-darkSoft text-white px-5 py-2 rounded-xl shadow-card transition-all flex items-center"
                    >
                        <i class="fas fa-print mr-2"></i> Print Report
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p
                        class="text-xs text-muted uppercase font-bold tracking-wider mb-1"
                    >
                        Total Revenue
                    </p>
                    <p class="text-3xl font-extrabold text-primary">
                        {{ formatPrice(totalRevenue) }}
                    </p>
                </div>
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p
                        class="text-xs text-muted uppercase font-bold tracking-wider mb-1"
                    >
                        Pending Shipments
                    </p>
                    <p class="text-3xl font-extrabold text-highlight">
                        {{ pendingShipments }}
                    </p>
                </div>
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p
                        class="text-xs text-muted uppercase font-bold tracking-wider mb-1"
                    >
                        Low Stock Items
                    </p>
                    <p class="text-3xl font-extrabold text-danger">
                        {{ lowStockCount }}
                    </p>
                </div>
            </div>

            <div
                class="bg-bgCard border border-border rounded-xl2 overflow-hidden shadow-card"
            >
                <div class="bg-tableHeader p-4 border-b border-border">
                    <h3 class="font-bold text-dark">Recent Purchase Orders</h3>
                </div>
                <Table
                    :columns="tableColumns"
                    :data="pendingOrders"
                    rowKey="id"
                    class="w-full"
                >
                    <template #cell-status="{ row }">
                        <span
                            class="px-3 py-1 rounded-full text-xxs font-bold uppercase tracking-widest border"
                            :class="
                                row.status === 'received'
                                    ? 'bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-bold'
                                    : 'bg-orange-100 text-orange-700 px-2 py-1 rounded-lg text-xs font-bold'
                            "
                        >
                            {{ row.status || "pending" }}
                        </span>
                    </template>
                </Table>
            </div>

            <div class="no-print mt-6">
                <Pigination
                    :currentPage="pagination.current_page"
                    :lastPage="pagination.last_page"
                    :total="pagination.total"
                    :perPage="pagination.per_page"
                    @page-change="fetchData"
                />
            </div>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Table from "../../../components/Backend/Table.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import Pigination from "../../../components/Backend/Pigination.vue";

export default defineComponent({
    name: "ReportIndex",
    components: { BackendLayout, Table, SearchInput, Pigination },
    setup() {
        const totalRevenue = ref(0);
        const pendingShipments = ref(0);
        const lowStockCount = ref(0);
        const pendingOrders = ref([]);
        const reportDate = ref("");
        const searchQuery = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 5,
        });

        const tableColumns = [
            { key: "id", label: "Order ID" },
            { key: "created_at", label: "Date" },
            { key: "supplier_name", label: "Supplier" },
            { key: "status", label: "Status" },
        ];

        const fetchData = async (page = 1) => {
            try {
                const response = await axios.get("/admin/report/data", {
                    params: {
                        page: page,
                        search: searchQuery.value,
                    },
                });
                const res = response.data;

                totalRevenue.value = res.dash.totalRevenue;
                pendingShipments.value = res.dash.pendingShipments;
                lowStockCount.value = res.notify.total;
                reportDate.value = res.reportDate;
                pendingOrders.value = res.orders.data;

                pagination.value = {
                    current_page: res.orders.current_page,
                    last_page: res.orders.last_page,
                    total: res.orders.total,
                    per_page: res.orders.per_page,
                };
            } catch (err) {
                console.error("Report Fetch Error:", err);
            }
        };

        const formatPrice = (val: number) => {
            return (
                "$" +
                Number(val).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })
            );
        };

        const printReport = () => window.print();

        onMounted(() => fetchData(1));

        return {
            totalRevenue,
            pendingShipments,
            lowStockCount,
            pendingOrders,
            reportDate,
            tableColumns,
            searchQuery,
            pagination,
            fetchData,
            formatPrice,
            printReport,
        };
    },
});
</script>

<style scoped>
@media print {
    .no-print {
        display: none !important;
    }
    /* Ensure background colors show in PDF */
    .bg-bgCard,
    .bg-gray-50,
    .bg-bgMain {
        background-color: white !important;
        -webkit-print-color-adjust: exact;
    }
    .shadow-card,
    .shadow-soft {
        box-shadow: none !important;
    }
}
</style>
