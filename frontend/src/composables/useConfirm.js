import Swal from 'sweetalert2'

// Configuración base reutilizable para mantener consistencia visual en toda la app
const BASE = {
  confirmButtonColor: '#212529',
  cancelButtonColor:  '#6c757d',
  reverseButtons:     true,

  // Clases personalizadas (Bootstrap / Tailwind híbrido o estilos globales)
  customClass: {
    popup:         'rounded-4 shadow',
    confirmButton: 'rounded-3 px-4 fw-bold',
    cancelButton:  'rounded-3 px-4',
  },
}

export function useConfirm() {

  /**
   * Muestra un modal de confirmación antes de ejecutar acciones destructivas
   * Ej: eliminar, desactivar, cancelar procesos
   */
  async function confirmar({
    titulo = '¿Estás seguro?',
    texto  = '',
    textoBoton = 'Sí, continuar',
    icono  = 'warning',
  } = {}) {

    const result = await Swal.fire({
      ...BASE,

      // Contenido del modal
      title: titulo,
      text: texto,
      icon: icono,

      // Botones de acción
      showCancelButton: true,
      confirmButtonText: textoBoton,
      cancelButtonText: 'Cancelar',
    })

    // Devuelve true si el usuario confirmó la acción
    return result.isConfirmed
  }

  /**
   * Modal de error bloqueante
   * Usado para errores del servidor o validaciones críticas
   */
  function alertaError(mensaje, titulo = '¡Error!') {
    return Swal.fire({
      ...BASE,
      title: titulo,
      text: mensaje,
      icon: 'error',
      confirmButtonText: 'Entendido',
    })
  }

  /**
   * Modal de éxito bloqueante
   * Usado en flujos donde el usuario debe confirmar antes de continuar
   */
  function alertaExito(mensaje, titulo = '¡Listo!') {
    return Swal.fire({
      ...BASE,
      title: titulo,
      text: mensaje,
      icon: 'success',
      confirmButtonText: 'Aceptar',
    })
  }

  // API pública del composable
  return {
    confirmar,
    alertaError,
    alertaExito,
  }
}
