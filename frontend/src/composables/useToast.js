import { ref } from 'vue'

// Estado global reactivo de notificaciones (toasts)
// Se comparte entre todas las instancias que usen este composable
const toasts = ref([])

// Contador incremental para generar IDs únicos de cada toast
let idCounter = 0

export function useToast() {

  /**
   * Agrega una notificación al sistema
   * @param {string} mensaje - Texto del toast
   * @param {string} tipo - Tipo de toast (success, danger, info, warning)
   * @param {number} duracion - Tiempo visible antes de ocultarse
   */
  function addToast(mensaje, tipo = 'success', duracion = 3500) {
    const id = ++idCounter

    // Se agrega el toast al array global
    toasts.value.push({
      id,
      mensaje,
      tipo,
      visible: true
    })

    // Timer para iniciar animación de salida
    setTimeout(() => {
      const t = toasts.value.find(t => t.id === id)

      // Marca como invisible (para animación CSS de fade out)
      if (t) t.visible = false

      // Después de la animación, se elimina del array
      setTimeout(() => {
        toasts.value = toasts.value.filter(t => t.id !== id)
      }, 400)

    }, duracion)
  }

  // Helpers para tipos de toast (API más limpia)
  const exito = (msg) => addToast(msg, 'success')
  const error = (msg) => addToast(msg, 'danger', 5000)
  const info = (msg) => addToast(msg, 'info')
  const warning = (msg) => addToast(msg, 'warning', 4500)

  // Exposición del estado y funciones
  return {
    toasts,
    exito,
    error,
    info,
    warning
  }
}
