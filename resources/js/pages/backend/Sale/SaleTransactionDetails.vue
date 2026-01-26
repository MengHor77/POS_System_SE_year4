<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    >
        <div
            class="bg-bgCard rounded-xl2 w-full max-w-lg shadow-card overflow-hidden animate-in fade-in zoom-in duration-200 border border-border"
        >
            <div
                class="p-5 border-b border-border flex justify-between items-center bg-tableHeader"
            >
                <h3 class="text-lg font-bold text-gray-800">
                    Sale Transaction Details
                </h3>
                <button
                    @click="$emit('close')"
                    class="text-muted hover:text-danger transition"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="space-y-3">
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Transaction ID:</span
                            >
                            <span class="font-bold text-gray-900"
                                >#{{ sale?.id }}</span
                            >
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Product ID:</span
                            >
                            <span class="text-gray-700">{{
                                sale?.product_id
                            }}</span>
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Product Name:</span
                            >
                            <span class="font-semibold text-primary">{{
                                sale?.product_name
                            }}</span>
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Category:</span
                            >
                            <span
                                class="px-2 py-0.5 bg-infoSoft text-info rounded text-xs font-bold"
                            >
                                {{ sale?.category }}
                            </span>
                        </p>
                    </div>
                    <div class="space-y-3 border-l border-border pl-4">
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Price per Unit:</span
                            >
                            <span class="text-gray-700"
                                >${{
                                    Number(sale?.unit_price).toFixed(2)
                                }}</span
                            >
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Quantity Sold:</span
                            >
                            <span class="font-bold text-gray-900">{{
                                sale?.quantity
                            }}</span>
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Total Amount:</span
                            >
                            <span class="text-xl font-bold text-success">
                                ${{ Number(sale?.total_amount).toFixed(2) }}
                            </span>
                        </p>
                        <p>
                            <span
                                class="text-muted block text-xxs uppercase tracking-wider font-bold"
                                >Date & Time:</span
                            >
                            <span class="text-gray-600">{{
                                sale?.created_at
                            }}</span>
                        </p>
                    </div>
                </div>

                <hr class="border-dashed border-border" />

                <div class="bg-infoSoft p-4 rounded-xl border border-info/20">
                    <p
                        class="text-xxs font-bold text-info uppercase mb-2 tracking-widest"
                    >
                        Processed By
                    </p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-soft"
                        >
                            {{ sale?.cashier_name?.charAt(0) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">
                                {{ sale?.cashier_name }}
                            </p>
                            <p class="text-xxs text-muted">
                                {{ sale?.cashier_email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-tableHeader text-right border-t border-border">
                <button
                    @click="$emit('close')"
                    class="px-6 py-2 bg-bgBtnCancel hover:bg-bgBtnCancelHover text-white rounded-lg transition font-medium shadow-soft text-sm"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface Sale {
    id: number;
    product_id: number;
    product_name: string;
    category: string;
    unit_price: number;
    cashier_name: string;
    cashier_email: string;
    quantity: number;
    total_amount: number;
    created_at: string;
}

export default defineComponent({
    name: "SaleTransactionDetails",
    props: {
        sale: {
            type: Object as PropType<Sale | null>,
            required: true,
        },
    },
    emits: ["close"],
});
</script>
