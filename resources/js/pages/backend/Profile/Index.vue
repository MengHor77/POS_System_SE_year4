<template>
    <BackendLayout>
        <div class="flex justify-center mt-10 px-4">
            <!-- Card Container -->
            <div class="bg-bgCard p-8 rounded-2xl shadow-md w-full max-w-lg">
                <h1 class="text-3xl font-bold mb-6 text-primary text-center">
                    Admin Profile
                </h1>

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
                            placeholder="Enter old password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <span
                            class="absolute pt-6 inset-y-0 right-3 flex items-center text-xl text-gray-500 hover:text-gray-700 cursor-pointer"
                            @click="showOldPassword = !showOldPassword"
                        >
                            <i
                                :class="
                                    showOldPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </span>
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
                        <span
                            class="absolute pt-6  inset-y-0 right-3 flex items-center text-xl text-gray-500 hover:text-gray-700 cursor-pointer"
                            @click="showNewPassword = !showNewPassword"
                        >
                            <i
                                :class="
                                    showNewPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <label class="block mb-1 font-semibold"
                            >Confirm Password</label
                        >
                        <input
                            :type="showNewPassword ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <span
                            class="absolute pt-6 inset-y-0 right-3 flex items-center text-xl text-gray-500 hover:text-gray-700 cursor-pointer"
                            @click="showNewPassword = !showNewPassword"
                        >
                            <i
                                :class="
                                    showNewPassword
                                        ? 'fas fa-eye-slash'
                                        : 'fas fa-eye'
                                "
                            ></i>
                        </span>
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
        </div>
    </BackendLayout>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import BackendLayout from "../../../layouts/BackendLayout.vue";

export default defineComponent({
    name: "Profile",
    components: { BackendLayout },
    setup() {
        const form = ref({
            name: "",
            email: "",
            old_password: "",
            password: "",
            password_confirmation: "",
        });

        const showOldPassword = ref(false);
        const showNewPassword = ref(false);

        // Fetch admin profile
        const fetchProfile = async () => {
            try {
                const res = await axios.get("/admin/profile");
                form.value.name = res.data.name;
                form.value.email = res.data.email;
            } catch (err) {
                console.error("Failed to fetch profile", err);
            }
        };

        // Update admin profile
        const updateProfile = async () => {
            try {
                const res = await axios.post("/admin/profile", form.value);
                alert(res.data.message);

                // Clear passwords
                form.value.old_password = "";
                form.value.password = "";
                form.value.password_confirmation = "";
            } catch (err: any) {
                alert(err.response?.data?.message || "Update failed");
            }
        };

        onMounted(fetchProfile);

        return { form, showOldPassword, showNewPassword, updateProfile };
    },
});
</script>
