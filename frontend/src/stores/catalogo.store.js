import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'

export const useCatalogoStore = defineStore('catalogo', () => {
  const tipos           = ref([])
  const sitiosTuristicos = ref([])
  const eventos         = ref([])
  const cargando        = ref(false)

  async function fetchTipos() {
    if (tipos.value.length) return
    cargando.value = true
    try {
      const { data } = await api.get(PUBLICO.TIPOS)
      tipos.value = data.data
    } finally {
      cargando.value = false
    }
  }

  async function fetchEntidades(slug, filtros = []) {
    cargando.value = true
    try {
      const params = filtros.length ? { subtipos: filtros } : {}
      const { data } = await api.get(PUBLICO.ENTIDADES(slug), { params })
      return data.data
    } finally {
      cargando.value = false
    }
  }

  async function fetchSitiosTuristicos() {
    if (sitiosTuristicos.value.length) return
    cargando.value = true
    try {
      const { data } = await api.get(PUBLICO.SITIOS)
      sitiosTuristicos.value = data.data
    } finally {
      cargando.value = false
    }
  }

  async function fetchEventos() {
    if (eventos.value.length) return
    cargando.value = true
    try {
      const { data } = await api.get(PUBLICO.EVENTOS)
      eventos.value = data.data
    } finally {
      cargando.value = false
    }
  }

  return {
    tipos, sitiosTuristicos, eventos, cargando,
    fetchTipos, fetchEntidades, fetchSitiosTuristicos, fetchEventos,
  }
})
