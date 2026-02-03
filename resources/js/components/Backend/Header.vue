<template>
    <header
        class="bg-white border-b border-gray-200 px-4 md:px-6 h-20 flex justify-between items-center no-print"
    >
        <div class="flex items-center gap-3">
            <button
                @click="$emit('toggle-sidebar')"
                class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            >
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h1
                class="flex items-center gap-2 text-lg md:text-2xl font-bold text-primary"
            >
                <i class="fas fa-boxes md:hidden"></i>
                <span class="hidden sm:inline">Admin Dashboard</span>
            </h1>
        </div>

        <div class="flex items-center gap-2 md:gap-6">
            <div
                class="flex items-center gap-2 md:gap-3 border border-gray-100 px-3 md:px-4 py-1.5 rounded-full shadow-lg bg-gray-50/50"
            >
                <div
                    class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600"
                >
                    <i class="fas fa-user text-sm md:text-lg"></i>
                </div>
                <div class="flex flex-col items-start leading-tight">
                    <span
                        class="font-bold text-gray-900 text-xs md:text-sm capitalize"
                        >{{ adminName }}</span
                    >
                    <span
                        class="hidden lg:block text-[10px] font-bold text-gray-500 uppercase tracking-widest"
                    >
                        Administrator
                    </span>
                </div>
            </div>

            <button
                class="border border-dark px-3 md:px-4 py-2 rounded-lg shadow-lg hover:bg-red-500 hover:text-white hover:border-red-500 transition flex items-center gap-2"
                @click="logout"
            >
                <i class="fas fa-sign-out-alt"></i>
                <span class="hidden lg:inline text-sm">Logout</span>
            </button>
        </div>
    </header>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
    emits: ["toggle-sidebar"],
    setup() {
        const adminName = ref(
            localStorage.getItem("admin_name") || "Admin User",
        );

        const fetchAdminProfile = async () => {
            try {
                const response = await axios.get("/admin/me");
                if (response.data?.name) {
                    adminName.value = response.data.name;
                    localStorage.setItem("admin_name", response.data.name);
                }
            } catch (error) {
                console.error(error);
            }
        };

        const logout = async () => {
            if (!confirm("Are you sure?")) return;
            try {
                await axios.post("/admin/logout");
                localStorage.removeItem("admin_name");
                window.location.href = "/";
            } catch (error) {
                localStorage.removeItem("admin_name");
                window.location.href = "/";
            }
        };

        onMounted(() => {
            fetchAdminProfile();
        });
        return { logout, adminName };
    },
});
</script>
