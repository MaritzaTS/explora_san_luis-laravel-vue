<template>
  <div class="historia-view">
    <section class="container my-5">
      <!-- ENCABEZADO: IDENTIDAD Y RESILIENCIA -->
      <div class="text-center mb-5 pb-3 ">
        <h1 class="fw-bold display-5 mb-2 reveal-up">Historia y Patrimonio</h1>
        <p class="text-secondary fs-5 mb-4 reveal-up">San Luis, Antioquia: Un relato de transformación y talante emprendedor</p>
        <div class="quote-container mx-auto reveal-up delay-2">
          <span class="quote-icon">“</span>
          <p class="fst-italic h5 text-muted">
            De la colonización paisa a la consolidación como referente del turismo y la agroindustria regional.
          </p>
          <hr class="short-hr mx-auto border-success opacity-100">
        </div>
      </div>

      <!-- SECCIÓN 1: HITOS HISTÓRICOS (TIMELINE HORIZONTAL) -->
<div class="row mb-5 pb-3">
  <div class="col-12">
    <h3 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-success"></i>Línea del Tiempo</h3>

    <!-- Carril de nodos -->
    <div class="timeline-track-wrapper">
      <div class="timeline-track">
        <div class="track-line"></div>
        <div
          v-for="(hito, index) in hitosHistoricos"
          :key="index"
          class="track-node reveal-up"
          :class="['delay-' + (index + 1),{ active: hitoActivo === index }]"
          @click="seleccionarHito(index)"
        >
        <div class="node-dot" :class="{ active: hitoActivo === index }">
            <img :src="hito.imagen" :alt="hito.titulo" class="node-img" />
        </div>
          <span class="node-ano">{{ hito.ano }}</span>
        </div>
      </div>
    </div>

    <!-- Panel de detalle -->
    <transition name="slide-detail">
      <div v-if="hitoActivo !== null" class="detalle-panel mt-4">
        <div class="detalle-header">
          <div>
            <span class="detalle-ano" :style="{ color: hitosActual.color }">{{ hitosActual.ano }}</span>
            <span class="badge ms-2" :style="{ backgroundColor: hitosActual.color }">{{ hitosActual.categoria }}</span>
            <h5 class="fw-bold mt-1 mb-0">{{ hitosActual.titulo }}</h5>
          </div>
          <button class="btn-cerrar" @click="hitoActivo = null">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <p class="detalle-desc">{{ hitosActual.descripcion }}</p>
        <div class="detalle-nav">
          <button class="btn btn-outline-success btn-sm rounded-pill" :disabled="hitoActivo === 0" @click="hitoActivo--">
            <i class="bi bi-arrow-left me-1"></i> Anterior
          </button>
          <span class="detalle-contador">{{ hitoActivo + 1 }} / {{ hitosHistoricos.length }}</span>
          <button class="btn btn-success btn-sm rounded-pill" :disabled="hitoActivo === hitosHistoricos.length - 1" @click="hitoActivo++">
            Siguiente <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </transition>

    <p v-if="hitoActivo === null" class="text-center text-muted small mt-4">
      <i class="bi bi-hand-index me-1"></i> Selecciona un punto para explorar ese período
    </p>
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
        
        <div class="col-md-6 col-lg-4 reveal-up" v-for="(pilar, idx) in pilaresEconomicos" :key="idx" 
            :class="'delay-' + (idx % 3 + 1)">
            
          <div class="card h-100 border-0 shadow-sm p-4 text-center pilar-card">
            <div class="icon-wrapper mb-3 " :class="pilar.bgClass">
              <i :class="[pilar.icon, pilar.textClass]"></i>
            </div>
            <h5 class="fw-bold">{{ pilar.titulo }}</h5>
            <p class="small text-muted mb-0">{{ pilar.descripcion }}</p>
          </div>
        </div>
      </div>

      <!-- SECCIÓN DE CURIOSIDADES -->
<div class="row mt-5 reveal-up delay-1">
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
import { ref, computed } from 'vue'
import imgCultura from '@/assets/img/home/cultura.webp'

import imgFundacion from '@/assets/img/home/fundacion.webp'
import imgColonizacion from '@/assets/img/home/colonizacion.webp'
import imgAuge from '@/assets/img/home/auge.webp'
import imgResiliencia from '@/assets/img/home/resiliencia.webp'
import imgRenacimiento from '@/assets/img/home/renacimiento.webp'
import imgActualidad from '@/assets/img/home/actualidad.webp'

const hitoActivo = ref(null)

const hitosActual = computed(() => hitosHistoricos.value[hitoActivo.value])

function seleccionarHito(index) {
  hitoActivo.value = hitoActivo.value === index ? null : index
}

