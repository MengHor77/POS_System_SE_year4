<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <div class="pb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-primary">
                    Cashiers Management
                </h1>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto pb-6">
                <div class="w-full sm:w-auto">
                    <button
                        @click="showCreateModal = true"
                        class="w-full bg-dark text-white px-4 py-2 rounded-xl hover:bg-darkSoft transition shadow-md flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-plus"></i> Add New Cashier
                    </button>
                </div>

                <div class="w-full md:w-80">
                    <SearchInput
                        v-model="search"
                        placeholder="Search by ID, Name or Email..."
                        @search="fetchCashiers(1)"
                    />
                </div>
            </div>

            <FlashMessage
                v-if="message"
                :message="message"
                :type="messageType"
            />

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-2">
                <div class="overflow-x-auto">
                    <Table
                        :columns="columns"
                        :data="cashiers"
                        class="min-w-full"
                    >
                        <template #cell-status="{ row }">
                            <span
                                @click="toggleStatus(row)"
                                :class="[
                                    'cursor-pointer px-3 py-1 text-xs rounded-full text-white capitalize font-medium whitespace-nowrap',
                                    row.status === 'active'
                                        ? 'bg-green-500'
                                        : 'bg-red-500',
                                ]"
                            >
                                {{ row.status }}
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <div class="flex gap-2">
                                <button
                                    @click="openEditModal(row)"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                >
                                    <i class="fas fa-pen text-sm"></i>
                                </button>
                                <button
                                    @click="deleteCashier(row.id)"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <div class="flex justify-center md:justify-start">
                <Pagination
                    v-if="pagination.total > pagination.per_page"
                    class="mt-6"
                    :current-page="pagination.current_page"
                    :last-page="pagination.last_page"
                    :total="pagination.total"
                    :per-page="pagination.per_page"
                    @page-change="fetchCashiers"
                />
            </div>

            <CreateCashier
                v-if="showCreateModal"
                @close="showCreateModal = false"
                @created="onCreated"
            />

            <EditCashier
                v-if="editingCashier"
                :cashier="editingCashier"
                @close="editingCashier = null"
                @updated="onUpdated"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pagination from "../../../components/Backend/Pigination.vue";
import CreateCashier from "./Create.vue";
import EditCashier from "./Edit.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import axios from "axios";
import Table from "../../../components/Backend/Table.vue";
import FlashMessage from "../../../components/Backend/FlassMessage.vue";

interface Cashier {
    id: number;
    name: string;
    email: string;
    status: "active" | "inactive";
    old_password?: string;
    new_password?: string;
    confirm_password?: string;
}

export default defineComponent({
    name: "CashierIndex",
    components: {
        BackendLayout,
        Pagination,
        CreateCashier,
        EditCashier,
        SearchInput,
        Table,
        FlashMessage,
    },
    setup() {
        const cashiers = ref<Cashier[]>([]);
        const editingCashier = ref<Cashier | null>(null);
        const showCreateModal = ref(false);
        const search = ref("");
        const message = ref("");
        const messageType = ref<"success" | "error">("success");

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

        const showMessage = (
            msg: string,
            type: "success" | "error" = "success",
        ) => {
            message.value = msg;
            messageType.value = type;
            setTimeout(() => {
                message.value = "";
            }, 3000);
        };

        const fetchCashiers = async (page = 1) => {
            try {
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
            } catch (error) {
                showMessage("Failed to load cashiers", "error");
            }
        };

        const openEditModal = (cashier: Cashier) => {
            editingCashier.value = {
                ...cashier,
                old_password: "",
                new_password: "",
                confirm_password: "",
            };
        };

        const deleteCashier = async (id: number) => {
            if (!confirm("Are you sure you want to delete this cashier?"))
                return;
            try {
                await axios.delete(`/admin/cashier/${id}`);
                fetchCashiers(pagination.value.current_page);
                showMessage("Cashier deleted successfully");
            } catch (error) {
                showMessage("Failed to delete cashier", "error");
            }
        };

        const toggleStatus = async (cashier: Cashier) => {
            try {
                await axios.patch(`/admin/cashier/${cashier.id}/toggle-status`);
                fetchCashiers(pagination.value.current_page);
                showMessage("Status updated");
            } catch (error) {
                showMessage("Failed to update status", "error");
            }
        };

        const onCreated = () => {
            showCreateModal.value = false;
            fetchCashiers(1);
            showMessage("Cashier created successfully");
        };

        const onUpdated = () => {
            editingCashier.value = null;
            fetchCashiers(pagination.value.current_page);
            showMessage("Cashier updated successfully");
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
            onCreated,
            onUpdated,
        };
    },
});
</script>

<style scoped>
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}
</style>
