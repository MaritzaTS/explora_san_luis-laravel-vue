<template>
  <div class="gastronomia-view">
    <!-- El carrusel se muestra automáticamente desde el DefaultLayout -->

    <section class="container my-5">
     
      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Gastronomía</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
        <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">
          Descubre los mejores lugares para comer en San Luis, Antioquia. 
          Usa los filtros de abajo para encontrar exactamente lo que buscas, 
          ya sea un restaurante, un café bar o comidas rápidas.
        </p>
      </div>

      <!-- FILTROS POR SUBTIPO (Reactivos) -->
      <div class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
          Tipo de establecimiento
        </div>

        <div class="border border-secondary-subtle bg-light p-4 pt-5 shadow-sm rounded-1">
          <div class="row g-3">
            <div class="col-6 col-md-3" v-for="subtipo in subtipos" :key="subtipo.id">
              <div class="form-check d-flex align-items-center p-0">
                <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none check-custom"
                       type="checkbox"
                       :id="'subtipo-' + subtipo.id"
                       :value="subtipo.id"
                       v-model="filtrosSeleccionados">
                <label class="form-check-label ms-2 small cursor-pointer" :for="'subtipo-' + subtipo.id">
                  {{ formatNombre(subtipo.nombre) }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      
      <!-- CONTENEDOR DE ENTIDADES -->
  <div class="row g-4" id="contenedor-entidades" style="min-height: 500px;">
  <div v-if="cargando" class="col-12 text-center py-5 text-muted">
    <div class="spinner-border text-success" role="status"></div>
    <p class="mt-3">Cargando delicias locales...</p>
  </div>

  <template v-else>
  <div class="col-md-6 col-lg-4"
    v-for="(entidad, idx) in entidadesFiltradas"
    :key="entidad.id"
    v-reveal="idx * 80">
    <EntidadCard :entidad="entidad" />
  </div>

  <div v-if="entidadesFiltradas.length === 0" class="col-12 text-center py-5 text-muted">
    <i class="bi bi-search fs-1"></i>
    <p class="mt-3">No hay establecimientos que coincidan con tu selección.</p>
  </div>
</template>
    </div>
      <!-- PAGINACIÓN -->
      <PaginacionNav :pagina="paginaActual" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
    </section>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useEntidades } from '@/composables/useEntidades'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'
import EntidadCard from '@/components/shared/EntidadCard.vue'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('gastronomia')

const filtrosSeleccionados = filtros
const paginaActual         = pagina
const entidadesFiltradas   = computed(() => entidades.value)

onMounted(init)

const imagen = (e) => e.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/400x250?text=Sin+imagen'
const subtipo = (e) => e.subtipos?.[0]?.nombre ?? ''
const formatNombre = (txt) => txt.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
</script>

<style scoped>
/* ── Filtros ── */
.tag-title { z-index: 1; margin-top: -1px; }
.check-custom { width: 22px; height: 22px; cursor: pointer; }
.cursor-pointer { cursor: pointer; }

/* ── Cards ── */

.hover-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
}
.hover-card:hover .card-logo {
  transform: scale(1.05);
}
</style>