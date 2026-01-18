<template>
    <header class="bg-white shadow px-6 py-4  pb-5 flex justify-between items-center">
        <h1 class="text-xl font-bold text-primary">POS Admin</h1>

        <button
            class="bg-danger text-white px-4 py-2 rounded hover:bg-red-600 transition"
            @click="logout"
        >
            Logout
        </button>
    </header>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default defineComponent({
    setup() {
        const router = useRouter();

        const logout = async () => {
            await axios.post("/api/admin/logout");
            router.push("/admin/login");
        };

        return { logout };
    },
});
</script>
