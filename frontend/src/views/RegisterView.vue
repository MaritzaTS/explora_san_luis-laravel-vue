<template>
  <div class="register-wrapper">
    <div class="register-card">

      <div class="text-center mb-4">
        <h2 class="fw-bold">Crear cuenta</h2>
        <p class="text-muted small">Únete a la comunidad de Explora San Luis</p>
      </div>

      <!-- Google -->
      <div class="d-grid mb-4">
        <button type="button" @click="registrarConGoogle" :disabled="loadingGoogle"
          class="btn btn-outline-dark py-2 d-flex align-items-center justify-content-center gap-2">
          <span v-if="loadingGoogle" class="spinner-border spinner-border-sm"></span>
          <svg v-else viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          {{ loadingGoogle ? 'Redirigiendo...' : 'Registrarse con Google' }}
        </button>
      </div>

      <div class="divider"><span>o usa tu correo</span></div>

      <!-- Formulario -->
      <form @submit.prevent="handleRegistro" novalidate>

        <!-- Nombre -->
        <div class="form-group">
          <label>Nombre completo</label>
          <input v-model="form.nombre" type="text" placeholder="Ej. Juan Pérez" required />
        </div>

        <!-- Email -->
        <div class="form-group">
          <label>Correo electrónico</label>
          <div class="input-icon">
            <input v-model="form.email" @blur="verificarEmail" type="email"
              placeholder="juan@mail.com" required
              :class="{ 'input-error': emailError, 'input-ok': emailOk }" />
            <span class="icon">
              <i v-if="checkingEmail" class="bi bi-hourglass-split text-muted"></i>
              <i v-else-if="emailOk" class="bi bi-check-lg text-success"></i>
              <i v-else-if="emailError" class="bi bi-x-lg text-danger"></i>
              <i v-else class="bi bi-envelope text-muted"></i>
            </span>
          </div>
          <p v-if="emailError" class="field-error">{{ emailError }}</p>
        </div>

        <!-- Contraseña -->
        <div class="form-group">
          <label>Contraseña</label>
          <div class="input-icon">
            <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
              placeholder="Crea una contraseña segura" required />
            <button type="button" class="icon icon-btn" @click="showPassword = !showPassword">
              <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>

          <!-- Barra de fortaleza -->
          <div class="strength-bar mt-2" v-if="form.password">
            <div class="strength-fill" :style="{ width: strengthPct + '%', background: strengthColor }"></div>
          </div>

          <!-- Requisitos -->
          <ul class="req-list" v-if="form.password">
            <li :class="{ ok: reqs.length }"><i :class="reqs.length ? 'bi bi-check-circle-fill' : 'bi bi-circle'"></i> Mínimo 8 caracteres</li>
            <li :class="{ ok: reqs.upper }"><i :class="reqs.upper ? 'bi bi-check-circle-fill' : 'bi bi-circle'"></i> Una mayúscula</li>
            <li :class="{ ok: reqs.lower }"><i :class="reqs.lower ? 'bi bi-check-circle-fill' : 'bi bi-circle'"></i> Una minúscula</li>
            <li :class="{ ok: reqs.number }"><i :class="reqs.number ? 'bi bi-check-circle-fill' : 'bi bi-circle'"></i> Un número</li>
            <li :class="{ ok: reqs.special }"><i :class="reqs.special ? 'bi bi-check-circle-fill' : 'bi bi-circle'"></i> Un carácter especial (@$!%*?&)</li>
          </ul>
        </div>

        <p v-if="serverError" class="field-error text-center mb-3">{{ serverError }}</p>

        <button type="submit" class="submit-btn" :disabled="loading || !formValido">
          <span v-if="loading" class="spinner"></span>
          {{ loading ? 'Creando cuenta...' : 'Registrarme' }}
        </button>

        <p class="text-center small mt-3">
          ¿Ya tienes una cuenta?
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="text-dark fw-bold">Inicia sesión aquí</a>
        </p>

      </form>

      <div class="footer-legal">
        Al registrarte, confirmas que tienes 18 años o más y aceptas nuestra
        <a href="#">Política de Privacidad</a> y <a href="#">Términos de Uso</a>.
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'
import { AUTH } from '@/api/endpoints'

const router = useRouter()

const form = reactive({ nombre: '', email: '', password: '' })
const showPassword  = ref(false)
const loading       = ref(false)
const loadingGoogle = ref(false)
const checkingEmail = ref(false)
const emailError    = ref('')
const emailOk       = ref(false)
const serverError   = ref('')

