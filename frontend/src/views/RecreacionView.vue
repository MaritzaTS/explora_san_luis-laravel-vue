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
            <div class="col-6 col-md-2" v-for="filtro in tiposSitios" :key="String(filtro.id)">
              <button
                class="btn rounded-pill w-100 small fw-bold"
                :class="filtroSeleccionado === filtro.id ? 'btn-success' : 'btn-outline-success'"
                @click="seleccionarFiltro(filtro.id)">
                <i :class="filtro.icon + ' me-1'"></i> {{ formatNombre(filtro.nombre) }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- LISTADO -->
      <div class="row g-4" style="min-height: 400px;">
        <div v-if="cargando" class="col-12 text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando sitios de recreación...</p>
        </div>

        <template v-else>
          <div class="col-md-6 col-lg-4" v-for="sitio in sitiosFiltrados" :key="sitio.id">
            <div class="card border-0 shadow-sm h-100 overflow-hidden tourist-card">
              <div class="position-relative">
                <img :src="imagen(sitio)" class="card-img-top object-fit-cover" style="height: 220px;" :alt="sitio.nombre_comercial">
              </div>
              <div class="card-body p-4">
                <h5 class="fw-bold mb-0">{{ sitio.nombre_comercial }}</h5>
                <small class="text-success fw-bold text-uppercase">{{ formatNombre(sitio.subtipos?.[0]?.nombre ?? '') }}</small>
                <p class="text-muted small mb-3 mt-2">{{ sitio.descripcion }}</p>
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                  <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ sitio.hora_atencion }}</span>
                  <a v-if="sitio.telefono" :href="'tel:' + sitio.telefono" class="btn btn-success btn-sm rounded-circle shadow-sm">
                    <i class="bi bi-telephone"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div v-if="!sitiosFiltrados.length" class="col-12 text-center py-5 text-muted">
            <i class="bi bi-search fs-1"></i>
            <p class="mt-3">No se encontraron resultados.</p>
          </div>
        </template>
      </div>

      <!-- PAGINACIÓN -->
      <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
        <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === 1" @click="irAnterior">
          <i class="bi bi-chevron-left fs-4"></i>
        </button>
        <span class="fw-bold h5 mb-0">{{ pagina }} de {{ totalPaginas }}</span>
        <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === totalPaginas" @click="irSiguiente">
          <i class="bi bi-chevron-right fs-4"></i>
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('recreacion')

// null = sin filtro (mostrar todos)
const filtroSeleccionado = ref(null)

const tiposSitios = computed(() => [
  { id: null, nombre: 'Todos', icon: 'bi-grid' },
  ...subtipos.value.map((s) => ({ id: s.id, nombre: s.nombre, icon: 'bi-tag' })),
])

function seleccionarFiltro(id) {
  filtroSeleccionado.value = id
  filtros.value = id ? [id] : []
}

const sitiosFiltrados = computed(() => entidades.value)

const imagen  = (e) => e.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/400x250?text=Sin+imagen'
const formatNombre = (txt) => txt.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())

onMounted(init)
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