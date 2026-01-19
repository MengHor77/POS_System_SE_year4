<template>
  <div class="flex h-screen overflow-hidden bg-bgMain">
        <!-- Sidebar -->
        <Sidebar :collapsed="collapsed" @toggle="toggleSidebar" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300 h-screen">
            <!-- Header -->
            <Header />

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto p-3">

                <!-- ✅ GLOBAL SUCCESS MESSAGE -->
                <div
                    v-if="successMessage"
                    class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow"
                >
                    {{ successMessage }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, onUnmounted } from "vue";
import Header from "../components/Header.vue";
import Sidebar from "../components/Sidebar.vue";

export default defineComponent({
    name: "BackendLayout",
    components: { Header, Sidebar },
    setup() {
        const collapsed = ref(false);
        const successMessage = ref("");

        const toggleSidebar = () => {
            collapsed.value = !collapsed.value;
        };

        const showSuccess = (event: any) => {
            successMessage.value = event.detail;
            setTimeout(() => {
                successMessage.value = "";
            }, 3000);
        };

        onMounted(() => {
            window.addEventListener("success", showSuccess);
        });

        onUnmounted(() => {
            window.removeEventListener("success", showSuccess);
        });

        return {
            collapsed,
            toggleSidebar,
            successMessage,
        };
    },
});
</script>

