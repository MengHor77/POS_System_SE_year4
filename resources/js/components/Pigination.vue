<template>
    <div class="flex items-center justify-between mt-6">
        <!-- Info -->
        <p class="text-sm text-gray-600">
            Showing
            <span class="font-medium">{{ from }}</span>
            to
            <span class="font-medium">{{ to }}</span>
            of
            <span class="font-medium">{{ total }}</span>
            results
        </p>

        <!-- Controls -->
        <div class="flex space-x-2">
            <button
                class="px-3 py-1 border rounded disabled:opacity-50"
                :disabled="currentPage === 1"
                @click="changePage(currentPage - 1)"
            >
                Prev
            </button>

            <button
                v-for="page in pages"
                :key="page"
                @click="changePage(page)"
                class="px-3 py-1 border rounded"
                :class="
                    page === currentPage ? 'bg-primary text-white' : 'bg-white'
                "
            >
                {{ page }}
            </button>

            <button
                class="px-3 py-1 border rounded disabled:opacity-50"
                :disabled="currentPage === lastPage"
                @click="changePage(currentPage + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";

export default defineComponent({
    name: "Pigination",
    props: {
        currentPage: { type: Number, required: true },
        lastPage: { type: Number, required: true },
        total: { type: Number, required: true },
        perPage: { type: Number, required: true },
    },
    emits: ["page-change"],
    setup(props, { emit }) {
        const pages = computed(() => {
            const pages = [];
            for (let i = 1; i <= props.lastPage; i++) {
                pages.push(i);
            }
            return pages;
        });

        const from = computed(
            () => (props.currentPage - 1) * props.perPage + 1,
        );

        const to = computed(() =>
            Math.min(props.currentPage * props.perPage, props.total),
        );

        const changePage = (page: number) => {
            emit("page-change", page);
        };

        return { pages, from, to, changePage };
    },
});
</script>
