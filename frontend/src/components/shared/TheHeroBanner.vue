<template>
  <div id="carouselExplora" class="carousel slide container-fluid p-0 mb-5" data-bs-ride="carousel">
    <!-- Indicadores -->
    <div class="carousel-indicators">
      <button 
        v-for="(slide, index) in slidesCarousel" 
        :key="'ind-' + index" 
        type="button" 
        data-bs-target="#carouselExplora" 
        :data-bs-slide-to="index" 
        :class="{ active: index === 0 }" 
        :aria-current="index === 0" 
        :aria-label="'Slide ' + (index + 1)">
      </button>
    </div>

    <!-- Imágenes del Carrusel -->
   <div class="hero-grid">
     <div class="hero-item">
       <img :src="imgSlide1" alt="El Prodigio">
   </div>

   <div class="hero-item">
     <img :src="imgSlide2" alt="Puente">
   </div>

   <div class="hero-item">
     <img :src="imgSlide3" alt="Quebrada">
   </div>

   

   
</div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExplora" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExplora" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
    
    
  </div>
  <!-- Contenedor con margen superior para separarlo del HeroBanner -->
  <div class="container mt-4 mb-2">
    <button @click="$router.go(-1)" class="btn btn-volver d-flex align-items-center px-4 py-2 shadow-sm">
      <i class="bi bi-arrow-left-circle-fill me-2"></i>
      <span class="fw-bold">Volver</span>
    </button>
  </div>
</template>

<script setup>

import { onMounted } from 'vue'
import { Carousel } from 'bootstrap' // Importamos la clase Carousel de Bootstrap
import imgSlide1 from '@/assets/img/principal/cuba1.webp'
import imgSlide2 from '@/assets/img/principal/San_Luis3.webp'
import imgSlide3 from '@/assets/img/principal/rio_samana.webp'

const slidesCarousel = [
  { src: imgSlide1, alt: 'Cascada San Luis' },
  { src: imgSlide2, alt: 'San Luis paisaje' },
  { src: imgSlide3, alt: 'Parque San Luis' },
]

onMounted(() => {
  const el = document.getElementById('carouselExplora')
  if (el) {
    // Esto fuerza a Bootstrap a tomar el control del div
    new Carousel(el, {
      interval: 3000,
      ride: 'carousel',
      pause: 'hover'
    })
  }
})
</script>

<style scoped>
.hero-grid {
  display: grid;
  grid-template-columns: 35% 30% 35%;
  width: 100%;
  height: 330px;
  overflow: hidden;
}

.hero-item {
  position: relative;
  overflow: hidden;
}

.hero-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Separación muy suave */
.hero-item:not(:last-child)::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 15px;
  height: 100%;
  background: linear-gradient(
    to right,
    rgba(255,255,255,0),
    rgba(255,255,255,0.15),
    rgba(255,255,255,0)
  );
}

@media (max-width: 768px) {
  .hero-grid {
    grid-template-columns: 1fr;
    height: auto;
  }

  .hero-item {
    height: 220px;
  }

  .hero-item::after {
    display: none;
  }
}
</style>