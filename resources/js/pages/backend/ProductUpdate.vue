<template>
    <BackendLayout>
        <div class="text-center mt-10">
            <h1 class="text-4xl font-bold text-primary">Product Update Page</h1>
        </div>
    </BackendLayout>
</template>
<script lg="ts">
import { defineComponent } from "vue";
import BackendLayout from "../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "ProductUpdate",
    components: { BackendLayout },
});
</script>
