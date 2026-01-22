<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div
            class="bg-bgCard p-6 rounded-2xl shadow-md w-full max-w-lg relative"
        >
            <!-- Close -->
            <button
                @click="$emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold"
            >
                &times;
            </button>

            <h2 class="text-2xl font-bold mb-4 text-primary">
                Add New Cashier
            </h2>

            <form @submit.prevent="saveCashier" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Enter cashier name"
                        class="input-field"
                        required
                    />
                </div>

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

                <div>
                    <label class="block font-medium mb-2">Password</label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Enter password"
                            class="input-field pr-10"
                            required
                        />
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted transition focus:outline-none"
                            @click="showPassword = !showPassword"
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

                <div class="flex justify-end gap-4 mt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded-lg bg-bgBtnCancel text-white hover:bg-bgBtnCancelHover"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-bgBtnSave text-white hover:bg-bgBtnSaveHover"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import axios from "axios";

export default defineComponent({
    name: "CreateCashier",
    setup(_, { emit }) {
        const form = reactive({ name: "", email: "", password: "" });
        const showPassword = ref(false);

        const saveCashier = async () => {
            try {
                await axios.post("/admin/cashier", form);
                emit("created");
                emit("close");
            } catch (error) {
                console.error(error);
                alert("Failed to create cashier.");
            }
        };

        return { form, saveCashier, showPassword };
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
