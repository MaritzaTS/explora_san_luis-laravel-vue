import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior: () => ({ top: 0 }),
  routes: [

    // ─── RUTAS PÚBLICAS ──────────────────────────────────
    {
      path: '/',
      component: () => import('@/layouts/DefaultLayout.vue'),
      children: [
        { path: '',                    name: 'bienvenida',          component: () => import('@/views/BienvenidaView.vue') },
        { path: 'home',                name: 'home',                component: () => import('@/views/HomeView.vue') },
        { path: 'alojamientos',        name: 'alojamientos',        component: () => import('@/views/AlojamientosView.vue') },
        { path: 'gastronomia',         name: 'gastronomia',         component: () => import('@/views/GastronomiaView.vue') },
        { path: 'recreacion',          name: 'recreacion',          component: () => import('@/views/RecreacionView.vue') },
        { path: 'transportes',         name: 'transportes',         component: () => import('@/views/TransportesView.vue') },
        { path: 'agencias-turisticas', name: 'agencias-turisticas', component: () => import('@/views/AgenciasTuristicasView.vue') },
        { path: 'sitios-turisticos',   name: 'sitios-turisticos',   component: () => import('@/views/SitiosTuristicosView.vue') },
        { path: 'eventos',             name: 'eventos',             component: () => import('@/views/EventosView.vue') },
        { path: 'historia',            name: 'historia',            component: () => import('@/views/HistoriaView.vue') },
      ]
    },

    // ─── AUTENTICACIÓN ───────────────────────────────────
    {
      path: '/registro',
      name: 'registro',
      component: () => import('@/views/RegisterView.vue')
    },
    {
      path: '/verificar-cuenta',
      name: 'verificar-cuenta',
      component: () => import('@/views/VerifyView.vue')
    },
    {
      path: '/auth/google/callback',
      name: 'google-callback',
      component: () => import('@/views/GoogleCallbackView.vue')
    },

    // ─── RUTAS ADMIN ─────────────────────────────────────
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      meta: { requiresAuth: true },
      children: [
        { path: '',             name: 'admin-home',         component: () => import('@/views/admin/AdminHome.vue') },
        { path: 'gastronomia',  name: 'admin-gastronomia',  component: () => import('@/views/admin/GastronomiaAdmin.vue') },
        { path: 'recreacion',   name: 'admin-recreacion',   component: () => import('@/views/admin/RecreacionAdmin.vue') },
        { path: 'alojamientos', name: 'admin-alojamientos', component: () => import('@/views/admin/AlojamientosAdmin.vue') },
        { path: 'transportes',  name: 'admin-transportes',  component: () => import('@/views/admin/TransportesAdmin.vue') },
        { path: 'agencias',     name: 'admin-agencias',     component: () => import('@/views/admin/AgenciasTuristicasAdmin.vue') },
        { path: 'sitios',       name: 'admin-sitios',       component: () => import('@/views/admin/SitiosTuristicosAdmin.vue') },
        { path: 'eventos',      name: 'admin-eventos',      component: () => import('@/views/admin/EventosAdmin.vue') },
        { path: 'resenas',      name: 'admin-resenas',      component: () => import('@/views/admin/ResenasAdmin.vue') },
        { path: 'usuarios',     name: 'admin-usuarios',     component: () => import('@/views/admin/UsuariosAdmin.vue') },
        { path: 'perfil',       name: 'admin-perfil',       component: () => import('@/views/admin/PerfilAdmin.vue') },
      ]
    },

  ]
})

// ─── LIMPIEZA DE MODALES BOOTSTRAP ──────────────────────
// Al navegar, Bootstrap deja el backdrop y la clase modal-open en el body.
// Esto bloquea toda la UI. Lo limpiamos en cada cambio de ruta.
function limpiarModalesBootstrap() {
  document.querySelectorAll('.modal-backdrop').forEach(el => el.remove())
  document.body.classList.remove('modal-open')
  document.body.style.removeProperty('overflow')
  document.body.style.removeProperty('padding-right')
}

// ─── PROTECCIÓN DE RUTAS (Guard global) ─────────────────
router.beforeEach((to, from, next) => {
  limpiarModalesBootstrap()

  const token   = localStorage.getItem('token')
  const user    = JSON.parse(localStorage.getItem('user') ?? 'null')
  const isAdmin = user?.rol?.id === 1

  if (to.meta.requiresAuth && !token) {
    next({ name: 'bienvenida' })
    return
  }

  // Rutas /admin requieren token Y rol de admin
  if (to.path.startsWith('/admin') && token && !isAdmin) {
    next({ name: 'home' })
    return
  }

  next()
})

export default router