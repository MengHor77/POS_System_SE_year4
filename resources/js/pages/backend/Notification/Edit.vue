<template>
  <!-- Modal Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-primary">Edit Product</h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
      </div>

      <!-- Form -->
      <form @submit.prevent="updateProduct" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Product Name</label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Enter product name"
            class="input-field"
            required
          />
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Brand</label>
          <input
            v-model="form.brand"
            type="text"
            placeholder="Enter brand"
            class="input-field"
            required
          />
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Barcode</label>
          <input
            v-model.number="form.barcode"
            type="number"
            min="0"
            placeholder="Enter barcode"
            class="input-field"
            required
          />
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Price</label>
          <input
            v-model.number="form.price"
            type="number"
            min="0"
            step="0.01"
            placeholder="Enter price"
            class="input-field"
            required
          />
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Stock</label>
          <input
            v-model.number="form.stock"
            type="number"
            min="0"
            placeholder="Enter stock quantity"
            class="input-field"
            required
          />
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4 mt-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white transition"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 rounded bg-bgBtnSave hover:bg-bgBtnSaveHover text-white transition"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch } from "vue";
import axios from "axios";

export default defineComponent({
  name: "EditProduct",
  props: {
    product: { type: Object, required: true },
  },
  setup(props, { emit }) {
    const form = reactive({ ...props.product });

    // Watch for prop changes when parent passes new product
    watch(
      () => props.product,
      (newVal) => Object.assign(form, newVal)
    );

    const updateProduct = async () => {
      try {
        await axios.put(`/admin/product/${form.id}`, form);
        emit("updated"); // notify parent to refresh table
        emit("close");   // close modal
      } catch (error) {
        console.error(error);
        alert("Failed to update product.");
      }
    };

    return { form, updateProduct };
  },
});
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #ccc;
  border-radius: 0.5rem;
  outline: none;
  transition: border 0.2s, box-shadow 0.2s;
}
.input-field:focus {
  border-color: #3b82f6; /* Tailwind blue-500 */
  box-shadow: 0 0 0 1px #3b82f6;
}
</style>
