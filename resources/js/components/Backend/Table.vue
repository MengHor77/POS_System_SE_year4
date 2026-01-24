<template>
    <div class="bg-bgCard rounded-xl shadow-card p-6">
        <table class="w-full border-border rounded-lg overflow-hidden">
            <thead class="bg-tableHeader text-sm">
                <tr
                    class="bg-gray-100 rounded-lg border"
                    :style="{ backgroundColor: 'var(--table-header)' }"
                >
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        class="p-3 border-y text-start"
                    >
                        {{ col.label }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="row in data"
                    :key="row[rowKey]"
                    class="text-sm hover:bg-tableRowHover transition"
                >
                    <td
                        v-for="col in columns"
                        :key="col.key"
                        class="p-3 border-y text-start"
                    >
                        <!-- Default cell -->
                        <slot
                            :name="`cell-${col.key}`"
                            :row="row"
                        >
                            {{ row[col.key] }}
                        </slot>
                    </td>
                </tr>

                <tr v-if="data.length === 0">
                    <td
                        :colspan="columns.length"
                        class="p-4 text-center text-muted"
                    >
                        No data available
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

interface Column {
    key: string;
    label: string;
}

export default defineComponent({
    name: "Table",
    props: {
        columns: {
            type: Array as PropType<Column[]>,
            required: true,
        },
        data: {
            type: Array as PropType<any[]>,
            required: true,
        },
        rowKey: {
            type: String,
            default: "id",
        },
    },
});
</script>
