<template>
  <header class="navbar navbar-light bg-white border-bottom px-4 py-3 sticky-top" style="z-index: 1030;">
    <div class="container-fluid px-0">
      <h2 class="fw-bold mb-0 fs-5">{{ titulo }}</h2>

      <ul class="navbar-nav ms-auto align-items-center flex-row gap-3">
        <li class="nav-item text-end d-none d-md-block">
          <span class="d-block fw-bold small">Administrador</span>
          <span class="text-muted small">{{ userName }}</span>
        </li>
        <li class="nav-item position-relative">
          <button class="btn p-0 border-0 bg-light rounded-circle d-flex align-items-center justify-content-center"
            type="button"
            @click="menuAbierto = !menuAbierto"
            style="width: 42px; height: 42px;">
            <i class="bi bi-person-fill fs-4"></i>
          </button>

          <div v-if="menuAbierto" class="position-fixed top-0 start-0 w-100 h-100"
            style="z-index: 1040;" @click="menuAbierto = false"></div>

          <ul v-if="menuAbierto"
            class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 show position-absolute"
            style="z-index: 1050; right: 0; top: 100%;">
            <li>
              <RouterLink class="dropdown-item" to="/admin/perfil" @click="menuAbierto = false">
                <i class="bi bi-person me-2"></i> Mi Perfil
              </RouterLink>
            </li>
            <li>
              <RouterLink class="dropdown-item" to="/" @click="menuAbierto = false">
                <i class="bi bi-house me-2"></i> Ver Sitio Web
              </RouterLink>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <button class="dropdown-item text-danger" @click="$emit('logout')">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  titulo:   { type: String, default: 'Administración' },
  userName: { type: String, default: 'Administrador' },
})

defineEmits(['logout'])

const menuAbierto = ref(false)
</script>
