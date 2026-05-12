<template>
  <div class="sitios-view">
    <section class="container py-5 px-4 px-lg-5">
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
        <div v-if="!sitios.length" class="text-center py-5 text-muted">
          <i class="bi bi-map fs-1 d-block mb-3"></i>
          <p>No hay sitios turísticos disponibles por el momento.</p>
        </div>

        <div class="row g-4">
          <div class="col-sm-6 col-lg-4" v-for="(sitio, idx) in sitios" :key="sitio.id" v-reveal="idx * 80">
            <div class="card h-100 border-0 shadow overflow-hidden place-card">

              <!-- Imagen principal -->
              <div class="position-relative overflow-hidden img-wrapper">
                <img
                  :src="sitio.imagenes?.[0] || 'https://placehold.co/600x400/198754/white?text=Sin+imagen'"
                  class="card-img-top hover-zoom"
                  :alt="sitio.nombre">
                <div class="overlay-gradient"></div>

                <!-- Badge ubicación -->
                <span class="position-absolute bottom-0 start-0 m-3 badge bg-success py-2 px-3 rounded-pill shadow-sm">
                  <i class="bi bi-geo-alt-fill me-1"></i>{{ sitio.lugar ?? 'San Luis' }}
                </span>

                <!-- Indicadores de galería -->
                <div v-if="sitio.imagenes?.filter(Boolean).length > 1"
                  class="position-absolute bottom-0 end-0 m-3 d-flex gap-1">
                  <span
                    v-for="(_, i) in sitio.imagenes.filter(Boolean)"
                    :key="i"
                    class="galeria-dot"
                    :class="i === 0 ? 'galeria-dot--active' : ''">
                  </span>
                </div>
              </div>

              <!-- Cuerpo -->
              <div class="card-body p-4 d-flex flex-column">
                <h5 class="fw-bold mb-2">{{ sitio.nombre }}</h5>
                <p class="text-muted small lh-lg mb-0 desc-clamp flex-grow-1">
                  {{ sitio.descripcion }}
                </p>
              </div>

            </div>
          </div>
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

import imgCascada1   from '@/assets/img/principal/cascada.webp'
import imgCascada2   from '@/assets/img/principal/cascada2.webp'
import imgCascada3   from '@/assets/img/principal/cascada3.webp'
import imgPlanta1    from '@/assets/img/principal/cascada_la_planta.webp'
import imgPlanta2    from '@/assets/img/principal/planta.webp'
import imgPlanta3    from '@/assets/img/principal/hero.webp'
import imgCerro1     from '@/assets/img/principal/cerro_castellon.webp'
import imgCerro2     from '@/assets/img/principal/San_Luis.webp'
import imgCerro3     from '@/assets/img/principal/San_Luis2.webp'
import imgRio1       from '@/assets/img/principal/rio_dormilon.webp'
import imgRio2       from '@/assets/img/principal/rio_dormilon2.webp'
import imgRio3       from '@/assets/img/principal/negro.webp'
import imgSamana1    from '@/assets/img/principal/rio_samana.webp'
import imgSamana2    from '@/assets/img/principal/rio_samana2.webp'
import imgSamana3    from '@/assets/img/principal/samana3.webp'
import imgParque1    from '@/assets/img/principal/parque.webp'
import imgParque2    from '@/assets/img/principal/San_Luis3.webp'
import imgParque3    from '@/assets/img/principal/San_Luis.webp'

const SITIOS_DEMO = [
  {
    id: 1,
    nombre: 'Cascada La Chorrera',
    descripcion: 'Una impresionante cascada rodeada de vegetación exuberante, ideal para senderismo y fotografía de naturaleza. Sus aguas cristalinas caen desde 40 metros de altura formando una piscina natural perfecta para refrescarse.',
    imagenes: [imgCascada1, imgCascada2, imgCascada3],
    lugar: 'San Luis',
    estado: true,
  },
  {
    id: 2,
    nombre: 'Cascada La Planta',
    descripcion: 'Accesible por un sendero de 20 minutos entre guaduales y helechos gigantes. Perfecta para un baño natural en familia. Sus aguas descienden suavemente formando pozas de diferentes profundidades.',
    imagenes: [imgPlanta1, imgPlanta2, imgPlanta3],
    lugar: 'San Luis',
    estado: true,
  },
  {
    id: 3,
    nombre: 'Cerro Castellón',
    descripcion: 'Desde este mirador natural se aprecia una vista panorámica de todo el municipio y sus alrededores. El ascenso toma unos 45 minutos por senderos bien marcados entre bosque nativo y cultivos de café.',
    imagenes: [imgCerro1, imgCerro2, imgCerro3],
    lugar: 'San Luis',
    estado: true,
  },
  {
    id: 4,
    nombre: 'Río Dormilón',
    descripcion: 'Afluente cristalino ideal para el baño y la contemplación. Sus pozos naturales de agua verde son un secreto bien guardado de los habitantes de San Luis, accesible solo por trocha.',
    imagenes: [imgRio1, imgRio2, imgRio3],
    lugar: 'San Luis',
    estado: true,
  },
  {
    id: 5,
    nombre: 'Río Samaná Sur',
    descripcion: 'Uno de los pocos ríos libres de represas en Colombia. El recorrido en balsa por su cañón es la experiencia de aventura más buscada del oriente antioqueño, con paisajes espectaculares.',
    imagenes: [imgSamana1, imgSamana2, imgSamana3],
    lugar: 'San Luis',
    estado: true,
  },
  {
    id: 6,
    nombre: 'Parque Principal',
    descripcion: 'Corazón del municipio rodeado de la iglesia colonial y casas de arquitectura tradicional antioqueña. Punto de encuentro de locales y visitantes, con kioscos de comida típica los fines de semana.',
    imagenes: [imgParque1, imgParque2, imgParque3],
    lugar: 'San Luis',
    estado: true,
  },
]

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
  } catch {
    // API no disponible → mostrar datos de demo
  } finally {
    cargando.value = false
  }
  if (!sitios.value.length) sitios.value = SITIOS_DEMO
}

function irAnterior() { if (pagina.value > 1) { pagina.value--; cargar() } }
function irSiguiente() { if (pagina.value < totalPaginas.value) { pagina.value++; cargar() } }

onMounted(cargar)
</script>

<style scoped>
/* Card */
.place-card {
  transition: transform 0.35s ease, box-shadow 0.35s ease;
  border-radius: 1rem !important;
}
.place-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.13) !important;
}

/* Imagen */
.img-wrapper { height: 230px; }
.hover-zoom {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}
.place-card:hover .hover-zoom { transform: scale(1.06); }

/* Gradiente */
.overlay-gradient {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, transparent 45%, rgba(0,0,0,0.55));
}

/* Descripción recortada a 3 líneas */
.desc-clamp {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Puntitos de galería */
.galeria-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  display: inline-block;
}
.galeria-dot--active { background: #fff; }
</style>
