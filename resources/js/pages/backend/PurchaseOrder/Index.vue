<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4"
            >
                <h1 class="text-2xl md:text-3xl font-bold text-primary">
                    Purchase Orders Management
                </h1>
                <button
                    @click="showCreate = true"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-dark text-white hover:bg-darkSoft transition shadow-md flex items-center justify-center gap-2"
                >
                    <i class="fas fa-plus"></i> New Purchase Order
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 w-full pb-6">
                <div class="w-full lg:w-1/3">
                    <SearchInput
                        v-model="filterText"
                        placeholder="Search ID, Product or Supplier..."
                        @search="fetch(1)"
                    />
                </div>

                <div class="flex flex-col md:flex-row gap-4 flex-1">
                    <div class="flex-1">
                        <DateRangeFilter
                            v-model:startDate="startDate"
                            v-model:endDate="endDate"
                            @filter="fetch(1)"
                        />
                    </div>
                    <div class="w-full md:w-48">
                        <FilterStatus
                            v-model="selectedStatus"
                            @update:modelValue="fetch(1)"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>

            <FlashMessage
                v-if="flash"
                :message="flash.message"
                :type="flash.type"
            />

            <div
                class="bg-white rounded-2xl shadow-sm overflow-hidden mt-2 border border-gray-100"
            >
                <div class="overflow-x-auto">
                    <Table
                        :columns="columns"
                        :data="purchaseOrders"
                        class="min-w-full"
                    >
                        <template #cell-product="{ row }">
                            <span
                                class="font-medium text-gray-700 whitespace-nowrap"
                                >{{ row.product?.name }}</span
                            >
                        </template>

                        <template #cell-status="{ row }">
                            <span
                                :class="
                                    row.status === 'received'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-orange-100 text-orange-700'
                                "
                                class="px-2 py-1 rounded-lg text-xs font-bold capitalize whitespace-nowrap"
                            >
                                {{ row.status || "Pending" }}
                            </span>
                        </template>

                        <template #cell-created_at="{ row }">
                            <span
                                class="text-sm text-gray-500 whitespace-nowrap"
                                >{{ row.created_at }}</span
                            >
                        </template>

                        <template #cell-actions="{ row }">
                            <div class="flex items-center gap-2">
                                <template v-if="row.status !== 'received'">
                                    <button
                                        @click="receiveOrder(row.id)"
                                        class="px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition flex items-center gap-1 shadow-sm whitespace-nowrap"
                                    >
                                        <i
                                            class="fas fa-check-circle text-sm"
                                        ></i
                                        ><span class="text-xs">Receive</span>
                                    </button>
                                    <button
                                        @click="openEdit(row)"
                                        class="px-3 py-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                    >
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button
                                        @click="deletePO(row.id)"
                                        class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                                    >
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </template>
                                <span
                                    v-else
                                    class="text-gray-400 text-xs italic flex items-center gap-1 whitespace-nowrap"
                                >
                                    <i class="fas fa-lock"></i> Locked
                                </span>
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <div class="flex justify-center sm:justify-start">
                <Pigination
                    v-if="total > perPage"
                    class="mt-6"
                    :current-page="currentPage"
                    :last-page="lastPage"
                    :total="total"
                    :per-page="perPage"
                    @page-change="fetch"
                />
            </div>

            <PurchaseOrderCreate
                v-if="showCreate"
                @close="showCreate = false"
                @saved="onCreated"
            />
            <PurchaseOrderEdit
                v-if="showEdit && editId !== null"
                :order-id="editId"
                @close="showEdit = false"
                @updated="onUpdated"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import PurchaseOrderCreate from "./Create.vue";
import PurchaseOrderEdit from "./Edit.vue";
import FilterStatus from "./FilterPendingOrRecievedStatus.vue";
import DateRangeFilter from "../../../components/Backend/DateRangeFilter.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import FlashMessage from "../../../components/Backend/FlassMessage.vue";
import Table from "../../../components/Backend/Table.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";

export default defineComponent({
    components: {
        BackendLayout,
        PurchaseOrderCreate,
        PurchaseOrderEdit,
        FilterStatus,
        DateRangeFilter,
        SearchInput,
        Pigination,
        FlashMessage,
        Table,
    },
    setup() {
        const purchaseOrders = ref<any[]>([]);
        const showCreate = ref(false);
        const showEdit = ref(false);
        const editId = ref<number | null>(null);
        const filterText = ref("");
        const selectedStatus = ref("");
        const startDate = ref("");
        const endDate = ref("");
        const currentPage = ref(1);
        const lastPage = ref(1);
        const perPage = ref(5);
        const total = ref(0);

        const columns = [
            { key: "id", label: "ID" },
            { key: "supplier_name", label: "Supplier Name" },
            { key: "product", label: "Product" },
            { key: "quantity", label: "Qty" },
            { key: "status", label: "Status" },
            { key: "created_at", label: "Date & Time" },
            { key: "actions", label: "Actions" },
        ];

        const flash = ref<{
            message: string;
            type: "success" | "error";
        } | null>(null);

        const showFlash = (
            message: string,
            type: "success" | "error" = "success",
        ) => {
            flash.value = { message, type };
            setTimeout(() => {
                flash.value = null;
            }, 3000);
        };

        const fetch = async (page = 1) => {
            try {
                const res = await axios.get("/admin/purchase-order/data", {
                    params: {
                        search: filterText.value,
                        status: selectedStatus.value,
                        start_date: startDate.value,
                        end_date: endDate.value,
                        page,
                        per_page: perPage.value,
                    },
                });
                purchaseOrders.value = res.data.data;
                currentPage.value = res.data.current_page;
                lastPage.value = res.data.last_page;
                total.value = res.data.total;
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        const receiveOrder = async (id: number) => {
            if (
                !confirm(
                    "Confirm receipt of goods? This will update product stock levels.",
                )
            )
                return;
            try {
                const res = await axios.post(
                    `/admin/purchase-order/${id}/receive`,
                );
                window.dispatchEvent(new Event("stock-updated"));
                showFlash(res.data.message);
                fetch(currentPage.value);
            } catch (error: any) {
                showFlash(
                    error.response?.data?.message || "Failed to receive order",
                    "error",
                );
            }
        };

        const openEdit = (po: any) => {
            editId.value = po.id;
            showEdit.value = true;
        };

        const deletePO = async (id: number) => {
            if (!confirm("Are you sure you want to delete this order?")) return;
            try {
                await axios.delete(`/admin/purchase-order/${id}`);
                showFlash("Order deleted successfully!");
                fetch(currentPage.value);
            } catch {
                showFlash("Delete failed!", "error");
            }
        };

        const onCreated = () => {
            showCreate.value = false;
            showFlash("Order created!");
            fetch(1);
        };

        const onUpdated = () => {
            showEdit.value = false;
            showFlash("Order updated!");
            fetch(currentPage.value);
        };

        onMounted(() => fetch());

        return {
            purchaseOrders,
            showCreate,
            showEdit,
            editId,
            filterText,
            selectedStatus,
            startDate,
            endDate,
            currentPage,
            lastPage,
            perPage,
            total,
            fetch,
            openEdit,
            deletePO,
            receiveOrder,
            onCreated,
            onUpdated,
            flash,
            columns,
        };
    },
});
</script>

<style scoped>
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}
:deep(th) {
    white-space: nowrap;
}
</style>
