<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div
      class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
    >
      <h2 class="text-2xl font-bold mb-4">Edit Cashier</h2>

      <form @submit.prevent="updateCashier" class="space-y-4">
        <!-- Name -->
        <div>
          <label class="block mb-1 font-semibold">Name</label>
          <input
            v-model="form.name"
            placeholder="Enter cashier name"
            class="input-field"
            required
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block mb-1 font-semibold">Email</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="Enter cashier email"
            class="input-field"
            required
          />
        </div>

        <!-- Password -->
        <div class="relative">
          <label class="block mb-1 font-semibold">Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="form.password"
            placeholder="Enter new password"
            class="input-field pr-10"
          />
          <!-- Eye Icon -->
          <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute top-1/2 right-2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
          >
            <span v-if="showPassword">🙈</span>
            <span v-else>👁️</span>
          </button>
        </div>

        <!-- Status -->
        <div>
          <label class="block mb-1 font-semibold">Status</label>
          <select v-model="form.status" class="input-field">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4 mt-6">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white"
          >
            Cancel
          </button>

          <button
            type="submit"
            class="px-4 py-2 rounded bg-bgBtnSave hover:bg-bgBtnSaveHover text-white"
          >
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch, ref } from "vue";
import axios from "axios";

interface Cashier {
  id: number;
  name: string;
  email: string;
  password?: string;
  status: "active" | "inactive";
}

export default defineComponent({
  name: "EditCashier",
  props: {
    cashier: {
      type: Object as () => Cashier,
      required: true,
    },
  },
  setup(props, { emit }) {
    const form = reactive<Cashier>({ ...props.cashier, password: "" });
    const showPassword = ref(false);

    // Watch for prop changes
    watch(
      () => props.cashier,
      (newVal) => Object.assign(form, { ...newVal, password: "" })
    );

    const updateCashier = async () => {
      try {
        // If password is empty, don't send it
        const payload = { ...form };
        if (!payload.password) delete payload.password;

        await axios.put(`/admin/cashier/${form.id}`, payload);
        emit("updated"); // notify parent
        emit("close");   // close modal
      } catch (error) {
        console.error(error);
        alert("Failed to update cashier.");
      }
    };

    return { form, showPassword, updateCashier };
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
  transition: border 0.2s;
}
.input-field:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}
</style>
