<template>
    <BackendLayout>
        <div class="p-6 bg-bgMain min-h-screen">
            <h1 class="text-3xl font-bold mb-6 text-primary">
                Products Management
            </h1>

            <!-- Add New Product Button -->
            <div class="flex flex-row gap-3 w-full pb-6">
                <div class="w-50">
                    <button
                        @click="openCreateModal"
                        class="mb-4 bg-dark text-white px-4 py-2 rounded hover:bg-darkSoft"
                    >
                        Add New Product
                    </button>
                </div>
                <div class="w-80">
                    <!-- Filter Component -->
                    <Filter
                        v-model="search"
                        placeholder="Filter by barcode or name"
                        @filter="fetchProducts(1)"
                        containerClass="px-2 flex gap-2 w-full"
                        inputClass="border p-2 rounded flex-1"
                        buttonClass="bg-dark hover:bg-darkSoft text-white px-4 py-2 rounded"
                    />
                </div>
            </div>
            <FlassMessage :message="flashMessage" :type="flashType" />

            <!-- Products Table -->
            <Table :columns="columns" :data="products">
                <!-- Actions column -->
                <template #cell-actions="{ row }">
                    <div class="flex gap-2">
                        <button
                            @click="openEditModal(row)"
                            class="px-3 py-1 rounded-lg bg-blue-100 text-bgBtnEdit hover:bg-bgBtnEdit hover:text-white transition"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <button
                            @click="deleteProduct(row.id)"
                            class="px-3 py-1 rounded-lg bg-dangerSoft text-danger hover:bg-bgBtnDelete hover:text-white transition"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>
            </Table>

            <!-- Pagination -->
            <Pigination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total="pagination.total"
                :per-page="pagination.per_page"
                @page-change="fetchProducts"
            />

            <!-- Modals -->
            <CreateProduct
                v-if="showCreateModal"
                @close="showCreateModal = false"
                @created="
                    () => {
                        fetchProducts(pagination.current_page);
                        showFlashMessage(
                            'Product created successfully!',
                            'success',
                        );
                    }
                "
                @error="(msg: string) => showFlashMessage(msg, 'error')"
            />
            <EditProduct
                v-if="editingProduct"
                :product="editingProduct"
                @close="editingProduct = null"
                @updated="
                    () => {
                        fetchProducts(pagination.current_page);
                        showFlashMessage(
                            'Product updated successfully!',
                            'success',
                        );
                    }
                "
                @error="(msg: string) => showFlashMessage(msg, 'error')"
            />
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import Pigination from "../../../components/Pigination.vue";
import CreateProduct from "./Create.vue";
import EditProduct from "./Edit.vue";
import axios from "axios";
import Filter from "../../../components/Filter.vue";
import FlassMessage from "../../../components/FlassMessage.vue";
import Table from "../../../components/Table.vue";

interface Product {
    id: number;
    name: string;
    brand: string;
    barcode: number;
    price: number;
    stock: number;
}

export default defineComponent({
    name: "ProductIndex",
    components: {
        BackendLayout,
        Pigination,
        CreateProduct,
        EditProduct,
        Filter,
        FlassMessage,
        Table,
    },
    setup() {
        const products = ref<Product[]>([]);
        const editingProduct = ref<Product | null>(null);
        const showCreateModal = ref(false);
        const search = ref("");

        const columns = [
            { key: "id", label: "ID" },
            { key: "name", label: "Name" },
            { key: "brand", label: "Brand" },
            { key: "barcode", label: "Barcode" },
            { key: "price", label: "Price" },
            { key: "stock", label: "Stock" },
            { key: "actions", label: "Actions" },
        ];

        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 5,
            total: 0,
        });

        // Flash message
        const flashMessage = ref("");
        const flashType = ref<"success" | "error">("success");
        const showFlashMessage = (
            message: string,
            type: "success" | "error" = "success",
        ) => {
            flashMessage.value = message;
            flashType.value = type;
            setTimeout(() => {
                flashMessage.value = "";
            }, 3000);
        };

        const fetchProducts = async (page = 1) => {
            const res = await axios.get("/admin/product/data", {
                params: {
                    page,
                    per_page: pagination.value.per_page,
                    search: search.value,
                },
            });

            products.value = res.data.data;
            pagination.value = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                per_page: res.data.per_page,
                total: res.data.total,
            };
        };

        const openCreateModal = () => {
            showCreateModal.value = true;
        };

        const openEditModal = (product: Product) => {
            editingProduct.value = { ...product };
        };

        const deleteProduct = async (id: number) => {
            if (!confirm("Are you sure you want to delete this product?"))
                return;
            await axios.delete(`/admin/product/${id}`);
            fetchProducts(pagination.value.current_page);
            showFlashMessage("Product deleted successfully!", "success");
        };

        onMounted(() => fetchProducts());

        return {
            products,
            editingProduct,
            showCreateModal,
            pagination,
            search,
            fetchProducts,
            openCreateModal,
            openEditModal,
            deleteProduct,
            flashMessage,
            flashType,
            showFlashMessage,
            columns,
        };
    },
});
</script>
