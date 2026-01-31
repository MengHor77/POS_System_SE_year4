<template>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
        
        <div class="flex items-center bg-white border border-border rounded-lg px-3 py-1 shadow-soft flex-1">
            <label class="text-xxs font-bold text-muted uppercase mr-2 whitespace-nowrap">From:</label>
            <input
                type="datetime-local"
                :value="startDate"
                @input="$emit('update:startDate', ($event.target as HTMLInputElement).value)"
                class="text-sm bg-transparent border-none focus:ring-0 text-gray-700 p-0 w-full"
            />
        </div>

        <div class="flex items-center bg-white border border-border rounded-lg px-3 py-1 shadow-soft flex-1">
            <label class="text-xxs font-bold text-muted uppercase mr-2 whitespace-nowrap">To:</label>
            <input
                type="datetime-local"
                :value="endDate"
                @input="$emit('update:endDate', ($event.target as HTMLInputElement).value)"
                class="text-sm bg-transparent border-none focus:ring-0 text-gray-700 p-0 w-full"
            />
        </div>

        <div class="flex gap-2 justify-end">
            <button
                @click="$emit('filter')"
                class="p-2 bg-primary text-white rounded-lg hover:opacity-90 transition shadow-md flex-1 sm:flex-none"
                title="Apply Date Filter"
            >
                <i class="fas fa-filter text-xs"></i>
            </button>

            <button
                @click="clearFilters"
                class="p-2 bg-gray-100 text-gray-500 rounded-lg hover:bg-gray-200 transition flex-1 sm:flex-none"
                title="Clear Dates"
            >
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "DateRangeFilter",
    props: {
        startDate: String,
        endDate: String,
    },
    emits: ["update:startDate", "update:endDate", "filter"],
    setup(props, { emit }) {
        const clearFilters = () => {
            emit("update:startDate", "");
            emit("update:endDate", "");
            emit("filter");
        };
        return { clearFilters };
    },
});
</script>