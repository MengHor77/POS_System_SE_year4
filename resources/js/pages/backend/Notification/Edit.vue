<template>
  <!-- Modal Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded w-96 shadow-lg">
      <h2 class="text-xl font-bold mb-4">Edit Product</h2>

      <div class="space-y-2">
        <input v-model="form.name" placeholder="Name" class="border p-2 w-full rounded" />
        <input v-model="form.brand" placeholder="Brand" class="border p-2 w-full rounded" />
        <input v-model="form.barcode" placeholder="Barcode" class="border p-2 w-full rounded" />
        <input v-model.number="form.price" placeholder="Price" type="number" class="border p-2 w-full rounded" />
        <input v-model.number="form.stock" placeholder="Stock" type="number" class="border p-2 w-full rounded" />
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button @click="$emit('close')" class="px-3 py-1 border rounded hover:bg-gray-100">Cancel</button>
        <button @click="updateProduct" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";
import axios from "axios";

export default defineComponent({
  name: "EditProduct",
  props: { product: { type: Object, required: true } },
  emits: ["close", "updated"],
  setup(props, { emit }) {
    const form = ref({ ...props.product });

    watch(() => props.product, (val) => {
      form.value = { ...val };
    });

    const updateProduct = async () => {
      try {
        const res = await axios.put(`/admin/notification/${form.value.id}`, form.value);
        alert(res.data.message || "Product updated successfully");
        emit("updated"); // Refresh parent table
        emit("close");   // Close modal
      } catch (err: any) {
        if (err.response?.status === 422) {
          const errors = err.response.data.errors;
          alert(Object.values(errors).map((e: any) => e[0]).join("\n"));
        } else {
          alert(err.response?.data?.message || "Failed to update product");
        }
      }
    };

    return { form, updateProduct };
  },
});
</script>
