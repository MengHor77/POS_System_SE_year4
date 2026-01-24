<template>
    <div
        class="flex items-center justify-center min-h-screen bg-bgMain bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-bgMain to-secondary/10"
    >
        <div
            class="bg-bgCard shadow-card rounded-xl2 w-full max-w-lg p-10 border border-border"
        >
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-infoSoft rounded-full mb-4"
                >
                    <i class="fas fa-user-shield text-primary text-2xl"></i>
                </div>
                <h2 class="text-3xl font-black text-dark mb-2 tracking-tight">
                    Admin Login
                </h2>
                <p class="text-muted font-medium">
                    Enter your credentials to access the management panel
                </p>
            </div>

            <form method="POST" action="/admin/login" class="space-y-6">
                <input type="hidden" name="_token" :value="csrfToken" />

                <div>
                    <label
                        class="block text-dark font-bold text-sm uppercase tracking-wider mb-2"
                    >
                        Admin Email
                    </label>
                    <input
                        name="email"
                        type="email"
                        placeholder="admin@example.com"
                        class="w-full border border-border rounded-xl py-4 px-5 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all shadow-sm"
                        required
                    />
                </div>

                <div>
                    <label
                        class="block text-dark font-bold text-sm uppercase tracking-wider mb-2"
                    >
                        Password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            placeholder="********"
                            class="w-full border border-border rounded-xl py-4 px-5 pr-14 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all shadow-sm"
                            required
                        />
                        <button
                            type="button"
                            @click="togglePassword"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-muted hover:text-primary transition-colors"
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

                <div
                    v-if="error"
                    class="bg-dangerSoft border border-danger/20 p-4 rounded-xl flex items-center gap-3"
                >
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <p class="text-danger text-sm font-bold leading-none">
                        {{ error }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-dark hover:bg-darkSoft text-white py-4 rounded-xl font-bold text-lg shadow-soft transition-all active:scale-95 flex items-center justify-center gap-2"
                >
                    <i class="fas fa-lock"></i>
                    Secure Login
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-border text-center">
                <a
                    href="/"
                    class="text-muted hover:text-primary font-bold text-sm transition-colors inline-flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    Back to selection
                </a>
            </div>

            <div
                class="mt-6 text-center text-grayLight text-xs font-semibold uppercase tracking-widest"
            >
                © {{ new Date().getFullYear() }} POS System
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
/* Scoped styles kept minimal as we use Tailwind config classes */
input:focus {
    transition: all 0.25s ease;
}
</style>
