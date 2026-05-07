<template>
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true" ref="modalEl">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 p-4 shadow-lg">
        <div class="modal-header border-0 justify-content-end pb-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-0">
          <h2 class="fw-bold text-center mb-4">Iniciar sesión</h2>

          <!-- Google -->
          <div class="d-grid gap-2 mb-4">
            <a :href="googleUrl" class="btn btn-outline-dark py-2 d-flex align-items-center justify-content-center gap-2">
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="18">
              Continuar con Google
            </a>
          </div>

          <div class="d-flex align-items-center gap-2 mb-4">
            <hr class="flex-grow-1 m-0">
            <span class="text-muted small">o</span>
            <hr class="flex-grow-1 m-0">
          </div>

          <!-- Formulario -->
          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label class="small fw-bold">Correo electrónico</label>
              <input v-model="form.email" type="email" class="form-control" placeholder="juan@mail.com" required :disabled="loading">
            </div>

            <div class="mb-4">
              <label class="small fw-bold">Contraseña</label>
              <div class="input-group">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="form-control" required :disabled="loading">
                <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                  <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>

            <p v-if="error" class="text-danger small text-center bg-danger-subtle rounded p-2 mb-3">{{ error }}</p>

            <div class="d-grid">
              <button type="submit" class="btn btn-lg fw-bold border-0 py-2 d-flex align-items-center justify-content-center gap-2"
                      style="background-color: #a3e635;" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                {{ loading ? 'Ingresando...' : 'Iniciar sesión' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Modal } from 'bootstrap'
import { useAuthStore } from '@/stores/auth.store'
import { AUTH } from '@/api/endpoints'

const router    = useRouter()
const authStore = useAuthStore()

const modalEl      = ref(null)
const showPassword = ref(false)
const loading      = ref(false)
const error        = ref(null)
const form         = reactive({ email: '', password: '' })

const googleUrl = `${import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api'}${AUTH.GOOGLE_REDIRECT}`

let bsModal = null
onMounted(() => {
  bsModal = new Modal(modalEl.value)
})

async function handleLogin() {
  loading.value = true
  error.value   = null
  try {
    await authStore.login(form.email, form.password)
    bsModal?.hide()
    form.email    = ''
    form.password = ''
    authStore.isAdmin ? router.push('/admin') : router.push('/home')
  } catch (e) {
    if (e.response?.status === 401) {
      error.value = 'Correo o contraseña incorrectos'
    } else if (e.response?.status === 422) {
      error.value = 'Por favor verifica los datos ingresados'
    } else {
      error.value = 'Error de conexión. Verifica que el servidor esté activo.'
    }
  } finally {
    loading.value = false
  }
}
</script>
