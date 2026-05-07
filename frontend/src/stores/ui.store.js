import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUiStore = defineStore('ui', () => {
  const sidebarAbierto = ref(false)
  const modalActivo    = ref(null)   // nombre del modal abierto o null
  const toasts         = ref([])     // [{ id, tipo, mensaje }]

  function toggleSidebar() { sidebarAbierto.value = !sidebarAbierto.value }
  function abrirModal(nombre) { modalActivo.value = nombre }
  function cerrarModal() { modalActivo.value = null }

  function mostrarToast(mensaje, tipo = 'success') {
    const id = Date.now()
    toasts.value.push({ id, tipo, mensaje })
    setTimeout(() => eliminarToast(id), 4000)
  }

  function eliminarToast(id) {
    toasts.value = toasts.value.filter((t) => t.id !== id)
  }

  return {
    sidebarAbierto, modalActivo, toasts,
    toggleSidebar, abrirModal, cerrarModal, mostrarToast, eliminarToast,
  }
})
