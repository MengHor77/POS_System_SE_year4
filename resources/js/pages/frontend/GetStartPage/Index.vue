<template>
  <div class="min-h-screen bg-bgMain flex flex-col items-center justify-center p-6">
    
    <div class="text-center mb-12">
      <h1 class="text-5xl font-black text-primary mb-2 tracking-tight">Phone Store POS</h1>
      <p class="text-muted text-xl font-medium">Inventory Management System</p>
    </div>

    <div class="flex flex-col md:flex-row gap-8 w-full max-w-5xl justify-center">
      
      <CardContinue 
        title="Admin"
        description="Full system access for management and analytics"
        subLabel="Manage products, inventory, and reports"
        iconBgColor="bg-infoSoft"
        buttonClass="bg-dark hover:bg-darkSoft text-white"
        @continue="navigateTo('/admin/login')"
      >
        <template #icon>
          <i class="fas fa-user-cog text-3xl text-iconEdit"></i>
        </template>
      </CardContinue>

      <CardContinue 
        title="Cashier"
        description="Point of Sale and customer service"
        subLabel="Process sales and manage transactions"
        iconBgColor="bg-successSoft"
        buttonClass="bg-white border-2 border-border text-dark hover:bg-bgHover"
        @continue="navigateTo('/cashier/login')"
      >
        <template #icon>
          <i class="fas fa-user text-3xl text-success"></i>
        </template>
      </CardContinue>

    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import CardContinue from "../../../components/Frontend/CardContinue.vue";

export default defineComponent({
  components: { CardContinue },
  setup() {
    const navigateTo = (path: string) => { window.location.href = path; };
    return { navigateTo };
  }
});
</script>