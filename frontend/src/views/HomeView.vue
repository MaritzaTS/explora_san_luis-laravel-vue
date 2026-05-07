<template>
  <!-- ==========================================
       SECCIÓN 1: CAROUSEL PRINCIPAL
  =========================================== -->



  <!-- ==========================================
       SECCIÓN 2: CARDS DE HISTORIA
  =========================================== -->
  <section class="container mb-5">
    <div class="row g-4 mb-5">
      <div
        class="col-md-4"
        v-for="card in cardsHistoria"
        :key="card.titulo">
        <div class="card h-100 border-0 shadow-sm overflow-hidden text-center historia-card">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-1">{{ card.titulo }}</h4>
            <p v-if="card.subtitulo" class="text-muted small mb-3">{{ card.subtitulo }}</p>
            <p class="small">{{ card.descripcion }}</p>
            <router-link to="/historia" class="stretched-link"></router-link>
          </div>
          <img
            :src="card.imagen"
            class="card-img-bottom historia-card-img"
            :alt="card.titulo">
        </div>
      </div>
    </div>

    <div class="mt-5">
      <h2 class="fw-bold mb-1">Historia del municipio</h2>
      <p class="text-secondary fs-5">Conoce la rica historia y patrimonio de San Luis, Antioquia</p>
      <hr class="text-muted opacity-25">
    </div>
  </section>


  <!-- ==========================================
       SECCIÓN 3: CAROUSEL DE CATEGORÍAS
  =========================================== -->
  <section class="container py-5">
    <div class="mb-5">
      <h2 class="fw-bold mb-1">Explora por categorías</h2>
      <p class="text-secondary fs-5">Descubre todo lo que San Luis tiene para ofrecerte</p>
    </div>

    <div id="carouselCategorias" class="carousel slide"
         data-bs-ride="carousel"
         data-bs-interval="3000"
         data-bs-pause="hover">

      <div class="carousel-indicators mb-n4">
        <button
          v-for="(grupo, index) in slidesCategorias"
          :key="'cat-ind-' + index"
          type="button"
          data-bs-target="#carouselCategorias"
          :data-bs-slide-to="index"
          :class="['bg-success', { active: index === 0 }]"
          :aria-current="index === 0 ? 'true' : undefined"
          :aria-label="'Slide ' + (index + 1)">
        </button>
      </div>

      <div class="carousel-inner">
        <div
          v-for="(grupo, index) in slidesCategorias"
          :key="'cat-slide-' + index"
          class="carousel-item"
          :class="{ active: index === 0 }">
          <div class="d-flex justify-content-center gap-3">
            <div
              v-for="cat in grupo"
              :key="cat.nombre"
              class="card border-0 shadow-sm flex-fill categoria-card"
              :class="cat.claseResponsive">
              <img
                :src="cat.imagen"
                class="card-img-top categoria-card-img"
                :alt="cat.nombre">
              <div class="card-body text-center py-2">
                <h5 class="fw-bold small mb-0">{{ cat.nombre }}</h5>
              </div>
              <router-link :to="cat.ruta" class="stretched-link"></router-link>
            </div>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev w-auto" type="button"
              data-bs-target="#carouselCategorias" data-bs-slide="prev">
        <span class="bg-dark rounded-circle p-2">
          <i class="bi bi-chevron-left text-white fs-4"></i>
        </span>
      </button>
      <button class="carousel-control-next w-auto" type="button"
              data-bs-target="#carouselCategorias" data-bs-slide="next">
        <span class="bg-dark rounded-circle p-2">
          <i class="bi bi-chevron-right text-white fs-4"></i>
        </span>
      </button>
    </div>
  </section>


  <!-- ==========================================
       SECCIÓN 4: LUGARES IMPERDIBLES
  =========================================== -->
  <section class="container mb-5">
    <div class="mb-4">
      <h2 class="fw-bold mb-1">Lugares imperdibles</h2>
      <p class="text-secondary fs-5">Los destinos que no puedes dejar de visitar</p>
    </div>

    <div id="carouselPrincipalSitios" class="carousel slide" data-bs-ride="false">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="d-flex justify-content-center gap-4">
            <div
              v-for="lugar in lugaresImperdibles"
              :key="lugar.id"
              class="card border-0 shadow-sm flex-fill overflow-hidden lugar-card"
              :class="lugar.claseResponsive">

              <!-- Carousel interno por lugar -->
              <div
                :id="'galeria-' + lugar.id"
                class="carousel slide carousel-fade"
                data-bs-ride="carousel">
                <div class="carousel-inner">
                  <div
                    v-for="(img, imgIndex) in lugar.imagenes"
                    :key="img + imgIndex"
                    class="carousel-item"
                    :class="{ active: imgIndex === 0 }">
                    <img
                      :src="img"
                      class="d-block w-100 lugar-card-img">
                  </div>
                </div>
                <!-- Badge de zona -->
                <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-50 py-1 px-2 rounded-1 small zona-badge">
                  <i class="bi bi-geo-alt-fill text-success"></i> {{ lugar.zona }}
                </span>
              </div>

              <div class="card-body">
                <h5 class="fw-bold mb-1">{{ lugar.nombre }}</h5>
                <p class="text-muted mb-0 lugar-desc">{{ lugar.descripcion }}</p>
              </div>

            </div>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev w-auto" type="button"
              data-bs-target="#carouselPrincipalSitios" data-bs-slide="prev">
        <span class="bg-dark rounded-circle p-2 shadow">
          <i class="bi bi-chevron-left text-white fs-5"></i>
        </span>
      </button>
      <button class="carousel-control-next w-auto" type="button"
              data-bs-target="#carouselPrincipalSitios" data-bs-slide="next">
        <span class="bg-dark rounded-circle p-2 shadow">
          <i class="bi bi-chevron-right text-white fs-5"></i>
        </span>
      </button>
    </div>
  </section>


  <!-- ==========================================
       SECCIÓN 5: PRÓXIMOS EVENTOS
  =========================================== -->
  <section class="container mb-5 pb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="fw-bold mb-1">Próximos eventos</h2>
        <p class="text-secondary fs-5 mb-0">No te pierdas las celebraciones y festividades locales</p>
      </div>
      <router-link to="/eventos" class="btn btn-outline-success btn-sm fw-bold">
        Ver todos <i class="bi bi-arrow-right ms-1"></i>
      </router-link>
    </div>

    <div class="row g-4">
      <div
        class="col-md-3"
        v-for="evento in eventos"
        :key="evento.nombre">
        <div class="card h-100 border-0 shadow-sm text-center overflow-hidden evento-card">
          <img
            :src="evento.imagen"
            class="card-img-top evento-card-img"
            :alt="evento.nombre">
          <div class="card-body py-3">
            <h6 class="fw-bold mb-2">{{ evento.nombre }}</h6>
            <p class="small text-muted mb-0">{{ evento.descripcion }}</p>
            <router-link to="/eventos" class="stretched-link"></router-link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>


