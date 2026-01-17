<template>
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="font-bold text-xl text-dark">
      <slot name="title">Dashboard</slot>
    </h1>
    <div>
      <button class="px-4 py-2 bg-danger text-white rounded hover:bg-red-600">
        Logout
      </button>
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
  name: 'Header'
});
</script>
