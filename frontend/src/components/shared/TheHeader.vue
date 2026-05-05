<template>
  <!-- navbar-light y bg-white mantienen el estilo limpio de Explora San Luis -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-0">
    <div class="container">
      <!-- router-link reemplaza a <a> para navegación SPA (sin recargar) -->
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
          <!-- Dropdown Categorías -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
              Categorías
            </a>
            <ul class="dropdown-menu shadow border-0">
              <li><router-link class="dropdown-item" to="/gastronomia">Gastronomía</router-link></li>
              <li><router-link class="dropdown-item" to="/recreacion">Recreación</router-link></li>
              <li><router-link class="dropdown-item" to="/alojamientos">Alojamiento</router-link></li>
              <li><router-link class="dropdown-item" to="/transportes">Transporte</router-link></li>
              <li><router-link class="dropdown-item" to="/sitios-turisticos">Sitios turisticos</router-link></li>
              <li><router-link class="dropdown-item" to="/agencias-turisticas">Agencias Turisticas</router-link></li>
              <li><router-link class="dropdown-item" to="/eventos">Eventos</router-link></li>
            </ul>
          </li>

          <!-- Lógica de Usuario con Vue -->
          <li class="nav-item ms-lg-3 dropdown">
            <!-- v-if verifica si hay un usuario autenticado (deberás conectar esto a tu store) -->
            <template v-if="user">
              <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                <span class="d-none d-md-inline text-dark fw-medium small">
                  Hola, {{ user.firstName }}
                </span>
                <i class="bi bi-person-circle fs-4 text-success"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><router-link class="dropdown-item small" to="/perfil">Mi Perfil</router-link></li>
                <li><hr class="dropdown-divider"></li>
                <li><button @click="logout" class="dropdown-item small text-danger">Cerrar Sesión</button></li>
              </ul>
            </template>
            
            <!-- v-else se muestra si no hay sesión iniciada -->
            <template v-else>
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
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
// Aquí importarías tu lógica de autenticación (ej. Pinia o un composable)
import { ref } from 'vue'

// Ejemplo de estado de usuario (esto vendrá de tu base de datos/API más adelante)
const user = ref(null) 

const logout = () => {
  console.log('Cerrando sesión...')
  // Aquí limpiarías los tokens y redireccionarías
}
</script>

<style scoped>
/* Estilos específicos para este componente (no afectan al resto) */
.navbar {
  padding: 0 !important;
}

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

.navbar-nav .nav-link:hover {
  color: #198754 !important;
}


/* Asegura que el menú desplegable siempre esté por encima de todo */
.dropdown-menu {
  z-index: 2000 !important;
}

/* Mejora la visibilidad al pasar el mouse (opcional) */
.nav-item.dropdown:hover .dropdown-menu {
  display: block;
  margin-top: 0; 
}
</style>