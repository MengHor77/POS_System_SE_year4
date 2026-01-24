<template>
    <FrontendLayout>
        <div class="flex flex-row gap-4 h-full bg-darkSoft p-4">
            <section class="flex-1 flex flex-col overflow-hidden">
                <div class="flex justify-between items-center mb-4 px-2">
                    <h2 class="text-2xl font-bold text-white">
                        Available Phones
                    </h2>
                    <div class="relative">
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"
                        ></i>
                        <input
                            v-model="search"
                            @input="fetchProducts"
                            type="text"
                            placeholder="Search barcode or name..."
                            class="border rounded-xl px-10 py-2 w-80 focus:ring-2 focus:ring-primary outline-none border-gray-200"
                        />
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div
                        v-if="loading"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                    >
                        <div
                            v-for="n in 8"
                            :key="n"
                            class="h-64 bg-white/20 animate-pulse rounded-[30px]"
                        ></div>
                    </div>

                    <div
                        v-else-if="products.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                    >
                        <CardProduct
                            v-for="item in products"
                            :key="item.id"
                            :product="item"
                            @add-to-cart="addToCart"
                        />
                    </div>

                    <div
                        v-else
                        class="flex flex-col items-center justify-center py-20 text-white/50"
                    >
                        <i class="fas fa-box-open text-6xl mb-4"></i>
                        <p class="text-xl font-medium">No products found.</p>
                    </div>
                </div>
            </section>

            <aside
                class="w-[400px] bg-white rounded-[30px] shadow-lg flex flex-col overflow-hidden my-2"
            >
                <div class="p-6 border-b">
                    <h2
                        class="text-xl font-bold text-gray-900 uppercase tracking-tight"
                    >
                        Current Order
                    </h2>
                </div>

                <div
                    class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar"
                >
                    <div
                        v-if="cart.length === 0"
                        class="flex flex-col items-center justify-center h-full text-gray-400"
                    >
                        <i
                            class="fas fa-shopping-cart text-5xl mb-4 opacity-20"
                        ></i>
                        <p>Your cart is empty</p>
                    </div>

                    <div
                        v-for="item in cart"
                        :key="item.id"
                        class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100"
                    >
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 text-lg">{{
                                item.name
                            }}</span>
                            <span class="text-sm text-gray-500 font-medium"
                                >Qty: {{ item.qty }}</span
                            >
                        </div>
                        <span class="font-bold text-info text-xl">
                            ${{ (Number(item.price) * item.qty).toFixed(2) }}
                        </span>
                    </div>
                </div>

                <div class="p-6 border-t bg-white">
                    <div class="flex justify-between items-end mb-6">
                        <span class="text-gray-500 font-bold uppercase text-sm"
                            >Total Amount</span
                        >
                        <span
                            class="text-4xl font-black text-gray-900 tracking-tighter"
                        >
                            ${{
                                cartTotal.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                })
                            }}
                        </span>
                    </div>
                    <button
                        @click="showPaymentModal = true"
                        :disabled="cart.length === 0"
                        class="w-full bg-[#0a0a14] text-white py-5 rounded-2xl font-bold text-xl hover:bg-primary transition-all active:scale-95 disabled:bg-gray-200"
                    >
                        Place Order
                    </button>
                </div>
            </aside>
        </div>
    </FrontendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import axios from "axios";
import FrontendLayout from "../../layouts/FrontendLayout.vue";
import CardProduct from "../../components/Frontend/CardProduct.vue";

interface Product {
    id: number;
    name: string;
    category: any;
    category_id: number;
    barcode: number;
    price: number | string;
    stock: number;
}

interface CartItem extends Product {
    qty: number;
}

export default defineComponent({
    name: "Home",
    components: { FrontendLayout, CardProduct },
    setup() {
        // --- State ---
        const products = ref<Product[]>([]);
        const cart = ref<CartItem[]>([]);
        const search = ref("");
        const loading = ref(true);
        const showPaymentModal = ref(false); // New state to fix error

        // --- Methods ---
        const fetchProducts = async () => {
            loading.value = true;
            try {
                const response = await axios.get("/admin/product/data", {
                    params: { search: search.value, per_page: 20 },
                });
                products.value = response.data.data || response.data;
            } catch (error) {
                console.error("Error fetching products:", error);
            } finally {
                loading.value = false;
            }
        };

        const addToCart = (product: Product) => {
            if (product.stock <= 0) return;
            const existing = cart.value.find((item) => item.id === product.id);
            if (existing) {
                existing.qty++;
            } else {
                cart.value.push({ ...product, qty: 1 });
            }
        };

        // --- Computed ---
        const cartTotal = computed(() => {
            return cart.value.reduce((acc, item) => {
                return acc + Number(item.price) * item.qty;
            }, 0);
        });

        // --- Lifecycle ---
        onMounted(() => {
            fetchProducts();
        });

        // --- IMPORTANT: Every variable/function used in <template> must be here ---
        return {
            products,
            cart,
            search,
            loading,
            showPaymentModal,
            fetchProducts,
            addToCart,
            cartTotal,
        };
    },
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}
aside .custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
}
</style>
