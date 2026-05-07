<template>
  <div class="callback-wrapper">
    <div v-if="error" class="callback-box">
      <div class="icon-error">✕</div>
      <h2>Error al iniciar sesión</h2>
      <p>{{ error }}</p>
      <a href="/login" class="back-btn">Volver al inicio de sesión</a>
    </div>
    <div v-else class="callback-box">
      <span class="spinner"></span>
      <p>Iniciando sesión con Google...</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router    = useRouter()
const route     = useRoute()
const authStore = useAuthStore()
const error     = ref(null)

onMounted(async () => {
  const token = route.query.token
  const err   = route.query.error

  if (err) {
    error.value = 'Google no pudo completar la autenticación. Intenta de nuevo.'
    return
  }

  if (!token) {
    error.value = 'No se recibió el token de sesión. Intenta de nuevo.'
    return
  }

  try {
    await authStore.loginWithToken(token)
    // Pequeña pausa para que el store reactive actualice isAdmin
    await new Promise(r => setTimeout(r, 50))
    authStore.isAdmin ? router.replace('/admin') : router.replace('/home')
  } catch (e) {
    const status = e?.response?.status
    if (status === 401) {
      error.value = 'La cuenta de Google no está registrada o no tiene acceso. Contacta al administrador.'
    } else if (status === 403) {
      error.value = 'Tu cuenta está desactivada. Contacta al administrador.'
    } else {
      error.value = `Error al verificar la sesión (${status ?? 'sin conexión'}). Intenta de nuevo.`
    }
  }
})
</script>

<style scoped>
.callback-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f6f9;
}
.callback-box {
  background: #fff;
  border-radius: 16px;
  padding: 48px 40px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08);
  border: 1px solid #e2e8f0;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}
.callback-box p {
  margin: 0;
  font-size: 14px;
  color: #64748b;
}
.callback-box h2 {
  font-size: 18px;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}
.icon-error {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #fef2f2;
  color: #dc2626;
  font-size: 20px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #fecaca;
}
.back-btn {
  margin-top: 8px;
  padding: 10px 24px;
  background: #1e293b;
  color: #fff;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.2s;
}
.back-btn:hover { background: #334155; }
.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #e2e8f0;
  border-top-color: #1e293b;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
