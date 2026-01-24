<template>
    <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
            <i class="fas fa-boxes"></i> Admin Dashboard
        </h1>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-circle text-gray-500 text-xl"></i>
                <span class="font-bold">Admin</span>
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
import { defineComponent } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default defineComponent({
    setup() {
        const logout = () => {
            // redirect to backend logout route
            window.location.href = "/";
        };

        return { logout };
    },
});
</script>
