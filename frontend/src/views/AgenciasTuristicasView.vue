<template>
  <div class="agencias-view">
    <!-- El HeroBanner se cargará automáticamente desde el Layout con el Carrusel -->
    
    <section class="container my-5">
      
      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Agencias Turísticas</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
        <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">
          Descubre las agencias turísticas locales de San Luis, Antioquia
          y planifica tu aventura perfecta.
        </p>
      </div>

      <!-- FILTROS (Solo se muestran si hay subtipos) -->
      <div v-if="subtipos.length > 0" class="position-relative mt-5 mb-5">
        <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
          Tipo de establecimiento
        </div>
        <div class="border border-secondary-subtle bg-light p-4 pt-5 shadow-sm rounded-1">
          <div class="row g-3">
            <div class="col-6 col-md-3" v-for="subtipo in subtipos" :key="subtipo.id">
              <div class="form-check d-flex align-items-center p-0">
                <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none check-custom"
                       type="checkbox"
                       :id="'check-' + subtipo.id"
                       :value="subtipo.id"
                       v-model="filtrosSeleccionados">
                <label class="form-check-label ms-2 small cursor-pointer" :for="'check-' + subtipo.id">
                  {{ subtipo.nombre }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTENEDOR DE ENTIDADES -->
<div class="row g-4" style="min-height: 400px;">

  <div v-if="cargando" class="col-12 text-center py-5 text-muted">
    <div class="spinner-border text-success" role="status"></div>
    <p class="mt-3">Cargando agencias...</p>
  </div>

  <template v-else>
    <div class="col-md-6 col-lg-4"
      v-for="(agencia, idx) in agenciasFiltradas"
      :key="agencia.id"
      v-reveal="idx * 80">
      <EntidadCard :entidad="agencia" />
    </div>

    <div v-if="agenciasFiltradas.length === 0" class="col-12 text-center py-5 text-muted">
      <i class="bi bi-info-circle fs-1"></i>
      <p class="mt-3">No se encontraron agencias con los filtros seleccionados.</p>
    </div>
  </template>

  </div>

      <!-- PAGINACIÓN -->
      <PaginacionNav :pagina="paginaActual" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'
import EntidadCard from '@/components/shared/EntidadCard.vue'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('agencias-turisticas')

const filtrosSeleccionados = filtros
const paginaActual         = pagina
const agenciasFiltradas    = computed(() => entidades.value)



onMounted(init)
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

.cursor-pointer {
  cursor: pointer;
}

.hover-card {
  transition: transform 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
}

.object-fit-cover {
  object-fit: cover;
}
</style>