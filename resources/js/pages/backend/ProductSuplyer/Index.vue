<template>
  <BackendLayout>
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-6 text-primary">Product Suppliers</h1>

      <button @click="showCreate = true" class="mb-4 bg-dark text-white px-4 py-2 rounded">Add Supplier</button>

      <table class="w-full bg-bgCard rounded shadow-card">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3">Product</th>
            <th class="p-3">Supplier</th>
            <th class="p-3">Quantity</th>
            <th class="p-3">Price</th>
            <th class="p-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in suppliers" :key="item.id">
            <td class="p-3">{{ item.product?.name }}</td>
            <td class="p-3">{{ item.supplier_name }}</td>
            <td class="p-3">{{ item.quantity }}</td>
            <td class="p-3">{{ item.price }}</td>
            <td class="p-3 flex gap-2">
              <button @click="edit(item)" class="btn-edit">✏️</button>
              <button @click="remove(item.id)" class="btn-delete">🗑</button>
            </td>
          </tr>
        </tbody>
      </table>

      <CreateSupplier v-if="showCreate" @close="showCreate=false" @created="fetch" />
      <EditSupplier v-if="editing" :supplier="editing" @close="editing=null" @updated="fetch" />
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import BackendLayout from '../../../layouts/BackendLayout.vue';
import CreateSupplier from './Create.vue';
import EditSupplier from './Edit.vue';

interface Product { id: number; name: string; }

interface Supplier {
  id: number;
  product_id: number;       // Required for Edit.vue
  product?: Product;        // optional product object
  supplier_name: string;
  quantity: number;
  price: number;
}

export default {
  components: { BackendLayout, CreateSupplier, EditSupplier },
  setup() {
    const suppliers = ref<Supplier[]>([]);
    const showCreate = ref(false);
    const editing = ref<Supplier | null>(null);

    const fetch = async () => {
      const res = await axios.get('/admin/supplier/data');
      // Map each supplier to include product_id
      suppliers.value = res.data.data.map((s: any) => ({
        ...s,
        product_id: s.product.id
      }));
    };

    const edit = (item: Supplier) => {
      // Add product_id before sending to Edit.vue
      editing.value = { ...item, product_id: item.product?.id ?? 0 };
    };

    const remove = async (id: number) => {
      if (!confirm('Delete?')) return;
      await axios.delete(`/admin/supplier/${id}`);
      fetch();
    };

    onMounted(fetch);
    return { suppliers, showCreate, editing, fetch, edit, remove };
  },
};
</script>
