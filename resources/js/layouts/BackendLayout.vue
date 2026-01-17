<template>
  <div class="flex min-h-screen bg-gray-100">
    <Sidebar />
    <div class="flex-1 flex flex-col">
      <Header>
        <template #title>
          <slot name="page-title">Dashboard</slot>
        </template>
      </Header>
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Sidebar from '../components/Sidebar.vue';
import Header from '../components/Header.vue';

export default defineComponent({
  components: { Sidebar, Header }
});
</script>
