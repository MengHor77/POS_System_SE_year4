<template>
    <div class="w-48">
        <select
            :value="modelValue"
            @change="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLSelectElement).value,
                )
            "
            class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-white text-sm font-medium text-gray-700 cursor-pointer shadow-sm"
        >
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="received">Received</option>
        </select>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "FilterStatus",
    props: {
        modelValue: {
            type: String,
            default: "",
        },
    },
    emits: ["update:modelValue"],
});
</script>
