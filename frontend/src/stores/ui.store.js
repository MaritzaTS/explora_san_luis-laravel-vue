import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUiStore = defineStore('ui', () => {
  const sidebarColapsado = ref(localStorage.getItem('sidebar-colapsado') === 'true')
  const modalActivo    = ref(null)
  const toasts         = ref([])

  function toggleSidebar() {
    sidebarColapsado.value = !sidebarColapsado.value
    localStorage.setItem('sidebar-colapsado', sidebarColapsado.value)
  }

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
    sidebarColapsado, modalActivo, toasts,
    toggleSidebar, abrirModal, cerrarModal, mostrarToast, eliminarToast,
  }
})
