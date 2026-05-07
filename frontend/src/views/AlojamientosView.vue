<template>
  <section class="container my-5">

    <div class="text-center mb-5">
      <h1 class="display-4 fw-bold">Alojamiento</h1>
      <hr class="opacity-100 text-secondary" style="height: 1px;">
    </div>

    <!-- Filtros -->
    <div v-if="subtipos.length" class="position-relative mt-5 mb-5">
      <div class="position-absolute top-0 start-0 translate-middle-y ms-4 border border-secondary-subtle bg-secondary-subtle px-3 py-1 fw-bold small shadow-sm tag-title">
        Tipo de establecimiento
      </div>
      <div class="border border-secondary-subtle bg-light p-5 pt-5 shadow-sm rounded-1">
        <div class="row g-3">
          <div class="col-6 col-md-3" v-for="subtipo in subtipos" :key="subtipo.id">
            <div class="form-check d-flex align-items-center p-0">
              <input class="form-check-input rounded-0 m-0 border-secondary-subtle shadow-none check-custom"
                     type="checkbox"
                     :id="'check-' + subtipo.id"
                     :value="subtipo.id"
                     v-model="filtros">
              <label class="form-check-label ms-2 small cursor-pointer" :for="'check-' + subtipo.id">
                {{ formatNombre(subtipo.nombre) }}
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista -->
    <div class="row g-4" style="min-height: 400px;">
      <div v-if="cargando" class="col-12 text-center py-5 text-muted">
        <div class="spinner-border text-success" role="status"></div>
        <p class="mt-3">Cargando alojamientos...</p>
      </div>

      <template v-else>
        <div class="col-md-6" v-for="entidad in entidades" :key="entidad.id">
          <div class="d-flex align-items-start border rounded-3 p-3 shadow-sm h-100 hover-card">
            <img :src="imagen(entidad)"
                 class="rounded-3 object-fit-cover flex-shrink-0"
                 width="130" height="130"
                 :alt="entidad.nombre_comercial">
            <div class="ms-4">
              <h5 class="fw-bold mb-1">{{ entidad.nombre_comercial }}</h5>
              <p class="text-muted small mb-1">
                <i class="bi bi-tag me-1"></i>{{ formatNombre(subtipo(entidad)) }}
              </p>
              <p class="text-muted small mb-0">
                <i class="bi bi-clock me-1"></i>{{ entidad.hora_atencion }}
              </p>
              <p v-if="entidad.direccion" class="text-muted small mb-0">
                <i class="bi bi-geo-alt me-1"></i>{{ entidad.direccion }}
              </p>
              <div class="mt-2 d-flex gap-3">
                <a v-if="entidad.sitio_web" :href="entidad.sitio_web" target="_blank" class="text-dark small fw-bold text-decoration-underline">
                  Sitio web <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
                <a v-if="entidad.telefono" :href="'tel:' + entidad.telefono" class="text-success small fw-bold text-decoration-underline">
                  <i class="bi bi-telephone me-1"></i>{{ entidad.telefono }}
                </a>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!entidades.length" class="col-12 text-center py-5 text-muted">
          <i class="bi bi-search fs-1"></i>
          <p class="mt-3">No se encontraron alojamientos con los filtros seleccionados.</p>
        </div>
      </template>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
      <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === 1" @click="irAnterior">
        <i class="bi bi-chevron-left fs-4"></i>
      </button>
      <span class="fw-bold h5 mb-0">{{ pagina }} de {{ totalPaginas }}</span>
      <button class="btn btn-link text-dark p-0 mx-4" :disabled="pagina === totalPaginas" @click="irSiguiente">
        <i class="bi bi-chevron-right fs-4"></i>
      </button>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue'
import { useEntidades } from '@/composables/useEntidades'

const { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente } = useEntidades('alojamientos')

onMounted(init)

const imagen    = (e) => e.imagenes?.[0]?.url_completa ?? 'https://via.placeholder.com/130x130?text=Sin+imagen'
const subtipo   = (e) => e.subtipos?.[0]?.nombre ?? ''
const formatNombre = (txt) => txt.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
</script>

<style scoped>
.tag-title { z-index: 1; margin-top: -1px; }
.check-custom { width: 22px; height: 22px; cursor: pointer; }
.cursor-pointer { cursor: pointer; }
.object-fit-cover { object-fit: cover; }
.hover-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
.hover-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
</style>
