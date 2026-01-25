<template>
    <header
        class="bg-white shadow px-6 py-4 flex justify-between items-center no-print"
    >
        <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
            <i class="fas fa-boxes"></i> POS Phone Store
        </h1>

        <div class="flex items-center gap-6">
            <div
                class="flex items-center gap-3 px-4 py-2 bg-bgMain rounded-full border border-border"
            >
                <div
                    class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center"
                >
                    <i class="fas fa-user text-primary text-sm"></i>
                </div>

                <div class="flex flex-col">
                    <span
                        v-if="loading"
                        class="h-4 w-20 bg-gray-200 animate-pulse rounded"
                    ></span>
                    <span v-else class="font-bold text-gray-800 leading-none">
                        {{ cashier?.name || "User" }}
                    </span>
                    <span
                        class="text-[10px] uppercase tracking-widest text-muted font-bold"
                        >Cashier</span
                    >
                </div>
            </div>

            <button
                @click="handleLogout"
                class="bg-white border border-border px-5 py-2.5 rounded-xl font-bold text-dark hover:bg-danger hover:text-white hover:border-danger transition-all active:scale-95 flex items-center gap-2 shadow-soft"
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
        const cashier = ref<any>(null);
        const loading = ref(true);

        // Fetch cashier data from the API route we created in step 1
        const getProfile = async () => {
            loading.value = true;
            try {
                const response = await axios.get("/api/cashier/current");
                cashier.value = response.data;
            } catch (error) {
                console.error("Auth Error:", error);
            } finally {
                loading.value = false;
            }
        };

        const handleLogout = async () => {
            // Small confirmation before logging out
            if (!confirm("Are you sure you want to end your shift?")) return;

            try {
                // Call the web route from step 2
                await axios.post("/cashier/logout");

                // Redirect to the Role Selection page (root)
                window.location.href = "/";
            } catch (error) {
                console.error("Logout failed:", error);
                // Force redirect anyway to be safe
                window.location.href = "/";
            }
        };

        onMounted(() => {
            getProfile();
        });

        return { cashier, loading, handleLogout };
    },
});
</script>
