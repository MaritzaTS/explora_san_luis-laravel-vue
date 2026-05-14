<template>
  <section class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold border-bottom pb-3 d-inline-block px-5">Eventos</h2>
    </div>

    <!-- Cargando -->
    <div v-if="cargando" class="text-center py-5 text-muted">
      <div class="spinner-border text-success" role="status"></div>
      <p class="mt-3">Cargando eventos...</p>
    </div>

    <template v-else>
      <div class="row g-4">
  <div class="col-md-6 col-lg-4"
    v-for="(evento, idx) in eventos"
    :key="evento.id"
    v-reveal="idx * 80">
    <EventoCard :evento="evento" />
  </div>
</div>

<div v-if="!eventos.length" class="text-center py-5 text-muted">
  <i class="bi bi-calendar-x fs-1"></i>
  <p class="mt-3">No hay eventos próximos disponibles.</p>
</div>

      <!-- Paginación -->
      <PaginacionNav :pagina="pagina" :total-paginas="totalPaginas" @anterior="irAnterior" @siguiente="irSiguiente" />
    </template>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'
import PaginacionNav from '@/components/ui/PaginacionNav.vue'
import EventoCard from '@/components/shared/EventoCard.vue'

const eventos      = ref([])
const cargando     = ref(false)
const pagina       = ref(1)
const totalPaginas = ref(1)

async function cargar() {
  cargando.value = true
  try {
    const { data } = await api.get(PUBLICO.EVENTOS, { params: { page: pagina.value } })
    const payload = data.data
    if (payload?.data) {
      eventos.value      = payload.data
      pagina.value       = payload.current_page ?? pagina.value
      totalPaginas.value = payload.last_page    ?? 1
    } else {
      eventos.value = Array.isArray(payload) ? payload : []
    }
  } finally {
    cargando.value = false
  }
}

function irAnterior() { if (pagina.value > 1) { pagina.value--; cargar() } }
function irSiguiente() { if (pagina.value < totalPaginas.value) { pagina.value++; cargar() } }

function formatFecha(fecha) {
  if (!fecha) return ''
  return new Date(fecha).toLocaleDateString('es-CO', { year: 'numeric', month: 'long', day: 'numeric' })
}

onMounted(cargar)
</script>

<style scoped>

</style>
