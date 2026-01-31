<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen no-print-container">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-border pb-4 gap-4"
            >
                <div>
                    <h1
                        class="text-2xl md:text-3xl font-bold text-primary tracking-tight"
                    >
                        Sales Transaction Report
                    </h1>
                    <p class="text-muted text-sm">
                        Generated on {{ reportDate }}
                    </p>
                </div>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-8"
            >
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p class="text-xs text-muted uppercase font-bold mb-1">
                        Total Revenue
                    </p>
                    <p class="text-2xl font-extrabold text-primary">
                        {{ formatPrice(totalRevenue) }}
                    </p>
                </div>
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p class="text-xs text-muted uppercase font-bold mb-1">
                        Today's Sales
                    </p>
                    <p class="text-2xl font-extrabold text-secondary">
                        {{ formatPrice(todaysSale) }}
                    </p>
                </div>
                <div
                    class="p-6 border border-border rounded-xl2 bg-bgCard shadow-soft"
                >
                    <p class="text-xs text-muted uppercase font-bold mb-1">
                        Totall Transactions
                    </p>
                    <p class="text-2xl font-extrabold text-info">
                        {{ pagination.total }}
                    </p>
                </div>
            </div>

            <div
                class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4 pb-6"
            >
                <div class="w-full lg:w-1/3">
                    <SearchInput
                        v-model="searchQuery"
                        @search="fetchData(1)"
                        placeholder="Search ID, Cashier, or Product..."
                    />
                </div>
                <div class="flex-1">
                    <DateRangeFilter
                        v-model:startDate="startDate"
                        v-model:endDate="endDate"
                        @filter="fetchData(1)"
                    />
                </div>

                <SaveFileToTxt
                    :transactions="allTransactionsForExport"
                    :reportDate="reportDate"
                    :totalRevenue="totalRevenue"
                    @click="prepareDataForExport"
                />
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
                <Table :columns="tableColumns" :data="transactions" rowKey="id">
                    <template #cell-id="{ row }">
                        <span
                            class="font-mono text-xs font-bold text-gray-600"
                            >{{ row.id }}</span
                        >
                    </template>
                    <template #cell-cashier_email="{ row }">
                        <div class="flex flex-col">
                            <span class="font-semibold text-gray-900">{{
                                row.cashier_name
                            }}</span>
                            <span class="text-[13px] text-muted">{{
                                row.cashier_email
                            }}</span>
                        </div>
                    </template>
                    <template #cell-product_name="{ row }">
                        <div
                            v-for="(item, i) in row.products"
                            :key="i"
                            class="font-semibold border-b border-gray-100 last:border-0 py-1"
                        >
                            <span class="font-semibold text-primary">{{
                                item.name
                            }}</span>
                            <span class="font-semibold text-success pl-3"
                                >x{{ item.qty }}</span
                            >
                        </div>
                    </template>
                    <template #cell-unit_price="{ row }">
                        <div
                            v-for="(item, i) in row.products"
                            :key="i"
                            class="font-semibold py-1"
                        >
                            {{ formatPrice(item.price) }}
                        </div>
                    </template>
                    <template #cell-total_amount="{ row }">
                        <span class="font-extrabold text-success">{{
                            formatPrice(row.total_amount)
                        }}</span>
                    </template>
                </Table>
            </div>

            <div class="mt-6 flex justify-center">
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
import DateRangeFilter from "../../../components/Backend/DateRangeFilter.vue";
import SaveFileToTxt from "../../../components/Backend/saveFiletoTxt.vue";

export default defineComponent({
    components: {
        BackendLayout,
        Table,
        SearchInput,
        Pigination,
        DateRangeFilter,
        SaveFileToTxt,
    },
    setup() {
        const transactions = ref([]);
        const allTransactionsForExport = ref([]);
        const totalRevenue = ref(0);
        const todaysSale = ref(0);
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
            { key: "product_name", label: "Items" },
            { key: "unit_price", label: "Price/Unit" },
            { key: "total_amount", label: "Total" },
            { key: "date_formatted", label: "Date" },
        ];

        const fetchData = async (page = 1, isExport = false) => {
            try {
                const params = {
                    page,
                    search: searchQuery.value,
                    start_date: startDate.value,
                    end_date: endDate.value,
                    per_page: isExport ? "all" : 10,
                };
                const { data } = await axios.get("/admin/report/data", {
                    params,
                });

                if (isExport) {
                    allTransactionsForExport.value = data.orders.data;
                } else {
                    transactions.value = data.orders.data;
                    totalRevenue.value = data.dash.totalRevenue;
                    todaysSale.value = data.dash.todaysSale;
                    reportDate.value = data.reportDate;
                    pagination.value = {
                        current_page: data.orders.current_page,
                        last_page: data.orders.last_page,
                        total: data.orders.total,
                        per_page: data.orders.per_page,
                    };
                }
            } catch (e) {
                console.error(e);
            }
        };

        const prepareDataForExport = () => fetchData(1, true);
        const formatPrice = (val: any) =>
            "$" +
            Number(val).toLocaleString(undefined, { minimumFractionDigits: 2 });

        onMounted(() => fetchData(1));

        return {
            transactions,
            allTransactionsForExport,
            totalRevenue,
            todaysSale,
            reportDate,
            searchQuery,
            startDate,
            endDate,
            pagination,
            tableColumns,
            fetchData,
            prepareDataForExport,
            formatPrice,
        };
    },
});
</script>
