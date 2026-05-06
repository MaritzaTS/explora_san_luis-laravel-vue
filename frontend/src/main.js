import { createPinia } from 'pinia'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'

// Importamos Bootstrap y sus iconos globalmente
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

// ── CONFIGURACIÓN GLOBAL DE AXIOS ──────────────────────────
// Apunta al backend Laravel
axios.defaults.baseURL = 'http://127.0.0.1:8000'

// Cada vez que haya un token guardado, lo agrega automáticamente
// a todas las peticiones sin tener que hacerlo manualmente
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Si el backend responde 401 (token vencido o inválido),
// limpia la sesión y manda al login automáticamente
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/login')
    }
    return Promise.reject(error)
  }
)
// ────────────────────────────────────────────────────────────

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.mount('#app')