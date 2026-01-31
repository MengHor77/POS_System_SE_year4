<template>
    <div class="bg-white p-6 rounded-xl2 shadow-card h-96 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-primary">
                Company Produce sales
            </h2>
            <span class="text-gray-400 text-xs font-medium"
                >In thousands (USD)</span
            >
        </div>

        <div class="flex-1 relative">
            <div
                v-if="!loaded"
                class="absolute inset-0 flex items-center justify-center"
            >
                <i
                    class="fas fa-circle-notch fa-spin text-green-600 text-2xl"
                ></i>
            </div>

            <Line v-if="loaded" :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import { Line } from "vue-chartjs";
import axios from "axios";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
    ChartOptions,
} from "chart.js";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
);

export default defineComponent({
    name: "SalesGraph",
    components: { Line },
    setup() {
        const loaded = ref(false);
        const chartData = ref<any>(null);

        const chartOptions: ChartOptions<"line"> = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#1e293b", // Dark tooltip
                    titleFont: { size: 13, weight: "bold" },
                    bodyFont: { size: 12 },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        title: (context: any) => `Month: ${context[0].label}`,
                        label: (context: any) =>
                            ` Revenue: $${context.parsed.y.toLocaleString()}k`,
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: "#f1f5f9" },
                    ticks: {
                        color: "#94a3b8",
                        callback: (val) => `$${val}`,
                    },
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: "#64748b",
                        font: { size: 11, weight: "bold" },
                    },
                },
            },
        };

        const fetchChartData = async () => {
            try {
                const res = await axios.get("/admin/dashboard/data");

                chartData.value = {
                    labels: res.data.chartLabels,
                    datasets: [
                        {
                            data: res.data.chartValues,
                            borderColor: "#16a34a", // THE GREEN LINE
                            backgroundColor: "transparent",
                            tension: 0, // SHARP ANGLES (No curves)
                            borderWidth: 2,
                            pointRadius: 6,
                            pointBackgroundColor: "#16a34a",
                            pointBorderColor: "#ffffff",
                            pointBorderWidth: 2,
                            pointHoverRadius: 8,
                            fill: false,
                        },
                    ],
                };

                loaded.value = true;
            } catch (err) {
                console.error("Chart Error:", err);
            }
        };

        onMounted(fetchChartData);

        return { chartData, chartOptions, loaded };
    },
});
</script>
