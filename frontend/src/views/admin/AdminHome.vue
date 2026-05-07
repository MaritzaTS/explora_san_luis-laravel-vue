<template>
  <div>

    <!-- SALUDO -->
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Bienvenido, {{ nombreAdmin }} 👋</h4>
      <p class="text-muted small mb-0">Resumen general del sistema</p>
    </div>

    <!-- ── TARJETAS DE RESUMEN GLOBAL ── -->
    <div class="row g-3 mb-5">

      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-muted small fw-semibold text-uppercase">Total</span>
            <span class="bg-dark bg-opacity-10 rounded-3 p-2 lh-1">
              <i class="bi bi-shop fs-5 text-dark"></i>
            </span>
          </div>
          <h2 class="fw-bold mb-0">{{ stats.total }}</h2>
          <p class="text-muted small mb-0">Entidades registradas</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-muted small fw-semibold text-uppercase">Activas</span>
            <span class="bg-success bg-opacity-10 rounded-3 p-2 lh-1">
              <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            </span>
          </div>
          <h2 class="fw-bold mb-0 text-success">{{ stats.activos }}</h2>
          <p class="text-muted small mb-0">Visibles en el sitio</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-muted small fw-semibold text-uppercase">Inactivas</span>
            <span class="bg-danger bg-opacity-10 rounded-3 p-2 lh-1">
              <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
            </span>
          </div>
          <h2 class="fw-bold mb-0 text-danger">{{ stats.inactivos }}</h2>
          <p class="text-muted small mb-0">Ocultas en el sitio</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-muted small fw-semibold text-uppercase">Usuarios</span>
            <span class="bg-primary bg-opacity-10 rounded-3 p-2 lh-1">
              <i class="bi bi-people-fill fs-5 text-primary"></i>
            </span>
          </div>
          <h2 class="fw-bold mb-0 text-primary">{{ stats.usuarios }}</h2>
          <p class="text-muted small mb-0">Registrados</p>
        </div>
      </div>

    </div>

    <!-- ── TARJETAS POR CATEGORÍA ── -->
    <h5 class="fw-bold mb-3">Entidades por categoría</h5>

    <div v-if="cargando" class="text-center py-5 text-muted">
      <div class="spinner-border text-dark mb-2" role="status"></div>
      <p>Cargando estadísticas...</p>
    </div>

    <div v-else class="row g-3 mb-5">
      <div class="col-md-4" v-for="tipo in porTipo" :key="tipo.nombre">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100">

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bg-dark bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center"
              style="width:46px; height:46px; flex-shrink:0;">
              <i class="bi bi-grid-fill fs-5 text-dark"></i>
            </div>
            <div>
              <p class="fw-bold mb-0">{{ capitalizar(tipo.nombre) }}</p>
              <p class="text-muted small mb-0">Establecimientos activos</p>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold mb-0">{{ tipo.total }}</h2>
            <RouterLink
              :to="rutaModulo(tipo.nombre)"
              class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
              Ver todos <i class="bi bi-arrow-right ms-1"></i>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>

    <!-- ── TABLA DE ACTIVIDAD RECIENTE ── -->
    <h5 class="fw-bold mb-3">Últimas entidades registradas</h5>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-4 py-3 text-muted small fw-semibold text-uppercase border-0">#</th>
            <th class="py-3 text-muted small fw-semibold text-uppercase border-0">Nombre comercial</th>
            <th class="py-3 text-muted small fw-semibold text-uppercase border-0">Categoría</th>
            <th class="py-3 text-muted small fw-semibold text-uppercase border-0">Estado</th>
            <th class="py-3 text-muted small fw-semibold text-uppercase border-0">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="ultimas.length === 0 && !cargando">
            <td colspan="5" class="text-center text-muted py-4">
              No hay registros recientes.
            </td>
          </tr>
          <tr v-for="e in ultimas" :key="e.id">
            <td class="ps-4 text-muted small">{{ e.id }}</td>
            <td class="fw-semibold">{{ e.nombre_comercial }}</td>
            <td><span class="text-muted small">{{ capitalizar(e.tipo?.nombre ?? e.tipo) }}</span></td>
            <td>
              <span v-if="e.estado"
                class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                Activo
              </span>
              <span v-else
                class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                Inactivo
              </span>
            </td>
            <td class="text-muted small">{{ formatFecha(e.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'
import { ADMIN } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'

// ── Estado ────────────────────────────────────────────────
const stats    = ref({ total: 0, activos: 0, inactivos: 0, usuarios: 0 })
const porTipo  = ref([])
const ultimas  = ref([])
const cargando = ref(true)

// ── Saludo ────────────────────────────────────────────────
const authStore = useAuthStore()
const nombreAdmin = computed(() => {
  const completo = authStore.usuario?.nombre ?? 'Administrador'
  return completo.split(' ')[0]
})

// ── Carga inicial ─────────────────────────────────────────
onMounted(async () => {
  try {
    cargando.value = true
    const { data } = await api.get(ADMIN.DASHBOARD_STATS)
    const payload = data.data
    stats.value = {
      total:    payload.total_entidades,
      activos:  payload.entidades_activas,
      inactivos: payload.entidades_inactivas,
      usuarios: payload.total_usuarios,
    }
    porTipo.value = payload.por_tipo ?? []
    ultimas.value = payload.ultimas_entidades ?? []
  } catch (err) {
    console.error('Error al cargar dashboard:', err)
  } finally {
    cargando.value = false
  }
})

// ── Helpers ───────────────────────────────────────────────
function capitalizar(texto) {
  if (!texto) return ''
  return texto.charAt(0).toUpperCase() + texto.slice(1)
}

function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  return `${d.getDate().toString().padStart(2,'0')} ${meses[d.getMonth()]} ${d.getFullYear()}`
}

function rutaModulo(nombre) {
  const mapa = {
    'gastronomia':         '/admin/gastronomia',
    'recreacion':          '/admin/recreacion',
    'alojamiento':         '/admin/alojamientos',
    'alojamientos':        '/admin/alojamientos',
    'transporte':          '/admin/transportes',
    'transportes':         '/admin/transportes',
    'agencias turisticas': '/admin/agencias',
    'agencias_turisticas': '/admin/agencias',
  }
  return mapa[nombre?.toLowerCase()] || '/admin'
}
</script>