<template>
  <FrontendLayout>
    <div class="flex flex-row gap-4 h-full bg-bgfrontend p-4 relative">
      
      <section class="flex-1 flex flex-col overflow-hidden no-print">
        <div class="flex justify-between items-center mb-4 px-2">
          <h2 class="text-2xl font-bold text-white tracking-tight">Available Phones</h2>
          <SearchProduct v-model="search" @search="fetchProducts" />
        </div>

        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
          <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="n in 6" :key="n" class="h-64 bg-white/5 animate-pulse rounded-[30px]"></div>
          </div>
          <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <CardProduct v-for="item in products" :key="item.id" :product="item" @add-to-cart="addToCart" />
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
import FrontendLayout from "../../layouts/FrontendLayout.vue";
import CardProduct from "../../components/Frontend/CardProduct.vue";
import SearchProduct from "../../components/Frontend/SearchProduct.vue";
import PaymentModal from "../../components/Frontend/PaymentModal.vue";
import Receipt from "../../components/Frontend/Receipt.vue";
import CartSidebar from "../../components/Frontend/CardSidebar.vue"; // New Import

export default defineComponent({
  name: "Home",
  components: { FrontendLayout, CardProduct, SearchProduct, PaymentModal, Receipt, CartSidebar },
  setup() {
    const products = ref<any[]>([]);
    const cart = ref<any[]>([]);
    const search = ref("");
    const loading = ref(true);
    const showPaymentModal = ref(false);

    const fetchProducts = async () => {
      loading.value = true;
      try {
        const response = await axios.get("/admin/product/data", { params: { search: search.value, per_page: 20 } });
        products.value = response.data.data || response.data;
      } finally { loading.value = false; }
    };

    const addToCart = (product: any) => {
      if (product.stock <= 0) return;
      const existing = cart.value.find((item) => item.id === product.id);
      if (existing) { existing.qty++; } else { cart.value.push({ ...product, qty: 1 }); }
    };

    const increaseQty = (id: number) => { 
      const item = cart.value.find(i => i.id === id); 
      if (item) item.qty++; 
    };

    const decreaseQty = (id: number) => { 
      const item = cart.value.find(i => i.id === id); 
      if (item && item.qty > 1) item.qty--; else removeFromCart(id); 
    };

    const removeFromCart = (id: number) => { 
      cart.value = cart.value.filter(i => i.id !== id); 
    };

    const handleCheckout = async () => {
      await nextTick();
      window.print();
      cart.value = [];
      showPaymentModal.value = false;
    };

    const cartTotal = computed(() => cart.value.reduce((acc, item) => acc + (Number(item.price) * item.qty), 0));

    onMounted(fetchProducts);

    return { products, cart, search, loading, showPaymentModal, fetchProducts, addToCart, cartTotal, increaseQty, decreaseQty, removeFromCart, handleCheckout };
  },
});
</script>

<style scoped>
/* Scoped styles to keep scrollbars looking consistent */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }

@media print {
  .no-print { display: none !important; }
  body * { visibility: hidden; }
  #print-section, #print-section * { visibility: visible; }
  #print-section { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>