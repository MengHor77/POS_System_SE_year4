<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-indigo-100 via-white to-green-100">
    <div class="bg-white shadow-xl rounded-2xl w-full max-w-lg p-10 border border-gray-200">
      <!-- Header -->
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Admin Login</h2>
        <p class="text-gray-500">Enter your credentials to access the POS system</p>
      </div>

      <!-- Login Form -->
      <form method="POST" action="/admin/login" class="space-y-6">
        <!-- CSRF -->
        <input type="hidden" name="_token" :value="csrfToken" />

        <!-- Email -->
        <div>
          <label class="block text-gray-700 font-medium mb-2">Email</label>
          <input
            name="email"
            type="email"
            placeholder="admin@example.com"
            class="w-full border border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-indigo-500"
            required
          />
        </div>

        <!-- Password -->
        <div class="relative">
          <label class="block text-gray-700 font-medium mb-2">Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            name="password"
            placeholder="********"
            class="w-full border border-gray-300 rounded-lg py-3 px-4 pr-10 focus:ring-2 focus:ring-indigo-500"
            required
          />
          <button
            type="button"
            @click="togglePassword"
            class="absolute right-3 top-3 text-gray-500 focus:outline-none"
          >
            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </button>
        </div>

        <!-- Error Message -->
        <p v-if="error" class="text-red-500 text-center text-sm">{{ error }}</p>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition duration-300"
        >
          Login
        </button>
      </form>

      <!-- Footer -->
      <div class="mt-6 text-center text-gray-400 text-sm">
        © {{ new Date().getFullYear() }} POS System. All rights reserved.
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
  name: "AdminLogin",
  setup() {
    const error = ref("");
    const csrfToken = ref("");
    const showPassword = ref(false);

    const togglePassword = () => {
      showPassword.value = !showPassword.value;
    };

    onMounted(() => {
      const el = document.getElementById("app");
      if (el) {
        csrfToken.value = el.dataset.csrf || "";
        error.value = el.dataset.error || "";
      }
    });

    return { error, csrfToken, showPassword, togglePassword };
  },
});
</script>

<style scoped>
/* Smooth input focus transition */
input:focus {
  transition: all 0.3s ease;
}

/* Button for eye icon */
button i {
  font-size: 1rem;
}
</style>
