<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-primary tracking-tight">
                    Sales Analytics
                </h1>
                <p class="text-muted">
                    Real-time overview of your POS performance
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <CardDashboard
                    title="Total Revenue"
                    :value="formatPrice(totalRevenue)"
                    class="shadow-card border-t-4 border-primary"
                >
                    <template #icon
                        ><i class="fas fa-wallet text-primary text-2xl"></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="Today's Sale"
                    :value="formatPrice(todaysSale)"
                    class="shadow-card border-t-4 border-secondary"
                >
                    <template #icon
                        ><i
                            class="fas fa-cash-register text-secondary text-2xl"
                        ></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="This Month"
                    :value="formatPrice(thisMonth)"
                    class="shadow-card border-t-4 border-warning"
                >
                    <template #icon
                        ><i
                            class="fas fa-calendar-check text-warning text-2xl"
                        ></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="This Year"
                    :value="formatPrice(thisYear)"
                    class="shadow-card border-t-4 border-info"
                >
                    <template #icon
                        ><i class="fas fa-chart-bar text-info text-2xl"></i
                    ></template>
                </CardDashboard>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <CardDashboard
                    title="Pending Shipments"
                    :value="pendingShipments"
                    class="bg-bgCard shadow-soft"
                >
                    <template #icon>
                        <i class="fas fa-truck text-pending text-2xl"></i>
                    </template>
                </CardDashboard>
                <CardDashboard
                    title="Low Stock Alerts"
                    :value="lowStockCount"
                    class="bg-bgCard shadow-soft"
                >
                    <template #icon
                        ><i
                            class="fas fa-exclamation-triangle text-danger text-2xl"
                        ></i
                    ></template>
                </CardDashboard>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <SalesGraph />
                <BestSelling :products="bestSellingProducts" />
            </div>

            <RecentSales :sales="recentSales" />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import CardDashboard from "../../../components/Backend/CardDashboard.vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import SalesGraph from "../../../components/Backend/SalesGraph.vue";
import BestSelling from "../../../components/Backend/BestSelling.vue";
import RecentSales from "../../../components/Backend/RecentSales.vue";

export default defineComponent({
    name: "Dashboard",
    components: {
        CardDashboard,
        BackendLayout,
        SalesGraph,
        BestSelling,
        RecentSales,
    },
    setup() {
        const totalRevenue = ref(0);
        const todaysSale = ref(0);
        const thisMonth = ref(0);
        const thisYear = ref(0);
        const pendingShipments = ref(0);
        const lowStockCount = ref(0);
        const bestSellingProducts = ref([]);
        const recentSales = ref([]);
        const formatPrice = (val: number) => {
            return (
                "$" +
                Number(val).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                })
            );
        };

        const fetchData = async () => {
            try {
                const [dash, notify] = await Promise.all([
                    axios.get("/admin/dashboard/data"),
                    axios.get("/admin/notification/count"),
                ]);

                totalRevenue.value = dash.data.totalRevenue ?? 0;
                todaysSale.value = dash.data.todaysSale ?? 0;
                thisMonth.value = dash.data.thisMonth ?? 0;
                thisYear.value = dash.data.thisYear ?? 0;
                pendingShipments.value = dash.data.pendingShipments ?? 0;
                bestSellingProducts.value = dash.data.bestSellingProducts ?? [];
                recentSales.value = dash.data.recentSales ?? [];
                lowStockCount.value = notify.data.total ?? 0;
            } catch (err) {
                console.error("Dashboard Fetch Error:", err);
            }
        };

        onMounted(fetchData);

        return {
            totalRevenue,
            todaysSale,
            thisMonth,
            thisYear,
            pendingShipments,
            lowStockCount,
            bestSellingProducts,
            recentSales,
            formatPrice,
        };
    },
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 20px;
}
</style>
