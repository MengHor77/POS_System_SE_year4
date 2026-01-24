<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative">
      <button @click="$emit('close')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold">
        &times;
      </button>

      <h2 class="text-2xl font-bold mb-4 text-primary">Edit Cashier</h2>

      <form @submit.prevent="updateCashier" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold">Name</label>
          <input v-model="form.name" type="text" class="input-field" required />
        </div>

        <div>
          <label class="block mb-1 font-semibold">Email</label>
          <input v-model="form.email" type="email" class="input-field" required />
        </div>

        <div>
          <label class="block mb-1 font-semibold">Status</label>
          <select v-model="form.status" class="input-field" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <hr class="border-gray-200 my-4" />
        <p class="text-sm text-gray-500 mb-2">Leave password fields empty if you don't want to change it.</p>

        <div>
          <label class="block mb-1 font-semibold">Old Password</label>
          <input :type="showPassword ? 'text' : 'password'" v-model="form.old_password" class="input-field" />
        </div>

        <div>
          <label class="block mb-1 font-semibold">New Password</label>
          <input :type="showPassword ? 'text' : 'password'" v-model="form.new_password" class="input-field" />
        </div>

        <div class="relative">
          <label class="block mb-1 font-semibold">Confirm New Password</label>
          <input :type="showPassword ? 'text' : 'password'" v-model="form.confirm_password" class="input-field pr-10" />
          <span class="absolute pt-6 inset-y-0 right-3 flex items-center text-xl text-gray-500 cursor-pointer" @click="showPassword = !showPassword">
            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </span>
        </div>

        <div class="flex justify-end gap-4 mt-6">
          <button type="button" @click="$emit('close')" class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-bgBtnSave hover:bg-bgBtnSaveHover text-white">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch, ref, PropType } from "vue";
import axios from "axios";

// FIXED: Added "?" to make password fields optional
interface Cashier {
  id: number;
  name: string;
  email: string;
  status: "active" | "inactive";
  old_password?: string;
  new_password?: string;
  confirm_password?: string;
}

export default defineComponent({
  name: "EditCashier",
  props: {
    // Use PropType to correctly map the interface
    cashier: { type: Object as PropType<Cashier>, required: true },
  },
  setup(props, { emit }) {
    // Initialize form with prop values + empty password strings
    const form = reactive({
      id: props.cashier.id,
      name: props.cashier.name,
      email: props.cashier.email,
      status: props.cashier.status,
      old_password: "",
      new_password: "",
      confirm_password: "",
    });

    const showPassword = ref(false);

    watch(() => props.cashier, (newVal) => {
      form.id = newVal.id;
      form.name = newVal.name;
      form.email = newVal.email;
      form.status = newVal.status;
      form.old_password = "";
      form.new_password = "";
      form.confirm_password = "";
    }, { deep: true });

    const updateCashier = async () => {
      const isChangingPassword = !!(form.old_password || form.new_password || form.confirm_password);

      if (isChangingPassword) {
        if (!form.old_password || !form.new_password || !form.confirm_password) {
          return alert("Please fill all password fields to change password.");
        }
        if (form.new_password !== form.confirm_password) {
          return alert("New password and confirm password do not match.");
        }
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
        alert(error.response?.data?.message ?? "Failed to update.");
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
}
.input-field:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}
</style>