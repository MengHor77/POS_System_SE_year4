<template>
  <div class="bg-bgCard rounded-xl2 p-10 shadow-card border border-border flex flex-col items-center text-center transition-all hover:shadow-soft w-full max-w-sm">
    
    <div :class="[iconBgColor, 'w-20 h-20 rounded-full flex items-center justify-center mb-6']">
       <slot name="icon"></slot>
    </div>

    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h3>
    
    <p class="text-muted text-sm mb-8 leading-relaxed">
      {{ description }}
    </p>

    <button 
      @click="$emit('continue')"
      :class="[
        buttonClass, 
        'w-full py-4 rounded-xl font-bold text-lg transition-all active:scale-95 mb-4'
      ]"
    >
      Continue as {{ title }}
    </button>

    <span class="text-xxs text-grayLight uppercase tracking-widest font-semibold">
        {{ subLabel }}
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "CardContinue",
  props: {
    title: String,
    description: String,
    subLabel: String,
    iconBgColor: { type: String, default: 'bg-infoSoft' }, // From your config
    buttonClass: { type: String, default: 'bg-dark hover:bg-darkSoft text-white' } // Using your dark colors
  },
  emits: ["continue"]
});
</script>