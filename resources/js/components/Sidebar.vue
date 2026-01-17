<template>
  <aside class="w-64 bg-white shadow p-4 flex flex-col space-y-4">
    <router-link to="/admin/dashboard" class="text-dark hover:text-primary font-semibold">Dashboard</router-link>
    <router-link to="/admin/products" class="text-dark hover:text-primary font-semibold">Products</router-link>
    <router-link to="/admin/orders" class="text-dark hover:text-primary font-semibold">Orders</router-link>
  </aside>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({});
</script>
