<template>
  <div class="sitios-turisticos">
    <!-- El banner superior se gestiona automáticamente en el DefaultLayout -->

    <section class="container my-5">
      <!-- ENCABEZADO DE IMPACTO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Descubre el Paraíso</h1>
        <p class="text-muted fs-5">San Luis: Donde cada rincón es una nueva aventura por vivir.</p>
        <hr class="opacity-100 text-success mx-auto" style="height: 3px; width: 60px;">
      </div>

      <!-- FILTROS MODERNOS -->
      <div class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-success bg-success text-white px-3 py-1 fw-bold small shadow-sm rounded-pill" 
             style="z-index: 1;">
          ¿Qué aventura buscas hoy?
        </div>
        
        <div class="border border-secondary-subtle bg-white p-4 pt-5 shadow-sm rounded-4">
          <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-2" v-for="filtro in tiposSitios" :key="filtro.id">
              <div class="form-check d-flex align-items-center p-0 justify-content-center filter-option">
                <input class="form-check-input d-none" 
                       type="radio" 
                       name="tipoSitio" 
                       :id="'f-' + filtro.id" 
                       :value="filtro.id"
                       v-model="filtroSeleccionado">
                <label class="btn btn-outline-success rounded-pill w-100 small fw-bold" :for="'f-' + filtro.id">
                  <i :class="filtro.icon + ' me-1'"></i> {{ filtro.nombre }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- LISTADO DE SITIOS TURÍSTICOS (GRID DE IMPACTO) -->
      <div class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="sitio in sitiosFiltrados" :key="sitio.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden tourist-card">
            <div class="position-relative">
              <img :src="sitio.imagen" class="card-img-top object-fit-cover" style="height: 220px;" :alt="sitio.nombre">
              <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-white text-dark shadow-sm rounded-pill fw-bold">
                  <i class="bi bi-star-fill text-warning"></i> {{ sitio.rating }}
                </span>
              </div>
            </div>
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h5 class="fw-bold mb-0">{{ sitio.nombre }}</h5>
                  <small class="text-success fw-bold text-uppercase">{{ sitio.categoria }}</small>
                </div>
              </div>
              <p class="text-muted small mb-3">{{ sitio.descripcion }}</p>
              <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ sitio.horario }}</span>
                <div class="d-flex gap-2">
                  <a :href="'https://wa.me/' + sitio.whatsapp" class="btn btn-success btn-sm rounded-circle shadow-sm">
                    <i class="bi bi-whatsapp"></i>
                  </a>
                  <button class="btn btn-outline-dark btn-sm rounded-circle shadow-sm">
                    <i class="bi bi-geo-alt"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PAGINACIÓN -->
      <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
        <button class="btn btn-link text-dark p-0 mx-4"><i class="bi bi-chevron-left fs-4"></i></button>
        <span class="fw-bold h5 mb-0">1 de 6</span>
        <button class="btn btn-link text-dark p-0 mx-4"><i class="bi bi-chevron-right fs-4"></i></button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const filtroSeleccionado = ref(1)

const tiposSitios = ref([
  { id: 1, nombre: 'Todos', icon: 'bi-grid' },
  { id: 2, nombre: 'Piscinas', icon: 'bi-water' },
  { id: 3, nombre: 'Fincas', icon: 'bi-house-heart' },
  { id: 4, nombre: 'Cascadas', icon: 'bi-moisture' },
  { id: 5, nombre: 'Aventura', icon: 'bi-bicycle' }
])

// Datos de ejemplo para impacto visual
const sitios = ref([
  {
    id: 1,
    nombre: 'La Huerta',
    categoria: 'Fincas Recreativas',
    descripcion: 'Un espacio natural rodeado de huertas orgánicas y aire puro sanluisano.',
    horario: '4:00 PM - 12:00 AM',
    rating: '4.8',
    whatsapp: '3100000000',
    imagen: 'https://via.placeholder.com/400x250',
    tipoId: 3
  },
  {
    id: 2,
    nombre: 'Río Samaná Adventure',
    categoria: 'Aventura Extrema',
    descripcion: 'Rafting en el último río libre de Antioquia. Una experiencia única en el mundo.',
    horario: '8:00 AM - 5:00 PM',
    rating: '5.0',
    whatsapp: '3100000000',
    imagen: 'https://via.placeholder.com/400x250',
    tipoId: 5
  }
  // Agregar más sitios...
])

const sitiosFiltrados = computed(() => {
  if (filtroSeleccionado.value === 1) return sitios.value
  return sitios.value.filter(s => s.tipoId === filtroSeleccionado.value)
})
</script>

<style scoped>
.tourist-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 1.2rem;
}

.tourist-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.object-fit-cover {
  object-fit: cover;
}

.filter-option input:checked + label {
  background-color: #198754;
  color: white;
}
</style>