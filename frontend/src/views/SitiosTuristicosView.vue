<template>
  <div class="sitios-view">
    <section class="container py-5">
      <div class="mb-5 text-center">
        <h1 class="fw-bold display-4">Sitios Turísticos</h1>
        <p class="text-secondary fs-5 mx-auto" style="max-width: 700px;">
          Déjate cautivar por la magia del agua y el verde infinito de nuestras montañas.
          San Luis te espera con rincones secretos que parecen sacados de un sueño.
        </p>
        <hr class="w-25 mx-auto border-success border-2 opacity-100 mt-4">
      </div>

      <!-- Cargando -->
      <div v-if="cargando" class="text-center py-5 text-muted">
        <div class="spinner-border text-success" role="status"></div>
        <p class="mt-3">Cargando sitios turísticos...</p>
      </div>

      <template v-else>
        <div class="row g-4">
          <div class="col-md-6 col-lg-4" v-for="sitio in sitios" :key="sitio.id">
            <div class="card h-100 border-0 shadow-lg overflow-hidden place-card">
              <div class="position-relative overflow-hidden">
                <img :src="sitio.imagenes?.[0] || sitio.imagenes?.[1] || 'https://via.placeholder.com/600x400?text=Sitio+Turístico'"
                     class="card-img-top hover-zoom"
                     :alt="sitio.nombre">
                <div class="overlay-gradient"></div>
                <span class="position-absolute bottom-0 start-0 m-3 badge bg-success py-2 px-3 rounded-pill shadow">
                  <i class="bi bi-geo-alt-fill me-1"></i>{{ sitio.lugar ?? 'San Luis' }}
                </span>
              </div>
              <div class="card-body p-4">
                <h4 class="fw-bold mb-2">{{ sitio.nombre }}</h4>
                <p class="text-muted small mb-4 lh-lg">{{ sitio.descripcion }}</p>
                <div v-if="sitio.url_imagen_2 || sitio.url_imagen_3" class="d-flex gap-2">
                  <img v-if="sitio.imagenes?.[1]"
                       :src="sitio.imagenes[1]"
                       class="rounded-2 object-fit-cover"
                       width="70" height="55"
                       alt="foto">
                  <img v-if="sitio.imagenes?.[2]"
                       :src="sitio.imagenes[2]"
                       class="rounded-2 object-fit-cover"
                       width="70" height="55"
                       alt="foto">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!sitios.length" class="text-center py-5 text-muted">
          <i class="bi bi-map fs-1"></i>
          <p class="mt-3">No hay sitios turísticos disponibles por el momento.</p>
        </div>

        <!-- Paginación -->
        <PaginacionNav :pagina="pagina" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
      </template>

      <!-- Sección de aves (estática, informativa) -->
      <div class="row mt-5 g-4 align-items-center bg-dark text-white rounded-4 overflow-hidden shadow-lg mx-0">
        <div class="col-md-5 p-0">
          <img src="@/assets/img/home/aves_sanluis.webp"
               class="img-fluid h-100"
               style="object-fit: cover; min-height: 300px;"
               alt="Avistamiento de aves en San Luis">
        </div>
        <div class="col-md-7 p-5">
          <div class="badge bg-success mb-3">Experiencia Destacada</div>
          <h2 class="fw-bold mb-3">San Luis: Un Santuario de Aves</h2>
          <p class="lh-lg opacity-75">
            Con más de 300 especies registradas, nuestro municipio es un destino emergente para el birdwatching.
            Desde el emblemático Barranquero hasta tucanes y colibríes exóticos, cada sendero en San Luis
            es una oportunidad para conectar con la vida silvestre.
          </p>
          <div class="d-flex gap-3 mt-4">
            <div class="text-center">
              <i class="bi bi-binoculars fs-3 text-success"></i>
              <p class="small">Equipo sugerido</p>
            </div>
            <div class="text-center border-start border-end px-4">
              <i class="bi bi-sunrise fs-3 text-success"></i>
              <p class="small">Mejor hora: 5:30 AM</p>
            </div>
            <div class="text-center">
              <i class="bi bi-map fs-3 text-success"></i>
              <p class="small">+10 rutas de observación</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Dato curioso (estático) -->
      <div class="row mt-5 pt-3">
        <div class="col-12">
          <div class="alert alert-success border-0 shadow rounded-4 p-5">
            <div class="row align-items-center">
              <div class="col-md-2 text-center mb-3 mb-md-0">
                <i class="bi bi-water display-1 text-success opacity-50"></i>
              </div>
              <div class="col-md-10">
                <h3 class="fw-bold">El último río libre de Antioquia</h3>
                <p class="mb-0 fs-5">
                  San Luis es custodio del <strong>Río Samaná Sur</strong>, uno de los pocos ríos en Colombia
                  que aún corre libre de represas. Nadar en sus aguas o recorrer su cañón es una experiencia
                  de reconexión pura con la naturaleza salvaje.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'

const sitios       = ref([])
const cargando     = ref(false)
const pagina       = ref(1)
const totalPaginas = ref(1)

async function cargar() {
  cargando.value = true
  try {
    const { data } = await api.get(PUBLICO.SITIOS, { params: { page: pagina.value } })
    const payload = data.data
    if (payload?.data) {
      sitios.value       = payload.data
      pagina.value       = payload.current_page ?? pagina.value
      totalPaginas.value = payload.last_page    ?? 1
    } else {
      sitios.value = Array.isArray(payload) ? payload : []
    }
  } finally {
    cargando.value = false
  }
}

function irAnterior() { if (pagina.value > 1) { pagina.value--; cargar() } }
function irSiguiente() { if (pagina.value < totalPaginas.value) { pagina.value++; cargar() } }

onMounted(cargar)
</script>

<style scoped>
.place-card { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.place-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }
.hover-zoom { transition: transform 0.6s ease; height: 250px; object-fit: cover; }
.place-card:hover .hover-zoom { transform: scale(1.08); }
.overlay-gradient {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, transparent 55%, rgba(0,0,0,0.45));
}
.object-fit-cover { object-fit: cover; }
</style>
