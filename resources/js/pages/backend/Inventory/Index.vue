<template>
    <BackendLayout>
        <div class="text-center mt-10">
        <h1 class="text-4xl font-bold text-primary">inventory Page</h1>
        </div>
    </BackendLayout>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
export default defineComponent({
    name: "Inventory",
    components: { BackendLayout },
});
</script>