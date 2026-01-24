<template>
    <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
            <i class="fas fa-boxes"></i> POS Phone Store
        </h1>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-circle text-gray-500 text-xl"></i>
                <span v-if="loading" class="text-gray-400 italic text-sm animate-pulse">
                    Loading...
                </span>
                <span v-else class="font-bold text-gray-800">
                    {{ cashier?.name || 'Guest User' }}
                </span>
            </div>

            <button
                class="border border-gray-900 px-4 py-2 rounded hover:bg-red-500 hover:text-white hover:border-red-500 transition flex items-center gap-2"
                @click="handleLogout"
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
    name: "HeaderComponent",
    setup() {
        // Reactive variables
        const cashier = ref<any>(null);
        const loading = ref(true);

        // Fetch the currently logged-in cashier profile
        // Note: You may need a specific route like /api/cashier/profile 
        // that returns auth()->user()
        const getProfile = async () => {
            loading.value = true;
            try {
                // Assuming you have an endpoint that returns the current authenticated user
                const response = await axios.get("/api/cashier/current"); 
                cashier.value = response.data;
            } catch (error) {
                console.error("Failed to fetch cashier data:", error);
                cashier.value = { name: "Unknown" };
            } finally {
                loading.value = false;
            }
        };

        const handleLogout = async () => {
            try {
                // Call your backend logout route if needed
                // await axios.post("/logout");
                window.location.href = "/login";
            } catch (error) {
                window.location.href = "/login";
            }
        };

        onMounted(() => {
            getProfile();
        });

        return { 
            cashier, 
            loading, 
            handleLogout 
        };
    },
});
</script>

<style scoped>
/* Add a small pulse animation for the loading text */
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}
</style>