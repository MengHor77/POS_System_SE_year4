<template>
  <FrontendLayout>
    <div class="text-center mt-10">
      <h1 class="text-4xl font-bold text-primary">Home Page</h1>
    </div>
  </FrontendLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FrontendLayout from '../../layouts/FrontendLayout.vue';

export default defineComponent({
  name: 'Home',
  components: { FrontendLayout }
});
</script>
