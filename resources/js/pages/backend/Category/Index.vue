<template>
    <BackendLayout>
        <div class="p-4 md:p-6 bg-bgMain min-h-screen">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-primary">
                Category Management
            </h1>

            <div class="flex flex-col sm:flex-row gap-4 w-full pb-6 items-start sm:items-center">
                <div class="w-full sm:w-auto">
                    <button
                        @click="showCreate = true"
                        class="w-full sm:w-auto bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft flex items-center justify-center gap-2 transition"
                    >
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </div>
                
                <div class="w-full sm:w-auto">
                    <SearchInput
                        v-model="search"
                        placeholder="Search categories name..."
                        @filter="fetchCategories(1)"
                        containerClass="flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1 min-w-0"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded shrink-0"
                    />
                </div>
            </div>

            <FlassMessage
                v-if="message"
                :message="message.text"
                :type="message.type"
            />

            <div class="w-full overflow-x-auto rounded-lg shadow-sm">
                <Table :columns="columns" :data="categories.data || []">
                    <template #cell-actions="{ row }">
                        <div class="flex gap-2">
                            <button
                                class="px-3 py-1.5 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                                @click="editCategory(row)"
                                title="Edit"
                            >
                                <i class="fas fa-pen"></i>
                            </button>
                            <button
                                class="px-3 py-1.5 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                                @click="deleteCategory(row.id)"
                                title="Delete"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <div class="mt-4 flex justify-end overflow-x-auto">
                <Pigination
                    v-if="categories.total > perPage"
                    :current-page="categories.current_page"
                    :last-page="categories.last_page"
                    :total="categories.total"
                    :per-page="perPage"
                    @page-change="fetchCategories"
                />
            </div>

            <CreateCategory
                v-if="showCreate"
                @close="showCreate = false"
                @saved="handleSaved"
            />

            <EditCategory
                v-if="showEdit"
                :category="selectedCategory"
                @close="showEdit = false"
                @updated="handleUpdated"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Table from "../../../components/Backend/Table.vue";
import Pigination from "../../../components/Backend/Pigination.vue";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";
import SearchInput from "../../../components/Backend/SearchInput.vue";
import CreateCategory from "./Create.vue";
import EditCategory from "./Edit.vue";

export default defineComponent({
    components: {
        BackendLayout,
        Table,
        Pigination,
        FlassMessage,
        SearchInput,
        CreateCategory,
        EditCategory,
    },
    setup() {
        const categories = ref<any>({});
        const perPage = 5;
        const search = ref("");
        const showCreate = ref(false);
        const showEdit = ref(false);
        const selectedCategory = ref<any>(null);
        const message = ref<{ text: string; type: "success" | "error" } | null>(
            null,
        );

        const columns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Name" },
            { key: "description", label: "Description" },
            { key: "actions", label: "Actions" },
        ];

        const fetchCategories = async (page = 1) => {
            const res = await axios.get("/admin/category/data", {
                params: { page, per_page: perPage, search: search.value },
            });
            categories.value = res.data;
        };

        const handleSaved = () => {
            message.value = {
                text: "Category created successfully",
                type: "success",
            };
            fetchCategories();
            showCreate.value = false;
        };

        const handleUpdated = () => {
            message.value = {
                text: "Category updated successfully",
                type: "success",
            };
            fetchCategories();
            showEdit.value = false;
        };

        const editCategory = (cat: any) => {
            selectedCategory.value = { ...cat };
            showEdit.value = true;
        };

        const deleteCategory = async (id: number) => {
            if (!confirm("Are you sure?")) return;
            await axios.delete(`/admin/category/${id}`);
            message.value = {
                text: "Category deleted successfully",
                type: "success",
            };
            fetchCategories();
        };

        onMounted(() => fetchCategories());
        watch(search, () => fetchCategories(1));

        return {
            categories,
            perPage,
            search,
            columns,
            showCreate,
            showEdit,
            selectedCategory,
            message,
            fetchCategories,
            editCategory,
            deleteCategory,
            handleSaved,
            handleUpdated,
        };
    },
});
</script>

<style scoped>
/* Ensure Table container doesn't break the layout on tiny screens */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
</style>