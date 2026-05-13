<template>
  <!-- Aquí se renderizará DefaultLayout.vue y, dentro de él, cada página -->
  <router-view />
  <AppToast />
</template>

<script setup>
// No se necesita lógica adicional aquí por ahora
import AppToast from '@/components/ui/AppToast.vue'
</script>
