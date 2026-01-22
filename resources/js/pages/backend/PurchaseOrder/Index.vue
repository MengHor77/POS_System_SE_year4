<template>
  <BackendLayout>
    <div class="p-6 bg-bgMain min-h-screen">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-primary">Purchase Orders</h1>
        <button @click="openCreate" class="px-4 py-2 rounded-lg bg-bgBtnSave text-white hover:bg-bgBtnSaveHover transition shadow-soft">
          <i class="fas fa-plus mr-2"></i> New Purchase Order
        </button>
      </div>

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
            <tr v-for="(po, index) in purchaseOrders" :key="po.id" class="text-sm hover:bg-tableRowHover transition">
              <td class="p-3 border-y text-start">{{ index + 1 }}</td>
              <td class="p-3 border-y">{{ po.supplier_name }}</td>
              <td class="p-3 border-y">{{ po.product.name }}</td>
              <td class="p-3 border-y">{{ po.quantity }}</td>
              <td class="p-3 border-y">{{ po.status }}</td>
              <td class="p-3 border-y space-x-2">
                <button @click="openEdit(po)" class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition">
                  <i class="fas fa-pen"></i>
                </button>
                <button @click="deletePO(po.id)" class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr v-if="purchaseOrders.length === 0">
              <td colspan="6" class="text-center py-8 text-muted">No purchase orders found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modals -->
      <PurchaseOrderCreate v-if="showCreate" @close="showCreate=false" @saved="loadData"/>
      <PurchaseOrderEdit v-if="showEdit" :orderId="editId" @close="showEdit=false" @updated="loadData"/>
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import PurchaseOrderCreate from "./Create.vue";
import PurchaseOrderEdit from "./Edit.vue";

export default defineComponent({
  components: { BackendLayout, PurchaseOrderCreate, PurchaseOrderEdit },
  setup() {
    const purchaseOrders = ref<any[]>([]);
    const showCreate = ref(false);
    const showEdit = ref(false);
    const editId = ref<number | null>(null);

    const loadData = async () => {
      const res = await axios.get("/admin/purchase-order/data");
      purchaseOrders.value = res.data.data || res.data; // depending on pagination
    };

    const openCreate = () => showCreate.value = true;
    const openEdit = (po: any) => { editId.value = po.id; showEdit.value = true; };

    const deletePO = async (id: number) => {
      if (!confirm("Delete this purchase order?")) return;
      await axios.delete(`/admin/purchase-order/${id}`);
      loadData();
    };

    onMounted(loadData);

    return { purchaseOrders, showCreate, showEdit, editId, openCreate, openEdit, deletePO, loadData };
  },
});
</script>
