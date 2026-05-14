<template>
  <div v-if="totalPaginas > 1" class="d-flex justify-content-between align-items-center mt-4 pt-2">
    <span class="text-muted small">
      Mostrando página {{ paginaActual }} de {{ totalPaginas }}
      <span v-if="total"> · {{ total }} registros</span>
    </span>

    <nav>
      <ul class="pagination pagination-sm mb-0 gap-1">
        <!-- Anterior -->
        <li class="page-item" :class="{ disabled: paginaActual <= 1 }">
          <button class="page-link border-0 rounded-2 shadow-none text-dark"
            @click="cambiar(paginaActual - 1)" :disabled="paginaActual <= 1">
            <i class="bi bi-chevron-left"></i>
          </button>
        </li>

        <!-- Números -->
        <li v-for="p in paginasVisibles" :key="p" class="page-item">
          <button v-if="p === '...'" class="page-link border-0 bg-transparent shadow-none text-muted px-2" disabled>
            ...
          </button>
          <button v-else
            class="page-link border-0 rounded-2 shadow-none fw-semibold"
            :class="p === paginaActual ? 'bg-dark text-white' : 'text-dark'"
            @click="cambiar(p)">
            {{ p }}
          </button>
        </li>

        <!-- Siguiente -->
        <li class="page-item" :class="{ disabled: paginaActual >= totalPaginas }">
          <button class="page-link border-0 rounded-2 shadow-none text-dark"
            @click="cambiar(paginaActual + 1)" :disabled="paginaActual >= totalPaginas">
            <i class="bi bi-chevron-right"></i>
          </button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  paginaActual:  { type: Number, required: true },
  totalPaginas:  { type: Number, required: true },
  total:         { type: Number, default: 0 },
})

const emit = defineEmits(['cambiar'])

function cambiar(pagina) {
  if (pagina >= 1 && pagina <= props.totalPaginas && pagina !== props.paginaActual) {
    emit('cambiar', pagina)
  }
}

const paginasVisibles = computed(() => {
  const total = props.totalPaginas
  const actual = props.paginaActual
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)

  const paginas = []
  paginas.push(1)

  if (actual > 3) paginas.push('...')

  const inicio = Math.max(2, actual - 1)
  const fin    = Math.min(total - 1, actual + 1)
  for (let i = inicio; i <= fin; i++) paginas.push(i)

  if (actual < total - 2) paginas.push('...')

  paginas.push(total)
  return paginas
})
</script>
