<template>
  <div class="eventos-view">
    <!-- El HeroBanner aparecerá automáticamente gracias al DefaultLayout -->

    <section class="container py-5">
      <div class="text-center mb-5">
        <h2 class="fw-bold border-bottom pb-3 d-inline-block px-5">Eventos</h2>
      </div>

      <div class="row g-4">
        <!-- Usamos v-for para no repetir el código de las tarjetas -->
        <div class="col-md-6" v-for="(evento, index) in listaEventos" :key="index">
          <div class="card border-0 shadow-sm h-100 overflow-hidden hover-effect">
            <div class="row g-0 h-100">
              <div class="col-4">
                <img :src="evento.imagen" class="img-fluid h-100 w-100 object-fit-cover" :alt="evento.titulo">
              </div>
              <div class="col-8">
                <div class="card-body">
                  <h5 class="fw-bold mb-1">{{ evento.titulo }}</h5>
                  <p class="small mb-1"><strong>Fecha:</strong> {{ evento.fecha }}</p>
                  <p class="small mb-0 text-secondary">
                    <strong>Descripción:</strong> {{ evento.descripcion }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginación reactiva -->
      <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
        <button class="btn btn-link text-dark p-0 mx-3" :disabled="paginaActual === 1">
          <i class="bi bi-chevron-left fs-4"></i>
        </button>
        <span class="fs-5 fw-bold">{{ paginaActual }} de {{ totalPaginas }}</span>
        <button class="btn btn-link text-dark p-0 mx-3" :disabled="paginaActual === totalPaginas">
          <i class="bi bi-chevron-right fs-4"></i>
        </button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Importación de imágenes locales
import imgMadera from '@/assets/img/fiestas_de_la_madera.webp'
import imgRetorno from '@/assets/img/fiesta_del_retorno.webp'

const paginaActual = ref(1)
const totalPaginas = ref(6)

// Datos organizados en un array para facilitar el mantenimiento
const listaEventos = ref([
  {
    titulo: 'Fiestas de la madera',
    fecha: 'Junio a Julio',
    descripcion: 'Se exalta la labor del campesino y su trabajo en el campo. Se realizan diferentes actividades enfocadas en la madera y la economía del municipio.',
    imagen: imgMadera
  },
  {
    titulo: 'Fiestas del retorno',
    fecha: 'Enero',
    descripcion: 'Encuentro de colonias, donde se realizan diferentes actividades deportivas con el fin de propiciar un encuentro ameno entre los municipios.',
    imagen: imgRetorno
  },
  // Aquí puedes agregar más eventos y el v-for los dibujará automáticamente
])
</script>

<style scoped>
.object-fit-cover {
  object-fit: cover;
}

.hover-effect {
  transition: transform 0.3s ease;
}

.hover-effect:hover {
  transform: translateY(-5px);
}

/* Ajuste para que la descripción no se vea cortada si es muy larga */
.card-body {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}
</style>