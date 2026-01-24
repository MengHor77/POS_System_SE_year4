<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-primary">
                    Purchase Orders Management
                </h1>
            </div>

            <div class="flex flex-row gap-3 w-full pb-6">
                <div class="w-50">
                    <button
                        @click="showCreate = true"
                        class="px-4 py-2 rounded bg-dark text-white hover:bg-darkSoft transition shadow-soft"
                    >
                        New Purchase Order
                    </button>
                </div>
                <!-- Filter -->
                <div class="w-80">
                    <Filter
                        v-model="filterText"
                        placeholder="Search by Product or Supplier"
                        @filter="fetch(1)"
                        containerClass="px-2 flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                    />
                </div>
            </div>
            <div>
                <FlashMessage
                    v-if="flash"
                    :message="flash.message"
                    :type="flash.type"
                />
            </div>
            <!-- Table -->
            <Table :columns="columns" :data="purchaseOrders">
                <!-- No column -->
                <template #cell-no="{ index }">
                    {{ (currentPage - 1) * perPage + index + 1 }}
                </template>

                <!-- Product column -->
                <template #cell-product="{ row }">
                    {{ row.product?.name }}
                </template>

                <!-- Actions column -->
                <template #cell-actions="{ row }">
                    <div class="flex gap-2">
                        <button
                            @click="openEdit(row)"
                            class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <button
                            @click="deletePO(row.id)"
                            class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>

                <!-- Empty state -->
                <template #empty>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-muted">
                            No purchase orders found
                        </td>
                    </tr>
                </template>
            </Table>

            <!-- Modals -->
            <PurchaseOrderCreate
                v-if="showCreate"
                @close="showCreate = false"
                @saved="onCreated"
            />
            <PurchaseOrderEdit
                v-if="showEdit"
                :orderId="editId!"
                @close="closeEditModal"
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
import Filter from "../../../components/Backend/Filter.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import FlashMessage from "../../../components/Backend/FlassMessage.vue";
import Table from "../../../components/Backend/Table.vue";

export default defineComponent({
    components: {
        BackendLayout,
        PurchaseOrderCreate,
        PurchaseOrderEdit,
        Filter,
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
        const currentPage = ref(1);
        const lastPage = ref(1);
        const perPage = ref(5);
        const total = ref(0);

        const columns = [
            { key: "id", label: "ID" },
            { key: "supplier_name", label: "Supplier" },
            { key: "product", label: "Product" },
            { key: "quantity", label: "Qty" },
            { key: "status", label: "Status" },
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
        };

        const fetch = async (page = 1) => {
            currentPage.value = page;
            const res = await axios.get("/admin/purchase-order/data", {
                params: {
                    search: filterText.value,
                    page,
                    per_page: perPage.value,
                },
            });
            purchaseOrders.value = res.data.data;
            lastPage.value = res.data.last_page;
            perPage.value = res.data.per_page;
            total.value = res.data.total;
        };

        const openEdit = (po: any) => {
            editId.value = po.id;
            showEdit.value = true;
        };

        const closeEditModal = () => {
            showEdit.value = false;
            editId.value = null;
        };

        const deletePO = async (id: number) => {
            if (!confirm("Delete this purchase order?")) return;
            try {
                await axios.delete(`/admin/purchase-order/${id}`);
                showFlash("Purchase order deleted!", "success");
                fetch(currentPage.value);
            } catch {
                showFlash("Failed to delete purchase order.", "error");
            }
        };

        const onCreated = () => {
            showCreate.value = false;
            showFlash("Purchase order created!", "success");
            fetch(currentPage.value);
        };

        const onUpdated = () => {
            showEdit.value = false;
            showFlash("Purchase order updated!", "success");
            fetch(currentPage.value);
        };

        onMounted(() => fetch());

        return {
            purchaseOrders,
            showCreate,
            showEdit,
            editId,
            filterText,
            currentPage,
            lastPage,
            perPage,
            total,
            fetch,
            openEdit,
            closeEditModal,
            deletePO,
            onCreated,
            onUpdated,
            flash,
            showFlash,
            columns,
        };
    },
});
</script>
