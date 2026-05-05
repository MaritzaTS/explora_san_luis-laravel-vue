<template>
  <div class="historia-view">
    <section class="container my-5">
      <!-- ENCABEZADO: IDENTIDAD Y RESILIENCIA -->
      <div class="text-center mb-5 pb-3">
        <h1 class="fw-bold display-5 mb-2">Historia y Patrimonio</h1>
        <p class="text-secondary fs-5 mb-4">San Luis, Antioquia: Un relato de transformación y talante emprendedor</p>
        <div class="quote-container mx-auto">
          <span class="quote-icon">“</span>
          <p class="fst-italic h5 text-muted">
            De la colonización paisa a la consolidación como referente del turismo y la agroindustria regional.
          </p>
          <hr class="short-hr mx-auto border-success opacity-100">
        </div>
      </div>

      <!-- SECCIÓN 1: HITOS HISTÓRICOS (TIMELINE) -->
      <div class="row mb-5 pb-5">
        <div class="col-12">
          <h3 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-success"></i>Línea del Tiempo</h3>
          <div class="timeline">
            <div v-for="(hito, index) in hitosHistoricos" :key="index" class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-content card border-0 shadow-sm p-3">
                <span class="fw-bold text-success">{{ hito.ano }}</span>
                <p class="small mb-0">{{ hito.evento }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECCIÓN 2: CULTURA VIVA -->
      <div class="row g-5 align-items-center mb-5 pb-5 bg-light-subtle rounded-4 p-4">
        <div class="col-md-7">
          <h4 class="fw-bold mb-3">Tradición que nos une</h4>
          <p class="small lh-lg text-justify mb-4">
            Nuestra historia se celebra en cada festival. Desde las colonias que retornan en enero hasta el homenaje al campesino en junio, San Luis mantiene viva su herencia cultural.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <span v-for="(fest, i) in festividades" :key="i" class="badge rounded-pill border border-success text-dark p-2 px-3 fest-badge">
              <i class="bi bi-stars me-1 text-success"></i> {{ fest }}
            </span>
          </div>
        </div>
        <div class="col-md-5">
          <img :src="imgCultura" class="img-fluid rounded-4 shadow-sm" alt="Cultura de San Luis">
        </div>
      </div>

      <!-- SECCIÓN DE ECONOMÍA: EL PENTÁGONO PRODUCTIVO -->
      <div class="row g-4 mt-5 justify-content-center">
        <div class="col-12 text-center mb-5">
          <h2 class="fw-bold">Nuestra Fuerza Productiva</h2>
          <p class="text-muted mx-auto" style="max-width: 800px;">
            La economía de San Luis es un equilibrio perfecto entre la herencia de la tierra, 
            la tecnificación del campo y la calidez de nuestra gente.
          </p>
        </div>
        
        <div class="col-md-6 col-lg-4" v-for="(pilar, idx) in pilaresEconomicos" :key="idx">
          <div class="card h-100 border-0 shadow-sm p-4 text-center pilar-card">
            <div class="icon-wrapper mb-3" :class="pilar.bgClass">
              <i :class="[pilar.icon, pilar.textClass]"></i>
            </div>
            <h5 class="fw-bold">{{ pilar.titulo }}</h5>
            <p class="small text-muted mb-0">{{ pilar.descripcion }}</p>
          </div>
        </div>
      </div>

      <!-- SECCIÓN DE CURIOSIDADES -->
<div class="row mt-5">
  <div class="col-12">
    <div class="bg-success text-white p-4 rounded-4 shadow-sm">
      <h4 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>¿Sabías que?</h4>
      <div class="row g-4">
        <div class="col-md-4">
          <p class="small mb-0"><strong>Río Samaná Sur:</strong> Es uno de los últimos ríos libres de Colombia, sin represas, lo que lo hace un paraíso para el rafting extremo.</p>
        </div>
        <div class="col-md-4 border-start border-white border-opacity-25">
          <p class="small mb-0"><strong>Petroglifos:</strong> En El Prodigio existen grabados indígenas milenarios que puedes visitar, siendo un museo arqueológico natural.</p>
        </div>
        <div class="col-md-4 border-start border-white border-opacity-25">
          <p class="small mb-0"><strong>Riqueza Hídrica:</strong> San Luis es conocido como "La Perla del Oriente" por tener la mayor cantidad de cascadas naturales de la región.</p>
        </div>
      </div>
      <div class="watermark-bg"></div>
    </div>
  </div>
</div>

      <!-- BOTÓN DE NAVEGACIÓN FINAL -->
      <div class="text-center mt-5">
        <router-link to="/sitios-turisticos" class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow">
          Descubre San Luis <i class="bi bi-arrow-right ms-2"></i>
        </router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import imgCultura from '@/assets/img/home/cultura.webp'

const hitosHistoricos = ref([
  { ano: '1875', evento: 'Fundación y establecimiento de los primeros trapiches paneleros.' },
  { ano: 'Siglo XIX', evento: 'Época dorada de la colonización paisa y expansión agrícola.' },
  { ano: 'Resiliencia', evento: 'Transformación social y superación de periodos de conflicto.' },
  { ano: 'Actualidad', evento: 'Liderazgo en producción de cacao fino y consolidación turística.' }
])

const festividades = ref([
  'Fiestas de la Madera (Junio)',
  'Fiestas del Retorno (Enero)',
  'Semana Santa',
  'San Luis Gonzaga (Agosto)'
])

const pilaresEconomicos = ref([
  { 
    titulo: 'Cacao y Café', 
    descripcion: 'Puntales de innovación y paz, reconocidos por su calidad excepcional en mercados internacionales.',
    icon: 'bi bi-box-seam-fill', bgClass: 'bg-success-subtle', textClass: 'text-success' 
  },
  { 
    titulo: 'Tradición Panelera', 
    descripcion: 'Identidad artesanal con más de 65 trapiches que transforman la caña en el dulce sustento de San Luis.',
    icon: 'bi bi-moisture', bgClass: 'bg-warning-subtle', textClass: 'text-warning' 
  },
  { 
    titulo: 'Agricultura Diversa', 
    descripcion: 'Sólida base de maíz, frutales, aguacate y yuca que garantiza la seguridad alimentaria de la región.',
    icon: 'bi bi-flower1', bgClass: 'bg-danger-subtle', textClass: 'text-danger' 
  },
  { 
    titulo: 'Ganadería Sostenible', 
    descripcion: 'Producción bovina y láctea que dinamiza las zonas rurales y fortalece el comercio local.',
    icon: 'bi bi-droplet-fill', bgClass: 'bg-primary-subtle', textClass: 'text-primary' 
  },
  { 
    titulo: 'Turismo y Naturaleza', 
    descripcion: 'Nuestra nueva frontera de desarrollo, abriendo el paraíso de San Luis al mundo entero.',
    icon: 'bi bi-geo-alt-fill', bgClass: 'bg-info-subtle', textClass: 'text-info' 
  }
])
</script>

<style scoped>
/* Timeline */
.timeline { display: flex; overflow-x: auto; padding: 20px 0; gap: 20px; }
.timeline-item { min-width: 250px; position: relative; border-top: 2px solid #198754; padding-top: 20px; }
.timeline-dot { width: 14px; height: 14px; background: #198754; border-radius: 50%; position: absolute; top: -8px; left: 0; }

/* Quote */
.quote-container { max-width: 650px; position: relative; }
.quote-icon { font-size: 3rem; color: #198754; opacity: 0.2; position: absolute; top: -30px; left: 0; }
.short-hr { width: 60px; }

.pilar-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 1.5rem;
}
.pilar-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
.icon-wrapper {
  width: 65px;
  height: 65px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  margin: 0 auto;
}

.fest-badge { transition: all 0.3s ease; cursor: default; }
.fest-badge:hover { background-color: #198754 !important; color: white !important; }
.text-justify { text-align: justify; }
</style>