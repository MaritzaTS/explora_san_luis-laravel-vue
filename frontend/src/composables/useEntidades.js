import { ref, watch } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'

export function useEntidades(slug) {
  const entidades    = ref([])
  const subtipos     = ref([])
  const filtros      = ref([])   // array de IDs seleccionados
  const cargando     = ref(false)
  const pagina       = ref(1)
  const totalPaginas = ref(1)

  async function cargarSubtipos() {
    try {
      const { data } = await api.get(PUBLICO.TIPOS)
      const tipo = data.data?.find((t) => t.slug === slug)
      subtipos.value = tipo?.tipos_especificos ?? []
    } catch { /* silencioso */ }
  }

  async function cargarEntidades() {
    cargando.value = true
    try {
      const params = { page: pagina.value }
      if (filtros.value.length) params.subtipos = filtros.value
      const { data } = await api.get(PUBLICO.ENTIDADES(slug), { params })
      const payload = data.data
      if (payload?.entidades) {
        entidades.value    = payload.entidades
        pagina.value       = payload.pagina_actual  ?? pagina.value
        totalPaginas.value = payload.total_paginas  ?? 1
      } else {
        entidades.value = Array.isArray(payload) ? payload : []
      }
    } finally {
      cargando.value = false
    }
  }

  watch(filtros, () => {
    pagina.value = 1
    cargarEntidades()
  }, { deep: true })

  function irAnterior() {
    if (pagina.value > 1) { pagina.value--; cargarEntidades() }
  }
  function irSiguiente() {
    if (pagina.value < totalPaginas.value) { pagina.value++; cargarEntidades() }
  }

  async function init() {
    await cargarSubtipos()
    await cargarEntidades()
  }

  return { entidades, subtipos, filtros, cargando, pagina, totalPaginas, init, irAnterior, irSiguiente }
}
