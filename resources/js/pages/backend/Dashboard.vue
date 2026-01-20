<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <!-- Dashboard Title -->
            <h1 class="text-3xl font-bold text-gray-800">Sales Analytics</h1>
            <p class="mb-6">Overview of sales performance and trends</p>

            <!-- Main Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <Card title="Total Revenue" :value="'$' + totalRevenue">
                    <template #icon>
                        <i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
                    </template>
                </Card>

                <!-- Today's Sale -->
                <Card title="Today's Sale" :value="'$' + todaysSale">
                    <template #icon>
                        <i class="fas fa-calendar-day text-blue-500 text-2xl"></i>
                    </template>
                </Card>

                <!-- This Month -->
                <Card title="This Month" :value="'$' + thisMonth">
                    <template #icon>
                        <i class="fas fa-calendar-alt text-yellow-500 text-2xl"></i>
                    </template>
                </Card>

                <!-- This Year -->
                <Card title="This Year" :value="'$' + thisYear">
                    <template #icon>
                        <i class="fas fa-calendar text-purple-500 text-2xl"></i>
                    </template>
                </Card>
            </div>

            <!-- Optional: Additional Section -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <Card title="Pending Shipments" :value="pendingShipments">
                    <template #icon>
                        <i class="fas fa-truck text-orange-500 text-2xl"></i>
                    </template>
                </Card>

                <Card title="Notifications" :value="notifications">
                    <template #icon>
                        <i class="fas fa-bell text-red-500 text-2xl"></i>
                    </template>
                </Card>
            </div>

            <!-- Sales Graph and Best Selling Product -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sales Graph -->
                <div class="bg-white p-6 rounded-2xl shadow-md h-96 flex items-center justify-center">
                    <span class="text-gray-400">[Sales Graph Placeholder]</span>
                </div>

                <!-- Best Selling Products -->
                <div class="bg-white p-6 rounded-2xl shadow-md h-96 flex flex-col">
                    <h2 class="text-lg font-bold mb-4">Best Selling Products</h2>
                    <ul class="flex-1 overflow-y-auto">
                        <li
                            v-for="product in bestSellingProducts"
                            :key="product.id"
                            class="flex justify-between py-2 border-b"
                        >
                            <span>{{ product.name }}</span>
                            <span>{{ product.sales_count }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="mt-8 bg-white p-6 rounded-2xl shadow-md h-96">
                <h2 class="text-lg font-bold mb-4">Recent Sales</h2>
                <ul class="overflow-y-auto h-80">
                    <li
                        v-for="sale in recentSales"
                        :key="sale.id"
                        class="flex justify-between py-2 border-b"
                    >
                        <span>{{ sale.product_name }}</span>
                        <span>${{ sale.total_amount }}</span>
                        <span>{{ sale.created_at }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import Card from "../../components/Card.vue";
import BackendLayout from "../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "Dashboard",
    components: { Card, BackendLayout },
    setup() {
        const totalRevenue = ref(0);
        const todaysSale = ref(0);
        const thisMonth = ref(0);
        const thisYear = ref(0);
        const pendingShipments = ref(0);
        const notifications = ref(0);
        const bestSellingProducts = ref([]);
        const recentSales = ref([]);

        const fetchDashboardData = async () => {
            try {
                const res = await axios.get('/admin/dashboard/data');
                totalRevenue.value = res.data.totalRevenue;
                todaysSale.value = res.data.todaysSale;
                thisMonth.value = res.data.thisMonth;
                thisYear.value = res.data.thisYear;
                pendingShipments.value = res.data.pendingShipments;
                notifications.value = res.data.notifications;
                bestSellingProducts.value = res.data.bestSellingProducts || [];
                recentSales.value = res.data.recentSales || [];
            } catch (err) {
                console.error("Failed to fetch dashboard data", err);
            }
        };

        onMounted(() => {
            fetchDashboardData();
        });

        return {
            totalRevenue,
            todaysSale,
            thisMonth,
            thisYear,
            pendingShipments,
            notifications,
            bestSellingProducts,
            recentSales,
        };
    },
});
</script>

<style scoped>
/* Optional scrollbar styling for long lists */
ul::-webkit-scrollbar {
    width: 6px;
}
ul::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 3px;
}
</style>
