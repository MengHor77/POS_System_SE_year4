<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-primary">
                    Purchase Orders
                </h1>

                <button
                    @click="openCreate"
                    class="px-4 py-2 rounded-lg bg-bgBtnSave text-white
                           hover:bg-bgBtnSaveHover transition shadow-soft"
                >
                    <i class="fas fa-plus mr-2"></i>
                    New Purchase Order
                </button>
            </div>

            <!-- TABLE -->
            <div class="bg-bgCard rounded-xl shadow-card p-6">
                <table class="w-full border border-border rounded-lg overflow-hidden">
                    <thead class="bg-tableHeader text-sm">
                        <tr>
                            <th class="p-3 border">#</th>
                            <th class="p-3 border">PO No</th>
                            <th class="p-3 border">Supplier</th>
                            <th class="p-3 border">Product</th>
                            <th class="p-3 border">Qty</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(po, index) in purchaseOrders"
                            :key="po.id"
                            class="text-sm hover:bg-tableRowHover transition"
                        >
                            <td class="p-3 border">{{ index + 1 }}</td>
                            <td class="p-3 border font-semibold">{{ po.po_number }}</td>
                            <td class="p-3 border">{{ po.supplier }}</td>
                            <td class="p-3 border">{{ po.product_name }}</td>
                            <td class="p-3 border">{{ po.quantity }}</td>

                            <td class="p-3 border">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold"
                                    :class="statusClass(po.status)"
                                >
                                    {{ po.status }}
                                </span>
                            </td>

                            <td class="p-3 border text-center space-x-2">
                                <button
                                    @click="openEdit(po)"
                                    class="px-3 py-1 rounded-lg
                                           bg-blue-100 text-bgBtnEdit
                                           hover:bg-bgBtnEdit hover:text-white transition"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button
                                    @click="deletePO(po.id)"
                                    class="px-3 py-1 rounded-lg
                                           bg-dangerSoft text-danger
                                           hover:bg-bgBtnDelete hover:text-white transition"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr v-if="purchaseOrders.length === 0">
                            <td colspan="7" class="text-center py-8 text-muted">
                                No purchase orders found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- CREATE MODAL -->
            <div
                v-if="showCreate"
                class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
            >
                <div class="bg-bgCard w-full max-w-xl rounded-xl shadow-card p-6">
                    <h2 class="text-xl font-bold text-primary mb-4">
                        Create Purchase Order
                    </h2>

                    <form @submit.prevent="storePO">
                        <input
                            v-model="form.supplier"
                            placeholder="Supplier Name"
                            class="w-full mb-3 px-3 py-2 border rounded-lg"
                            required
                        />

                        <select
                            v-model="form.product_name"
                            class="w-full mb-3 px-3 py-2 border rounded-lg"
                            required
                        >
                            <option value="">Select Product</option>
                            <option v-for="p in products" :key="p.id">
                                {{ p.name }}
                            </option>
                        </select>

                        <input
                            v-model.number="form.quantity"
                            type="number"
                            min="1"
                            placeholder="Quantity"
                            class="w-full mb-4 px-3 py-2 border rounded-lg"
                            required
                        />

                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 rounded-lg bg-bgBtnCancel text-white"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="px-4 py-2 rounded-lg bg-bgBtnSave text-white"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- EDIT MODAL -->
            <div
                v-if="showEdit"
                class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
            >
                <div class="bg-bgCard w-full max-w-xl rounded-xl shadow-card p-6">
                    <h2 class="text-xl font-bold text-primary mb-4">
                        Edit Purchase Order
                    </h2>

                    <form @submit.prevent="updatePO">
                        <input
                            v-model="form.supplier"
                            class="w-full mb-3 px-3 py-2 border rounded-lg"
                            required
                        />

                        <select
                            v-model="form.product_name"
                            class="w-full mb-3 px-3 py-2 border rounded-lg"
                            required
                        >
                            <option v-for="p in products" :key="p.id">
                                {{ p.name }}
                            </option>
                        </select>

                        <input
                            v-model.number="form.quantity"
                            type="number"
                            min="1"
                            class="w-full mb-4 px-3 py-2 border rounded-lg"
                            required
                        />

                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 rounded-lg bg-bgBtnCancel text-white"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="px-4 py-2 rounded-lg bg-bgBtnEdit text-white"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";

export default defineComponent({
    components: { BackendLayout },

    setup() {
        const showCreate = ref(false);
        const showEdit = ref(false);
        const editId = ref<number | null>(null);

        const products = ref([
            { id: 1, name: "iPhone 14" },
            { id: 2, name: "Galaxy S23" },
        ]);

        const purchaseOrders = ref<any[]>([
            {
                id: 1,
                po_number: "PO-0001",
                supplier: "Samsung Supplier",
                product_name: "Galaxy S23",
                quantity: 10,
                status: "Pending",
            },
        ]);

        const form = ref({
            supplier: "",
            product_name: "",
            quantity: 1,
        });

        const openCreate = () => {
            resetForm();
            showCreate.value = true;
        };

        const openEdit = (po: any) => {
            editId.value = po.id;
            form.value = { ...po };
            showEdit.value = true;
        };

        const closeModal = () => {
            showCreate.value = false;
            showEdit.value = false;
            editId.value = null;
        };

        const storePO = () => {
            purchaseOrders.value.push({
                id: Date.now(),
                po_number: "PO-" + Math.floor(Math.random() * 10000),
                status: "Pending",
                ...form.value,
            });
            closeModal();
        };

        const updatePO = () => {
            const index = purchaseOrders.value.findIndex(p => p.id === editId.value);
            purchaseOrders.value[index] = { ...purchaseOrders.value[index], ...form.value };
            closeModal();
        };

        const deletePO = (id: number) => {
            if (!confirm("Delete this purchase order?")) return;
            purchaseOrders.value = purchaseOrders.value.filter(p => p.id !== id);
        };

        const statusClass = (status: string) => {
            if (status === "Approved") return "bg-poApprovedSoft text-poApproved";
            return "bg-poPendingSoft text-poPending";
        };

        const resetForm = () => {
            form.value = { supplier: "", product_name: "", quantity: 1 };
        };

        return {
            purchaseOrders,
            products,
            form,
            showCreate,
            showEdit,
            openCreate,
            openEdit,
            closeModal,
            storePO,
            updatePO,
            deletePO,
            statusClass,
        };
    },
});
</script>
