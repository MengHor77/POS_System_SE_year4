<template>
  <BackendLayout>
    <div class="p-6 bg-bgMain min-h-screen">
      <h1 class="text-3xl font-bold mb-6">Edit Product</h1>

      <form @submit.prevent="updateProduct" class="bg-white p-6 rounded-2xl shadow-md max-w-lg">
        <input v-model="form.name" placeholder="Product Name" class="input-field mb-4" required />
        <input v-model="form.brand" placeholder="Brand" class="input-field mb-4" required />
        <input v-model="form.barcode" type="number" placeholder="Barcode" class="input-field mb-4" required />
        <input v-model="form.price" type="number" step="0.01" placeholder="Price" class="input-field mb-4" required />
        <input v-model="form.stock" type="number" placeholder="Stock" class="input-field mb-4" required />

        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-600">
          Update
        </button>
      </form>
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, reactive, onMounted } from "vue";
import BackendLayout from "../../../layouts/BackendLayout.vue";
import axios from "axios";
import { useRouter, useRoute } from "vue-router";

export default defineComponent({
  name: "ProductEdit",
  components: { BackendLayout },
  setup() {
    const router = useRouter();
    const route = useRoute();

    const form = reactive({
      name: "",
      brand: "",
      barcode: 0,
      price: 0,
      stock: 0,
    });

    const fetchProduct = async () => {
      try {
        const res = await axios.get(`/admin/products/${route.params.id}`);
        Object.assign(form, res.data);
      } catch (error) {
        console.error(error);
      }
    };

    const updateProduct = async () => {
      try {
        await axios.put(`/admin/products/${route.params.id}`, form);
        router.push("/admin/products");
      } catch (error) {
        console.error(error);
      }
    };

    onMounted(fetchProduct);

    return { form, updateProduct };
  },
});
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.5rem;
}
</style>
