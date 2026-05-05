import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [

    // Rutas públicas — usan DefaultLayout
    {
      path: '/',
      component: () => import('@/layouts/DefaultLayout.vue'),
      children: [
        { path: '', name: 'bienvenida', component: () => import('@/views/BienvenidaView.vue') },
        { path: 'home', name: 'home',   component: () => import('@/views/HomeView.vue') },
        { path: 'alojamientos', name: 'alojamientos', component: () => import('@/views/AlojamientosView.vue') },
        { path: 'gastronomia', name: 'gastronomia', component: () => import('@/views/GastronomiaView.vue') },
        { path: 'recreacion',  name: 'recreacion', component: () => import('@/views/RecreacionView.vue') },
        { path: 'transportes',  name: 'transportes', component: () => import('@/views/TransportesView.vue') },
        { path: 'agencias-turisticas', name: 'agencias-turisticas', component: () => import('@/views/AgenciasTuristicasView.vue') },
        { path: 'sitios-turisticos', name: 'sitios-turisticos', component: () => import('@/views/SitiosTuristicosView.vue') },
        { path: 'eventos', name: 'eventos', component: () => import('@/views/EventosView.vue') },
        { path: 'historia',  name: 'historia', component: () => import('@/views/HistoriaView.vue') },
      ]
    },

    // Rutas de autenticación — usan AuthLayout
  

    // Rutas admin — usan AdminLayout
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      meta: { requiresAuth: true },
      children: [
        { path: '',             component: () => import('@/views/admin/AdminHome.vue') },
        { path: 'alojamientos', component: () => import('@/views/admin/AlojamientosAdmin.vue') },
        { path: 'gastronomia',  component: () => import('@/views/admin/GastronomiaAdmin.vue') },
        { path: 'eventos',      component: () => import('@/views/admin/EventosAdmin.vue') },
        { path: 'sitios',       component: () => import('@/views/admin/SitiosAdmin.vue') },
        { path: 'usuarios',     component: () => import('@/views/admin/UsuariosAdmin.vue') },
      ]
    },

  ]
})

export default router