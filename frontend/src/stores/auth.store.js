import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'
import { AUTH } from '@/api/endpoints'

export const useAuthStore = defineStore('auth', () => {
  const token   = ref(localStorage.getItem('token') ?? null)
  const usuario = ref(JSON.parse(localStorage.getItem('user') ?? 'null'))

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin         = computed(() => usuario.value?.rol?.id === 1)

  async function login(email, password) {
    const { data } = await api.post(AUTH.LOGIN, { email, password })
    _persistSession(data.data.token, data.data.usuario)
  }

  async function logout() {
    try { await api.post(AUTH.LOGOUT) } catch { /* ignora errores de red al cerrar */ }
    _clearSession()
  }

  async function fetchMe() {
    const { data } = await api.get(AUTH.ME)
    usuario.value = data.data
    localStorage.setItem('user', JSON.stringify(data.data))
  }

  function _persistSession(newToken, newUsuario) {
    token.value   = newToken
    usuario.value = newUsuario
    localStorage.setItem('token', newToken)
    localStorage.setItem('user',  JSON.stringify(newUsuario))
  }

  function _clearSession() {
    token.value   = null
    usuario.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  return { token, usuario, isAuthenticated, isAdmin, login, logout, fetchMe }
})
