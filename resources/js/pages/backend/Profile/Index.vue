<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-primary">
                Admin Profiles
            </h1>

            <FlassMessage :message="flashMessage" :type="flashType" />

            <div class="bg-bgCard rounded-2xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <Table :columns="columns" :data="admins" class="min-w-full">
                        <template #cell-id="{ row }">
                            <span class="font-medium text-gray-700">
                                {{ row.id }}
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <div class="flex justify-start gap-2">
                                <button
                                    @click="openEdit(row.id)"
                                    class="px-3 py-1.5 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition shadow-sm"
                                    title="Edit"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                        </template>

                        <template v-if="admins.length === 0" #empty>
                            <div class="p-8 text-center text-gray-500">
                                No admins found
                            </div>
                        </template>
                    </Table>
                </div>
            </div>

            <Edit
                v-if="showEditModal && selectedId !== null"
                :id="selectedId"
                @close="closeEdit"
                @updated="handleUpdateSuccess"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Edit from "./Edit.vue";
import Table from "../../../components/Backend/Table.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";

interface Admin {
    id: number;
    name: string;
    email: string;
}

export default defineComponent({
    name: "ProfileIndex",
    components: {
        BackendLayout,
        Edit,
        FlassMessage,
        Table,
    },
    setup() {
        const admins = ref<Admin[]>([]);
        const showEditModal = ref(false);
        const selectedId = ref<number | null>(null);

        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");

        // Removed text-center to keep alignment consistent with other pages
        const columns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Name" },
            { key: "email", label: "Email" },
            { key: "actions", label: "Actions" },
        ];

        const fetchAdmins = async () => {
            try {
                const res = await axios.get("/admin/profile/data");
                admins.value = res.data;
            } catch (err) {
                console.error("Failed to load admins", err);
            }
        };

        const handleUpdateSuccess = () => {
            fetchAdmins();
            flashMessage.value = "Admin profile updated successfully!";
            flashType.value = "success";
            setTimeout(() => {
                flashMessage.value = "";
            }, 3000);
        };

        const openEdit = (id: number) => {
            selectedId.value = id;
            showEditModal.value = true;
        };

        const closeEdit = () => {
            showEditModal.value = false;
            selectedId.value = null;
        };

        onMounted(fetchAdmins);

        return {
            admins,
            columns,
            showEditModal,
            selectedId,
            openEdit,
            closeEdit,
            fetchAdmins,
            flashMessage,
            flashType,
            handleUpdateSuccess,
        };
    },
});
</script>

<style scoped>
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
</style>
