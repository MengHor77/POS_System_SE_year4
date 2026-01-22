<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">
                Cashiers Management
            </h1>

            <div class="flex flex-row gap-3 w-full pb-6">
                <div class="w-50">
                    <!-- Add New Cashier -->
                    <button
                        @click="showCreateModal = true"
                        class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
                    >
                        Add New Cashier
                    </button>
                </div>

                <div class="w-80">
                    <!-- Search -->
                    <Filter
                        v-model="search"
                        placeholder="Filter by name or email"
                        @filter="fetchCashiers(1)"
                        containerClass="px-2 flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                    />
                </div>
            </div>

            <div class="bg-bgCard rounded-xl shadow-card p-6">
                <!-- Cashiers Table -->
                <table class="w-full border-border rounded-lg overflow-hidden">
                    <thead class="bg-tableHeader text-sm">
                        <tr class="bg-gray-100 rounded-lg border">
                            <th class="p-3 border-y text-start">ID</th>
                            <th class="p-3 border-y text-start">Name</th>
                            <th class="p-3 border-y text-start">Email</th>
                            <th class="p-3 border-y text-start">Status</th>
                            <th class="p-3 border-y text-start">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="cashier in cashiers"
                            :key="cashier.id"
                            class="text-sm hover:bg-tableRowHover transition"
                        >
                            <td class="p-3 border-y text-start">
                                {{ cashier.id }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ cashier.name }}
                            </td>
                            <td class="p-3 border-y text-start">
                                {{ cashier.email }}
                            </td>
                            <td class="p-3 border-y text-start">
                                <span
                                    @click="toggleStatus(cashier)"
                                    :class="{
                                        'px-2 py-1  cursor-pointer border-y text-white text-start': true,
                                        'bg-green-500 px-2 py-1 text-xs rounded-full  text-white ':
                                            cashier.status === 'active',
                                        'bg-red-500 px-2 py-1 text-xs rounded-full  text-white':
                                            cashier.status === 'inactive',
                                    }"
                                >
                                    {{ cashier.status }}
                                </span>
                            </td>
                            <td class="p-3 border-y text-start flex gap-2">
                                <button
                                    @click="openEditModal(cashier)"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button
                                    @click="deleteCashier(cashier.id)"
                                    class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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
    components: {
        BackendLayout,
        Pigination,
        CreateCashier,
        EditCashier,
        Filter,
    },
    setup() {
        const cashiers = ref<Cashier[]>([]);
        const editingCashier = ref<(Cashier & { password?: string }) | null>(
            null,
        );
        const showCreateModal = ref(false);
        const search = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });

        const fetchCashiers = async (page = 1) => {
            const res = await axios.get("/admin/cashier/data", {
                params: {
                    page,
                    per_page: pagination.value.per_page,
                    search: search.value,
                },
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
