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

        <button type="submit" class="login-btn" :disabled="loading">
          <span v-if="loading" class="spinner"></span>
          {{ loading ? 'Ingresando...' : 'Iniciar sesión' }}
        </button>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router    = useRouter()
const authStore = useAuthStore()

const showPassword = ref(false)
const loading      = ref(false)
const error        = ref(null)

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
</style>