const hitosHistoricos = ref([
  {
    ano: '1875', 
    imagen: imgFundacion, 
    titulo: 'Fundación del municipio',
    evento: 'Fundación y establecimiento de los primeros trapiches paneleros.',
    descripcion: 'San Luis fue erigido como municipio en 1875, impulsado por familias colonizadoras paisas que encontraron en sus tierras fértiles el escenario ideal para establecer trapiches paneleros, base de la economía regional durante décadas.',
    categoria: 'Fundación',
    icono: 'bi-building',
    color: '#198754'
  },
  {
    ano: 'Siglo XIX',
    imagen: imgColonizacion,
    titulo: 'Colonización paisa',
    evento: 'Época dorada de la colonización paisa y expansión agrícola.',
    descripcion: 'La colonización antioqueña transformó el paisaje de San Luis. Familias enteras llegaron desde el altiplano, talaron montañas, trazaron caminos y sembraron café, caña y maíz, configurando la identidad cultural del municipio.',
    categoria: 'Expansión',
    icono: 'bi-tree',
    color: '#5e7a2e'
  },
  {
    ano: 'Siglo XX',
    imagen: imgAuge, 
    titulo: 'Auge productivo',
    evento: 'Consolidación de la economía agropecuaria y la vida campesina.',
    descripcion: 'San Luis vivió su mayor auge productivo con la diversificación agrícola. La ganadería, la panela y el cacao se convirtieron en los pilares económicos, y las veredas florecieron con una intensa vida comunitaria.',
    categoria: 'Prosperidad',
    icono: 'bi-cash-coin',
    color: '#d4a017'
  },
  {
    ano: 'Resiliencia',
    imagen: imgResiliencia,
    titulo: 'Superación del conflicto',
    evento: 'Transformación social y superación de periodos de conflicto.',
    descripcion: 'Como muchos municipios de Antioquia, San Luis atravesó periodos difíciles de conflicto armado. Sin embargo, la fortaleza de su gente y el apoyo institucional lograron una transformación social profunda que hoy es ejemplo nacional.',
    categoria: 'Resiliencia',
    icono: 'bi-heart',
    color: '#c0392b'
  },
  {
    ano: '2000s',
    imagen: imgRenacimiento,
    titulo: 'Renacimiento turístico',
    evento: 'Apertura del municipio al turismo de aventura y naturaleza.',
    descripcion: 'Con la recuperación de la seguridad, San Luis descubrió su potencial turístico. El Río Samaná Sur, las cascadas y los senderos comenzaron a atraer visitantes de toda Colombia, posicionando al municipio como destino de ecoturismo.',
    categoria: 'Turismo',
    icono: 'bi-compass',
    color: '#1a6fb5'
  },
  {
    ano: 'Actualidad',
    imagen: imgActualidad,
    titulo: 'Referente regional',
    evento: 'Liderazgo en producción de cacao fino y consolidación turística.',
    descripcion: 'Hoy San Luis es reconocido como "La Perla del Oriente Antioqueño". Su cacao fino de aroma conquista mercados internacionales, sus cascadas son patrimonio natural, y la Plataforma Explora San Luis conecta al mundo con esta tierra maravillosa.',
    categoria: 'Presente',
    icono: 'bi-stars',
    color: '#198754'
  }
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
/* ── Timeline horizontal ── */
.timeline-track-wrapper {
  overflow-x: auto;
  padding-bottom: 8px;
}
.timeline-track {
  display: flex;
  align-items: flex-start;
  position: relative;
  min-width: 600px;
  padding: 30px 20px 10px;
  gap: 0;
}
.track-line {
  position: absolute;
  top: 66px;
  left: 20px;
  right: 20px;
  height: 3px;
  background: linear-gradient(to right, #198754, #a8d5b5);
  z-index: 0;
}
.track-node {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  z-index: 1;
  transition: transform 0.2s ease;
}
.track-node:hover { transform: translateY(-4px); }
.track-node.active { transform: translateY(-6px); }

.node-dot {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  flex-shrink: 0;
}
.track-node.active .node-dot {
  transform: scale(1.15);
  box-shadow: 0 6px 20px rgba(0,0,0,0.25);
  border-color: #198754;
}
.node-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.node-ano {
  font-size: 0.75rem;
  font-weight: 700;
  color: #444;
  margin-top: 10px;
  text-align: center;
  white-space: nowrap;
}

/* ── Panel de detalle ── */
.detalle-panel {
  background: #f8fffe;
  border: 1px solid #b7dfc9;
  border-radius: 1.2rem;
  padding: 1.5rem;
}
.detalle-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.8rem;
}
.detalle-ano { font-weight: 700; font-size: 1rem; }
.detalle-desc { color: #555; line-height: 1.7; margin-bottom: 1rem; }
.detalle-nav {
  display: flex;
  align-items: center;
  gap: 0.8rem;
}
.detalle-contador { font-size: 0.85rem; color: #888; }
.btn-cerrar {
  background: none;
  border: 1px solid #dee2e6;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;
  flex-shrink: 0;
  transition: all 0.2s;
}
.btn-cerrar:hover { background: #f1f1f1; }

/* ── Transición del panel ── */
.slide-detail-enter-active { transition: all 0.3s ease; }
.slide-detail-leave-active { transition: all 0.2s ease; }
.slide-detail-enter-from { opacity: 0; transform: translateY(-10px); }
.slide-detail-leave-to   { opacity: 0; transform: translateY(-6px); }

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