<template>
  <div class="sitios-view">
    <!-- El banner superior se mantiene desde el Layout para coherencia visual -->

    <section class="container py-5">
      <!-- ENCABEZADO EMOCIONAL -->
      <div class="mb-5 text-center" data-aos="fade-up">
        <h1 class="fw-bold display-4">Sitios Turisticos</h1>
        <p class="text-secondary fs-5 mx-auto" style="max-width: 700px;">
          Déjate cautivar por la magia del agua y el verde infinito de nuestras montañas. 
          San Luis te espera con rincones secretos que parecen sacados de un sueño.
        </p>
        <hr class="w-25 mx-auto border-success border-2 opacity-100 mt-4">
      </div>

      <!-- GRID DE SITIOS CON IMPACTO VISUAL -->
      <div class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="(sitio, index) in sitiosNaturales" :key="index">
          <div class="card h-100 border-0 shadow-lg overflow-hidden place-card">
            <!-- Imagen con Badge de Ubicación -->
            <div class="position-relative overflow-hidden">
              <img :src="sitio.imagen" class="card-img-top hover-zoom" :alt="sitio.nombre">
              <div class="overlay-gradient"></div>
              <span class="position-absolute bottom-0 start-0 m-3 badge bg-success py-2 px-3 rounded-pill shadow">
                <i class="bi bi-geo-alt-fill me-1"></i> {{ sitio.ubicacion }}
              </span>
            </div>

            <!-- Cuerpo de la Card -->
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold m-0">{{ sitio.nombre }}</h4>
                <div class="text-warning small">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
              </div>
              
              <p class="text-muted small mb-4 lh-lg">{{ sitio.descripcion }}</p>
              
              <!-- Detalles Técnicos -->
              <div class="bg-light rounded-3 p-2 mb-4 d-flex justify-content-around text-center">
                <div class="small">
                  <i class="bi bi-clock text-success d-block mb-1"></i>
                  <span>{{ sitio.tiempo }}</span>
                </div>
                <div class="small border-start border-end px-3">
                  <i class="bi bi-droplets text-primary d-block mb-1"></i>
                  <span>{{ sitio.tipo }}</span>
                </div>
                <div class="small">
                  <i class="bi bi-bar-chart-steps text-danger d-block mb-1"></i>
                  <span>{{ sitio.dificultad }}</span>
                </div>
              </div>

              <div class="d-grid">
                <a href="#" class="btn btn-outline-success border-2 rounded-pill fw-bold btn-explore">
                  Explorar este tesoro <i class="bi bi-arrow-right ms-2"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECCIÓN DE EXPERIENCIAS: AVISTAMIENTO DE AVES -->
      <div class="row mt-5 g-4 align-items-center bg-dark text-white rounded-4 overflow-hidden shadow-lg mx-0">
         <div class="col-md-5 p-0">
           <!-- Una imagen impactante de un ave colorida de la región (ej. un tucán o un barranquero) -->
             <img src="@/assets/img/home/aves_sanluis.webp" class="img-fluid h-100" style="object-fit: cover; min-height: 300px;" alt="Avistamiento de aves en San Luis">
         </div>
         <div class="col-md-7 p-5">
         <div class="badge bg-success mb-3">Experiencia Destacada</div>
           <h2 class="fw-bold mb-3">San Luis: Un Santuario de Aves</h2>
           <p class="lh-lg opacity-75">
             Con más de **300 especies registradas**, nuestro municipio es un destino emergente para el birdwatching. 
             Desde el emblemático **Barranquero** hasta tucanes y colibríes exóticos, cada sendero en San Luis es una oportunidad para conectar con la vida silvestre.
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

      <!-- SECCIÓN EXTRA: ¿SABÍAS QUÉ? (CURIOSIDAD DE IMPACTO) -->
      <div class="row mt-5 pt-5">
        <div class="col-12">
          <div class="alert alert-success border-0 shadow rounded-4 p-5 position-relative overflow-hidden">
            <div class="row align-items-center position-relative" style="z-index: 2;">
              <div class="col-md-2 text-center mb-3 mb-md-0">
                <i class="bi bi-water display-1 text-success opacity-50"></i>
              </div>
              <div class="col-md-10">
                <h3 class="fw-bold">El último río libre de Antioquia</h3>
                <p class="mb-0 fs-5">
                  San Luis es custodio del <strong>Río Samaná Sur</strong>, uno de los pocos ríos en Colombia que aún corre libre de represas. 
                  Nadar en sus aguas o recorrer su cañón es una experiencia de reconexión pura con la naturaleza salvaje.
                </p>
              </div>
            </div>
            <!-- Decoración de fondo -->
            <div class="watermark-bg"></div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const sitiosNaturales = ref([
  {
    nombre: 'Cascada La Planta',
    ubicacion: 'Vereda La Planta',
    descripcion: 'Un imponente salto de agua que emerge entre la selva tropical. Es el corazón hídrico donde el avistamiento de aves y el rugir del agua te transportan a otro mundo.',
    tiempo: '20 min',
    tipo: 'Cascada',
    dificultad: 'Baja',
    imagen: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=600&q=80'
  },
  {
    nombre: 'Río Dormilón',
    ubicacion: 'Sendero Ecológico',
    descripcion: 'Aguas color esmeralda que invitan a la calma. Sus charcos son piscinas naturales perfectas para el tradicional "paseo de olla" o una tarde de reflexión.',
    tiempo: '10 min',
    tipo: 'Río',
    dificultad: 'Muy Baja',
    imagen: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=600&q=80'
  },
  {
    nombre: 'Cerro El Castellón',
    ubicacion: 'Zona Alta',
    descripcion: 'El guardián de piedra de San Luis. Desde su cima, la inmensidad del cañón del Samaná se despliega ante tus ojos en una vista de 360 grados inolvidable.',
    tiempo: '1.5 h',
    tipo: 'Trekking',
    dificultad: 'Alta',
    imagen: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=600&q=80'
  }
])
</script>

<style scoped>
.place-card {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.place-card:hover {
  transform: translateY(-15px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.hover-zoom {
  transition: transform 0.6s ease;
  height: 250px;
  object-fit: cover;
}

.place-card:hover .hover-zoom {
  transform: scale(1.1);
}

.overlay-gradient {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.4));
}

.btn-explore {
  transition: all 0.3s ease;
}

.btn-explore:hover {
  background-color: #198754;
  color: white;
  letter-spacing: 1px;
}

.watermark-bg {
  position: absolute;
  top: -20px;
  right: -20px;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(25,135,84,0.05) 0%, transparent 70%);
  z-index: 1;
}
</style>