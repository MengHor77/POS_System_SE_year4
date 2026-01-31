<template>
    <button
        @click="$emit('click')"
        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold flex items-center shadow-md transition-all active:scale-95"
    >
        <i class="fas fa-file-download mr-2"></i>
        Export Filtered to TXT
    </button>
</template>

<script lang="ts">
import { defineComponent, watch } from "vue";

export default defineComponent({
    props: {
        transactions: { type: Array as () => any[], required: true },
        reportDate: { type: String, default: "" },
        totalRevenue: { type: Number, default: 0 },
    },
    emits: ["click"],
    setup(props) {
        const formatCurr = (val: number) =>
            "$" + val.toLocaleString(undefined, { minimumFractionDigits: 2 });

        const generateAndDownloadTxt = () => {
            if (props.transactions.length === 0) return;

            const wId = 8;
            const wCash = 20;
            const wItem = 30;
            const wPrice = 12;
            const wTotal = 14;
            const wDate = 16;

            let txt = `====================================================================================================\n`;
            txt += `                                SALES TRANSACTION REPORT (FILTERED)\n`;
            txt += `                                Generated: ${props.reportDate}\n`;
            txt += `====================================================================================================\n\n`;

            const header =
                `ID`.padEnd(wId) +
                `CASHIER`.padEnd(wCash) +
                `ITEMS SOLD`.padEnd(wItem) +
                `PRICE/UNIT`.padStart(wPrice) +
                `TOTAL`.padStart(wTotal) +
                `  ` +
                `DATE`.padEnd(wDate) +
                `\n`;

            txt += header;
            txt += `-`.repeat(100) + `\n`;

            props.transactions.forEach((order) => {
                const idStr = `${order.id}`.padEnd(wId);

                const rawCashier = (order.cashier_name || "N/A").substring(
                    0,
                    wCash - 2,
                );
                const cashierStr = rawCashier
                    .padStart(Math.floor((wCash + rawCashier.length) / 2))
                    .padEnd(wCash);

                const totalStr = formatCurr(order.total_amount).padStart(
                    wTotal,
                );

                const rawDate = (order.date_formatted || "").substring(
                    0,
                    wDate,
                );
                const dateStr =
                    `  ` +
                    rawDate
                        .padStart(Math.floor((wDate + rawDate.length) / 2))
                        .padEnd(wDate);

                const emptyIdCashier = " ".repeat(wId + wCash);
                const emptyTotalDate = " ".repeat(wTotal + wDate + 2);

                if (order.products && order.products.length > 0) {
                    order.products.forEach((p: any, index: number) => {
                        const pName = `${p.name} x${p.qty}`
                            .substring(0, wItem - 1)
                            .padEnd(wItem);
                        const pPrice = formatCurr(p.price).padStart(wPrice);

                        if (index === 0) {
                            txt += `${idStr}${cashierStr}${pName}${pPrice}${totalStr}${dateStr}\n`;
                        } else {
                            txt += `${emptyIdCashier}${pName}${pPrice}${emptyTotalDate}\n`;
                        }
                    });
                }
                txt += `-`.repeat(100) + `\n`;
            });

            txt += `\nSUMMARY:\n`;
            txt += `Total Revenue: ${formatCurr(props.totalRevenue)}\n`;
            txt += `Total transactions:  ${props.transactions.length}\n`;
            txt += `======================================= END OF REPORT ===============================================\n`;

            const blob = new Blob([txt], { type: "text/plain" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = `Report_${new Date().getTime()}.txt`;
            link.click();
            window.URL.revokeObjectURL(url);
        };

        watch(
            () => props.transactions,
            (newVal) => {
                if (newVal && newVal.length > 0) {
                    generateAndDownloadTxt();
                }
            },
            { deep: true },
        );

        return { generateAndDownloadTxt };
    },
});
</script>
