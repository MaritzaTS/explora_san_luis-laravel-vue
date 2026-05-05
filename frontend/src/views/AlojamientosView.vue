<template>
    

  <section class="container my-5">
      
      
      <!-- Título -->
      <div class="text-center mb-5">  
        <h1 class="display-4 fw-bold">Alojamiento</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
      </div>

      <!-- 2. Filtros (Reactivos con v-model) -->
      <div class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
          Tipo de establecimiento
        </div>
        
        <div class="border border-secondary-subtle bg-light p-5 pt-5 shadow-sm rounded-1">
          <div class="row g-3">
            <!-- Agregué la opción "Todos" para mejor UX -->
            <div class="col-6 col-md-2" v-for="opcion in opcionesFiltro" :key="opcion">
              <div class="form-check d-flex align-items-center p-0">
                <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none radio-custom" 
                       type="radio" 
                       name="tipoFiltro" 
                       :id="'radio-' + opcion" 
                       :value="opcion"
                       v-model="filtroActual">
                <label class="form-check-label ms-2 small cursor-pointer" 
                       :class="{ 'fw-bold': filtroActual === opcion }" 
                       :for="'radio-' + opcion">
                  {{ opcion }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Lista de Alojamientos (v-for dinámico) -->
      <div class="row g-5">
        <!-- Mostramos un mensaje si no hay resultados para el filtro -->
        <div v-if="alojamientosFiltrados.length === 0" class="col-12 text-center text-muted py-5">
          <i class="bi bi-search fs-1"></i>
          <p class="mt-3">No se encontraron alojamientos de este tipo.</p>
        </div>

        <div class="col-md-6" v-for="lugar in alojamientosFiltrados" :key="lugar.id">
          <div class="d-flex align-items-start border-0">
            <div class="flex-shrink-0 position-relative">
              <img :src="lugar.imagen" class="rounded-4 shadow-sm object-fit-cover" width="140" height="140" :alt="lugar.nombre">
              <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2 d-flex gap-1">
                <div class="bg-white rounded-circle indicator-small-active"></div>
                <div class="bg-white rounded-circle opacity-50 indicator-small"></div>
                <div class="bg-white rounded-circle opacity-50 indicator-small"></div>
              </div>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold mb-1">{{ lugar.nombre }}</h5>
              <p class="text-muted small mb-0">{{ lugar.tipo }}</p>
              <p class="text-muted small mb-0">Horario - {{ lugar.horario }}</p>
              <div class="mt-2 d-flex gap-3">
                <a :href="lugar.link" target="_blank" class="text-dark small fw-bold text-decoration-underline">Link <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                <a :href="'https://wa.me/' + lugar.whatsapp" target="_blank" class="text-success small fw-bold text-decoration-underline"><i class="bi bi-whatsapp"></i> WhatsApp</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. Paginación -->
      <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
        <button class="btn btn-link text-dark p-0 mx-4"><i class="bi bi-chevron-left fs-4"></i></button>
        <span class="fw-bold h5 mb-0">1 de 6</span>
        <button class="btn btn-link text-dark p-0 mx-4"><i class="bi bi-chevron-right fs-4"></i></button>
      </div>
  </section>
  
</template>

<script setup>
import { ref, computed } from 'vue'


// 1. Importas el componente reutilizable


// 2. Importas la imagen específica para esta vista


// ... resto de tu lógica (ref, computed, etc.) ...


// Opciones disponibles para los radio buttons
const opcionesFiltro = ['Todos', 'Hotel', 'Hostal', 'Glamping', 'Finca Hotel', 'Casa Amoblada']

// Por defecto, mostramos todos (puedes cambiarlo a 'Hostal' si lo prefieres)
const filtroActual = ref('Todos')

// DATA MOCK: Esta es la data que luego te enviará Laravel desde el Controller (Base de datos)
const alojamientos = ref([
  { 
    id: 1, 
    nombre: 'La Estrella', 
    tipo: 'Hotel', 
    horario: '4:00 pm - 11:59 pm', 
    whatsapp: '573000000000',
    link: '#',
    imagen: 'https://via.placeholder.com/150' 
  },
  { 
    id: 2, 
    nombre: 'El Mirador', 
    tipo: 'Hostal', 
    horario: 'Abierto 24h', 
    whatsapp: '573000000000',
    link: '#',
    imagen: 'https://via.placeholder.com/150' 
  },
  { 
    id: 3, 
    nombre: 'Bosque Adentro', 
    tipo: 'Glamping', 
    horario: 'Check-in 3:00 pm', 
    whatsapp: '573000000000',
    link: '#',
    imagen: 'https://via.placeholder.com/150' 
  },
  { 
    id: 4, 
    nombre: 'Descanso Paisa', 
    tipo: 'Finca Hotel', 
    horario: '6:00 am - 10:00 pm', 
    whatsapp: '573000000000',
    link: '#',
    imagen: 'https://via.placeholder.com/150' 
  }
])

// PROPIEDAD COMPUTADA: Esto hace la magia del filtro en tiempo real
const alojamientosFiltrados = computed(() => {
  if (filtroActual.value === 'Todos') {
    return alojamientos.value
  }
  return alojamientos.value.filter(lugar => lugar.tipo === filtroActual.value)
})
</script>

<style scoped>
.hero-img {
  height: 450px;
  object-fit: cover;
}

.tag-title {
  z-index: 1;
  margin-top: -1px;
}

.radio-custom {
  width: 22px;
  height: 22px;
  cursor: pointer;
}

.cursor-pointer {
  cursor: pointer;
}

/* Bolitas del Hero */
.dot-indicator { width: 10px; height: 10px; }
.dot-indicator-active { width: 10px; height: 10px; }

/* Bolitas de las imágenes pequeñas */
.indicator-small { width: 6px; height: 6px; }
.indicator-small-active { width: 6px; height: 6px; }

.object-fit-cover {
  object-fit: cover;
}
</style>