<template>
  <div class="alojamientos-view">
    <section class="container my-5">

      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Alojamiento</h1>
        <hr class="opacity-100 text-secondary" style="height: 1px;">
        <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">
          Encuentra el lugar perfecto para descansar en San Luis, Antioquia.
        </p>
      </div>

      <!-- FILTROS -->
      <div v-if="subtipos.length" class="filtros-wrapper mb-5">
        <span class="filtros-tag">Tipo de alojamiento</span>
        <div class="filtros-body">
          <label
            v-for="subtipo in subtipos"
            :key="subtipo.id"
            class="filtro-check"
            :class="{ active: filtros.includes(subtipo.id) }">
            <input type="checkbox" :value="subtipo.id" v-model="filtros" class="d-none">
            <span class="filtro-box" :class="{ checked: filtros.includes(subtipo.id) }"></span>
            {{ formatNombre(subtipo.nombre) }}
          </label>
        </div>
      </div>

      <!-- LISTA -->
      <div class="row g-4" style="min-height: 400px;">
        <div v-if="cargando" class="col-12 text-center py-5 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-3">Cargando alojamientos...</p>
        </div>

        <template v-else>
  <div class="col-md-6 col-lg-4"
    v-for="(entidad, idx) in entidades"
    :key="entidad.id"
    v-reveal="idx * 80">
    <EntidadCard :entidad="entidad" />
  </div>

  <div v-if="!entidades.length" class="col-12 text-center py-5 text-muted">
    <i class="bi bi-search fs-1"></i>
    <p class="mt-3">No se encontraron alojamientos con los filtros seleccionados.</p>
  </div>
</template>
      </div>

      <!-- PAGINACIÓN -->
      <PaginacionNav :pagina="pagina" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
    </section>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'
import EntidadCard from '@/components/shared/EntidadCard.vue'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('alojamientos')

onMounted(init)


const formatNombre = (txt) => txt.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
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
  gap: 1rem;
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
}
.filtro-check.active { color: #198754; font-weight: 700; }
.filtro-box {
  width: 20px;
  height: 20px;
  border: 2px solid #adb5bd;
  border-radius: 4px;
  display: inline-block;
  flex-shrink: 0;
  transition: all 0.2s;
}
.filtro-box.checked {
  background: #198754;
  border-color: #198754;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='white' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  background-size: 14px;
}
</style>
