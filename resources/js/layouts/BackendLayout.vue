<template>
    <div class="flex h-screen overflow-hidden bg-bgMain relative">
        <div
            v-if="!collapsed"
            @click="toggleSidebar"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity duration-300"
        ></div>

        <Sidebar
            :collapsed="collapsed"
            @toggle="toggleSidebar"
            class="fixed lg:static z-50 h-full"
        />

        <div
            class="flex-1 flex flex-col transition-all duration-300 h-screen w-full min-w-0"
        >
            <Header @toggle-sidebar="toggleSidebar" />

            <main class="flex-1 overflow-y-auto p-3 md:p-6">
                <div
                    v-if="successMessage"
                    class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow border border-green-200"
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
import Header from "../components/Backend/Header.vue";
import Sidebar from "../components/Backend/Sidebar.vue";

export default defineComponent({
    name: "BackendLayout",
    components: { Header, Sidebar },
    setup() {
        // Detect mobile on load
        const isMobile = () => window.innerWidth < 1024;
        const collapsed = ref(isMobile());
        const successMessage = ref("");

        const toggleSidebar = () => {
            collapsed.value = !collapsed.value;
        };

        // Auto-collapse when resizing to mobile
        const handleResize = () => {
            if (isMobile()) {
                collapsed.value = true;
            }
        };

        const showSuccess = (event: any) => {
            successMessage.value = event.detail;
            setTimeout(() => {
                successMessage.value = "";
            }, 3000);
        };

        onMounted(() => {
            window.addEventListener("success", showSuccess);
            window.addEventListener("resize", handleResize);
        });

        onUnmounted(() => {
            window.removeEventListener("success", showSuccess);
            window.removeEventListener("resize", handleResize);
        });

        return { collapsed, toggleSidebar, successMessage };
    },
});
</script>
