<template>
    <BackendLayout>
        <template #default>
            <div class="container mx-auto p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-primary">
                        Sales Management
                    </h1>
                    <button
                        @click="fetchSales"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition"
                    >
                        🔄 Refresh Table
                    </button>
                </div>

                <Table :columns="columns" :data="sales" row-key="id">
                    <template #cell-cashier_name="{ row }">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900">{{
                                row.cashier_name
                            }}</span>
                            <span class="text-xs text-gray-500">{{
                                row.cashier_email
                            }}</span>
                        </div>
                    </template>

                    <template #cell-total_amount="{ row }">
                        <span class="font-semibold text-green-600">
                            ${{ Number(row.total_amount).toFixed(2) }}
                        </span>
                    </template>

                    <template #cell-actions="{ row }">
                        <button
                            @click="openDetail(row)"
                            class="px-3 py-1 bg-info text-white text-xs rounded-md hover:bg-info/90 transition flex items-center gap-1"
                        >
                            <i class="fas fa-eye"></i> View
                        </button>
                    </template>

                    <template #cell-created_at="{ row }">
                        <span class="text-sm text-gray-600">{{
                            row.created_at
                        }}</span>
                    </template>
                </Table>

                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                >
                    <div
                        class="bg-white rounded-2xl w-full max-w-lg shadow-xl overflow-hidden animate-in fade-in zoom-in duration-200"
                    >
                        <div
                            class="p-5 border-b flex justify-between items-center bg-gray-50"
                        >
                            <h3 class="text-lg font-bold text-gray-800">
                                Sale Transaction Details
                            </h3>
                            <button
                                @click="showModal = false"
                                class="text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-3">
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Transaction ID:</span
                                        >
                                        <span class="font-bold"
                                            >#{{ selectedSale?.id }}</span
                                        >
                                    </p>
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Product ID:</span
                                        >
                                        {{ selectedSale?.product_id }}
                                    </p>
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
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
                                            class="text-gray-500 block text-xs"
                                            >Category:</span
                                        >
                                        <span
                                            class="px-2 py-0.5 bg-gray-100 rounded text-gray-700 font-medium"
                                            >{{ selectedSale?.category }}</span
                                        >
                                    </p>
                                </div>
                                <div class="space-y-3">
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Price per Unit:</span
                                        >
                                        ${{
                                            Number(
                                                selectedSale?.unit_price,
                                            ).toFixed(2)
                                        }}
                                    </p>
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Quantity Sold:</span
                                        >
                                        {{ selectedSale?.quantity }}
                                    </p>
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Total Amount:</span
                                        >
                                        <span
                                            class="text-lg font-bold text-green-600"
                                            >${{
                                                Number(
                                                    selectedSale?.total_amount,
                                                ).toFixed(2)
                                            }}</span
                                        >
                                    </p>
                                    <p>
                                        <span
                                            class="text-gray-500 block text-xs"
                                            >Date & Time:</span
                                        >
                                        {{ selectedSale?.created_at }}
                                    </p>
                                </div>
                            </div>

                            <hr class="border-dashed" />

                            <div
                                class="bg-blue-50 p-4 rounded-xl border border-blue-100"
                            >
                                <p
                                    class="text-xs font-bold text-blue-800 uppercase mb-2"
                                >
                                    Processed By
                                </p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold"
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
                                        <p class="text-xs text-gray-600">
                                            {{ selectedSale?.cashier_email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 text-right">
                            <button
                                @click="showModal = false"
                                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition font-medium"
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
</script>
