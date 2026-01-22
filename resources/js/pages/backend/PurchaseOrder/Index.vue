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
                        class="px-4 py-2 rounded-lg bg-bgBtnSave text-white hover:bg-bgBtnSaveHover transition shadow-soft"
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

            <!-- Table -->
            <div class="bg-bgCard rounded-xl shadow-card p-6">
                <table class="w-full border-border rounded-lg overflow-hidden">
                    <thead class="bg-tableHeader text-sm">
                        <tr>
                            <th class="p-3 border-y text-start">No</th>
                            <th class="p-3 border-y text-start">Supplier</th>
                            <th class="p-3 border-y text-start">Product</th>
                            <th class="p-3 border-y text-start">Qty</th>
                            <th class="p-3 border-y text-start">Status</th>
                            <th class="p-3 border-y text-start">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(po, index) in purchaseOrders"
                            :key="po.id"
                            class="text-sm hover:bg-tableRowHover transition"
                        >
                            <td class="p-3 border-y text-start">
                                {{ (currentPage - 1) * perPage + index + 1 }}
                            </td>
                            <td class="p-3 border-y">{{ po.supplier_name }}</td>
                            <td class="p-3 border-y">{{ po.product.name }}</td>
                            <td class="p-3 border-y">{{ po.quantity }}</td>
                            <td class="p-3 border-y">{{ po.status }}</td>
                            <td class="p-3 border-y space-x-2">
                                <button
                                    @click="openEdit(po)"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button
                                    @click="deletePO(po.id)"
                                    class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="purchaseOrders.length === 0">
                            <td colspan="6" class="text-center py-8 text-muted">
                                No purchase orders found
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <Pigination
                    v-if="lastPage > 1"
                    :current-page="currentPage"
                    :last-page="lastPage"
                    :total="total"
                    :per-page="perPage"
                    @page-change="fetch"
                />
            </div>

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
import Filter from "../../../components/Filter.vue";
import Pigination from "../../../components/Pigination.vue";

export default defineComponent({
    components: {
        BackendLayout,
        PurchaseOrderCreate,
        PurchaseOrderEdit,
        Filter,
        Pigination,
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
            await axios.delete(`/admin/purchase-order/${id}`);
            fetch(currentPage.value);
        };

        const onCreated = () => {
            showCreate.value = false;
            fetch(currentPage.value);
        };

        const onUpdated = () => {
            showEdit.value = false;
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
        };
    },
});
</script>
