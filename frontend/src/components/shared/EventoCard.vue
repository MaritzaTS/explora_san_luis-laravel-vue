<template>
  <div class="card h-100 border-0 shadow-sm hover-card">
    <div class="card-img-wrapper">
      <img :src="poster" class="card-logo" :alt="evento.nombre">
    </div>
    <div class="card-body px-4 pb-4">
      <div class="evento-fecha-badge mb-2">
        <i class="bi bi-calendar3 me-1"></i>
        {{ formatFecha(evento.fecha_inicio) }}
        <span v-if="evento.fecha_fin"> — {{ formatFecha(evento.fecha_fin) }}</span>
      </div>
      <h5 class="fw-bold mb-1">{{ evento.nombre }}</h5>
      <p class="small text-muted mb-1" v-if="evento.lugar">
        <i class="bi bi-geo-alt me-1 text-success"></i>{{ evento.lugar }}
      </p>
      <p class="small text-secondary mb-0 evento-desc">{{ evento.descripcion }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  evento: { type: Object, required: true }
})

const poster = computed(() =>
  props.evento.url_poster ?? 'https://via.placeholder.com/400x250?text=Evento'
)

function formatFecha(fecha) {
  if (!fecha) return ''
  return new Date(fecha).toLocaleDateString('es-CO', {
    year: 'numeric', month: 'long', day: 'numeric'
  })
}
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
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-bottom: 1px solid #f0f0f0;
}
.card-logo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}
.hover-card:hover .card-logo {
  transform: scale(1.05);
}
.evento-fecha-badge {
  display: inline-flex;
  align-items: center;
  background: #f0faf4;
  color: #198754;
  border: 1px solid #b7dfc9;
  border-radius: 999px;
  padding: 0.25rem 0.85rem;
  font-size: 0.78rem;
  font-weight: 700;
}
.evento-desc {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>