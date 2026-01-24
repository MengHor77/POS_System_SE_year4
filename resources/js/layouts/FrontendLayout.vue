<template>
    <div class="flex flex-col min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow p-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-primary">
                    frontend! layout
                </h1>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 max-w-7xl mx-auto p-6 w-full">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-white p-4 text-center">
            &copy; {{ new Date().getFullYear() }} POS System
        </footer>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "FrontendLayout",
});
</script>
