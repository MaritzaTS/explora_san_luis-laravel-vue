<template>
  <div class="app-container">
    <TheHeader />

    <!-- El banner solo desaparece en 'bienvenida' -->
    <TheHeroBanner v-if="route.name !== 'bienvenida'" />

    <main class="main-content">
      <router-view />
    </main>

    <TheFooter />
    <BaseModal/>
 
  </div>
</template> 

<script setup>
// Importación de componentes compartidos según tu estructura 
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import TheHeader from '@/components/shared/TheHeader.vue'
import TheFooter from '@/components/shared/TheFooter.vue'
import TheHeroBanner from '@/components/shared/TheHeroBanner.vue'
import BaseModal from '@/components/ui/BaseModal.vue';

// Importación de imágenes para el Carrusel (Home/Bienvenida)
import imgSlide1 from '@/assets/img/principal/planta.webp'
import imgSlide2 from '@/assets/img/principal/San_Luis2.webp'
import imgSlide3 from '@/assets/img/principal/parque.webp'


const route = useRoute()

const slidesCarousel = [
  { src: imgSlide1, alt: 'Cascada San Luis' },
  { src: imgSlide2, alt: 'San Luis paisaje' },
  { src: imgSlide3, alt: 'Parque San Luis' },
]



// 1. Lógica para Ocultar/Mostrar
const debeMostrarBanner = computed(() => {
  // Verificamos por nombre de ruta o por path para estar seguros
  const nombresExcluidos = ['bienvenida']
  const pathsExcluidos = ['/']
  
  return !nombresExcluidos.includes(route.name)
})

</script>

<style>
/* Configuración para que el footer siempre esté abajo */
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1 0 auto; /* Permite que el contenido crezca y empuje al footer */
}
</style>