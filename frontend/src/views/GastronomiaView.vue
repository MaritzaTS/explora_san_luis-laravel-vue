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
        <!-- Estado de carga -->
        <div v-if="cargando" class="col-12 text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando delicias locales...</p>
        </div>

        <!-- Cards de Gastronomía -->
        <template v-else>
          <div class="col-md-6 col-lg-4" v-for="entidad in entidadesFiltradas" :key="entidad.id">
            <div class="card h-100 border-0 shadow-sm hover-card">
              <img :src="imagen(entidad)"
                   class="card-img-top object-fit-cover"
                   style="height: 200px;"
                   :alt="entidad.nombre_comercial">
              <div class="card-body">
                <h5 class="fw-bold mb-1">{{ entidad.nombre_comercial }}</h5>
                <p class="text-muted small mb-2">
                  <i class="bi bi-clock me-1"></i>{{ entidad.hora_atencion }}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="badge bg-secondary-subtle text-dark fw-normal">{{ formatNombre(subtipo(entidad)) }}</span>
                  <a :href="'tel:' + entidad.telefono" class="btn btn-outline-success btn-sm rounded-circle">
                    <i class="bi bi-telephone"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Mensaje si no hay resultados -->
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
.tag-title {
  z-index: 1;
  margin-top: -1px;
}

.check-custom {
  width: 22px;
  height: 22px;
  cursor: pointer;
}

.hover-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.object-fit-cover {
  object-fit: cover;
}

.cursor-pointer {
  cursor: pointer;
}
</style>