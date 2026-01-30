<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">Admin Profiles</h1>

            <FlassMessage :message="flashMessage" :type="flashType" />

            <div class="bg-bgCard rounded-2xl shadow overflow-hidden">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="admin in admins"
                            :key="admin.id"
                            class="border-t hover:bg-gray-50"
                        >
                            <td class="p-3">{{ admin.id }}</td>
                            <td class="p-3">{{ admin.name }}</td>
                            <td class="p-3">{{ admin.email }}</td>
                            <td class="p-3 text-center">
                                <button
                                    @click="openEdit(admin.id)"
                                    class="px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <tr v-if="admins.length === 0">
                            <td
                                colspan="4"
                                class="p-4 text-center text-gray-500"
                            >
                                No admins found
                            </td>
                        </tr>
                    </tbody>
                </table>
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
import FlassMessage from "../../../components/Backend/FlassMessage.vue";

interface Admin {
    id: number;
    name: string;
    email: string;
}

export default defineComponent({
    name: "ProfileIndex",
    components: { BackendLayout, Edit, FlassMessage },
    setup() {
        const admins = ref<Admin[]>([]);
        const showEditModal = ref(false);
        const selectedId = ref<number | null>(null);

        // Message states
        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");

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
tbody tr:hover {
    transition: background-color 0.2s ease-in-out;
}
</style>
