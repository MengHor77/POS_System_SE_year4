<template>
    <div
        class="bg-white p-4 rounded-2xl shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between"
    >
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg text-gray-700">{{ title }}</h2>
            <!-- Optional icon slot -->
            <slot name="icon" />
        </div>
        <div class="mt-4">
            <p class="text-2xl font-extrabold text-gray-900">{{ value }}</p>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

export default defineComponent({
    name: "Card",
    props: {
        title: { type: String as PropType<string>, required: true },
        value: {
            type: [String, Number] as PropType<string | number>,
            default: "",
        },
    },
});
</script>
