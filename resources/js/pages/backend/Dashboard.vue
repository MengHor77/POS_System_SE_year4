<template>
    <BackendLayout>
        <div>
            <p>Welcome, Admin!</p>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import BackendLayout from "../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "Dashboard",
    components: { BackendLayout },
});
</script>
