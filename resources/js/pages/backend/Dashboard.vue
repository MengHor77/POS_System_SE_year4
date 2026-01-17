<template>
  <BackendLayout>
    <template #page-title>
      Dashboard
    </template>

    <div class="grid grid-cols-3 gap-6">
      <Card title="Total Products" :value="120" />
      <Card title="Total Orders" :value="35" />
      <Card title="Total Users" :value="50" />
    </div>
  </BackendLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BackendLayout from '../../layouts/BackendLayout.vue';
import Card from '../../components/Card.vue';

export default defineComponent({
  components: { BackendLayout, Card }
});
</script>
