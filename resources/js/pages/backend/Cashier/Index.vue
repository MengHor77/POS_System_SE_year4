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
            <FlassMessage
                v-if="message"
                :message="message"
                :type="messageType"
            />

            <Table :columns="columns" :data="cashiers">
                <!-- Status column -->
                <template #cell-status="{ row }">
                    <span
                        @click="toggleStatus(row)"
                        :class="[
                            'cursor-pointer px-2 py-1 text-xs rounded-full text-white',
                            row.status === 'active'
                                ? 'bg-green-500'
                                : 'bg-red-500',
                        ]"
                    >
                        {{ row.status }}
                    </span>
                </template>

                <!-- Actions column -->
                <template #cell-actions="{ row }">
                    <div class="flex gap-2">
                        <button
                            @click="openEditModal(row)"
                            class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <button
                            @click="deleteCashier(row.id)"
                            class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>
            </Table>

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
                @created="
                    () => {
                        fetchCashiers(pagination.current_page);
                        showMessage('Cashier created successfully', 'success');
                    }
                "
            />
            <EditCashier
                v-if="editingCashier"
                :cashier="editingCashier"
                @close="editingCashier = null"
                @updated="
                    () => {
                        fetchCashiers(pagination.current_page);
                        showMessage('Cashier updated successfully', 'success');
                    }
                "
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import CreateCashier from "./Create.vue";
import EditCashier from "./Edit.vue";
import Filter from "../../../components/Backend/Filter.vue";
import axios from "axios";
import Table from "../../../components/Backend/Table.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";

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
        Table,
        FlassMessage,
    },
    setup() {
        const cashiers = ref<Cashier[]>([]);
        const editingCashier = ref<(Cashier & { password?: string }) | null>(
            null,
        );
        const message = ref("");
        const messageType = ref<"success" | "error">("success");
        const showMessage = (
            msg: string,
            type: "success" | "error" = "success",
        ) => {
            message.value = msg;
            messageType.value = type;

            // Optional: clear after 3 seconds (matches FlassMessage)
            setTimeout(() => {
                message.value = "";
            }, 3000);
        };

        const showCreateModal = ref(false);
        const search = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });

        const columns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Name" },
            { key: "email", label: "Email" },
            { key: "status", label: "Status" },
            { key: "actions", label: "Actions" },
        ];

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

            try {
                await axios.delete(`/admin/cashier/${id}`);
                fetchCashiers(pagination.value.current_page);
                showMessage("Cashier deleted successfully", "success");
            } catch (error) {
                showMessage("Failed to delete cashier", "error");
            }
        };

        const toggleStatus = async (cashier: Cashier) => {
            try {
                await axios.patch(`/admin/cashier/${cashier.id}/toggle-status`);
                fetchCashiers(pagination.value.current_page);
                showMessage(
                    `Cashier status changed to ${cashier.status === "active" ? "inactive" : "active"}`,
                    "success",
                );
            } catch (error) {
                showMessage("Failed to update status", "error");
            }
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
            columns,
            message,
            messageType,
            showMessage,
        };
    },
});
</script>
