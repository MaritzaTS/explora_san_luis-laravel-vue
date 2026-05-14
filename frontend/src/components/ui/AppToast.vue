<template>
  <!-- Renderiza el toast fuera del DOM normal del componente -->
  <!-- Esto evita problemas de z-index y stacking context -->
  <Teleport to="body">

    <!-- Contenedor principal de toasts -->
    <div class="toast-wrapper">

      <!-- TransitionGroup permite animaciones en listas dinámicas -->
      <TransitionGroup name="toast">

        <!-- Itera todos los toasts activos del store -->
        <div
          v-for="t in toasts"
          :key="t.id"

          
          class="toast-item"
          :class="[`toast--${t.tipo}`, { 'toast--saliendo': !t.visible }]"
        >

          <!-- Icono dinámico según tipo de toast -->
          <i :class="iconos[t.tipo]" class="toast-icono"></i>

          <!-- Mensaje del toast -->
          <span class="toast-msg">{{ t.mensaje }}</span>
        </div>

      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '@/composables/useToast'

// Accede al estado global de toasts (reactivo compartido)
const { toasts } = useToast()

// Mapeo de iconos según tipo de notificación
const iconos = {
  success: 'bi bi-check-circle-fill',
  danger:  'bi bi-x-circle-fill',
  info:    'bi bi-info-circle-fill',
  warning: 'bi bi-exclamation-triangle-fill',
}
</script>

<style scoped>
/* Contenedor fijo en la esquina superior derecha */
.toast-wrapper {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 380px;
}

/* Estilo base de cada toast */
.toast-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 20px;
  border-radius: 12px;
  color: #fff;
  font-size: 0.875rem;
  font-weight: 500;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  backdrop-filter: blur(8px);
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Icono del toast */
.toast-icono {
  font-size: 1.15rem;
  flex-shrink: 0;
}

/* Texto del toast */
.toast-msg {
  line-height: 1.4;
}

/* Colores por tipo de toast */
.toast--success { background: #16a34a; }
.toast--danger  { background: #dc2626; }
.toast--info    { background: #2563eb; }
.toast--warning { background: #d97706; }

/* Animación de salida manual (cuando visible = false) */
.toast--saliendo {
  opacity: 0;
  transform: translateX(30px);
}

/* Animaciones automáticas de Vue TransitionGroup */

/* Entrada */
.toast-enter-from {
  opacity: 0;
  transform: translateX(60px);
}

.toast-enter-active {
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Salida */
.toast-leave-active {
  transition: all 0.3s ease-in;
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(60px);
}
</style>
