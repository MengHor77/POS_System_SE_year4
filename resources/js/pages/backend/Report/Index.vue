<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <div
                class="flex justify-between items-center mb-8 border-b border-border pb-4 no-print"
            >
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                        Sales Transaction Report
                    </h1>
                    <p class="text-muted text-sm">
                        Generated on {{ reportDate }}
                    </p>
                </div>
            </div>

            <div id="printable-area">
                <div class="print-only mb-6">
                    <h1 class="text-2xl font-bold">Sales Transaction Report</h1>
                    <p class="text-sm">Generated on {{ reportDate }}</p>
                    <hr class="my-4 border-gray-300" />
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
                            Today's Sales
                        </p>
                        <p class="text-3xl font-extrabold text-secondary">
                            {{ formatPrice(todaysSale) }}
                        </p>
                    </div>
                    <div
                        class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                    >
                        <p
                            class="text-xs text-muted uppercase font-bold tracking-wider mb-1"
                        >
                            Transactions Count
                        </p>
                        <p class="text-3xl font-extrabold text-info">
                            {{ pagination.total }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pb-6 no-print">
                    <SearchInput
                        v-model="searchQuery"
                        @search="fetchData(1)"
                        placeholder="Search ID, Cashier name , email..."
                    />
                    <DateRangeFilter
                        v-model:startDate="startDate"
                        v-model:endDate="endDate"
                        @filter="fetchData(1)"
                    />
                    <button
                        @click="printReport"
                        class="bg-primary hover:bg-darkSoft text-white px-5 py-2 rounded-xl shadow-card transition-all flex items-center"
                    >
                        <i class="fas fa-print mr-2"></i> Print Report
                    </button>
                </div>

                <div
                    class="bg-bgCard border border-border rounded-xl2 overflow-hidden shadow-card"
                >
                    <div class="bg-tableHeader p-4 border-b border-border">
                        <h3 class="font-bold text-dark">
                            Grouped Sales Transactions
                        </h3>
                    </div>
                    <Table
                        :columns="tableColumns"
                        :data="transactions"
                        rowKey="id"
                        class="w-full"
                    >
                        <template #cell-id="{ row }">
                            <span
                                class="font-mono text-xs font-bold text-gray-600"
                                >#{{ row.id }}</span
                            >
                        </template>
                        <template #cell-cashier_email="{ row }">
                            <div class="flex flex-col py-2">
                                <span class="font-bold text-gray-900">{{
                                    row.cashier_name || "N/A"
                                }}</span>
                                <span class="text-xxs text-muted">{{
                                    row.cashier_email || "N/A"
                                }}</span>
                            </div>
                        </template>
                        <template #cell-product_name="{ row }">
                            <div class="flex flex-col gap-1 py-2">
                                <div
                                    v-for="(item, index) in row.products"
                                    :key="index"
                                    class="text-xs border-b border-gray-100 last:border-0 pb-1"
                                >
                                    <span class="font-semibold text-primary">{{
                                        item.name
                                    }}</span>
                                    <span class="text-muted ml-2 font-bold"
                                        >x{{ item.qty }}</span
                                    >
                                </div>
                            </div>
                        </template>
                        <template #cell-unit_price="{ row }">
                            <div class="flex flex-col gap-1 py-2">
                                <div
                                    v-for="(item, index) in row.products"
                                    :key="index"
                                    class="text-xs pb-1 text-gray-600"
                                >
                                    {{ formatPrice(item.price) }}
                                </div>
                            </div>
                        </template>
                        <template #cell-total_amount="{ row }">
                            <span class="font-extrabold text-success text-lg">{{
                                formatPrice(row.total_amount || 0)
                            }}</span>
                        </template>
                        <template #cell-date_formatted="{ row }">
                            <div class="text-sm text-gray-700 py-2">
                                <i class="far fa-clock mr-1 text-muted"></i>
                                {{ row.date_formatted }}
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <div class="no-print mt-6">
                <Pigination
                    v-if="pagination.total > 0"
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
import DateRangeFilter from "../../../components/Backend/DateRangeFilter.vue"; // Import this

export default defineComponent({
    name: "ReportIndex",
    components: {
        BackendLayout,
        Table,
        SearchInput,
        Pigination,
        DateRangeFilter,
    },
    setup() {
        const totalRevenue = ref(0);
        const todaysSale = ref(0);
        const transactions = ref([]);
        const reportDate = ref("");
        const searchQuery = ref("");
        const startDate = ref("");
        const endDate = ref("");

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        });

        const tableColumns = [
            { key: "id", label: "ID" },
            { key: "cashier_email", label: "Cashier" },
            { key: "product_name", label: "Items Sold" },
            { key: "unit_price", label: "Price/Unit" },
            { key: "total_amount", label: "Transaction Total" },
            { key: "date_formatted", label: "Date & Time" },
        ];

        const fetchData = async (page = 1) => {
            try {
                const response = await axios.get("/admin/report/data", {
                    params: {
                        page: page,
                        search: searchQuery.value,
                        start_date: startDate.value,
                        end_date: endDate.value,
                    },
                });
                const res = response.data;
                totalRevenue.value = res.dash?.totalRevenue || 0;
                todaysSale.value = res.dash?.todaysSale || 0;
                reportDate.value = res.reportDate || "";
                transactions.value = res.orders?.data || [];
                pagination.value = {
                    current_page: Number(res.orders?.current_page) || 1,
                    last_page: Number(res.orders?.last_page) || 1,
                    total: Number(res.orders?.total) || 0,
                    per_page: Number(res.orders?.per_page) || 10,
                };
            } catch (err) {
                console.error("Report Fetch Error:", err);
            }
        };

        const formatPrice = (val: any) => {
            const num = Number(val);
            return isNaN(num)
                ? "$0.00"
                : "$" +
                      num.toLocaleString(undefined, {
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2,
                      });
        };

        const printReport = () => window.print();

        onMounted(() => fetchData(1));

        return {
            totalRevenue,
            todaysSale,
            transactions,
            reportDate,
            tableColumns,
            searchQuery,
            pagination,
            startDate,
            endDate,
            fetchData,
            formatPrice,
            printReport,
        };
    },
});
</script>

<style scoped>
.print-only {
    display: none;
}
@media print {
    :deep(aside),
    :deep(nav),
    :deep(header),
    :deep(.sidebar),
    :deep(.top-navbar),
    .no-print {
        display: none !important;
    }
    .p-6.bg-bgMain {
        padding: 0 !important;
        background: white !important;
    }
    #printable-area {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
    }
    .grid {
        display: flex !important;
        flex-direction: row !important;
        gap: 10px !important;
    }
    .grid > div {
        flex: 1 !important;
        border: 1px solid #eee !important;
    }
    .text-primary,
    .text-secondary,
    .text-success,
    .text-info {
        color: #000 !important;
    }
    .print-only {
        display: block !important;
        text-align: center;
        border-bottom: 2px solid #333;
    }
}
</style>
