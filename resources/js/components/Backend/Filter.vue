<template>
  <div :class="containerClass" :style="containerStyle">
    <input
      v-model="filterValue"
      type="text"
      :placeholder="placeholder"
      :class="inputClass"
      :style="inputStyle"
      @keyup.enter="applyFilter"
    />
    <button
      @click="applyFilter"
      :class="buttonClass"
      :style="buttonStyle"
    >
      Filter
    </button>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
  name: "Filter",
  props: {
    modelValue: { type: String, default: "" },
    placeholder: { type: String, default: "Type to filter..." },
    containerClass: { type: String, default: "mb-4 flex gap-2" },
    containerStyle: { type: Object, default: () => ({}) },
    inputClass: { type: String, default: "border p-2 rounded flex-1" },
    inputStyle: { type: Object, default: () => ({}) },
    buttonClass: { type: String, default: "bg-primary text-white px-4 py-2 rounded" },
    buttonStyle: { type: Object, default: () => ({}) },
  },
  emits: ["update:modelValue", "filter"],
  setup(props, { emit }) {
    const filterValue = ref(props.modelValue);

    watch(
      () => props.modelValue,
      (newVal) => {
        filterValue.value = newVal;
      }
    );

    const applyFilter = () => {
      emit("update:modelValue", filterValue.value);
      emit("filter", filterValue.value);
    };

    return { filterValue, applyFilter };
  },
});
</script>
