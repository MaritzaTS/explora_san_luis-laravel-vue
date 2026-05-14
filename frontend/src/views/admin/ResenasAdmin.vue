<template>
  <div>

    <!-- TARJETAS ESTADÍSTICAS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <AdminStatCard
      label="Total Reseñas"
      :valor="totalResenas"
      icono="bi bi-chat-left-heart-fill"
      variante="default"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Aprobadas"
      :valor="aprobadas"
      icono="bi bi-check-circle-fill"
      variante="success"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Pendientes"
      :valor="pendientes"
      icono="bi bi-hourglass-split"
      variante="warning"
    />
  </div>
</div>

    <!-- TABLA -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6">
            <h4 class="fw-bold mb-0">Gestión de Reseñas</h4>
            <p class="text-muted small mb-0">Modera los comentarios enviados por los usuarios</p>
          </div>
          <div class="col-md-6">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar por usuario o comentario..." />
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando reseñas...</p>
          </div>

          <div v-else-if="errorCarga" class="text-center p-5 text-muted">
            <i class="bi bi-exclamation-circle fs-1 d-block mb-3 text-warning"></i>
            <p class="fw-semibold">No se pudo cargar las reseñas.</p>
            <button class="btn btn-sm btn-outline-dark mt-1" @click="cargarResenas">
              <i class="bi bi-arrow-clockwise me-1"></i>Reintentar
            </button>
          </div>

          <table v-else class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4 py-3 text-muted small fw-semibold text-uppercase">Usuario</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Comentario</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Fecha</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Estado</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase text-end pe-4">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="resenasFiltradas.length === 0">
                <td colspan="5" class="text-center text-muted py-5">
                  <i class="bi bi-chat-left-heart fs-1 d-block mb-3"></i>
                  No se encontraron reseñas.
                </td>
              </tr>
              <tr v-for="resena in resenasFiltradas" :key="resena.id">
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width:36px;height:36px;">
                      <i class="bi bi-person-fill text-dark small"></i>
                    </div>
                    <span class="fw-semibold small">{{ resena.usuario?.nombre ?? 'Anónimo' }}</span>
                  </div>
                </td>
                <td>
                  <p class="mb-0 small text-muted" style="max-width:350px;">{{ resena.comentario }}</p>
                </td>
                <td class="text-muted small">{{ formatFecha(resena.created_at) }}</td>
                <td>
                  <span v-if="resena.estado"
                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                    Aprobada
                  </span>
                  <span v-else
                    class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">
                    Pendiente
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button
                    :class="resena.estado
                      ? 'btn btn-sm btn-light border text-danger shadow-none'
                      : 'btn btn-sm btn-light border text-success shadow-none'"
                    :title="resena.estado ? 'Rechazar' : 'Aprobar'"
                    @click="toggleEstado(resena)">
                    <i :class="resena.estado ? 'bi bi-x-circle' : 'bi bi-check-circle'"></i>
                    {{ resena.estado ? 'Rechazar' : 'Aprobar' }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <AdminPaginacion
  :pagina-actual="paginaActual"
  :total-paginas="totalPaginas"
  :total="totalRegistros"
  @cambiar="cargarResenas"
/>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'
import { ADMIN } from '@/api/endpoints'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'
import AdminPaginacion from '@/components/admin/AdminPaginacion.vue'

const toast = useToast()
const { confirmar, alertaError } = useConfirm()

const resenas    = ref([])
const totalApi   = ref(0)
const cargando   = ref(true)
const errorCarga = ref(false)
const busqueda   = ref('')
const paginaActual   = ref(1)
const totalPaginas   = ref(1)
const totalRegistros = ref(0)


const totalResenas = computed(() => totalApi.value || resenas.value.length)
const aprobadas    = computed(() => resenas.value.filter(r => r.estado).length)
const pendientes   = computed(() => resenas.value.filter(r => !r.estado).length)

const resenasFiltradas = computed(() => {
  const q = busqueda.value.toLowerCase()
  return resenas.value.filter(r =>
    !q ||
    r.comentario?.toLowerCase().includes(q) ||
    r.usuario?.nombre?.toLowerCase().includes(q)
  )
})

onMounted(cargarResenas)

async function cargarResenas(pagina = 1) {
  cargando.value = true
  try {
    const { data } = await api.get(ADMIN.RESENAS, { params: { page: pagina } })
    const payload = data.data
    resenas.value        = payload?.resenas ?? (Array.isArray(payload) ? payload : [])
    paginaActual.value   = payload?.pagina_actual ?? 1
    totalPaginas.value   = payload?.total_paginas ?? 1
    totalRegistros.value = payload?.total ?? resenas.value.length
  } finally { cargando.value = false }
}

async function toggleEstado(resena) {
  const accion = resena.estado ? 'rechazar' : 'aprobar'
  const ok = await confirmar({
    titulo: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} reseña?`,
    texto: `La reseña de "${resena.usuario?.nombre ?? 'este usuario'}" será ${resena.estado ? 'rechazada' : 'aprobada y visible en el sitio'}.`,
    textoBoton: `Sí, ${accion}`,
  })
  if (!ok) return
  try {
    await api.patch(ADMIN.RESENA_ESTADO(resena.id), { estado: !resena.estado })
    resena.estado = !resena.estado
    toast.exito(`Reseña ${resena.estado ? 'aprobada' : 'rechazada'}.`)
  } catch {
    alertaError('No se pudo cambiar el estado de la reseña.')
  }
}

function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  return `${d.getDate().toString().padStart(2,'0')} ${meses[d.getMonth()]} ${d.getFullYear()}`
}


</script>
