<template>
  <div class="verify-wrapper">
    <div class="verify-card">

      <div class="check-icon">
        <i class="bi bi-patch-check text-success"></i>
      </div>

      <h3 class="fw-bold">Verifica tu cuenta</h3>
      <p class="text-muted small">
        Hemos enviado un código a:<br>
        <strong class="text-dark">{{ email }}</strong>
      </p>

      <form @submit.prevent="verificar" v-if="!success">
        <input
          v-model="codigo"
          type="text"
          inputmode="numeric"
          maxlength="6"
          placeholder="000000"
          class="code-input"
          required
          autofocus
          @input="codigo = codigo.replace(/\D/g, '').slice(0, 6)"
        />

        <p v-if="error" class="field-error">{{ error }}</p>

        <button type="submit" class="submit-btn" :disabled="loading || codigo.length !== 6">
          <span v-if="loading" class="spinner"></span>
          {{ loading ? 'Verificando...' : 'Verificar código' }}
        </button>
      </form>

      <!-- Éxito -->
      <div v-else class="success-box">
        <i class="bi bi-check-circle-fill text-success fs-1"></i>
        <p class="fw-semibold mt-2 mb-1">¡Cuenta verificada!</p>
        <p class="text-muted small">Redirigiendo al inicio...</p>
      </div>

      <div class="reenviar" v-if="!success">
        <span class="text-muted">¿No recibiste el código?</span>
        <button type="button" @click="reenviar" :disabled="countdown > 0" class="reenviar-btn">
          {{ countdown > 0 ? `Reenviar en ${countdown}s` : 'Reenviar' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/axios'
import { AUTH } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'

const route     = useRoute()
const router    = useRouter()
const authStore = useAuthStore()

const email     = ref(route.query.email ?? '')
const codigo    = ref('')
const loading   = ref(false)
const error     = ref('')
const success   = ref(false)
const countdown = ref(0)

let timer = null

async function verificar() {
  error.value = ''
  loading.value = true
  try {
    const { data } = await api.post(AUTH.VERIFICAR, { email: email.value, codigo: codigo.value })
    await authStore.loginWithToken(data.data.token)
    success.value = true
    setTimeout(() => router.replace('/home'), 1500)
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Código incorrecto. Intenta de nuevo.'
  } finally {
    loading.value = false
  }
}

async function reenviar() {
  try {
    await api.post(AUTH.REGISTER, { email: email.value, reenviar: true })
  } catch { /* silencioso */ }
  countdown.value = 60
  timer = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) clearInterval(timer)
  }, 1000)
}

onMounted(() => { countdown.value = 60; timer = setInterval(() => { countdown.value--; if (countdown.value <= 0) clearInterval(timer) }, 1000) })
onUnmounted(() => clearInterval(timer))
</script>

<style scoped>
.verify-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f6f9;
  padding: 40px 16px;
}
.verify-card {
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
  gap: 8px;
}
.check-icon { font-size: 3rem; line-height: 1; }
.verify-card h3 { margin: 0; }
.verify-card p  { margin: 0; }

.code-input {
  width: 100%;
  margin: 16px 0 8px;
  padding: 14px;
  font-size: 2rem;
  font-weight: 700;
  letter-spacing: 12px;
  text-align: center;
  border: 2px solid #a3e635;
  border-radius: 10px;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s;
}
.code-input:focus { border-color: #84cc16; box-shadow: 0 0 0 3px rgba(163,230,53,0.25); }

.field-error { font-size: 13px; color: #dc2626; margin: 0; }

.submit-btn {
  width: 100%;
  padding: 12px;
  background: #a3e635;
  color: #1a1a1a;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 8px;
  transition: opacity 0.2s;
}
.submit-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.submit-btn:not(:disabled):hover { opacity: 0.9; }

.success-box { display: flex; flex-direction: column; align-items: center; }

.reenviar {
  margin-top: 16px;
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.reenviar-btn {
  background: none;
  border: none;
  font-weight: 700;
  color: #1e293b;
  cursor: pointer;
  font-size: 13px;
  padding: 0;
}
.reenviar-btn:disabled { color: #94a3b8; cursor: default; }

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(0,0,0,0.2);
  border-top-color: #1a1a1a;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
