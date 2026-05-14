<template>
  <div class="card h-100 border-0 shadow-sm hover-card">
    <div class="card-img-wrapper">
      <img :src="imagen" class="card-logo" :alt="entidad.nombre_comercial">
    </div>
    <div class="card-body px-4 pb-4">
      <h5 class="fw-bold mb-1">{{ entidad.nombre_comercial }}</h5>
      <p class="text-muted small mb-0">
        <i class="bi bi-clock me-1"></i>{{ entidad.hora_atencion }}
      </p>
      <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2 rounded-pill">
          {{ subtipo }}
        </span>
        <a :href="'tel:' + entidad.telefono" class="btn btn-outline-success btn-sm rounded-circle">
          <i class="bi bi-telephone"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  entidad: { type: Object, required: true }
})

const imagen = computed(() =>
  props.entidad.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/400x250?text=Sin+imagen'
)

const subtipo = computed(() => {
  const nombre = props.entidad.subtipos?.[0]?.nombre ?? ''
  return nombre.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
})
</script>

<style scoped>
.hover-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 1rem;
  overflow: hidden;
}
.hover-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
}
.card-img-wrapper {
  background: #f8f9fa;
  height: 200px;
  display: flex;
  align-items: cente;
   justify-content: center;
  overflow: hidden;
  border-bottom: 1px solid #f0f0f0;
}
.card-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 1rem;
  transition: transform 0.3s ease;
}
.hover-card:hover .card-logo {
  transform: scale(1.05);
}
</style>