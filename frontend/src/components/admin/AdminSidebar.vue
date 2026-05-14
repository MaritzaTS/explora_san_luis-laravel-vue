<template>
  <aside class="admin-sidebar" :class="{ 'sidebar--colapsado': colapsado }">

    <!-- HEADER -->
    <div class="sidebar-header">
      <span v-if="!colapsado" class="fw-bold text-dark" style="font-size: 0.95rem;">Panel Administrativo</span>
      <button class="btn btn-sm btn-light border-0 rounded-3 p-1 d-flex align-items-center justify-content-center shadow-none"
        style="width: 32px; height: 32px;"
        @click="uiStore.toggleSidebar"
        :title="colapsado ? 'Expandir menú' : 'Minimizar menú'">
        <i :class="colapsado ? 'bi bi-chevron-right' : 'bi bi-chevron-left'" style="font-size: 0.85rem;"></i>
      </button>
    </div>

    <!-- NAVEGACIÓN -->
    <nav class="sidebar-nav">
      <ul class="nav flex-column w-100">

        <!-- Panel Principal -->
        <li class="nav-item">
          <RouterLink to="/admin"
            class="sidebar-link"
            active-class="sidebar-link--activo"
            exact
            :title="colapsado ? 'Panel Principal' : ''">
            <i class="bi bi-grid-fill sidebar-icono"></i>
            <span v-if="!colapsado" class="sidebar-texto">Panel Principal</span>
          </RouterLink>
        </li>

        <!-- Separador Módulos -->
        <li v-if="!colapsado" class="sidebar-separador">Módulos</li>
        <li v-else class="sidebar-separador-mini"></li>

        <!-- Módulos -->
        <li v-for="item in modulos" :key="item.to" class="nav-item">
          <RouterLink :to="item.to"
            class="sidebar-link"
            active-class="sidebar-link--activo"
            :title="colapsado ? item.label : ''">
            <i :class="item.icono" class="sidebar-icono"></i>
            <span v-if="!colapsado" class="sidebar-texto">{{ item.label }}</span>
          </RouterLink>
        </li>

      </ul>
    </nav>

    <!-- CUENTA DE USUARIO (fondo del sidebar) -->
    <div class="sidebar-cuenta" :class="{ 'sidebar-cuenta--colapsado': colapsado }">
      <RouterLink to="/admin/usuarios"
        class="sidebar-link"
        active-class="sidebar-link--activo"
        :title="colapsado ? 'Usuarios' : ''">
        <i class="bi bi-people-fill sidebar-icono"></i>
        <span v-if="!colapsado" class="sidebar-texto">Usuarios</span>
      </RouterLink>

      <div class="sidebar-divider"></div>

      <RouterLink to="/admin/perfil"
        class="sidebar-perfil"
        :title="colapsado ? userName : ''">
        <div class="sidebar-avatar">
          {{ iniciales }}
        </div>
        <div v-if="!colapsado" class="sidebar-perfil-info">
          <span class="sidebar-perfil-nombre">{{ userName }}</span>
          <span class="sidebar-perfil-rol">Administrador</span>
        </div>
      </RouterLink>
    </div>

  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { useUiStore } from '@/stores/ui.store'
import { useAuthStore } from '@/stores/auth.store'

const uiStore   = useUiStore()
const authStore = useAuthStore()

const colapsado = computed(() => uiStore.sidebarColapsado)
const userName  = computed(() => authStore.usuario?.nombre ?? 'Administrador')
const iniciales = computed(() => {
  const nombre = userName.value.trim()
  const partes = nombre.split(' ')
  if (partes.length >= 2) return (partes[0][0] + partes[1][0]).toUpperCase()
  return nombre.substring(0, 2).toUpperCase()
})

const modulos = [
  { to: '/admin/gastronomia',  icono: 'bi bi-egg-fried',       label: 'Gastronomía' },
  { to: '/admin/recreacion',   icono: 'bi bi-tree',            label: 'Recreación' },
  { to: '/admin/alojamientos', icono: 'bi bi-houses',          label: 'Alojamientos' },
  { to: '/admin/transportes',  icono: 'bi bi-bus-front',       label: 'Transporte' },
  { to: '/admin/agencias',     icono: 'bi bi-map',             label: 'Agencias Turísticas' },
  { to: '/admin/sitios',       icono: 'bi bi-geo-alt',         label: 'Sitios Turísticos' },
  { to: '/admin/eventos',      icono: 'bi bi-calendar-event',  label: 'Eventos' },
  { to: '/admin/resenas',      icono: 'bi bi-chat-left-heart', label: 'Reseñas' },
]
</script>

<style scoped>
.admin-sidebar {
  width: 260px;
  min-height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  background: #fff;
  border-right: 1px solid #e9ecef;
  display: flex;
  flex-direction: column;
  transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.sidebar--colapsado {
  width: 72px;
}

/* Header */
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 16px 12px;
  border-bottom: 1px solid #f0f0f0;
  min-height: 60px;
}

.sidebar--colapsado .sidebar-header {
  justify-content: center;
  padding: 16px 8px 12px;
}

/* Nav */
.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  padding: 12px 10px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 14px;
  border-radius: 10px;
  color: #495057;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.15s ease;
  white-space: nowrap;
}

.sidebar-link:hover {
  background: #f3f4f6;
  color: #212529;
}

.sidebar-link--activo {
  background: #212529 !important;
  color: #fff !important;
}

.sidebar--colapsado .sidebar-link {
  justify-content: center;
  padding: 10px;
}

.sidebar-icono {
  font-size: 1.1rem;
  flex-shrink: 0;
  width: 20px;
  text-align: center;
}

.sidebar-texto {
  transition: opacity 0.2s ease;
}

/* Separadores */
.sidebar-separador {
  font-size: 0.68rem;
  font-weight: 700;
  text-transform: uppercase;
  color: #adb5bd;
  padding: 16px 14px 4px;
  letter-spacing: 0.5px;
}

.sidebar-separador-mini {
  height: 1px;
  background: #f0f0f0;
  margin: 10px 12px;
}

/* Cuenta de usuario (fondo) */
.sidebar-cuenta {
  border-top: 1px solid #f0f0f0;
  padding: 8px 10px 12px;
}

.sidebar-divider {
  height: 1px;
  background: #f0f0f0;
  margin: 6px 4px;
}

.sidebar-perfil {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 10px;
  border-radius: 10px;
  text-decoration: none;
  transition: background 0.15s ease;
}

.sidebar-perfil:hover {
  background: #f3f4f6;
}

.sidebar-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #212529;
  color: #fff;
  font-size: 0.75rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  letter-spacing: 0.5px;
}

.sidebar-perfil-info {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.sidebar-perfil-nombre {
  font-size: 0.82rem;
  font-weight: 600;
  color: #212529;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-perfil-rol {
  font-size: 0.7rem;
  color: #adb5bd;
}

.sidebar-cuenta--colapsado .sidebar-perfil {
  justify-content: center;
  padding: 8px 4px;
}
</style>
