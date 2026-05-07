<template>
  <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
    <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === 1" @click="$emit('anterior')">
      <i class="bi bi-chevron-left fs-4"></i>
    </button>
    <span class="fw-bold h5 mb-0">{{ pagina }} de {{ totalPaginas }}</span>
    <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === totalPaginas" @click="$emit('siguiente')">
      <i class="bi bi-chevron-right fs-4"></i>
    </button>
  </div>
</template>

<script setup>
defineProps({
  pagina:       { type: Number, required: true },
  totalPaginas: { type: Number, required: true },
})
defineEmits(['anterior', 'siguiente'])
</script>
