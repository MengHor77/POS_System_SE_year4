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
                        {{ adminName || "Loading..." }}
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
        const adminName = ref("");

        const fetchAdminProfile = async () => {
            try {
                const response = await axios.get("/admin/me");
                adminName.value = response.data.name;
            } catch (error) {
                console.error("Failed to fetch admin profile", error);
                adminName.value = "Admin User";
            }
        };

        const logout = async () => {
            if (!confirm("Are you sure you want to logout?")) return;
            try {
                await axios.post("/admin/logout");
                window.location.href = "/admin/login";
            } catch (error) {
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
/* បន្ថែម Font បើចាំបាច់ */
.tracking-widest {
    letter-spacing: 0.1em;
}
</style>
