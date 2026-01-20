<template>
    <BackendLayout>
        <div class="flex justify-center mt-10 px-4">
            <!-- Profile form card -->
            <div
                v-if="loaded"
                class="bg-bgCard p-8 rounded-2xl shadow-md w-full max-w-lg"
            >
                <h1 class="text-3xl font-bold mb-6 text-primary text-center">
                    Admin Profile
                </h1>

                <!-- Success / Error Messages -->
                <div
                    v-if="message"
                    class="mb-4 text-center text-green-600 font-semibold"
                >
                    {{ message }}
                </div>
                <div
                    v-if="errorMessage"
                    class="mb-4 text-center text-red-600 font-semibold"
                >
                    {{ errorMessage }}
                </div>

                <form @submit.prevent="updateProfile" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block mb-1 font-semibold">Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Enter name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block mb-1 font-semibold">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Enter email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                            required
                        />
                    </div>

                    <!-- Old Password -->
                    <div class="relative">
                        <label class="block mb-1 font-semibold"
                            >Old Password</label
                        >
                        <input
                            :type="showOldPassword ? 'text' : 'password'"
                            v-model="form.old_password"
                            placeholder="Enter old password if changing"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-3 flex items-center text-lg text-gray-500 hover:text-gray-700 transition"
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

                    <!-- New Password -->
                    <div class="relative">
                        <label class="block mb-1 font-semibold"
                            >New Password</label
                        >
                        <input
                            :type="showNewPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Enter new password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-3 flex items-center text-lg text-gray-500 hover:text-gray-700 transition"
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

                    <!-- Confirm Password -->
                    <div class="relative">
                        <label class="block mb-1 font-semibold"
                            >Confirm Password</label
                        >
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-3 flex items-center text-lg text-gray-500 hover:text-gray-700 transition"
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

                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
                        <button
                            type="submit"
                            class="px-6 py-2 rounded-lg bg-bgBtnSave text-white hover:bg-bgBtnSaveHover transition"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading Spinner -->
            <div v-else class="flex flex-col items-center justify-center h-64">
                <div
                    class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"
                ></div>
                <span class="text-gray-500">Loading profile...</span>
            </div>
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";

interface Form {
    name: string;
    email: string;
    old_password: string;
    password: string;
    password_confirmation: string;
}

export default defineComponent({
    name: "Profile",
    components: { BackendLayout },
    setup() {
        const form = ref<Form>({
            name: "",
            email: "",
            old_password: "",
            password: "",
            password_confirmation: "",
        });

        const loaded = ref(false); // ✅ smooth loading
        const showOldPassword = ref(false);
        const showNewPassword = ref(false);
        const showConfirmPassword = ref(false);
        const message = ref("");
        const errorMessage = ref("");

        // Fetch profile data
        const fetchProfile = async () => {
            try {
                const res = await axios.get("/admin/profile");
                form.value.name = res.data.name;
                form.value.email = res.data.email;
            } catch (err) {
                console.error(err);
                errorMessage.value = "Failed to load profile.";
            } finally {
                loaded.value = true; // ✅ show form only after data is ready
            }
        };

        // Update profile
        const updateProfile = async () => {
            try {
                const res = await axios.post("/admin/profile", form.value);
                message.value = res.data.message;
                errorMessage.value = "";
                form.value.old_password = "";
                form.value.password = "";
                form.value.password_confirmation = "";
            } catch (err: any) {
                errorMessage.value =
                    err.response?.data?.message || "Update failed";
                message.value = "";
            }
        };

        onMounted(fetchProfile);

        return {
            form,
            loaded,
            showOldPassword,
            showNewPassword,
            showConfirmPassword,
            updateProfile,
            message,
            errorMessage,
        };
    },
});
</script>

<style scoped>
.loader {
    border-top-color: #3498db;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
