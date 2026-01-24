<template>
    <div class="flex flex-col h-screen bg-gray-100 overflow-hidden">
        <Header />
        <main class="flex-1 overflow-hidden"> 
             <slot />
        </main>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import Header from "../components/Frontend/Header.vue";

export default defineComponent({
    name: "FrontendLayout",
    components: { Header },
    setup() {},
});
</script>
