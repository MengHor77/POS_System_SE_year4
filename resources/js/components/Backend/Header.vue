<template>
    <header
        class="bg-white shadow px-6 py-4 flex justify-between items-center no-print"
    >
        <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
            <i class="fas fa-boxes"></i> Admin Dashboard
        </h1>

        <div class="flex items-center gap-6">
            <div
                class="flex items-center gap-3 border border-gray-100 px-4 py-1.5 rounded-full shadow-sm bg-gray-50/50"
            >
                <div
                    class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600"
                >
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div class="flex flex-col items-start leading-tight">
                    <span class="font-bold text-gray-900 text-sm capitalize">
                        {{ adminName }}
                    </span>
                    <span
                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest"
                    >
                        Administrator
                    </span>
                </div>
            </div>

            <button
                class="border border-dark px-4 py-2 rounded hover:bg-red-500 hover:text-white hover:border-red-500 transition flex items-center gap-2"
                @click="logout"
            >
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </header>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    setup() {
        // ១. ចាប់យកឈ្មោះពី LocalStorage មកដាក់ក្នុង ref ភ្លាមៗ
        const adminName = ref(
            localStorage.getItem("admin_name") || "Admin User",
        );

        const fetchAdminProfile = async () => {
            try {
                const response = await axios.get("/admin/me");
                if (response.data && response.data.name) {
                    // ២. រក្សាទុកក្នុង ref និង LocalStorage សម្រាប់លើកក្រោយ
                    adminName.value = response.data.name;
                    localStorage.setItem("admin_name", response.data.name);
                }
            } catch (error) {
                console.error("Failed to fetch admin profile", error);
            }
        };

        const logout = async () => {
            if (!confirm("Are you sure you want to logout?")) return;
            try {
                await axios.post("/admin/logout");
                // ៣. លុបទិន្នន័យចេញពី LocalStorage ពេល Logout
                localStorage.removeItem("admin_name");
                window.location.href = "/admin/login";
            } catch (error) {
                localStorage.removeItem("admin_name");
                window.location.href = "/admin/login";
            }
        };

        onMounted(() => {
            fetchAdminProfile();
        });

        return {
            logout,
            adminName,
        };
    },
});
</script>

<style scoped>
.tracking-widest {
    letter-spacing: 0.1em;
}
</style>
