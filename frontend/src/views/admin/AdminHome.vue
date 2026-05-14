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
    <AdminStatCard
      label="Entidades"
      :valor="stats.total"
      icono="bi bi-shop-window"
      variante="default"
      descripcion="Registradas en total"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Sitios Turísticos"
      :valor="stats.sitios"
      icono="bi bi-geo-alt-fill"
      variante="success"
      descripcion="Lugares registrados"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Eventos"
      :valor="stats.eventos"
      icono="bi bi-calendar-event-fill"
      variante="warning"
      descripcion="Festividades activas"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Usuarios"
      :valor="stats.usuarios"
      icono="bi bi-people-fill"
      variante="primary"
      descripcion="Registrados"
    />
  </div>
</div>

    <!-- ── TARJETAS POR CATEGORÍA ── -->
    <h5 class="fw-bold mb-3">Entidades por categoría</h5>

    <div v-if="cargando" class="text-center py-5 text-muted">
      <div class="spinner-border text-dark mb-2" role="status"></div>
      <p>Cargando estadísticas...</p>
    </div>

    <div v-else class="row g-3 mb-5">
  <div class="col-md-4" v-for="tipo in porTipo" :key="tipo.slug">
    <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="bg-dark bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center"
          style="width:46px; height:46px; flex-shrink:0;">
          <i class="bi bi-grid-fill fs-5 text-dark"></i>
        </div>
        <div>
          <p class="fw-bold mb-0">{{ tipo.tipo }}</p>
          <p class="text-muted small mb-0">{{ tipo.total }} establecimientos</p>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold mb-0">{{ tipo.total }}</h2>
        <RouterLink
          :to="rutaModulo(tipo.slug)"
          class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
          Ver todos <i class="bi bi-arrow-right ms-1"></i>
        </RouterLink>
      </div>
    </div>
  </div>
</div>



  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'
import { ADMIN } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'

// ── Estado ────────────────────────────────────────────────
// ── Estado ────────────────────────────────────────────────
const stats    = ref({ total: 0, sitios: 0, eventos: 0, usuarios: 0 })
const porTipo  = ref([])
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
      sitios:   payload.total_sitios,
      eventos:  payload.total_eventos,
      usuarios: payload.total_usuarios,
    }
    porTipo.value = payload.entidades_por_tipo ?? []
  } catch (err) {
    console.error('Error al cargar dashboard:', err)
  } finally {
    cargando.value = false
  }
})



function rutaModulo(slug) {
  const mapa = {
    'gastronomia':         '/admin/gastronomia',
    'recreacion':          '/admin/recreacion',
    'alojamiento':         '/admin/alojamientos',
    'alojamientos':        '/admin/alojamientos',
    'transporte':          '/admin/transportes',
    'transportes':         '/admin/transportes',
    'agencias-turisticas': '/admin/agencias',
  }
  return mapa[slug] || '/admin'
}
</script>


