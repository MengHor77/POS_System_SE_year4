<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Edit Category</h3>
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
                        class="px-4 py-2 rounded bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-bgBtnSave hover:bg-bgBtnSaveHover text-white"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch } from "vue";
import axios from "axios";

export default defineComponent({
    props: { category: { type: Object, required: true } },
    emits: ["close", "updated"],
    setup(props, { emit }) {
        const form = reactive({ name: "", description: "" });
        const errors = reactive<any>({});

        watch(
            () => props.category,
            (val) => {
                form.name = val.name;
                form.description = val.description;
            },
            { immediate: true },
        );

        const submit = async () => {
            errors.name = null;
            errors.description = null;

            try {
                await axios.put(`/admin/category/${props.category.id}`, form);
                emit("updated");
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
