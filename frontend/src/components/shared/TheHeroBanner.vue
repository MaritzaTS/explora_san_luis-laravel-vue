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
    <div class="carousel-inner">
      <div 
        v-for="(slide, index) in slidesCarousel" 
        :key="slide.alt" 
        class="carousel-item" 
        :class="{ active: index === 0 }">
        <img :src="slide.src" class="d-block w-200 carousel-img" :alt="slide.alt">
      </div>
    </div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExplora" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExplora" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
    <!-- Indicador de scroll -->
    <div class="scroll-indicator">
       <span class="scroll-texto">Desliza para explorar</span>
       <div class="scroll-flecha">
         <i class="bi bi-chevron-down"></i>
      </div>
    </div>
    
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
import imgSlide1 from '@/assets/img/principal/planta.webp'
import imgSlide2 from '@/assets/img/principal/San_Luis2.webp'
import imgSlide3 from '@/assets/img/principal/parque.webp'

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
.carousel-img {
  width: 100%;
  height: 1040px;
  object-fit: cover;
}

@media (max-width: 768px) {
  .carousel-img {
    height: 200px;
  }
}

.btn-volver {
  background-color: transparent;
  color: seagreen; /* Color solicitado */
  border: 2px solid seagreen;
  border-radius: 50px; /* Estilo redondeado (pill) */
  transition: all 0.3s ease-in-out;
  font-size: 0.9rem;
}

.btn-volver:hover {
  background-color: seagreen;
  color: white;
  transform: translateX(-5px); /* Pequeño desplazamiento a la izquierda */
  box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3); /* Sombra suave color seagreen */
}

.bi-arrow-left-circle-fill {
  font-size: 1.2rem;
}

/* ── Indicador de scroll ── */
.scroll-indicator {
  position: absolute;
  bottom: 80px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  z-index: 10;
  animation: fadeInUp 1s ease 1s both;
}

.scroll-texto {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.85);
  text-shadow: 0 1px 4px rgba(0,0,0,0.4);
}

.scroll-flecha {
  width: 36px;
  height: 36px;
  border: 2px solid rgba(255, 255, 255, 0.7);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  animation: bounce 1.6s ease-in-out infinite;
}

.scroll-flecha i {
  font-size: 1rem;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(6px); }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateX(-50%) translateY(10px); }
  to   { opacity: 1; transform: translateX(-50%) translateY(0); }
}

@media (max-width: 768px) {
  .scroll-indicator { display: none; }
}
</style>