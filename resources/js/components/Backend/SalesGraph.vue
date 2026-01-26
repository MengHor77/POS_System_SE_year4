<template>
    <div class="bg-bgCard p-6 rounded-xl2 shadow-card h-96 flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-primary">Sales Analytics</h2>
        <div
            class="flex-1 flex items-center justify-center bg-bgMain rounded-lg border border-dashed border-grayLight"
        >
            <div class="text-center">
                <i class="fas fa-chart-line text-4xl text-info mb-3"></i>
                <p class="text-muted font-medium">Monthly Revenue Chart</p>
                <p class="text-xxs text-grayLight uppercase tracking-widest">
                    Data Visualization Placeholder
                </p>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "SalesGraph",
});
</script>
