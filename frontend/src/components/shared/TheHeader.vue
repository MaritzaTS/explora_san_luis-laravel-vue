<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-0">
    <div class="container">
      <router-link class="navbar-brand d-flex align-items-center" to="/">
        <img src="@/assets/img/logo(2).svg" alt="Explora San Luis">
      </router-link>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <router-link class="nav-link px-3" to="/">Bienvenido</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link px-3" to="/home">Inicio</router-link>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
              Categorías
            </a>
            <ul class="dropdown-menu shadow border-0">
              <li><router-link class="dropdown-item" to="/gastronomia">Gastronomía</router-link></li>
              <li><router-link class="dropdown-item" to="/recreacion">Recreación</router-link></li>
              <li><router-link class="dropdown-item" to="/alojamientos">Alojamiento</router-link></li>
              <li><router-link class="dropdown-item" to="/transportes">Transporte</router-link></li>
              <li><router-link class="dropdown-item" to="/sitios-turisticos">Sitios turísticos</router-link></li>
              <li><router-link class="dropdown-item" to="/agencias-turisticas">Agencias turísticas</router-link></li>
              <li><router-link class="dropdown-item" to="/eventos">Eventos</router-link></li>
            </ul>
          </li>

          <!-- Usuario autenticado -->
          <li class="nav-item ms-lg-3 dropdown">
            <template v-if="authStore.isAuthenticated">
              <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                 href="#" role="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                  {{ iniciales }}
                </div>
                <span class="d-none d-md-inline text-dark fw-semibold small">
                  Hola, {{ primerNombre }}
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2">
                <li class="px-3 pb-2 border-bottom mb-1">
                  <p class="mb-0 fw-semibold small text-dark">{{ authStore.usuario?.nombre }}</p>
                  <p class="mb-0 text-muted" style="font-size:12px;">{{ authStore.usuario?.email }}</p>
                </li>
                <li v-if="authStore.isAdmin">
                  <router-link class="dropdown-item small" to="/admin">
                    <i class="bi bi-speedometer2 me-2"></i>Panel de administración
                  </router-link>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                  <button @click="cerrarSesion" class="dropdown-item small text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                  </button>
                </li>
              </ul>
            </template>

            <!-- Sin sesión -->
            <template v-else>
              <a class="nav-link px-2" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"
                 title="Iniciar sesión">
                <i class="bi bi-person-circle fs-4"></i>
              </a>
            </template>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router    = useRouter()
const authStore = useAuthStore()

const primerNombre = computed(() => {
  const nombre = authStore.usuario?.nombre ?? ''
  return nombre.split(' ')[0]
})

const iniciales = computed(() => {
  const nombre = authStore.usuario?.nombre ?? ''
  return nombre.split(' ').slice(0, 2).map(p => p[0]).join('').toUpperCase() || '?'
})

async function cerrarSesion() {
  await authStore.logout()
  router.push('/home')
}
</script>

<style scoped>
.navbar { padding: 0 !important; }

.navbar-brand img {
  display: block;
  object-fit: contain;
  height: 90px;
  width: auto;
}

.navbar-nav .nav-link {
  font-size: 1.05rem;
  font-weight: 600;
  color: #1a1a1a !important;
}
.navbar-nav .nav-link:hover { color: #198754 !important; }

.user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #1e293b;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.dropdown-menu { z-index: 2000 !important; }
.nav-item.dropdown:hover .dropdown-menu { display: block; margin-top: 0; }
</style>
