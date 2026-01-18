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
                        <i
                            class="fas fa-dollar-sign text-green-500 text-2xl"
                        ></i>
                    </template>
                </Card>

                <!-- Today's Sale -->
                <Card title="Today's Sale" :value="'$' + todaysSale">
                    <template #icon>
                        <i
                            class="fas fa-calendar-day text-blue-500 text-2xl"
                        ></i>
                    </template>
                </Card>

                <!-- This Month -->
                <Card title="This Month" :value="'$' + thisMonth">
                    <template #icon>
                        <i
                            class="fas fa-calendar-alt text-yellow-500 text-2xl"
                        ></i>
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
                <div
                    class="bg-white p-6 rounded-2xl shadow-md h-96 flex items-center justify-center"
                >
                    <span class="text-gray-400">[Sales Graph Placeholder]</span>
                </div>

                <!-- Best Selling Products -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-md h-96 flex items-center justify-center"
                >
                    <span class="text-gray-400"
                        >[Best Selling Products Placeholder]</span
                    >
                </div>
            </div>
            <div
                class="mt-8 bg-white p-6 rounded-2xl shadow-md items-center justify-center h-96"
            >
                <h1>Recent Sales</h1>
            </div>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Card from "../../components/Card.vue";
import BackendLayout from "../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "Dashboard",
    components: { Card, BackendLayout },
    setup() {
        // Example data, replace with API calls later
        const totalRevenue = ref(50000);
        const todaysSale = ref(1200);
        const thisMonth = ref(15000);
        const thisYear = ref(50000);
        const pendingShipments = ref(12);
        const notifications = ref(5);

        return {
            totalRevenue,
            todaysSale,
            thisMonth,
            thisYear,
            pendingShipments,
            notifications,
        };
    },
});
</script>