<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { Carousel } from 'bootstrap'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'

// ── Imágenes estáticas (fallback por slug si el backend no tiene imagen) ──
import imgFundacion       from '@/assets/img/home/fundacion.webp'
import imgCultura         from '@/assets/img/home/cultura.webp'
import imgEconomia        from '@/assets/img/home/economia.webp'
import imgHospedaje       from '@/assets/img/principal/Hospedaje.webp'
import imgPiscina         from '@/assets/img/piscina.webp'
import imgTransporte      from '@/assets/img/principal/transporte.webp'
import imgGastronomia     from '@/assets/img/principal/gastronomia.webp'
import imgAgencia         from '@/assets/img/principal/agencia_turistica.webp'
import imgPlanta1         from '@/assets/img/principal/planta.webp'
import imgPlanta2         from '@/assets/img/principal/cascada_la_planta.webp'
import imgDormilon1       from '@/assets/img/principal/rio_dormilon.webp'
import imgDormilon2       from '@/assets/img/principal/rio_dormilon2.webp'
import imgSamana1         from '@/assets/img/principal/rio_samana.webp'
import imgSamana2         from '@/assets/img/principal/rio_samana2.webp'
import imgSamana3         from '@/assets/img/principal/samana3.webp'

const imgFallbackPorSlug = {
  'alojamientos':        imgHospedaje,
  'recreacion':          imgPiscina,
  'transportes':         imgTransporte,
  'gastronomia':         imgGastronomia,
  'agencias-turisticas': imgAgencia,
}

// ── Estado API ───────────────────────────────────
const eventos = ref([])
const tipos   = ref([])

async function cargarEventos() {
  try {
    const { data } = await api.get(PUBLICO.EVENTOS)
    const payload = data.data
    const lista = payload?.data ?? (Array.isArray(payload) ? payload : [])
    eventos.value = lista.slice(0, 4)
  } catch { /* muestra sección vacía si falla */ }
}

