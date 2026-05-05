<template>
  <div class="gastronomia-view">
    <!-- El carrusel se muestra automáticamente desde el DefaultLayout -->

    <section class="container my-5">
     
      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Gastronomía</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
        <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">
          Descubre los mejores lugares para comer en San Luis, Antioquia. 
          Usa los filtros de abajo para encontrar exactamente lo que buscas, 
          ya sea un restaurante, un café bar o comidas rápidas.
        </p>
      </div>

      <!-- FILTROS POR SUBTIPO (Reactivos) -->
      <div class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
          Tipo de establecimiento
        </div>

        <div class="border border-secondary-subtle bg-light p-4 pt-5 shadow-sm rounded-1">
          <div class="row g-3">
            <div class="col-6 col-md-3" v-for="subtipo in subtipos" :key="subtipo.id">
              <div class="form-check d-flex align-items-center p-0">
                <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none check-custom"
                       type="checkbox"
                       :id="'subtipo-' + subtipo.id"
                       :value="subtipo.id"
                       v-model="filtrosSeleccionados">
                <label class="form-check-label ms-2 small cursor-pointer" :for="'subtipo-' + subtipo.id">
                  {{ formatNombre(subtipo.nombre) }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTENEDOR DE ENTIDADES -->
      <div class="row g-4" id="contenedor-entidades" style="min-height: 500px;">
        <!-- Estado de carga -->
        <div v-if="cargando" class="col-12 text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando delicias locales...</p>
        </div>

        <!-- Cards de Gastronomía -->
        <template v-else>
          <div class="col-md-6 col-lg-4" v-for="entidad in entidadesFiltradas" :key="entidad.id">
            <div class="card h-100 border-0 shadow-sm hover-card">
              <img :src="entidad.imagen || 'https://via.placeholder.com/400x250?text=Gastronomía'" 
                   class="card-img-top object-fit-cover" 
                   style="height: 200px;" 
                   :alt="entidad.nombre">
              <div class="card-body">
                <h5 class="fw-bold mb-1">{{ entidad.nombre }}</h5>
                <p class="text-muted small mb-2">
                  <i class="bi bi-clock me-1"></i>{{ entidad.horario }}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="badge bg-secondary-subtle text-dark fw-normal">{{ entidad.subtipoNombre }}</span>
                  <a :href="'tel:' + entidad.telefono" class="btn btn-outline-success btn-sm rounded-circle">
                    <i class="bi bi-telephone"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Mensaje si no hay resultados -->
          <div v-if="entidadesFiltradas.length === 0" class="col-12 text-center py-5 text-muted">
            <i class="bi bi-search fs-1"></i>
            <p class="mt-3">No hay establecimientos que coincidan con tu selección.</p>
          </div>
        </template>
      </div>

      <!-- PAGINACIÓN -->
      <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
        <button class="btn btn-link text-dark p-0 mx-4" :disabled="paginaActual === 1" @click="paginaActual--">
          <i class="bi bi-chevron-left fs-4"></i>
        </button>
        <span class="fw-bold h5 mb-0">{{ paginaActual }} de {{ totalPaginas }}</span>
        <button class="btn btn-link text-dark p-0 mx-4" :disabled="paginaActual === totalPaginas" @click="paginaActual++">
          <i class="bi bi-chevron-right fs-4"></i>
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// --- ESTADO ---
const cargando = ref(true)
const paginaActual = ref(1)
const totalPaginas = ref(1)
const filtrosSeleccionados = ref([])

// --- DATA MOCK (Se reemplazará por Axios/Laravel) ---
const subtipos = ref([
  { id: 1, nombre: 'restaurante' },
  { id: 2, nombre: 'cafe_bar' },
  { id: 3, nombre: 'comidas_rapidas' },
  { id: 4, nombre: 'panaderia' }
])

const entidades = ref([
  { 
    id: 1, 
    nombre: 'Sabor Sanluisano', 
    subtipoId: 1, 
    subtipoNombre: 'Restaurante', 
    horario: '11:00 AM - 9:00 PM',
    telefono: '3100000000',
    imagen: null 
  },
  { 
    id: 2, 
    nombre: 'Café de la Montaña', 
    subtipoId: 2, 
    subtipoNombre: 'Café Bar', 
    horario: '3:00 PM - 11:00 PM',
    telefono: '3200000000',
    imagen: null 
  }
])

// --- MÉTODOS Y COMPUTADAS ---
onMounted(() => {
  // Por defecto seleccionamos todos los filtros
  filtrosSeleccionados.value = subtipos.value.map(s => s.id)
  
  // Simulación de carga
  setTimeout(() => {
    cargando.value = false
  }, 600)
})

const formatNombre = (txt) => {
  return txt.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const entidadesFiltradas = computed(() => {
  return entidades.value.filter(e => filtrosSeleccionados.value.includes(e.subtipoId))
})
</script>

<style scoped>
.tag-title {
  z-index: 1;
  margin-top: -1px;
}

.check-custom {
  width: 22px;
  height: 22px;
  cursor: pointer;
}

.hover-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.object-fit-cover {
  object-fit: cover;
}

.cursor-pointer {
  cursor: pointer;
}
</style>