<template>
    <FrontendLayout>
        <div class="flex flex-row gap-4 h-full bg-bgfrontend p-4 relative">
            <section class="flex-1 flex flex-col overflow-hidden no-print">
                <div class="flex justify-between items-center mb-4 px-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">
                        Available Phones
                    </h2>
                    <SearchProduct v-model="search" @search="fetchProducts" />
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div
                        v-if="loading"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="n in 6"
                            :key="n"
                            class="h-64 bg-white/5 animate-pulse rounded-[30px]"
                        ></div>
                    </div>
                    <div
                        v-else-if="products.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
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
                        class="flex justify-center items-center h-64 text-gray-400"
                    >
                        No products found.
                    </div>
                </div>
            </section>

            <CartSidebar
                :cart="cart"
                :total="cartTotal"
                @increase="increaseQty"
                @decrease="decreaseQty"
                @remove="removeFromCart"
                @submit="showPaymentModal = true"
            />

            <PaymentModal
                v-if="showPaymentModal"
                :total="cartTotal"
                :cartItems="cart"
                @close="showPaymentModal = false"
                @confirm="handleCheckout"
            />

            <div id="print-section">
                <Receipt :items="cart" :total="cartTotal" />
            </div>
        </div>
    </FrontendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed, nextTick } from "vue";
import axios from "axios";
import FrontendLayout from "../../../layouts/FrontendLayout.vue";
import CardProduct from "../../../components/Frontend/CardProduct.vue";
import SearchProduct from "../../../components/Frontend/SearchProduct.vue";
import PaymentModal from "../../../components/Frontend/PaymentModal.vue";
import Receipt from "../../../components/Frontend/Receipt.vue";
import CartSidebar from "../../../components/Frontend/CardSidebar.vue";

export default defineComponent({
    name: "Home",
    components: {
        FrontendLayout,
        CardProduct,
        SearchProduct,
        PaymentModal,
        Receipt,
        CartSidebar,
    },
    setup() {
        // --- State ---
        const products = ref<any[]>([]);
        const cart = ref<any[]>([]);
        const search = ref("");
        const loading = ref(true);
        const showPaymentModal = ref(false);

        // --- Magic Logic: Real-time Stock Calculation ---
        /**
         * This computed property takes the master product list and subtracts
         * whatever is currently sitting in the user's cart.
         * This updates the UI instantly as they click.
         */
        const displayedProducts = computed(() => {
            return products.value.map((product) => {
                const cartItem = cart.value.find(
                    (item) => item.id === product.id,
                );
                const currentCartQty = cartItem ? cartItem.qty : 0;
                return {
                    ...product,
                    // Calculated stock = Database Stock - Cart Quantity
                    stock: product.stock - currentCartQty,
                };
            });
        });

        const cartTotal = computed(() => {
            return cart.value.reduce(
                (acc, item) => acc + Number(item.price) * item.qty,
                0,
            );
        });

        // --- Methods ---
        const fetchProducts = async () => {
            loading.value = true;
            try {
                const response = await axios.get("/pos/product", {
                    params: {
                        search: search.value,
                        per_page: 20,
                    },
                });

                if (response.data && response.data.data) {
                    products.value = response.data.data;
                } else {
                    products.value = response.data;
                }
            } catch (error) {
                console.error("Error fetching products:", error);
            } finally {
                loading.value = false;
            }
        };

        const addToCart = (product: any) => {
            // Important: Check stock against the computed "Live" stock
            const liveProduct = displayedProducts.value.find(
                (p) => p.id === product.id,
            );
            if (!liveProduct || liveProduct.stock <= 0) {
                alert("Out of stock!");
                return;
            }

            const existing = cart.value.find((item) => item.id === product.id);
            if (existing) {
                existing.qty++;
            } else {
                cart.value.push({ ...product, qty: 1 });
            }
        };

        const increaseQty = (id: number) => {
            // Check if we can increase (is there more stock available?)
            const product = products.value.find((p) => p.id === id);
            const item = cart.value.find((i) => i.id === id);

            if (item && product && item.qty < product.stock) {
                item.qty++;
            } else {
                alert("No more stock available!");
            }
        };

        const decreaseQty = (id: number) => {
            const item = cart.value.find((i) => i.id === id);
            if (item && item.qty > 1) {
                item.qty--;
            } else {
                removeFromCart(id);
            }
        };

        const removeFromCart = (id: number) => {
            cart.value = cart.value.filter((i) => i.id !== id);
        };

        const handleCheckout = async () => {
            if (cart.value.length === 0) return;

            try {
                // 1. Save to Database
                await axios.post("/pos/sale", {
                    items: cart.value.map((item) => ({
                        id: item.id,
                        qty: item.qty,
                        price: item.price,
                    })),
                });

                // 2. Trigger Print
                await nextTick();
                window.print();

                // 3. Success Workflow
                cart.value = []; // This resets the card stock labels to the 'pre-sale' values
                showPaymentModal.value = false;

                // 4. Refresh: Get the NEW official stock levels from DB
                await fetchProducts();

                alert("Transaction Successful!");
            } catch (err: any) {
                console.error(err);
                alert(err.response?.data?.message || "Failed to save sale");
            }
        };

        // --- Lifecycle ---
        onMounted(fetchProducts);

        return {
            // We return displayedProducts AS 'products' so the template doesn't need to change
            products: displayedProducts,
            cart,
            search,
            loading,
            showPaymentModal,
            fetchProducts,
            addToCart,
            cartTotal,
            increaseQty,
            decreaseQty,
            removeFromCart,
            handleCheckout,
        };
    },
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(128, 132, 131, 0.582);
    border-radius: 10px;
}

@media print {
    .no-print {
        display: none !important;
    }
    body * {
        visibility: hidden;
    }
    #print-section,
    #print-section * {
        visibility: visible;
    }
    #print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