// ── Requisitos de contraseña ──────────────────────────────
const reqs = computed(() => ({
  length:  form.password.length >= 8,
  upper:   /[A-Z]/.test(form.password),
  lower:   /[a-z]/.test(form.password),
  number:  /[0-9]/.test(form.password),
  special: /[@$!%*?&]/.test(form.password),
}))

const strengthScore = computed(() => Object.values(reqs.value).filter(Boolean).length)
const strengthPct   = computed(() => (strengthScore.value / 5) * 100)
const strengthColor = computed(() => {
  if (strengthScore.value <= 1) return '#ef4444'
  if (strengthScore.value <= 2) return '#f97316'
  if (strengthScore.value <= 3) return '#eab308'
  if (strengthScore.value <= 4) return '#84cc16'
  return '#22c55e'
})

const formValido = computed(() =>
  form.nombre.trim() &&
  form.email.trim() &&
  emailOk.value &&
  Object.values(reqs.value).every(Boolean)
)

// ── Verificar email ───────────────────────────────────────
async function verificarEmail() {
  const email = form.email.trim()
  if (!email || !/\S+@\S+\.\S+/.test(email)) {
    emailError.value = 'Por favor, ingresa un correo electrónico válido.'
    emailOk.value = false
    return
  }
  checkingEmail.value = true
  emailError.value = ''
  emailOk.value = false
  try {
    await api.post(AUTH.CHECK_EMAIL, { email })
    emailOk.value = true
  } catch (e) {
    if (e.response?.status === 422 || e.response?.status === 409) {
      emailError.value = 'Este correo ya está registrado.'
    } else {
      emailOk.value = true
    }
  } finally {
    checkingEmail.value = false
  }
}

// ── Registro ──────────────────────────────────────────────
async function handleRegistro() {
  serverError.value = ''
  loading.value = true
  try {
    await api.post(AUTH.REGISTER, {
      nombre:   form.nombre,
      email:    form.email,
      password: form.password,
    })
    router.push(`/verificar-cuenta?email=${encodeURIComponent(form.email)}`)
  } catch (e) {
    serverError.value = e.response?.data?.message ?? 'Error al crear la cuenta. Intenta de nuevo.'
  } finally {
    loading.value = false
  }
}

// ── Google ────────────────────────────────────────────────
async function registrarConGoogle() {
  loadingGoogle.value = true
  try {
    const { data } = await api.get(AUTH.GOOGLE_REDIRECT)
    window.location.href = data.data.url
  } catch {
    loadingGoogle.value = false
  }
}
</script>

<style scoped>
.register-wrapper {
  min-height: 100vh;
  background: #f4f6f9;
  padding: 40px 16px;
}
.register-card {
  background: #fff;
  border-radius: 16px;
  padding: 40px;
  width: 100%;
  max-width: 500px;
  margin: 0 auto;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08);
  border: 1px solid #e2e8f0;
}
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0 0 24px;
}
.divider::before, .divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e2e8f0;
}
.divider span { color: #94a3b8; font-size: 13px; white-space: nowrap; }

.form-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-bottom: 20px;
}
.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.form-group input {
  padding: 10px 42px 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  color: #1e293b;
  outline: none;
  width: 100%;
  box-sizing: border-box;
  transition: border-color 0.2s;
}
.form-group input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
.input-error { border-color: #ef4444 !important; }
.input-ok    { border-color: #22c55e !important; }

.input-icon { position: relative; }
.icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 15px;
  pointer-events: none;
}
.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  pointer-events: all;
  padding: 0;
  font-size: 16px;
  color: #64748b;
}

.strength-bar {
  height: 5px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
}
.strength-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease, background 0.3s ease;
}
.req-list {
  list-style: none;
  padding: 0;
  margin: 8px 0 0;
  display: flex;
  flex-direction: column;
  gap: 3px;
}
.req-list li {
  font-size: 0.72rem;
  color: #94a3b8;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: color 0.2s;
}
.req-list li.ok { color: #22c55e; }

.field-error {
  font-size: 12px;
  color: #dc2626;
  margin: 2px 0 0;
}

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
  transition: opacity 0.2s;
}
.submit-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.submit-btn:not(:disabled):hover { opacity: 0.9; }

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(0,0,0,0.2);
  border-top-color: #1a1a1a;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.footer-legal {
  margin-top: 24px;
  padding-top: 16px;
  border-top: 1px solid #e2e8f0;
  font-size: 0.72rem;
  color: #94a3b8;
  text-align: center;
}
.footer-legal a { color: #94a3b8; }
</style>
