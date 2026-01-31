<template>
    <div class="bg-bgCard p-6 rounded-xl2 shadow-card h-96 flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-primary">Sales Analytics</h2>
        <div class="flex-1 relative">
            <div
                v-if="!loaded"
                class="absolute inset-0 flex items-center justify-center bg-bgMain rounded-lg border border-dashed border-grayLight"
            >
                <div class="text-center">
                    <i
                        class="fas fa-circle-notch fa-spin text-info text-2xl mb-2"
                    ></i>
                    <p
                        class="text-xxs text-muted uppercase tracking-widest font-bold"
                    >
                        Loading Data...
                    </p>
                </div>
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

        const myColors = {
            primary: "#4f46e5",
            info: "#0ea5e9",
            border: "#e5e7eb",
            muted: "#6b7280",
        };

        const chartOptions: ChartOptions<"line"> = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#4f46e5",
                    titleFont: {
                        size: 12,
                        weight: "bold" as const, // Fixed: as const ensures this is the literal 'bold'
                    },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: (context: any) =>
                            ` Revenue: $${Number(context.parsed.y).toLocaleString()}`,
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: "#f1f5f9" },
                    ticks: {
                        color: myColors.muted,
                        font: { size: 10 },
                        callback: (value: any) => "$" + value.toLocaleString(),
                    },
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: myColors.muted,
                        font: {
                            size: 10,
                            weight: 600,
                        },
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
                            label: "Monthly Revenue",
                            data: res.data.chartValues,
                            borderColor: myColors.primary,
                            backgroundColor: "rgba(79, 70, 229, 0.1)",
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: "#ffffff",
                            pointBorderColor: myColors.primary,
                            pointBorderWidth: 2,
                        },
                    ],
                };

                loaded.value = true;
            } catch (err) {
                console.error("Failed to load chart data:", err);
            }
        };

        onMounted(fetchChartData);

        return { chartData, chartOptions, loaded };
    },
});
</script>
