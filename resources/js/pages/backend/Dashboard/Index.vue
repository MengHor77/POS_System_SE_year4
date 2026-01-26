<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold text-primary mb-1">
                Sales Analytics
            </h1>
            <p class="mb-6 text-gray-600">
                Overview of sales performance and trends
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <CardDashboard
                    title="Total Revenue"
                    :value="formatPrice(totalRevenue)"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i
                            class="fas fa-dollar-sign text-secondary text-2xl"
                        ></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="Today's Sale"
                    :value="formatPrice(todaysSale)"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i class="fas fa-calendar-day text-info text-2xl"></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="This Month"
                    :value="formatPrice(thisMonth)"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i
                            class="fas fa-calendar-alt text-warning text-2xl"
                        ></i
                    ></template>
                </CardDashboard>

                <CardDashboard
                    title="This Year"
                    :value="formatPrice(thisYear)"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i class="fas fa-calendar text-primary text-2xl"></i
                    ></template>
                </CardDashboard>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <CardDashboard
                    title="Pending Shipments"
                    :value="pendingShipments"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i class="fas fa-truck text-pending text-2xl"></i
                    ></template>
                </CardDashboard>
                <CardDashboard
                    title="Low Stock Alerts"
                    :value="lowStockCount"
                    class="bg-bgCard"
                >
                    <template #icon
                        ><i class="fas fa-bell text-danger text-2xl"></i
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
// New Component Imports
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
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}
</style>
