<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-bgCard w-full max-w-2xl rounded-xl shadow-card p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-primary">Create Purchase Order</h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-danger text-2xl">&times;</button>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Product / Supplier</label>
          <select
            v-model="form.product_supplier_id"
            @change="onProductChange"
            class="w-full px-3 py-2 border rounded-lg"
            required
          >
            <option :value="null">-- Select Product --</option>
            <option v-for="item in products" :key="item.id" :value="item.id">
              {{ item.product.name }} ({{ item.supplier_name }})
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-semibold">Supplier Name</label>
          <input type="text" v-model="form.supplier_name" readonly class="w-full px-3 py-2 border rounded-lg bg-gray-100"/>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-semibold">Quantity</label>
          <input type="number" min="1" v-model.number="form.quantity" class="w-full px-3 py-2 border rounded-lg"/>
        </div>

        <div class="flex justify-end gap-3 mt-6">
          <button type="button" @click="$emit('close')" class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
  name: "PurchaseOrderCreate",
  emits: ["close", "saved"],
  setup(_, { emit }) {
    const products = ref<any[]>([]);
    const form = ref({
      product_supplier_id: null as number | null,
      supplier_name: "",
      quantity: 1
    });

    const loadProducts = async () => {
      const res = await axios.get("/admin/product-supplier");
      products.value = res.data;
    };

    const onProductChange = () => {
      const selected = products.value.find(p => p.id === form.value.product_supplier_id);
      form.value.supplier_name = selected ? selected.supplier_name : "";
    };

    const submit = async () => {
      try {
        await axios.post("/admin/purchase-order", form.value);
        emit("saved");
        emit("close");
      } catch (err) {
        console.error(err);
        alert("Failed to save purchase order.");
      }
    };

    onMounted(loadProducts);

    return { products, form, onProductChange, submit };
  }
});
</script>
