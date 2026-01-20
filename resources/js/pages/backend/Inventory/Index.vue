
<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-primary">
                    Inventory Management
                </h1>
                <p class="text-muted mt-1">
                    Manually control and monitor product stock levels
                </p>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                    <h3 class="text-sm text-muted mb-1">Stock In</h3>
                    <p class="text-lg font-semibold">
                        Add products from suppliers
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                    <h3 class="text-sm text-muted mb-1">Stock Out</h3>
                    <p class="text-lg font-semibold">
                        Adjust damaged or lost items
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                    <h3 class="text-sm text-muted mb-1">Current Stock</h3>
                    <p class="text-lg font-semibold">
                        View real-time stock levels
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
                    <h3 class="text-sm text-muted mb-1">Low Stock</h3>
                    <p class="text-lg font-semibold">
                        Monitor products running low
                    </p>
                </div>
            </div>

            <!-- Inventory Policy
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-dark">
                    Inventory Management Process
                </h2>

                <ul class="space-y-3 text-dark">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>
                            <strong>Stock-In:</strong> Add inventory when new
                            products arrive from suppliers.
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>
                            <strong>Stock-Out:</strong> Manually reduce stock for
                            damaged, expired, or lost items.
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>
                            <strong>View Stock:</strong> Check current product
                            quantities at any time.
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>
                            <strong>Low-Stock Monitoring:</strong> Integrated with
                            notifications for timely restocking.
                        </span>
                    </li>
                </ul>

                <div
                    class="mt-6 p-4 rounded-lg bg-yellow-50 border border-yellow-300 text-yellow-800"
                >
                    📌 <strong>Important:</strong> All inventory changes are
                    <strong>manual and admin-authorized</strong> to prevent
                    misuse or stock manipulation.
                </div>
            </div> -->
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "InventoryIndex",
    components: {
        BackendLayout,
    },
});
</script>

<style scoped>
/* Optional future custom styles */
</style>
