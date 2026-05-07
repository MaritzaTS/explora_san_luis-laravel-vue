<template>
  <div style="font-family: 'Inter', sans-serif; background-color: #f8f9fa; min-height: 100vh; display: flex;">

    <!-- SIDEBAR -->
    <div class="col-auto px-0 bg-white border-end vh-100 position-fixed"
         style="width: 260px; z-index: 1040; display: flex; flex-direction: column;">

      <div class="px-3 pt-4 pb-3 border-bottom">
        <span class="fw-bold fs-6 text-dark">Panel Administrativo</span>
      </div>

      <nav class="px-3 pt-3 flex-grow-1 d-flex flex-column">
        <ul class="nav nav-pills flex-column w-100 mb-0">

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white"
              exact>
              <i class="bi bi-grid-fill"></i> Panel Principal
            </RouterLink>
          </li>

          <li class="w-100 mt-3 mb-1">
            <p class="text-muted fw-bold mb-1 px-2 text-uppercase" style="font-size: 0.68rem;">Módulos</p>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/gastronomia"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-egg-fried"></i> Gastronomía
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/recreacion"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-tree"></i> Recreación
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/alojamientos"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-houses"></i> Alojamientos
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/transportes"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-bus-front"></i> Transporte
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/agencias"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-map"></i> Agencias Turísticas
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/sitios"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-geo-alt"></i> Sitios Turísticos
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/eventos"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-calendar-event"></i> Eventos
            </RouterLink>
          </li>

          <li class="nav-item w-100 mb-1">
            <RouterLink to="/admin/resenas"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-chat-left-heart"></i> Reseñas
            </RouterLink>
          </li>

        </ul>

        <!-- Usuarios al fondo -->
        <ul class="nav nav-pills flex-column w-100 mt-auto border-top pt-2">
          <li class="nav-item w-100">
            <RouterLink to="/admin/usuarios"
              class="nav-link px-3 py-2 rounded-3 text-dark d-flex align-items-center gap-2"
              active-class="active bg-dark text-white">
              <i class="bi bi-person-circle"></i> Usuarios
            </RouterLink>
          </li>
        </ul>
      </nav>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div style="margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh;">

      <!-- NAVBAR SUPERIOR -->
      <header class="navbar navbar-light bg-white border-bottom px-4 py-3 sticky-top" style="z-index: 1030;">
        <div class="container-fluid px-0">
          <h2 class="fw-bold mb-0 fs-5">{{ pageTitle }}</h2>

          <ul class="navbar-nav ms-auto align-items-center flex-row gap-3">
            <li class="nav-item text-end d-none d-md-block">
              <span class="d-block fw-bold small">Administrador</span>
              <span class="text-muted small">{{ userName }}</span>
            </li>
            <li class="nav-item">
              <div class="dropdown">
                <button class="btn p-0 border-0 bg-light rounded-circle d-flex align-items-center justify-content-center"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  style="width: 42px; height: 42px;">
                  <i class="bi bi-person-fill fs-4"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                  <li>
                    <RouterLink class="dropdown-item" to="/admin/perfil">
                      <i class="bi bi-person me-2"></i> Mi Perfil
                    </RouterLink>
                  </li>
                  <li>
                    <RouterLink class="dropdown-item" to="/">
                      <i class="bi bi-house me-2"></i> Ver Sitio Web
                    </RouterLink>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <button class="dropdown-item text-danger" @click="logout">
                      <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </header>

      <!-- VISTAS HIJAS -->
      <main class="p-4">
        <RouterView />
      </main>
    </div>

  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const route     = useRoute()
const router    = useRouter()
const authStore = useAuthStore()

const userName = computed(() => authStore.usuario?.nombre ?? 'Administrador')

const pageTitle = computed(() => {
  const titles = {
    '/admin':               'Panel Principal',
    '/admin/gastronomia':   'Gastronomía',
    '/admin/recreacion':    'Recreación',
    '/admin/alojamientos':  'Alojamientos',
    '/admin/transportes':   'Transporte',
    '/admin/agencias':      'Agencias Turísticas',
    '/admin/sitios':        'Sitios Turísticos',
    '/admin/eventos':       'Eventos',
    '/admin/resenas':       'Reseñas',
    '/admin/usuarios':      'Usuarios',
    '/admin/perfil':        'Mi Perfil',
  }
  return titles[route.path] ?? 'Administración'
})

async function logout() {
  await authStore.logout()
  router.push('/login')
}
</script>