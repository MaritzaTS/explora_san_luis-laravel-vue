<template>
  <div class="login-wrapper">
    <div class="login-card">

      <div class="login-header">
        <h1>Explora San Luis</h1>
        <p>Ingresa tus credenciales para continuar</p>
      </div>

      <form @submit.prevent="handleLogin">

        <div class="form-group">
          <label>Correo electrónico</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="correo@ejemplo.com"
            required
            autofocus
          />
        </div>

        <div class="form-group">
          <label>Contraseña</label>
          <div class="input-password">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
            />
            <button type="button" class="toggle-pass" @click="showPassword = !showPassword">
              {{ showPassword ? '' : '' }}
            </button>
          </div>
        </div>

        <p v-if="error" class="error-msg"> {{ error }}</p>

        <button type="submit" class="login-btn" :disabled="loading || loadingGoogle">
          <span v-if="loading" class="spinner"></span>
          {{ loading ? 'Ingresando...' : 'Iniciar sesión' }}
        </button>

        <div class="divider"><span>o</span></div>

        <button type="button" class="google-btn" :disabled="loading || loadingGoogle" @click="loginConGoogle">
          <span v-if="loadingGoogle" class="spinner spinner-dark"></span>
          <svg v-else viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          {{ loadingGoogle ? 'Redirigiendo...' : 'Continuar con Google' }}
        </button>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'
import api from '@/api/axios'
import { AUTH } from '@/api/endpoints'

const router    = useRouter()
const authStore = useAuthStore()

const showPassword  = ref(false)
const loading       = ref(false)
const loadingGoogle = ref(false)
const error         = ref(null)

const form = ref({ email: '', password: '' })

async function handleLogin() {
  loading.value = true
  error.value   = null
  try {
    await authStore.login(form.value.email, form.value.password)
    authStore.isAdmin ? router.push('/admin') : router.push('/home')
  } catch (e) {
    if (e.response?.status === 401) {
      error.value = 'Correo o contraseña incorrectos'
    } else if (e.response?.status === 422) {
      error.value = 'Por favor verifica los datos ingresados'
    } else {
      error.value = 'Error de conexión. Intenta de nuevo.'
    }
  } finally {
    loading.value = false
  }
}

async function loginConGoogle() {
  loadingGoogle.value = true
  error.value = null
  try {
    const { data } = await api.get(AUTH.GOOGLE_REDIRECT)
    window.location.href = data.data.url
  } catch {
    error.value = 'No se pudo conectar con Google. Intenta de nuevo.'
    loadingGoogle.value = false
  }
}
</script>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f6f9;
}
.login-card {
  background: #fff;
  border-radius: 16px;
  padding: 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08);
  border: 1px solid #e2e8f0;
}
.login-header {
  text-align: center;
  margin-bottom: 32px;
}
.login-header h1 {
  font-size: 22px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 6px;
}
.login-header p {
  font-size: 14px;
  color: #64748b;
  margin: 0;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 20px;
}
.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.form-group input {
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  color: #1e293b;
  outline: none;
  width: 100%;
  box-sizing: border-box;
  transition: border-color 0.2s;
}
.form-group input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.input-password {
  position: relative;
  display: flex;
  align-items: center;
}
.input-password input { padding-right: 44px; }
.toggle-pass {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  padding: 0;
}
.error-msg {
  color: #dc2626;
  font-size: 13px;
  margin: 0 0 16px;
  text-align: center;
  background: #fef2f2;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #fecaca;
}
.login-btn {
  width: 100%;
  padding: 12px;
  background: #1e293b;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background 0.2s;
}
.login-btn:hover:not(:disabled) { background: #334155; }
.login-btn:disabled { background: #94a3b8; cursor: not-allowed; }
.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #cbd5e1;
  font-size: 13px;
  margin: 4px 0;
}
.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e2e8f0;
}
.divider span { color: #94a3b8; }
.google-btn {
  width: 100%;
  padding: 11px;
  background: #fff;
  color: #1e293b;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background 0.2s, border-color 0.2s;
}
.google-btn:hover:not(:disabled) { background: #f8fafc; border-color: #94a3b8; }
.google-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.spinner-dark {
  border-color: rgba(0,0,0,0.15);
  border-top-color: #1e293b;
}
</style>