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
import { defineComponent, ref, onMounted, onUnmounted, watch } from "vue";
import { useRoute } from "vue-router";
import Header from "../components/Backend/Header.vue";
import Sidebar from "../components/Backend/Sidebar.vue";

export default defineComponent({
    name: "BackendLayout",
    components: { Header, Sidebar },
    setup() {
        const route = useRoute();
        const isMobile = () => window.innerWidth < 1024;

        const savedState = localStorage.getItem("sidebar-collapsed");
        const collapsed = ref(
            savedState !== null ? JSON.parse(savedState) : isMobile(),
        );

        const successMessage = ref("");

        const toggleSidebar = () => {
            collapsed.value = !collapsed.value;
            localStorage.setItem(
                "sidebar-collapsed",
                JSON.stringify(collapsed.value),
            );
        };

        // 1. WATCHER: When the route changes (user clicks a menu item),
        // collapse the sidebar automatically if on mobile.
        watch(
            () => route.path,
            () => {
                if (isMobile()) {
                    collapsed.value = true;
                    localStorage.setItem(
                        "sidebar-collapsed",
                        JSON.stringify(true),
                    );
                }
            },
        );

        const handleResize = () => {
            if (isMobile()) {
                collapsed.value = true;
                localStorage.setItem("sidebar-collapsed", JSON.stringify(true));
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
