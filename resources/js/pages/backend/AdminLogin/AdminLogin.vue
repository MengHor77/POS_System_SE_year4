<template>
    <div
        class="flex items-center justify-center min-h-screen bg-gradient-to-r from-primary/20 via-bgMain to-secondary/20"
    >
        <div
            class="bg-bgCard shadow-xl rounded-2xl w-full max-w-lg p-10 border border-border"
        >
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-dark mb-2">
                    Admin Login
                </h2>
                <p class="text-muted">
                    Enter your credentials to access the POS system
                </p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="/admin/login" class="space-y-6">
                <!-- CSRF -->
                <input type="hidden" name="_token" :value="csrfToken" />

                <!-- Email -->
                <div>
                    <label class="block text-dark font-medium mb-2">
                        Email
                    </label>
                    <input
                        name="email"
                        type="email"
                        placeholder="admin@example.com"
                        class="w-full border border-border rounded-lg py-3 px-4 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    />
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-dark font-medium mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            placeholder="********"
                            class="w-full border border-border rounded-lg py-3 px-4 pr-12 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />

                        <!-- Eye Icon -->
                        <button
                            type="button"
                            @click="togglePassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted hover:text-primary transition focus:outline-none"
                        >
                            <i
                                :class="
                                    showPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                                class="text-lg"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <p
                    v-if="error"
                    class="text-danger text-center text-sm font-medium"
                >
                    {{ error }}
                </p>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-lg font-semibold transition duration-300"
                >
                    Login
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-muted text-sm">
                © {{ new Date().getFullYear() }} POS System. All rights
                reserved.
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    name: "AdminLogin",
    setup() {
        const error = ref<string>("");
        const csrfToken = ref<string>("");
        const showPassword = ref<boolean>(false);

        const togglePassword = () => {
            showPassword.value = !showPassword.value;
        };

        onMounted(() => {
            const el = document.getElementById("app") as HTMLElement | null;
            if (el) {
                csrfToken.value = el.dataset.csrf || "";
                error.value = el.dataset.error || "";
            }
        });

        return {
            error,
            csrfToken,
            showPassword,
            togglePassword,
        };
    },
});
</script>

<style scoped>
input:focus {
    transition: all 0.25s ease;
}

button i {
    transition:
        color 0.2s ease,
        transform 0.2s ease;
}

button:hover i {
    transform: scale(1.1);
}
</style>
