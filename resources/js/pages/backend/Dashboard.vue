<template>
  <BackendLayout>
    <div class="p-6 bg-bgMain min-h-screen">
      <!-- Dashboard Title -->
      <h1 class="text-3xl font-bold text-primary mb-1">Sales Analytics</h1>
      <p class="mb-6 text-gray-600">Overview of sales performance and trends</p>

      <!-- Main Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <Card class="bg-bgCard" title="Total Revenue" :value="'$' + totalRevenue">
          <template #icon>
            <i class="fas fa-dollar-sign text-secondary text-2xl"></i>
          </template>
        </Card>

        <!-- Today's Sale -->
        <Card class="bg-bgCard" title="Today's Sale" :value="'$' + todaysSale">
          <template #icon>
            <i class="fas fa-calendar-day text-info text-2xl"></i>
          </template>
        </Card>

        <!-- This Month -->
        <Card class="bg-bgCard" title="This Month" :value="'$' + thisMonth">
          <template #icon>
            <i class="fas fa-calendar-alt text-warning text-2xl"></i>
          </template>
        </Card>

        <!-- This Year -->
        <Card class="bg-bgCard" title="This Year" :value="'$' + thisYear">
          <template #icon>
            <i class="fas fa-calendar text-primary text-2xl"></i>
          </template>
        </Card>
      </div>

      <!-- Additional Section -->
      <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card class="bg-bgCard" title="Pending Shipments" :value="pendingShipments">
          <template #icon>
            <i class="fas fa-truck text-pending text-2xl"></i>
          </template>
        </Card>

        <Card class="bg-bgCard" title="Notifications" :value="notifications">
          <template #icon>
            <i class="fas fa-bell text-danger text-2xl"></i>
          </template>
        </Card>
      </div>

      <!-- Sales Graph & Best Selling Products -->
      <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Graph -->
        <div class="bg-bgCard p-6 rounded-2xl shadow-md h-96 flex items-center justify-center">
          <span class="text-gray-400">[Sales Graph Placeholder]</span>
        </div>

        <!-- Best Selling Products -->
        <div class="bg-bgCard p-6 rounded-2xl shadow-md h-96 flex flex-col">
          <h2 class="text-lg font-bold mb-4 text-primary">Best Selling Products</h2>
          <ul class="flex-1 overflow-y-auto">
            <li
              v-for="product in bestSellingProducts"
              :key="product.id"
              class="flex justify-between py-2 border-b border-border"
            >
              <span class="text-gray-700">{{ product.name }}</span>
              <span class="font-semibold text-secondary">{{ product.sales_count }}</span>
            </li>
            <li v-if="bestSellingProducts.length === 0" class="text-center text-gray-400 py-4">
              No best selling products yet.
            </li>
          </ul>
        </div>
      </div>

      <!-- Recent Sales -->
      <div class="mt-8 bg-bgCard p-6 rounded-2xl shadow-md h-96">
        <h2 class="text-lg font-bold mb-4 text-primary">Recent Sales</h2>
        <ul class="overflow-y-auto h-80">
          <li
            v-for="sale in recentSales"
            :key="sale.id"
            class="flex justify-between py-2 border-b border-border"
          >
            <span class="text-gray-700">{{ sale.product_name }}</span>
            <span class="font-semibold text-secondary">${{ sale.total_amount }}</span>
            <span class="text-gray-500">{{ sale.created_at }}</span>
          </li>
          <li v-if="recentSales.length === 0" class="text-center text-gray-400 py-4">
            No recent sales yet.
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

interface BestSellingProduct {
  id: number;
  name: string;
  sales_count: number;
}

interface RecentSale {
  id: number;
  product_name: string;
  total_amount: number;
  created_at: string;
}

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
    const bestSellingProducts = ref<BestSellingProduct[]>([]);
    const recentSales = ref<RecentSale[]>([]);

    const fetchDashboardData = async () => {
      try {
        const res = await axios.get("/admin/dashboard/data");
        totalRevenue.value = res.data.totalRevenue ?? 0;
        todaysSale.value = res.data.todaysSale ?? 0;
        thisMonth.value = res.data.thisMonth ?? 0;
        thisYear.value = res.data.thisYear ?? 0;
        pendingShipments.value = res.data.pendingShipments ?? 0;
        notifications.value = res.data.notifications ?? 0;
        bestSellingProducts.value = res.data.bestSellingProducts ?? [];
        recentSales.value = res.data.recentSales ?? [];
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
/* Scrollbar styling for long lists */
ul::-webkit-scrollbar {
  width: 6px;
}
ul::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}
</style>
