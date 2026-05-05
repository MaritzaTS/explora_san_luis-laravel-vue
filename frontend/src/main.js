
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Importamos Bootstrap y sus iconos globalmente
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

// Importamos tus estilos personalizados (según tu imagen)
//import '@/assets/styles/main.css'

// Estilos globales
// import '@/assets/css/BienvenidaView.css'
    

const app = createApp(App)

app.use(router)
app.mount('#app')
app.use(createPinia())












