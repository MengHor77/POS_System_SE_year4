<template>
  <BackendLayout>
    <div class="p-6 bg-bgMain min-h-screen">
      <h1 class="text-3xl font-bold mb-6">Cashiers List</h1>

      <!-- Add New Cashier -->
      <button
        @click="showCreateModal = true"
        class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
      >
        Add New Cashier
      </button>

      <!-- Search -->
      <Filter
        v-model="search"
        placeholder="Filter by name or email"
        @filter="fetchCashiers(1)"
        containerClass="mb-4 flex gap-2 w-20"
        inputClass="border p-2 rounded flex-1"
        buttonClass="bg-darkSoft text-white px-4 py-2 rounded"
      />

      <!-- Cashiers Table -->
      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cashier in cashiers" :key="cashier.id">
            <td class="border px-4 py-2">{{ cashier.id }}</td>
            <td class="border px-4 py-2">{{ cashier.name }}</td>
            <td class="border px-4 py-2">{{ cashier.email }}</td>
            <td class="border px-4 py-2">
              <span
                @click="toggleStatus(cashier)"
                :class="{
                  'px-2 py-1 rounded cursor-pointer text-white': true,
                  'bg-green-500': cashier.status === 'active',
                  'bg-red-500': cashier.status === 'inactive',
                }"
              >
                {{ cashier.status }}
              </span>
            </td>
            <td class="border px-4 py-2 flex gap-2">
              <button
                @click="openEditModal(cashier)"
                class="bg-bgBtnEdit text-white px-2 py-1 rounded hover:bg-bgBtnEditHover"
              >
                Edit
              </button>
              <button
                @click="deleteCashier(cashier.id)"
                class="bg-bgBtnDelete text-white px-2 py-1 rounded hover:bg-bgBtnDeleteHover"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <Pigination
        :current-page="pagination.current_page"
        :last-page="pagination.last_page"
        :total="pagination.total"
        :per-page="pagination.per_page"
        @page-change="fetchCashiers"
      />

      <!-- Modals -->
      <CreateCashier
        v-if="showCreateModal"
        @close="showCreateModal = false"
        @created="fetchCashiers(pagination.current_page)"
      />
      <EditCashier
        v-if="editingCashier"
        :cashier="editingCashier"
        @close="editingCashier = null"
        @updated="fetchCashiers(pagination.current_page)"
      />
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Pigination.vue";
import CreateCashier from "./Create.vue";
import EditCashier from "./Edit.vue";
import Filter from "../../../components/Filter.vue";
import axios from "axios";

interface Cashier {
  id: number;
  name: string;
  email: string;
  status: "active" | "inactive";
  password?: string;
}

export default defineComponent({
  name: "CashierIndex",
  components: { BackendLayout, Pigination, CreateCashier, EditCashier, Filter },
  setup() {
    const cashiers = ref<Cashier[]>([]);
    const editingCashier = ref<(Cashier & { password?: string }) | null>(null);
    const showCreateModal = ref(false);
    const search = ref("");
    const pagination = ref({ current_page: 1, last_page: 1, per_page: 5, total: 0 });

    const fetchCashiers = async (page = 1) => {
      const res = await axios.get("/admin/cashier/data", {
        params: { page, per_page: pagination.value.per_page, search: search.value },
      });
      cashiers.value = res.data.data;
      pagination.value = {
        current_page: res.data.current_page,
        last_page: res.data.last_page,
        per_page: res.data.per_page,
        total: res.data.total,
      };
    };

    const openEditModal = (cashier: Cashier) => {
      editingCashier.value = { ...cashier, password: "" };
    };

    const deleteCashier = async (id: number) => {
      if (!confirm("Are you sure?")) return;
      await axios.delete(`/admin/cashier/${id}`);
      fetchCashiers(pagination.value.current_page);
    };

    const toggleStatus = async (cashier: Cashier) => {
      await axios.patch(`/admin/cashier/${cashier.id}/toggle-status`);
      fetchCashiers(pagination.value.current_page);
    };

    onMounted(() => fetchCashiers());

    return {
      cashiers,
      editingCashier,
      showCreateModal,
      pagination,
      search,
      fetchCashiers,
      openEditModal,
      deleteCashier,
      toggleStatus,
    };
  },
});
</script>
