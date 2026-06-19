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
  height: 100%;
  display: flex;
  flex-direction: column;
  border-radius: 1rem;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1) !important;
}

/* Contenedor de la imagen */
.card-img-wrapper {
  height: 220px;
  min-height: 220px;
  background: linear-gradient(
    180deg,
    #fafafa 0%,
    #f4f4f4 100%
  );
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  overflow: hidden;
  border-bottom: 1px solid #f0f0f0;
}

/* Imagen */
.card-logo {
  max-width: 90%;
  max-height: 90%;
  width: auto;
  height: auto;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.hover-card:hover .card-logo {
  transform: scale(1.05);
}

/* Hace que todas las cards tengan la misma altura */
.card-body {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Empuja la parte inferior hacia abajo */
.card-body .d-flex {
  margin-top: auto;
}

.card-img-wrapper {
  height: 220px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f8f9fa;
}

.card-logo {
  width: 180px;
  height: 180px;
  border-radius: 12px;
  object-fit: cover;
}
</style>