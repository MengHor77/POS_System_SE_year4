<template>
    <div
        class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
    >
        <!-- Info -->
        <p class="text-sm text-muted">
            Showing
            <span class="font-semibold text-dark">{{ from }}</span>
            to
            <span class="font-semibold text-dark">{{ to }}</span>
            of
            <span class="font-semibold text-dark">{{ total }}</span>
            results
        </p>

        <!-- Pagination Controls -->
        <div
            class="flex items-center gap-1 bg-bgCard border border-border rounded-xl p-1 shadow-sm"
        >
            <!-- Prev -->
            <button
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition text-muted hover:bg-bgHover disabled:opacity-40 disabled:cursor-not-allowed"
                :disabled="currentPage === 1"
                @click="changePage(currentPage - 1)"
            >
                ‹ Prev
            </button>

            <!-- Page Numbers -->
            <button
                v-for="page in pages"
                :key="page"
                @click="changePage(page)"
                class="min-w-[36px] px-3 py-1.5 rounded-lg text-sm font-medium transition"
                :class="
                    page === currentPage
                        ? 'bg-primary text-white shadow'
                        : 'text-dark hover:bg-bgHover'
                "
            >
                {{ page }}
            </button>

            <!-- Next -->
            <button
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition text-muted hover:bg-bgHover disabled:opacity-40 disabled:cursor-not-allowed"
                :disabled="currentPage === lastPage"
                @click="changePage(currentPage + 1)"
            >
                Next ›
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
