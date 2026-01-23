<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div
      class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
    >
      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold"
      >
        &times;
      </button>

      <h2 class="text-2xl font-bold mb-4 text-primary">Edit Cashier</h2>

      <form @submit.prevent="updateCashier" class="space-y-4">
        <!-- Name -->
        <div>
          <label class="block mb-1 font-semibold">Name</label>
          <input
            v-model="form.name"
            type="text"
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
            class="input-field"
            required
          />
        </div>

        <!-- Status -->
        <div>
          <label class="block mb-1 font-semibold">Status</label>
          <select v-model="form.status" class="input-field" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <!-- Old Password -->
        <div>
          <label class="block mb-1 font-semibold">Old Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="form.old_password"
            placeholder="Enter old password (required if changing)"
            class="input-field"
          />
        </div>

        <!-- New Password -->
        <div>
          <label class="block mb-1 font-semibold">New Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="form.new_password"
            placeholder="Enter new password"
            class="input-field"
          />
        </div>

        <!-- Confirm New Password -->
        <div class="relative">
          <label class="block mb-1 font-semibold">Confirm New Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            v-model="form.confirm_password"
            placeholder="Confirm new password"
            class="input-field pr-10"
          />
          <span
            class="absolute pt-6 inset-y-0 right-3 flex items-center text-xl text-gray-500 hover:text-gray-700 transition"
            @click="showPassword = !showPassword"
          >
            <i
              :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
            ></i>
          </span>
        </div>

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
  status: "active" | "inactive";
  old_password: string;
  new_password: string;
  confirm_password: string;
}

export default defineComponent({
  name: "EditCashier",
  props: {
    cashier: { type: Object as () => Cashier, required: true },
  },
  setup(props, { emit }) {
    const form = reactive<Cashier>({
      ...props.cashier,
      old_password: "",
      new_password: "",
      confirm_password: "",
    });

    const showPassword = ref(false);

    watch(
      () => props.cashier,
      (newVal) => {
        Object.assign(form, newVal);
        form.old_password = "";
        form.new_password = "";
        form.confirm_password = "";
      }
    );

    const updateCashier = async () => {
      // Only validate password if user wants to change
      const changePassword =
        form.old_password || form.new_password || form.confirm_password;

      if (changePassword) {
        if (!form.old_password || !form.new_password || !form.confirm_password) {
          return alert("Please fill all password fields to change password.");
        }
        if (form.new_password !== form.confirm_password) {
          return alert("New password and confirm password do not match.");
        }
      }

      if (!form.name || !form.email || !form.status) {
        return alert("Please fill in all required fields.");
      }

      try {
        await axios.put(`/admin/cashier/${form.id}`, {
          name: form.name,
          email: form.email,
          status: form.status,
          old_password: form.old_password || undefined,
          new_password: form.new_password || undefined,
          new_password_confirmation: form.confirm_password || undefined,
        });

        emit("updated");
        emit("close");
      } catch (error: any) {
        alert(error.response?.data?.message ?? "Failed to update cashier.");
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
