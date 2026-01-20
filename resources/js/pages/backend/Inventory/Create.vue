<template>
    <div
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-xl w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">
                Stock In - {{ product.name }}
            </h2>

            <form @submit.prevent="submit">
                <label for="quantity">quantity</label>
                <input
                    type="number"
                    v-model="qty"
                    min="1"
                    class="w-full border rounded p-2 mb-4"
                    required
                />
                <label for="reason">reason</label>
                <textarea
                    v-model="note"
                    class="w-full border rounded p-2 mb-4"
                    placeholder="Note"
                ></textarea>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="$emit('close')">
                        Cancel
                    </button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded">
                        Save
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
    emits: ["close", "success"],

    setup(props, { emit }) {
        const qty = ref(1);
        const note = ref("");

        const submit = async () => {
            await axios.post(`/admin/inventory/${props.product.id}/stock-in`, {
                qty: qty.value,
                note: note.value,
            });
            emit("success");
            emit("close");
        };

        return { qty, note, submit };
    },
});
</script>
