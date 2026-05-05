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
// ================================================
// IMPORTACIÓN DE IMÁGENES
// Vite procesa y hashea cada imagen correctamente.
// Cuando Laravel tenga los endpoints listos,
// estos arrays se reemplazarán por llamadas a la API.
// ================================================

// ── Carousel principal ──────────────────────────
// import imgSlide1 from '@/assets/img/principal/planta.webp'
//import imgSlide2 from '@/assets/img/principal/San_Luis2.webp'
//import imgSlide3 from '@/assets/img/principal/parque.webp'

// ── Historia ────────────────────────────────────
import imgFundacion from '@/assets/img/home/fundacion.webp'
import imgCultura   from '@/assets/img/home/cultura.webp'
import imgEconomia  from '@/assets/img/home/economia.webp'

// ── Categorías ──────────────────────────────────
import imgHospedaje        from '@/assets/img/principal/Hospedaje.webp'
import imgPiscina          from '@/assets/img/piscina.webp'
import imgTransporte       from '@/assets/img/transporte.webp'
import imgGastronomia      from '@/assets/img/principal/gastronomia.webp'
import imgAgenciaTuristica from '@/assets/img/principal/agencia_turistica.webp'

// ── Lugares imperdibles ─────────────────────────
import imgPlanta1   from '@/assets/img/principal/planta.webp'
import imgPlanta2   from '@/assets/img/principal/cascada_la_planta.webp'
import imgDormilon1 from '@/assets/img/principal/rio_dormilon.webp'
import imgDormilon2 from '@/assets/img/principal/rio_dormilon2.webp'
import imgSamana1   from '@/assets/img/principal/rio_samana.webp'
import imgSamana2   from '@/assets/img/principal/rio_samana2.webp'
import imgSamana3   from '@/assets/img/principal/samana3.webp'

// ── Eventos ─────────────────────────────────────
import imgMadera   from '@/assets/img/fiestas_de_la_madera.webp'
import imgRetorno  from '@/assets/img/fiesta_del_retorno.webp'
import imgSemana   from '@/assets/img/semana_santa.webp'
import imgVirgen   from '@/assets/img/virgen_del_carmen.webp'

import { onMounted, nextTick } from 'vue' // <-- Asegúrate de importar nextTick: Garantiza que el <div id="galeria-1"> realmente exista en el navegador antes de que Bootstrap intente manipularlo.
import { Carousel } from 'bootstrap'

// ... tus variables e importaciones de imágenes ...

onMounted(async () => {
  // nextTick asegura que el v-for ya terminó de renderizar el HTML
  await nextTick();

  // Verificamos si lugaresImperdibles es un ref (usa .value) o un arreglo normal
  const lugares = lugaresImperdibles.value || lugaresImperdibles;

  if (lugares && lugares.length > 0) {
    lugares.forEach(lugar => {
      const idCarrusel = 'galeria-' + lugar.id;
      const elemento = document.getElementById(idCarrusel);
      
      if (elemento) {
        new Carousel(elemento, {
          interval: 4500,
          ride: 'carousel',
          pause: false
        });
      }
    });
  }
});

// ================================================
// DATOS ESTÁTICOS
// Estructura MVC: estos datos vendrán del backend
// Laravel via API cuando esté disponible.
// Endpoint esperado: GET /api/home
// ================================================

//const slidesCarousel = [
 // { src: imgSlide1, alt: 'Cascada San Luis' },
 // { src: imgSlide2, alt: 'San Luis paisaje' },
  //{ src: imgSlide3, alt: 'Parque San Luis' },
//]

const cardsHistoria = [
  {
    titulo: 'Fundación',
    subtitulo: '1875',
    descripcion: 'Fundado por el padre Clemente Giraldo, su nombre se dio en honor a San Luis Gonzaga patrono del pueblo.',
    imagen: imgFundacion
  },
  {
    titulo: 'Cultura y tradición',
    subtitulo: null,
    descripcion: 'San Luis conserva vivas sus raíces campesinas y religiosas, con fiestas populares, música típica y gran sentido comunitario.',
    imagen: imgCultura
  },
  {
    titulo: 'Economía',
    subtitulo: null,
    descripcion: 'La economía local se basa en la agricultura, la ganadería y la producción de madera, con crecimiento en el ecoturismo y productos artesanales.',
    imagen: imgEconomia
  },
]

const slidesCategorias = [
  [
    { nombre: 'Alojamiento',  ruta: '/alojamientos',        imagen: imgHospedaje,        claseResponsive: '' },
    { nombre: 'Recreación',   ruta: '/recreacion',          imagen: imgPiscina,          claseResponsive: 'd-none d-md-block' },
    { nombre: 'Transporte',   ruta: '/transportes',         imagen: imgTransporte,       claseResponsive: 'd-none d-lg-block' },
  ],
  [
    { nombre: 'Gastronomía',          ruta: '/gastronomia',         imagen: imgGastronomia,      claseResponsive: '' },
    { nombre: 'Agencias Turísticas',  ruta: '/agencias-turisticas', imagen: imgAgenciaTuristica, claseResponsive: 'd-none d-md-block' },
  ],
]

const lugaresImperdibles = [
  {
    id: 'sitio1',
    nombre: 'Cascada La Planta',
    zona: 'La Planta',
    descripcion: 'Naturaleza pura y aguas cristalinas.',
    claseResponsive: '',
    imagenes: [ imgPlanta1, imgPlanta2 ]
  },
  {
    id: 'sitio2',
    nombre: 'Charcos del Dormilón',
    zona: 'El Dormilón',
    descripcion: 'El mejor lugar para un baño relajante.',
    claseResponsive: 'd-none d-md-block',
    imagenes: [ imgDormilon1, imgDormilon2 ]
  },
  {
    id: 'sitio3',
    nombre: 'Río el Samaná',
    zona: 'Samaná',
    descripcion: 'Naturaleza pura y aguas cristalinas.',
    claseResponsive: '',
    imagenes: [ imgSamana1, imgSamana2, imgSamana3 ]
  },
  {
    id: 'sitio4',
    nombre: 'Charcos del Dormilón',
    zona: 'El Dormilón',
    descripcion: 'El mejor lugar para un baño relajante.',
    claseResponsive: 'd-none d-md-block',
    imagenes: [ imgDormilon1, imgDormilon2 ]
  },
]

const eventos = [
  {
    nombre: 'Fiestas de la madera',
    descripcion: 'Celebración cultural que resalta la tradición maderera del municipio.',
    imagen: imgMadera
  },
  {
    nombre: 'Fiestas del retorno',
    descripcion: 'Evento para reencontrar a las familias sanluisanas ausentes.',
    imagen: imgRetorno
  },
  {
    nombre: 'Semana Santa',
    descripcion: 'Que tu única preocupación sea disfrutar del camino.',
    imagen: imgSemana
  },
  {
    nombre: 'Fiesta Virgen del Carmen',
    descripcion: 'Homenaje a la labor agrícola y a la vida rural.',
    imagen: imgVirgen
  },
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