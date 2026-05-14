<template>
  <div class="transporte-view">
    <section class="container my-5">

      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Transporte</h1>
        <hr class="w-25 mx-auto border-success border-2 opacity-100 mt-4">
      </div>

      <!-- FILTROS -->
      <div class="filtros-wrapper mb-5">
        <span class="filtros-tag">Tipo de Transporte</span>
        <div class="filtros-body">
          <label
            v-for="opcion in opcionesFiltro"
            :key="String(opcion.id)"
            class="filtro-check"
            :class="{ active: filtroActivo === opcion.id }"
            @click="seleccionarFiltro(opcion.id)">
            <span class="filtro-box" :class="{ checked: filtroActivo === opcion.id }"></span>
            {{ formatNombre(String(opcion.nombre)) }}
          </label>
        </div>
      </div>

      <!-- SECCIÓN: CÓMO LLEGAR -->
      <div v-if="filtroActivo === 'como_llegar'" class="fade-in">
        <div class="row g-4 mb-4">
          <div class="col-md-4">
            <div class="info-card">
              <i class="bi bi-signpost-2-fill text-success fs-3 mb-3 d-block"></i>
              <h5 class="fw-bold">La Ruta</h5>
              <p class="text-muted small lh-lg">
                Desde Medellín toma la autopista Medellín-Bogotá. Un viaje de aproximadamente 2 horas con paisajes verdes y montañas imponentes.
              </p>
              <ul class="list-unstyled small mt-3">
                <li class="mb-2">
                  <i class="bi bi-check-circle-fill text-success me-2"></i>
                  <strong>Distancia:</strong> 120 km desde Medellín
                </li>
                <li>
                  <i class="bi bi-check-circle-fill text-success me-2"></i>
                  <strong>Peajes:</strong> Copacabana y Santuario
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-8">
            <div class="mapa-wrapper">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d253818.1751848577!2d-75.34065649999999!3d6.115291750000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8e4428dfc80fad05%3A0x421374a24f009006!2sMedell%C3%ADn%2C%20Antioquia!3m2!1d6.244203!2d-75.581211!4m5!1s0x8e4693a0b5a3e14d%3A0x9c3d9a5b6d5e0a0!2sSan%20Luis%2C%20Antioquia!3m2!1d6.0425!2d-74.992222!5e0!3m2!1ses!2sco!4v1700000000000!5m2!1ses!2sco"
                allowfullscreen=""
                loading="lazy">
              </iframe>
            </div>
          </div>
        </div>
      </div>

      <!-- SECCIÓN: LISTA DE TRANSPORTES -->
      <div v-else class="fade-in">
        <div v-if="cargando" class="text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando transportes...</p>
        </div>

        <div v-else class="row g-4">
          <div
            class="col-md-6 col-lg-4"
            v-for="(item, idx) in transporteFiltrado"
            :key="item.id"
            v-reveal="idx * 80">
            <div class="card h-100 border-0 shadow-sm overflow-hidden transport-card">
              <img :src="imagen(item)"
                class="card-img-top"
                style="height: 200px; object-fit: cover;"
                :alt="item.nombre_comercial">
              <div class="card-body p-4 text-center">
                <h5 class="fw-bold">{{ item.nombre_comercial }}</h5>
                <p class="text-muted small mb-3">{{ item.descripcion }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                  <span class="badge bg-light text-dark border">
                    <i class="bi bi-clock me-1"></i> {{ item.hora_atencion }}
                  </span>
                </div>
                <a :href="'tel:' + item.telefono"
                  class="btn btn-success rounded-pill w-100 fw-bold shadow-sm">
                  <i class="bi bi-telephone-fill me-2"></i> Contactar
                </a>
              </div>
            </div>
          </div>

          <div v-if="!transporteFiltrado.length" class="col-12 text-center py-5 text-muted">
            <i class="bi bi-bus-front fs-1"></i>
            <p class="mt-3">No se encontraron transportes.</p>
          </div>
        </div>

        <PaginacionNav
          :pagina="pagina"
          :total-paginas="totalPaginas"
          @anterior="irAnterior"
          @siguiente="irSiguiente" />
      </div>

    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'

import imgRuta from '@/assets/img/principal/Transporte.webp'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('transportes')

const filtroActivo = ref('como_llegar')

const opcionesFiltro = computed(() => [
  { id: 'como_llegar', nombre: '¿Cómo llegar?', icon: 'bi-signpost-2' },
  ...subtipos.value.map((s) => ({ id: s.id, nombre: s.nombre, icon: 'bi-bus-front' })),
])

function seleccionarFiltro(id) {
  filtroActivo.value = id
  filtros.value = (id && id !== 'como_llegar') ? [id] : []
}

const transporteFiltrado = computed(() => entidades.value)

const imagen = (e) => e.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/400x200?text=Sin+imagen'
const formatNombre = (txt) => String(txt).replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())

onMounted(async () => { await init() })
</script>

<style scoped>
/* ── Filtros ── */
.filtros-wrapper {
  position: relative;
  border: 1.5px solid #dee2e6;
  border-radius: 0.75rem;
  padding: 1.2rem 1.5rem;
  padding-top: 1.8rem;
}

.filtros-tag {
  position: absolute;
  top: -13px;
  left: 1.2rem;
  background: #fff;
  padding: 0 0.5rem;
  font-size: 0.8rem;
  font-weight: 700;
  color: #444;
  border: 1.5px solid #dee2e6;
  border-radius: 0.4rem;
}

.filtros-body {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  align-items: center;
}

.filtro-check {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.92rem;
  font-weight: 500;
  color: #333;
  user-select: none;
  transition: color 0.2s;
}
.filtro-check.active { color: #198754; font-weight: 700; }

.filtro-box {
  width: 20px;
  height: 20px;
  border: 2px solid #adb5bd;
  border-radius: 4px;
  display: inline-block;
  transition: all 0.2s;
  flex-shrink: 0;
}
.filtro-box.checked {
  background: #198754;
  border-color: #198754;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='white' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  background-size: 14px;
}

/* ── Info card ── */
.info-card {
  background: #f8fffe;
  border: 1.5px solid #b7dfc9;
  border-radius: 1rem;
  padding: 1.8rem;
  height: 100%;
}

/* ── Mapa ── */
.mapa-wrapper {
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  height: 100%;
  min-height: 320px;
}
.mapa-wrapper iframe {
  width: 100%;
  height: 100%;
  min-height: 320px;
  border: none;
  display: block;
}

/* ── Cards transporte ── */
.transport-card {
  transition: transform 0.3s ease;
  border-radius: 1.5rem;
}
.transport-card:hover { transform: translateY(-8px); }

/* ── Animación ── */
.fade-in {
  animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .filtros-body { flex-direction: column; align-items: flex-start; gap: 0.8rem; }
  .mapa-wrapper { min-height: 220px; }
}</style>