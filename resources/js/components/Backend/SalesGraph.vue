<template>
    <div class="bg-bgCard p-6 rounded-2xl shadow-md h-96 flex items-center justify-center">
        <div class="text-center">
            <i class="fas fa-chart-line text-4xl text-gray-300 mb-2"></i>
            <p class="text-gray-400 font-medium">Sales Analytics Graph </p>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "SalesGraph"
});
</script>