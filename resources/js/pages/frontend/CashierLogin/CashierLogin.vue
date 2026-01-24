<template>
    <div class="flex items-center justify-center min-h-screen bg-bgMain bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-secondary/10 via-bgMain to-primary/10">
        <div class="bg-bgCard shadow-card rounded-xl2 w-full max-w-lg p-10 border border-border">
            
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-successSoft rounded-full mb-4">
                    <i class="fas fa-user-tag text-success text-2xl"></i>
                </div>
                <h2 class="text-3xl font-black text-dark tracking-tight mb-2">
                    Cashier Login
                </h2>
                <p class="text-muted font-medium">
                    Access the POS terminal to start selling
                </p>
            </div>

            <form method="POST" action="/cashier/login" class="space-y-6">
                <input type="hidden" name="_token" :value="csrfToken" />

                <div>
                    <label class="block text-dark font-bold text-sm uppercase tracking-wider mb-2">
                        Work Email
                    </label>
                    <input
                        name="email"
                        type="email"
                        placeholder="cashier@store.com"
                        class="w-full border border-border rounded-xl py-4 px-5 bg-bgMain focus:outline-none focus:ring-2 focus:ring-secondary/50 transition-all shadow-sm"
                        required
                    />
                </div>

                <div>
                    <label class="block text-dark font-bold text-sm uppercase tracking-wider mb-2">
                        Secure Password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            placeholder="••••••••"
                            class="w-full border border-border rounded-xl py-4 px-5 pr-14 bg-bgMain focus:outline-none focus:ring-2 focus:ring-secondary/50 transition-all shadow-sm"
                            required
                        />
                        <button
                            type="button"
                            @click="togglePassword"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-muted hover:text-secondary transition-colors"
                        >
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-lg"></i>
                        </button>
                    </div>
                </div>

                <div v-if="error" class="bg-dangerSoft border border-danger/20 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <p class="text-danger text-sm font-bold leading-none">
                        {{ error }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-dark hover:bg-darkSoft text-white py-4 rounded-xl font-bold text-lg shadow-soft transition-all active:scale-95 flex items-center justify-center gap-2"
                >
                    <i class="fas fa-sign-in-alt"></i>
                    Start Shift
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-border text-center">
                <a href="/" class="text-muted hover:text-primary font-bold text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Back to selection
                </a>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    name: "CashierLogin",
    setup() {
        const error = ref<string>("");
        const csrfToken = ref<string>("");
        const showPassword = ref<boolean>(false);

        const togglePassword = () => {
            showPassword.value = !showPassword.value;
        };

        onMounted(() => {
            // Fetch CSRF and Error data from the root div meta-attributes
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