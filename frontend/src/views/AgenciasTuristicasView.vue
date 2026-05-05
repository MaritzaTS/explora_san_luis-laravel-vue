<template>
  <div class="agencias-view">
    <!-- El HeroBanner se cargará automáticamente desde el Layout con el Carrusel -->
    
    <section class="container my-5">
      
      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Agencias Turísticas</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
        <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">
          Descubre las agencias turísticas locales de San Luis, Antioquia
          y planifica tu aventura perfecta.
        </p>
      </div>

      <!-- FILTROS (Solo se muestran si hay subtipos) -->
      <div v-if="subtipos.length > 0" class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
          Tipo de establecimiento
        </div>
        <div class="border border-secondary-subtle bg-light p-4 pt-5 shadow-sm rounded-1">
          <div class="row g-3">
            <div class="col-6 col-md-3" v-for="subtipo in subtipos" :key="subtipo.id">
              <div class="form-check d-flex align-items-center p-0">
                <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none check-custom"
                       type="checkbox"
                       :id="'check-' + subtipo.id"
                       :value="subtipo.id"
                       v-model="filtrosSeleccionados">
                <label class="form-check-label ms-2 small cursor-pointer" :for="'check-' + subtipo.id">
                  {{ subtipo.nombre }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTENEDOR DE ENTIDADES -->
      <div class="row g-4" style="min-height: 400px;">
        <!-- Estado de carga -->
        <div v-if="cargando" class="col-12 text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando agencias...</p>
        </div>

        <!-- Lista de Agencias -->
        <div v-else-if="agenciasFiltradas.length > 0" 
             class="col-md-6 col-lg-4" 
             v-for="agencia in agenciasFiltradas" 
             :key="agencia.id">
          <div class="card h-100 border-0 shadow-sm hover-card">
            <img :src="agencia.imagen" class="card-img-top object-fit-cover" style="height: 200px;" :alt="agencia.nombre">
            <div class="card-body">
              <h5 class="fw-bold">{{ agencia.nombre }}</h5>
              <p class="text-muted small mb-2"><i class="bi bi-tag-fill me-1"></i>{{ agencia.subtipoNombre }}</p>
              <p class="small text-secondary">{{ agencia.descripcion }}</p>
              <div class="d-flex gap-2 mt-3">
                <a :href="'https://wa.me/' + agencia.whatsapp" class="btn btn-success btn-sm w-100">
                  <i class="bi bi-whatsapp me-1"></i>WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Sin resultados -->
        <div v-else class="col-12 text-center py-5 text-muted">
          <i class="bi bi-info-circle fs-1"></i>
          <p class="mt-3">No se encontraron agencias con los filtros seleccionados.</p>
        </div>
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

// --- DATA MOCK (Esto vendrá de tu API Laravel) ---
const subtipos = ref([
  { id: 1, nombre: 'Guianza local' },
  { id: 2, nombre: 'Transporte Turístico' },
  { id: 3, nombre: 'Deportes de Aventura' },
  { id: 4, nombre: 'Avistamiento de aves' }
])

const agencias = ref([
  { 
    id: 101, 
    nombre: 'Aventuras San Luis', 
    subtipoId: 3, 
    subtipoNombre: 'Deportes de Aventura',
    descripcion: 'Rafting y senderismo por el Río Samaná.',
    whatsapp: '573000000000',
    imagen: 'https://via.placeholder.com/400x250'
    
  },
  { 
    id: 102, 
    nombre: 'Caminantes del Samaná', 
    subtipoId: 1, 
    subtipoNombre: 'Guianza local',
    descripcion: 'Tours especializados en avistamiento de aves.',
    whatsapp: '573000000000',
    imagen: 'https://via.placeholder.com/400x250'
  }
])

// --- LÓGICA ---
onMounted(() => {
  // Inicializamos los filtros seleccionados con todos los subtipos
  filtrosSeleccionados.value = subtipos.value.map(s => s.id)
  
  // Simulamos carga de API
  setTimeout(() => {
    cargando.value = false
  }, 800)
})

const agenciasFiltradas = computed(() => {
  if (filtrosSeleccionados.value.length === 0) return []
  return agencias.value.filter(a => filtrosSeleccionados.value.includes(a.subtipoId))
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

.cursor-pointer {
  cursor: pointer;
}

.hover-card {
  transition: transform 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
}

.object-fit-cover {
  object-fit: cover;
}
</style>