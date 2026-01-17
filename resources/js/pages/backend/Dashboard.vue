<template>
  <BackendLayout>
    <div>
      <h1 class="text-3xl font-bold text-indigo-600 mb-4">Dashboard</h1>
      <p>Welcome, Admin!</p>
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BackendLayout from '../../layouts/BackendLayout.vue';

export default defineComponent({
  name: 'Dashboard',
  components: { BackendLayout },
});
</script>
