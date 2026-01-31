<template>
    <div class="relative w-full">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>

        <input
            type="text"
            :value="modelValue"
            @input="handleInput"
            :placeholder="placeholder"
            class="border rounded-xl px-10 py-2 w-full focus:ring-2 focus:ring-primary outline-none border-gray-200 transition-all shadow-sm"
        />

        <button
            v-if="modelValue"
            @click="clearSearch"
            class="absolute right-3 top-2 text-gray-400 hover:text-gray-600"
        >
            <i class="fas fa-times-circle"></i>
        </button>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "SearchInput",
    props: {
        modelValue: { type: String, default: "" },
        placeholder: { type: String, default: "Search barcode, name, id..." },
    },
    emits: ["update:modelValue", "search"],
    setup(props, { emit }) {
        let timeout: any = null;

        const handleInput = (event: Event) => {
            const value = (event.target as HTMLInputElement).value;
            emit("update:modelValue", value);

            clearTimeout(timeout);
            timeout = setTimeout(() => {
                emit("search");
            }, 500);
        };

        const clearSearch = () => {
            emit("update:modelValue", "");
            emit("search");
        };

        return { handleInput, clearSearch };
    },
});
</script>
