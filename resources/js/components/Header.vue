<template>
    <header
        class="bg-white shadow px-6 py-4 pb-5 flex justify-between items-center"
    >
        <h1 class="text-xl font-bold text-primary">Dashboard Admin</h1>

        <div class="flex gap-3">
            <h1 class="mt-2 font-bold">Admin</h1>
            <button
                class="border border-dark px-4 py-2 rounded hover:bg-danger transition flex items-center gap-2"
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
            window.location.href = "/admin/login";
        };

        return { logout };
    },
});
</script>
