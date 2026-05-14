import { ref, computed } from 'vue'

export function usePagination(totalItems, itemsPorPagina = 6) {
  const paginaActual  = ref(1)
  const total         = ref(totalItems ?? 0)

  const totalPaginas = computed(() =>
    Math.max(1, Math.ceil(total.value / itemsPorPagina))
  )

  function irAnterior() {
    if (paginaActual.value > 1) paginaActual.value--
  }

  function irSiguiente() {
    if (paginaActual.value < totalPaginas.value) paginaActual.value++
  }

  function irAPagina(n) {
    if (n >= 1 && n <= totalPaginas.value) paginaActual.value = n
  }

  function setTotal(n) {
    total.value     = n
    paginaActual.value = 1
  }

  return {
    paginaActual,
    totalPaginas,
    irAnterior,
    irSiguiente,
    irAPagina,
    setTotal
  }
}