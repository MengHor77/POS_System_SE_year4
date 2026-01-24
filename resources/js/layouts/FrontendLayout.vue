<template>
    <div class="flex flex-col h-screen bg-gray-100 overflow-hidden">
        <header class="bg-white shadow p-4 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-primary">
                    <i class="fas fa-boxes"></i>Phone Store POS
                </h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto w-full">
                <slot />
            </div>
        </main>

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
