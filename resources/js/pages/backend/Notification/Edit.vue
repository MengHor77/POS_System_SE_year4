<template>
  <!-- Modal Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-96 shadow-xl border border-gray-200">
      
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Form Fields -->
      <div class="space-y-3">
        <div>
          <label class="block text-gray-700 text-sm mb-1">Name</label>
          <input v-model="form.name" type="text" placeholder="Product Name" class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm mb-1">Brand</label>
          <input v-model="form.brand" type="text" placeholder="Brand" class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm mb-1">Barcode</label>
          <input v-model.number="form.barcode" type="number" placeholder="Barcode" class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm mb-1">Price</label>
          <input v-model.number="form.price" type="number" placeholder="Price" class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="block text-gray-700 text-sm mb-1">Stock</label>
          <input v-model.number="form.stock" type="number" placeholder="Stock" class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
      </div>

      <!-- Modal Actions -->
      <div class="flex justify-end gap-2 mt-5">
        <button 
          @click="$emit('close')" 
          class="px-4 py-2  bg-bgBtnCancel border rounded hover:bg-bgBtnCancelHover text-white"
        >
          Cancel
        </button>
        <button 
          @click="updateProduct" 
          class="px-4 py-2 bg-bgBtnSave  text-white rounded hover:bg-bgBtnSaveHover transition"
        >
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

    // Watch for prop changes to update form
    watch(() => props.product, (val) => {
      form.value = { ...val };
    });

    // Update product function
    const updateProduct = async () => {
      try {
        const res = await axios.put(`/admin/product/${form.value.id}`, form.value);
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
