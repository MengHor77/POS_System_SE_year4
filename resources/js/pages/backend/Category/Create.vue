<template>
    <div class="fixed inset-0 bg-black/30 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Create Category</h3>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="border p-2 w-full rounded"
                    />
                    <p v-if="errors.name" class="text-red-500 text-sm">
                        {{ errors.name[0] }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        class="border p-2 w-full rounded"
                    ></textarea>
                    <p v-if="errors.description" class="text-red-500 text-sm">
                        {{ errors.description[0] }}
                    </p>
                </div>

                <div class="flex justify-end gap-2">
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
import { defineComponent, reactive } from "vue";
import axios from "axios";

export default defineComponent({
    props: {},
    emits: ["close", "saved"],
    setup(_, { emit }) {
        const form = reactive({ name: "", description: "" });
        const errors = reactive<any>({});

        const submit = async () => {
            errors.name = null;
            errors.description = null;

            try {
                await axios.post("/admin/category", form);
                emit("saved");
            } catch (err: any) {
                if (err.response?.status === 422) {
                    Object.assign(errors, err.response.data.errors);
                }
            }
        };

        return { form, errors, submit };
    },
});
</script>
