<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <Header />

      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Header from '../components/Header.vue';
import Sidebar from '../components/Sidebar.vue';

export default defineComponent({
  name: 'BackendLayout',
  components: { Header, Sidebar },
});
</script>
