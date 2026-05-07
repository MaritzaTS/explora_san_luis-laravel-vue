<template>
  <div class="transporte-view">
    <!-- El carrusel superior se mantiene desde el Layout -->

    <section class="container my-5">
      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Rutas y Transporte</h1>
        <p class="text-secondary fs-5 mx-auto" style="max-width: 700px;">
          Planifica tu viaje a "La Perla del Oriente". Te mostramos las mejores opciones para llegar y moverte por San Luis.
        </p>
        <hr class="w-25 mx-auto border-success border-2 opacity-100 mt-4">
      </div>

      <!-- FILTROS DE TRANSPORTE -->
      <div class="row g-3 justify-content-center mb-5">
        <div class="col-md-4" v-for="opcion in opcionesFiltro" :key="String(opcion.id)">
          <button
            @click="seleccionarFiltro(opcion.id)"
            :class="['btn w-100 py-3 rounded-4 fw-bold shadow-sm transition-all',
                    filtroActivo === opcion.id ? 'btn-success' : 'btn-light border']">
            <i :class="opcion.icon + ' me-2'"></i> {{ formatNombre(String(opcion.nombre)) }}
          </button>
        </div>
      </div>

      <!-- SECCIÓN 1: CÓMO LLEGAR (ESTÁTICA) -->
      <div v-if="filtroActivo === 'como_llegar'" class="fade-in">
        <div class="row g-4 align-items-center mb-5">
          <div class="col-md-6">
            <h3 class="fw-bold mb-3">La Ruta hacia la Naturaleza</h3>
            <p class="text-muted lh-lg">
              Desde Medellín, toma la autopista Medellín-Bogotá. Es un viaje de aproximadamente 2 horas lleno de paisajes verdes y montañas imponentes.
            </p>
            <ul class="list-unstyled">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Distancia:</strong> 120 km desde Medellín.</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Peajes:</strong> Copacabana y Santuario.</li>
            </ul>
          </div>
          <div class="col-md-6">
            <img :src="imgRuta" class="img-fluid rounded-4 shadow hover-zoom" alt="Ruta Medellín - San Luis">
          </div>
        </div>
        <div class="ratio ratio-21x9 border shadow-sm rounded-4 overflow-hidden">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d253818.1751848577!2d-75.34065649999999!3d6.115291750000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8e4428dfc80fad05%3A0x421374a24f009006!2sMedell%C3%ADn%2C%20Antioquia!3m2!1d6.244203!2d-75.581211!4m5!1s0x8e4693a0b5a3e14d%3A0x9c3d9a5b6d5e0a0!2sSan%20Luis%2C%20Antioquia!3m2!1d6.0425!2d-74.992222!5e0!3m2!1ses!2sco!4v1700000000000!5m2!1ses!2sco"
            allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

      <!-- SECCIÓN 2: LISTA DE TRANSPORTES -->
      <div v-else class="fade-in">
        <div v-if="cargando" class="text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando transportes...</p>
        </div>

        <div v-else class="row g-4">
          <div class="col-md-6 col-lg-4" v-for="(item, idx) in transporteFiltrado" :key="item.id" v-reveal="idx * 80">
            <div class="card h-100 border-0 shadow-sm overflow-hidden transport-card">
              <img :src="imagen(item)" class="card-img-top" style="height: 200px; object-fit: cover;" :alt="item.nombre_comercial">
              <div class="card-body p-4 text-center">
                <h5 class="fw-bold">{{ item.nombre_comercial }}</h5>
                <p class="text-muted small mb-3">{{ item.descripcion }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                  <span class="badge bg-light text-dark border">
                    <i class="bi bi-clock me-1"></i> {{ item.hora_atencion }}
                  </span>
                </div>
                <a :href="'tel:' + item.telefono" class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                  <i class="bi bi-telephone-fill me-2"></i> Contactar
                </a>
              </div>
            </div>
          </div>

          <div v-if="!transporteFiltrado.length" class="col-12 text-center py-5 text-muted">
            <i class="bi bi-bus-front fs-1"></i>
            <p class="mt-3">No se encontraron transportes.</p>
          </div>
        </div>

        <PaginacionNav :pagina="pagina" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'

import imgRuta from '@/assets/img/principal/Transporte.webp'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('transportes')

const filtroActivo = ref('como_llegar')

const opcionesFiltro = computed(() => [
  { id: 'como_llegar', nombre: '¿Cómo llegar?', icon: 'bi-signpost-2' },
  ...subtipos.value.map((s) => ({ id: s.id, nombre: s.nombre, icon: 'bi-bus-front' })),
])

function seleccionarFiltro(id) {
  filtroActivo.value = id
  filtros.value = (id && id !== 'como_llegar') ? [id] : []
}

const transporteFiltrado = computed(() => entidades.value)

const imagen = (e) => e.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/400x200?text=Sin+imagen'
const formatNombre = (txt) => String(txt).replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())

onMounted(async () => { await init() })
</script>

<style scoped>
.transport-card {
  transition: transform 0.3s ease;
  border-radius: 1.5rem;
}
.transport-card:hover {
  transform: translateY(-10px);
}
.hover-zoom {
  transition: transform 0.5s ease;
}
.hover-zoom:hover {
  transform: scale(1.03);
}
.transition-all { transition: all 0.3s ease; }
.fade-in { animation: fadeIn 0.6s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>