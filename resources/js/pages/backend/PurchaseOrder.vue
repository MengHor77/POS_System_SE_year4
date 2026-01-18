<template>
    <BackendLayout>
        <div class="text-center mt-10">
        <h1 class="text-4xl font-bold text-primary">Purchase Order Page</h1>
        </div>
    </BackendLayout>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import BackendLayout from "../../layouts/BackendLayout.vue";
export default defineComponent({
    name: "PurchaseOrder",
    components: { BackendLayout },
});
</script>