<template>
    <BackendLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Categories</h2>
            </div>

            <div class="flex flex-row gap-3 w-full pb-6">
                <div class="w-50">
                    <button
                        @click="showCreate = true"
                        class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
                    >
                        Add Category
                    </button>
                </div>
                <!-- Filter/Search -->
                <div class="w-80">
                    <Filter
                        v-model="search"
                        placeholder="Search categories..."
                        @filter="fetchCategories(1)"
                        containerClass="px-2 flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                    />
                </div>
            </div>
            <!-- Flash Messages -->
            <FlassMessage
                v-if="message"
                :message="message.text"
                :type="message.type"
            />

            <!-- Table -->
            <Table :columns="columns" :data="categories.data || []">
                <template #cell-actions="{ row }">
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                            @click="editCategory(row)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <button
                            class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                            @click="deleteCategory(row.id)"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>
            </Table>

            <!-- Pagination -->
            <Pigination
                v-if="categories.total > perPage"
                :current-page="categories.current_page"
                :last-page="categories.last_page"
                :total="categories.total"
                :per-page="perPage"
                @page-change="fetchCategories"
            />

            <!-- Create Modal -->
            <CreateCategory
                v-if="showCreate"
                @close="showCreate = false"
                @saved="handleSaved"
            />

            <!-- Edit Modal -->
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
import Table from "../../../components/Table.vue";
import Pigination from "../../../components/Pigination.vue";
import FlassMessage from "../../../components/FlassMessage.vue";
import Filter from "../../../components/Filter.vue";
import CreateCategory from "./Create.vue";
import EditCategory from "./Edit.vue";

export default defineComponent({
    components: {
        BackendLayout,
        Table,
        Pigination,
        FlassMessage,
        Filter,
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
            const res = await axios.get("/admin/category", {
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