async function cargarTipos() {
  try {
    const { data } = await api.get(PUBLICO.TIPOS)
    const lista = data.data ?? (Array.isArray(data) ? data : [])
    tipos.value = lista
  } catch { /* mantiene array vacío */ }
}

// Agrupa los tipos en slides de 3 para el carousel
const slidesCategorias = computed(() => {
  const cats = tipos.value.map((t) => ({
    nombre: t.nombre,
    ruta:   '/' + t.slug,
    imagen: t.url_imagen ?? imgFallbackPorSlug[t.slug] ?? imgHospedaje,
  }))
  const grupos = []
  for (let i = 0; i < cats.length; i += 3) {
    grupos.push(cats.slice(i, i + 3))
  }
  return grupos
})

onMounted(async () => {
  await Promise.all([cargarEventos(), cargarTipos()])
  await nextTick()
  lugaresImperdibles.forEach((lugar) => {
    const el = document.getElementById('galeria-' + lugar.id)
    if (el) new Carousel(el, { interval: 4500, ride: 'carousel', pause: false })
  })
})

// ── Datos estáticos ──────────────────────────────
const cardsHistoria = [
  { titulo: 'Fundación', subtitulo: '1875', descripcion: 'Fundado por el padre Clemente Giraldo, su nombre se dio en honor a San Luis Gonzaga patrono del pueblo.', imagen: imgFundacion },
  { titulo: 'Cultura y tradición', subtitulo: null, descripcion: 'San Luis conserva vivas sus raíces campesinas y religiosas, con fiestas populares, música típica y gran sentido comunitario.', imagen: imgCultura },
  { titulo: 'Economía', subtitulo: null, descripcion: 'La economía local se basa en la agricultura, la ganadería y la producción de madera, con crecimiento en el ecoturismo y productos artesanales.', imagen: imgEconomia },
]

const lugaresImperdibles = [
  { id: 'sitio1', nombre: 'Cascada La Planta',    zona: 'La Planta',   descripcion: 'Naturaleza pura y aguas cristalinas.',    claseResponsive: '',                  imagenes: [imgPlanta1,   imgPlanta2] },
  { id: 'sitio2', nombre: 'Charcos del Dormilón', zona: 'El Dormilón', descripcion: 'El mejor lugar para un baño relajante.',  claseResponsive: 'd-none d-md-block', imagenes: [imgDormilon1, imgDormilon2] },
  { id: 'sitio3', nombre: 'Río el Samaná',        zona: 'Samaná',      descripcion: 'El único río libre de Antioquia.',        claseResponsive: '',                  imagenes: [imgSamana1,   imgSamana2, imgSamana3] },
  { id: 'sitio4', nombre: 'Charcos del Dormilón', zona: 'El Dormilón', descripcion: 'El mejor lugar para un baño relajante.',  claseResponsive: 'd-none d-md-block', imagenes: [imgDormilon1, imgDormilon2] },
]
</script>


<style scoped>
/* ── CAROUSEL PRINCIPAL ── */

.carousel-img {
  width: 100%;
  height: 600px;
  object-fit: cover;
}

@media (max-width: 768px) {
  .carousel-img {
    height: 300px;
  }
}

/* ── HISTORIA ── */
.historia-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}
.historia-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12) !important;
}
.historia-card-img {
  height: 200px;
  object-fit: cover;
}

/* ── CATEGORÍAS ── */
.categoria-card {
  min-width: 250px;
  transition: transform 0.3s ease;
  cursor: pointer;
}
.categoria-card:hover {
  transform: translateY(-4px);
}
.categoria-card-img {
  height: 150px;
  object-fit: cover;
}

/* ── LUGARES IMPERDIBLES ── */
.lugar-card {
  min-width: 300px;
  max-width: 350px;
  transition: transform 0.3s ease;
}
.lugar-card:hover {
  transform: translateY(-4px);
}
.lugar-card-img {
  height: 200px;
  object-fit: cover;
}
.lugar-desc {
  font-size: 0.8rem;
}
.zona-badge {
  z-index: 5;
}

/* ── EVENTOS ── */
.evento-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}
.evento-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12) !important;
}
.evento-card-img {
  height: 150px;
  object-fit: cover;
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .carousel-img {
    height: 300px;
  }
  .lugar-card {
    min-width: 260px;
  }
}
</style>
