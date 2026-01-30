<template>
    <div
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >
        <div class="bg-bgCard w-full max-w-lg rounded-2xl p-6 shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-primary">Edit Admin</h2>
                <button
                    @click="$emit('close')"
                    class="text-gray-500 hover:text-red-500"
                >
                    ✕
                </button>
            </div>

            <FlassMessage :message="message" type="success" />
            <FlassMessage :message="errorMessage" type="error" />

            <form @submit.prevent="updateAdmin" class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Name</label>
                    <input
                        v-model="form.name"
                        class="w-full border rounded-lg px-4 py-2"
                        required
                    />
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full border rounded-lg px-4 py-2"
                        required
                    />
                </div>

                <div>
                    <label class="block font-medium mb-2">Old Password</label>
                    <div class="relative">
                        <input
                            :type="showOldPassword ? 'text' : 'password'"
                            v-model="form.old_password"
                            placeholder="Enter old password"
                            class="w-full border border-border rounded-lg py-3 px-4 pr-12 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted hover:text-primary transition focus:outline-none"
                            @click="showOldPassword = !showOldPassword"
                        >
                            <i
                                :class="
                                    showOldPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-2">New Password</label>
                    <div class="relative">
                        <input
                            :type="showNewPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Enter new password"
                            class="w-full border border-border rounded-lg py-3 px-4 pr-12 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted hover:text-primary transition focus:outline-none"
                            @click="showNewPassword = !showNewPassword"
                        >
                            <i
                                :class="
                                    showNewPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-2"
                        >Confirm Password</label
                    >
                    <div class="relative">
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full border border-border rounded-lg py-3 px-4 pr-12 bg-bgMain focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-muted hover:text-primary transition focus:outline-none"
                            @click="showConfirmPassword = !showConfirmPassword"
                        >
                            <i
                                :class="
                                    showConfirmPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end pt-4 space-x-2">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-bgBtnSave text-white rounded-lg"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import FlassMessage from "../../../components/Backend/FlassMessage.vue";

export default defineComponent({
    name: "EditProfile",
    components: { FlassMessage },
    props: { id: { type: Number, required: true } },
    emits: ["close", "updated"],
    setup(props, { emit }) {
        const form = ref({
            name: "",
            email: "",
            old_password: "",
            password: "",
            password_confirmation: "",
        });

        const message = ref("");
        const errorMessage = ref("");

        const showOldPassword = ref(false);
        const showNewPassword = ref(false);
        const showConfirmPassword = ref(false);

        const fetchAdmin = async () => {
            try {
                const res = await axios.get(`/admin/profile/${props.id}`);
                form.value.name = res.data.name;
                form.value.email = res.data.email;
            } catch {
                errorMessage.value = "Failed to load admin data";
            }
        };

        const updateAdmin = async () => {
            message.value = "";
            errorMessage.value = "";

            try {
                await axios.post(`/admin/profile/${props.id}`, form.value);
                message.value = "Updated successfully";
                emit("updated");
                setTimeout(() => emit("close"), 2000);
            } catch (err: any) {
                errorMessage.value =
                    err.response?.data?.message || "Update failed";
            }
        };

        onMounted(fetchAdmin);

        return {
            form,
            message,
            errorMessage,
            showOldPassword,
            showNewPassword,
            showConfirmPassword,
            updateAdmin,
        };
    },
});
</script>
