<template>
    <div
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm"
    >
        <div
            class="bg-bgCard p-6 rounded-xl2 w-full max-w-md shadow-card border border-border"
        >
            <h2 class="text-xl font-bold mb-4 text-darkSoft">
                Stock In - {{ product.name }}
            </h2>

            <form @submit.prevent="submit">
                <label class="block text-sm font-medium text-muted mb-1"
                    >Quantity</label
                >
                <input
                    type="number"
                    v-model="qty"
                    min="1"
                    class="w-full border border-border rounded-lg p-2 mb-4 focus:ring-2 focus:ring-primary/20 outline-none"
                    required
                />

                <label class="block text-sm font-medium text-muted mb-1"
                    >Reason / Note</label
                >
                <textarea
                    v-model="note"
                    class="w-full border border-border rounded-lg p-2 mb-4 focus:ring-2 focus:ring-primary/20 outline-none"
                    placeholder="Enter reason for stock adjustment..."
                    rows="3"
                ></textarea>

                <div class="flex justify-end gap-3 mt-2">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded-lg bg-bgBtnCancel text-white hover:bg-bgBtnCancelHover"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-bgBtnSave hover:bg-bgBtnSaveHover text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-soft"
                    >
                        Save Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType } from "vue";
import axios from "axios";

interface Product {
    id: number;
    name: string;
    brand: string;
    barcode: string;
    price: number;
    stock: number;
}

export default defineComponent({
    props: {
        product: {
            type: Object as PropType<Product>,
            required: true,
        },
    },
    // Changed "success" to "created" to match your parent's @created listener
    emits: ["close", "created"],

    setup(props, { emit }) {
        const qty = ref(1);
        const note = ref("");

        const submit = async () => {
            try {
                await axios.post(
                    `/admin/inventory/${props.product.id}/stock-in`,
                    {
                        qty: qty.value,
                        note: note.value,
                    },
                );

                // 1. Tell parent to refresh data
                emit("created");

                // 2. Close the modal
                emit("close");
            } catch (error) {
                console.error("Stock update failed", error);
                // You could add a flash message here
            }
        };

        return { qty, note, submit };
    },
});
</script>
