<template>
  <div style="font-family: 'Inter', sans-serif; background-color: #f8f9fa; min-height: 100vh; display: flex;">

    <AdminSidebar />

    <div class="admin-content" :style="{ marginLeft: sidebarColapsado ? '72px' : '260px' }">
      <AdminNavbar
        :titulo="pageTitle"
        :user-name="userName"
        @logout="logout"
      />

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
import { useUiStore } from '@/stores/ui.store'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import AdminNavbar from '@/components/admin/AdminNavbar.vue'

const route     = useRoute()
const router    = useRouter()
const authStore = useAuthStore()
const uiStore   = useUiStore()

const sidebarColapsado = computed(() => uiStore.sidebarColapsado)
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
  router.push('/')
}
</script>

<style scoped>
.admin-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
