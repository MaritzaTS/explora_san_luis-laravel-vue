<template>
  <!-- ==========================================
       SECCIÓN 1: HERO CON MARCADORES
  =========================================== -->
  <section class="hero">
    <!-- Imagen de fondo -->
    <img :src="imgHero" alt="San Luis" class="hero-img">

    <!-- Overlay oscuro -->
    <div class="hero-overlay"></div>

    <!-- Contenido central -->
    <div class="hero-content text-center text-white">
      <h1 class="hero-title anim-hero-title">
        Explora la belleza única de San Luis
      </h1>
      <p class="hero-subtitle anim-hero-subtitle">
        Ríos que conectan, montañas que inspiran.
      </p>
      <router-link to="/home" class="btn-aventura anim-hero-btn">
        Comienza tu Aventura <i class="bi bi-arrow-right ms-2"></i>
      </router-link>
    </div>

    <!-- Marcador 1 — Cascadas -->
    <div class="marker cascadas anim-marker" style="animation-delay:1.4s">
      <span class="dot pulse"></span>
      <span class="line"></span>
      <span class="label">Cascadas la Planta</span>
    </div>

    <!-- Marcador 2 — Río Samaná -->
    <div class="marker rio anim-marker" style="animation-delay:1.7s">
      <span class="dot pulse"></span>
      <span class="line"></span>
      <span class="label">Río Samaná</span>
    </div>

    <!-- Marcador 3 — El Prodigio -->
    <div class="marker prodigio anim-marker" style="animation-delay:2s">
      <span class="dot pulse"></span>
      <span class="line"></span>
      <span class="label">Las Confusas</span>
    </div>
  </section>

  <!-- ==========================================
       SECCIÓN 2: CARDS DE LUGARES DESTACADOS
  =========================================== -->
  <section class="lugares-section">
    <div class="container">
      <div class="row g-4 justify-content-center align-items-stretch">
        <div
          class="col-md-4"
          v-for="(lugar, idx) in lugaresDestacados"
          :key="lugar.nombre"
          v-reveal="idx * 120">
          <div class="lugar-card">
            <div class="card-body">
              <h5>{{ lugar.nombre }}</h5>
              <p>{{ lugar.descripcion }}</p>
            </div>
            <img :src="lugar.imagen" :alt="lugar.nombre">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       SECCIÓN 3: ACTIVIDADES DE AVENTURA
  =========================================== -->
  <section class="actividades-section" v-reveal>
    <div class="container">
      <h2>Actividades de aventura</h2>
      <p>Vive experiencias únicas en contacto directo con la naturaleza.</p>
      <hr>
    </div>
  </section>

  <!-- ==========================================
       SECCIÓN 4: BANNER INFO
  =========================================== -->
  <!-- ==========================================
     SECCIÓN 4: BANNER INFO
=========================================== -->
<section class="container mb-5" v-reveal>
  <div class="info-banner-brand p-4 p-md-5">
    <div class="row g-4 text-center align-items-center">

      <div class="col-md-4" v-reveal="0">
        <i class="bi bi-cloud-sun brand-icon mb-3"></i>
        <h4 class="brand-subtitle mb-3">Clima</h4>
        <p class="mb-1 small" v-for="item in clima" :key="item">{{ item }}</p>
        <button class="btn-banner-action mt-3" @click="abrirClima">
          <i class="bi bi-thermometer-sun me-2"></i>Ver clima actual
        </button>
      </div>

      <div class="col-md-4" v-reveal="150">
        <i class="bi bi-lightbulb brand-icon mb-3"></i>
        <h4 class="brand-subtitle">Consejos</h4>
        <p class="mb-1 small" v-for="consejo in consejos" :key="consejo">{{ consejo }}</p>
      </div>

      <div class="col-md-4" v-reveal="300">
        <i class="bi bi-geo-alt brand-icon mb-3"></i>
        <h4 class="brand-subtitle">Ubicación</h4>
        <p class="mb-1 small" v-for="dato in ubicacion" :key="dato">{{ dato }}</p>
        <button class="btn-banner-action mt-3" @click="abrirMapa">
          <i class="bi bi-map me-2"></i>Ver en el mapa
        </button>
      </div>

    </div>
  </div>
</section>

  <!-- ==========================================
       SECCIÓN 5: RESEÑAS DE VISITANTES
  =========================================== -->
  <section class="resenas-bg py-5 mb-0" v-reveal>
    <div class="container">
      <div class="text-center mb-5">
         <h2 class="resenas-titulo">Lo que dicen nuestros visitantes</h2>
         <p class="resenas-subtitulo">Experiencias auténticas de quienes han vivido la magia de San Luis</p>
      </div>

      <div v-if="cargandoResenas" class="text-center py-4">
        <div class="spinner-border text-success" role="status"></div>
      </div>

      <div v-else-if="resenas.length === 0" class="text-center text-muted py-4">
        <i class="bi bi-chat-left-heart fs-1 d-block mb-2 opacity-25"></i>
        <p>Aún no hay reseñas. ¡Sé el primero!</p>
      </div>

      <div v-else class="resenas-scroll mb-5">
        <div
          v-for="(resena, idx) in resenas"
           :key="resena.id"
            class="resena-card"
             v-reveal="idx * 80">

             <p class="resena-texto">"{{ resena.comentario }}"</p>

        <div class="resena-autor">
          <div v-if="resena.usuario?.foto" class="resena-foto">
          <img :src="resena.usuario.foto" :alt="resena.usuario.nombre" />
          </div>
          <div v-else class="resena-avatar">
         {{ iniciales(resena.usuario?.nombre) }}
         </div>
         <div>
           <div class="fw-bold small resena-nombre">{{ resena.usuario?.nombre ?? 'Anónimo' }}</div>
           <div class="resena-fecha">{{ formatFecha(resena.created_at) }}</div>
          </div>
        </div>

  </div>
</div>

      <!-- FORMULARIO DE RESEÑA -->
      <div class="resena-form-wrapper mx-auto">

        <!-- Usuario autenticado: mostrar formulario -->
        <template v-if="authStore.isAuthenticated">
          <div v-if="resenaEnviada" class="text-center py-4">
            <div class="resena-success-icon mb-3">
              <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
            </div>
            <h5 class="fw-bold mb-1">¡Gracias por tu reseña!</h5>
            <p class="text-muted small">Tu comentario será visible una vez que el administrador lo apruebe.</p>
            <button class="btn btn-outline-success btn-sm mt-2" @click="resenaEnviada = false">
              Escribir otra reseña
            </button>
          </div>

          <form v-else @submit.prevent="enviarResena">
            <h5 class="fw-bold mb-1 text-center">¿Ya visitaste San Luis?</h5>
            <p class="text-muted small text-center mb-3">Cuéntanos tu experiencia</p>

            <div class="mb-3">
              <textarea
                v-model="nuevoComentario"
                class="form-control resena-textarea"
                :class="{ 'is-invalid': errorResena }"
                placeholder="Escribe aquí tu experiencia visitando San Luis..."
                rows="4"
                maxlength="1000"
                @input="errorResena = ''"
              ></textarea>
              <div class="d-flex justify-content-between mt-1">
                <div class="invalid-feedback d-block" v-if="errorResena">{{ errorResena }}</div>
                <div v-else></div>
                <small class="text-muted">{{ nuevoComentario.length }}/1000</small>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button
                type="submit"
                class="btn btn-success px-4 fw-bold"
                :disabled="enviandoResena">
                <span v-if="enviandoResena" class="spinner-border spinner-border-sm me-2"></span>
                <i v-else class="bi bi-send me-2"></i>
                {{ enviandoResena ? 'Enviando...' : 'Enviar reseña' }}
              </button>
            </div>
          </form>
        </template>

        <!-- Usuario no autenticado: invitación a iniciar sesión -->
        <template v-else>
          <div class="text-center py-3">
            <i class="bi bi-chat-left-heart fs-1 text-success opacity-50 d-block mb-3"></i>
            <h5 class="fw-bold mb-1">¿Ya visitaste San Luis?</h5>
            <p class="text-muted small mb-3">Inicia sesión para dejar tu reseña y ayudar a otros viajeros.</p>
            <button
              class="btn btn-success px-4 fw-bold"
              data-bs-toggle="modal"
              data-bs-target="#loginModal">
              <i class="bi bi-person-circle me-2"></i>Iniciar sesión para comentar
            </button>
          </div>
        </template>

      </div>
    </div>

    <!-- MODAL CLIMA -->
<teleport to="body">
  <div v-if="mostrarClima" class="modal-overlay" @click.self="mostrarClima = false">
    <div class="modal-custom">

      <!-- Header -->
      <div class="modal-custom-header">
        <div>
          <span class="modal-custom-tag">San Luis, Antioquia</span>
          <h5 class="modal-custom-title">Clima en tiempo real</h5>
        </div>
        <button class="btn-cerrar-modal" @click="mostrarClima = false">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Cargando -->
      <div v-if="cargandoClima" class="modal-custom-body text-center py-5">
        <div class="spinner-border text-success"></div>
        <p class="mt-3 text-muted small">Obteniendo datos del clima...</p>
      </div>

      <!-- Error -->
      <div v-else-if="errorClima" class="modal-custom-body text-center py-4">
        <i class="bi bi-wifi-off fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted small">{{ errorClima }}</p>
      </div>

      <!-- Datos -->
      <div v-else-if="climaData" class="modal-custom-body">

        <!-- Temperatura principal -->
        <div class="clima-main">
          <i :class="['bi', climaData.icono, 'clima-main-icon']"></i>
          <div>
            <span class="clima-temp">{{ climaData.temperatura }}°C</span>
            <span class="clima-condicion">{{ climaData.condicion }}</span>
          </div>
        </div>

        <!-- Stats -->
        <div class="clima-stats">
          <div class="clima-stat">
            <i class="bi bi-droplet-fill text-primary"></i>
            <span class="clima-stat-val">{{ climaData.humedad }}%</span>
            <span class="clima-stat-label">Humedad</span>
          </div>
          <div class="clima-stat">
            <i class="bi bi-wind text-info"></i>
            <span class="clima-stat-val">{{ climaData.viento }} km/h</span>
            <span class="clima-stat-label">Viento</span>
          </div>
          <div class="clima-stat">
            <i class="bi bi-geo-alt-fill text-success"></i>
            <span class="clima-stat-val">624 m</span>
            <span class="clima-stat-label">Altitud</span>
          </div>
        </div>

        <!-- Próximas horas -->
        <div class="clima-horas">
          <p class="clima-horas-title">Próximas horas</p>
          <div class="clima-horas-scroll">
            <div v-for="h in climaData.horas" :key="h.hora" class="clima-hora-item">
              <span class="hora-tiempo">{{ h.hora }}</span>
              <i class="bi bi-thermometer-half text-danger"></i>
              <span class="hora-temp">{{ h.temp }}°</span>
            </div>
          </div>
        </div>

        <p class="clima-fuente">Fuente: Open-Meteo · Actualizado ahora</p>
      </div>

    </div>
  </div>
</teleport>
<!-- MODAL UBICACIÓN -->
<teleport to="body">
  <div v-if="mostrarMapa" class="modal-overlay" @click.self="mostrarMapa = false">
    <div class="modal-custom modal-custom--mapa">

      <!-- Header -->
      <div class="modal-custom-header">
        <div>
          <span class="modal-custom-tag">Oriente Antioqueño</span>
          <h5 class="modal-custom-title">Ubicación de San Luis</h5>
        </div>
        <button class="btn-cerrar-modal" @click="mostrarMapa = false">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Mapa -->
      <div class="modal-custom-body p-0">
        <iframe
          class="mapa-iframe"
          loading="lazy"
          allowfullscreen
          referrerpolicy="no-referrer-when-downgrade"
          src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d253818.1751848577!2d-75.34065649999999!3d6.115291750000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8e4428dfc80fad05%3A0x421374a24f009006!2sMedell%C3%ADn%2C%20Antioquia!3m2!1d6.244203!2d-75.581211!4m5!1s0x8e4693a0b5a3e14d%3A0x9c3d9a5b6d5e0a0!2sSan%20Luis%2C%20Antioquia!3m2!1d6.0425!2d-74.992222!5e0!3m2!1ses!2sco!4v1700000000000!5m2!1ses!2sco"
        ></iframe>

        <!-- Info debajo del mapa -->
        <div class="mapa-info">
          <div class="mapa-dato">
            <i class="bi bi-signpost-2 text-success"></i>
            <span>A 124 km de Medellín por la Autopista Medellín–Bogotá</span>
          </div>
          <div class="mapa-dato">
            <i class="bi bi-clock text-success"></i>
            <span>Aproximadamente 2h 30min en carro</span>
          </div>
          
            href="https://maps.google.com/?q=San+Luis,+Antioquia,+Colombia"
            target="_blank"
            class="btn btn-success btn-sm rounded-pill px-4 mt-2"
          >
            <i class="bi bi-box-arrow-up-right me-2"></i>Abrir en Google Maps
          
        </div>
      </div>

    </div>
  </div>
</teleport>


  </section>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'
import { useClima } from '@/composables/useClima'

import imgHero      from '@/assets/img/principal/hero.webp'
import imgPlanta    from '@/assets/img/principal/planta.webp'
import imgCastellon from '@/assets/img/principal/cerro_castellon.webp'
import imgSamana    from '@/assets/img/principal/rio_samana.webp'

const authStore       = useAuthStore()
const resenas         = ref([])
const cargandoResenas = ref(true)
const nuevoComentario = ref('')
const enviandoResena  = ref(false)
const resenaEnviada   = ref(false)
const errorResena     = ref('')

// Modales
const mostrarClima = ref(false)
const mostrarMapa  = ref(false)

// Clima
const { clima: climaData, cargando: cargandoClima, error: errorClima, cargarClima } = useClima()

async function abrirClima() {
  mostrarClima.value = true
  await cargarClima()
}

function abrirMapa() {
  mostrarMapa.value = true
}

async function enviarResena() {
  const texto = nuevoComentario.value.trim()
  if (texto.length < 10) {
    errorResena.value = 'El comentario debe tener al menos 10 caracteres.'
    return
  }
  enviandoResena.value = true
  errorResena.value    = ''
  try {
    await api.post(PUBLICO.RESENAS, { comentario: texto })
    nuevoComentario.value = ''
    resenaEnviada.value   = true
  } catch (e) {
    const msg = e.response?.data?.message
    errorResena.value = msg ?? 'No se pudo enviar la reseña. Intenta de nuevo.'
  } finally {
    enviandoResena.value = false
  }
}

function iniciales(nombre) {
  return (nombre ?? '?').split(' ').slice(0, 2).map(p => p[0]).join('').toUpperCase()
}

function formatFecha(fecha) {
  if (!fecha) return ''
  const [y, m, d] = fecha.split('-')
  const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic']
  return `${parseInt(d)} ${meses[parseInt(m) - 1]} ${y}`
}

async function cargarResenas() {
  try {
    const { data } = await api.get(PUBLICO.RESENAS)
    resenas.value = data.data ?? []
  } catch {
    resenas.value = []
  } finally {
    cargandoResenas.value = false
  }
}

onMounted(cargarResenas)

const lugaresDestacados = [
  { nombre: 'Cascada la planta',  descripcion: 'Considerado el charco natural más grande del Oriente antioqueño.', imagen: imgPlanta },
  { nombre: 'Cerro el Castellón', descripcion: 'Un lugar lleno de historia y paisajes que enamoran.',               imagen: imgCastellon },
  { nombre: 'Rio Samaná',         descripcion: 'El único río libre de Antioquia.',                                  imagen: imgSamana }
]

const clima    = ['Temperatura entre 23°C y 36°C', 'Rara vez baja de 21°C', 'o sube más de 38°C']
const consejos = ['Ropa cómoda y repelente', 'Calzado antideslizante', 'Hidratación constante']
const ubicacion = ['A 124 Km de Medellín', 'Autopista Medellín - Bogotá', 'Corazón del Oriente']
</script>


<style scoped>
/* ────────────────────────────────────────────
   VARIABLES DE MARCA
──────────────────────────────────────────── */
:root {
  --verde-bosque:  #4F7352;
  --verde-lima:    #A8CF45;
  --verde-claro:   #EAF3DE;
  --azul-link:     #2469A6;
  --negro:         #000000;
  --blanco:        #ffffff;
  --sombra-card:   0 4px 24px rgba(0, 0, 0, 0.10);
  --font-display:  'Playfair Display', serif;
  --font-body:     'DM Sans', sans-serif;
}

/* ── 1. HERO CON MARCADORES ── */
.hero {
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

.hero-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45));
  z-index: 1;
}

.hero-content {
  position: absolute;
  inset: 0;
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0 1rem;
}

.hero-title {
  font-family: var(--font-display);
  font-weight: 900;
  font-size: clamp(2rem, 5vw, 3.5rem);
  letter-spacing: 3px;
  text-shadow: 0 2px 16px rgba(0, 0, 0, 0.3);
  margin-bottom: 1rem;
  color: #ffffff;
}

.hero-subtitle {
  font-size: clamp(1rem, 2.5vw, 1.5rem);
  letter-spacing: 3px;
  opacity: 0.92;
  margin-bottom: 2.5rem;
  color: #ffffff;
}

.btn-aventura {
  font-family: var(--font-body); /* */
  font-weight: 500; /* */
  padding: 1rem 2.8rem; /* */
  border-radius: 999px; /* */
  background: #4F7352; /* El color verde bosque actual */
  color: #ffffff; /* */
  border: 2px solid #ffffff; /* Mantiene el borde blanco */
  transition: all 0.3s ease; /* Suaviza el cambio */
  text-decoration: none; /* */
  display: inline-flex; /* */
  align-items: center; /* */
  gap: 10px; /* */
}

.btn-aventura:hover {
  /* Al pasar el mouse, el fondo se vuelve transparente */
  background-color: transparent !important; 
  
  /* El texto permanece blanco para que no se pierda contra el fondo */
  color: #ffffff !important; 
  
  /* El borde se mantiene o se puede resaltar un poco */
  border-color: #ffffff; 
  
  /* Un ligero efecto de elevación opcional */
  transform: translateY(-2px); /* */
}

/* ── MARCADORES ── */
.marker {
  position: absolute;
  color: white;
  font-family: var(--font-body);
  z-index: 3;
  opacity: 0.85;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.marker:hover {
  opacity: 0.95;
  transform: scale(1.05);
}

.dot {
  width: 8px;
  height: 8px;
  background:rgba(255, 255, 255, 0.6);
  border-radius: 50%;
  display: block;
  box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
}

.line {
  width: 1px;
  height: 30px;
  background: white;
  display: block;
}

.label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 2px;
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
  white-space: nowrap;
}

/* Posiciones de cada marcador */
.cascadas {
  top: 25%;
  left: 15%;
}

.rio {
  top: 20%;
  left: 52%;
  transform: translateX(-20%);
  text-align: center;
}

.prodigio {
  top: 20%;
  right: 20%;
  
}

/* ── 2. CARDS LUGARES ── */
.lugares-section {
  margin-top: -120px;
  position: relative;
  z-index: 100;
  padding: 0 1.5rem;
  padding-bottom: 2rem;
}

.lugar-card {
  background: #ffffff;
  background-color: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.20);
  border: none;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 100;
  isolation: isolate;
}
.lugar-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15);
}

.lugar-card .card-body {
  padding: 1.6rem 1.4rem 1.2rem;
  text-align: center;
  min-height: 140px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.lugar-card h5 {
  font-family: var(--font-display);
  font-weight: 700;
  font-size: 1.2rem;
  letter-spacing: 3px;
  color: var(--negro);
  margin-bottom: 0.5rem;
}

.lugar-card p {
  font-size: 0.9rem;
  letter-spacing: 3px;
  color: #555555;
  line-height: 1.5;
}

.lugar-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
  margin-top: auto;
}

/* ── 3. ACTIVIDADES ── */
.actividades-section {
  padding: 5rem 0 1rem;
}

.actividades-section h2 {
  font-family: var(--font-display);
  font-weight: 700;
  font-size: clamp(1.6rem, 3.5vw, 2.2rem);
  letter-spacing: 3px;
  color: var(--negro);
  margin-bottom: 0.6rem;
}

.actividades-section p {
  font-size: 1.05rem;
  letter-spacing: 4px;
  color: #444444;
}

.actividades-section hr {
  border-top: 1px solid #cccccc;
  opacity: 1;
  margin-top: 1.8rem;
}

/* ── 4. BANNER INFO ── */
.info-banner-brand {
  background-color: #4F7352 !important;
  color: #ffffff !important;
  border-radius: 1rem;
  padding: 2.5rem 3rem;
}

.brand-subtitle {
  font-family: var(--font-body);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #A8CF45 !important;
  margin-bottom: 0.75rem;
  font-size: 1.05rem;
}

.brand-icon {
  color: #A8CF45 !important;
  font-size: 3rem;
  display: block;
  margin-bottom: 0.75rem;
}

.info-banner-brand p,
.info-banner-brand .small {
  color: #ffffff !important;
  font-size: 0.88rem;
  opacity: 0.85;
  line-height: 1.8;
  margin-bottom: 0.1rem;
}

/* ── 5. RESEÑAS ── */
.resenas-bg {
  background: #fff;
}

.resenas-titulo {
  font-size: clamp(1.6rem, 3vw, 2.2rem);
  font-weight: 800;
  color: #1a1a1a;
  margin-bottom: 0.4rem;
}

.resenas-subtitulo {
  font-size: 1rem;
  color: #888;
  letter-spacing: 1px;
}

.resenas-scroll {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.resena-card {
  background: #fff;
  border: 1px solid #ebebeb;
  border-radius: 1rem;
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 1.2rem;
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}
.resena-card:hover {
  box-shadow: 0 8px 28px rgba(0,0,0,0.09);
  transform: translateY(-4px);
}

.resena-texto {
  font-size: 0.92rem;
  color: #333;
  line-height: 1.7;
  flex: 1;
  margin: 0;
  display: -webkit-box;
  -webkit-line-clamp: 5;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.resena-autor {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-top: 1px solid #f0f0f0;
  padding-top: 1rem;
}

.resena-foto {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}
.resena-foto img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.resena-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #198754;
  color: #fff;
  font-size: 0.85rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.resena-nombre {
  color: #1a1a1a;
  font-size: 0.9rem;
}

.resena-fecha {
  font-size: 0.75rem;
  color: #aaa;
  margin-top: 0.1rem;
}

/* ── 6. ANIMACIONES DE ENTRADA (hero — se ejecutan al cargar) ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(40px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

@keyframes markerAppear {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 0.85; transform: translateY(0); }
}

@keyframes dotPulse {
  0%, 100% { box-shadow: 0 0 0 3px rgba(255,255,255,0.3); }
  50%       { box-shadow: 0 0 0 7px rgba(255,255,255,0.1); }
}

/* Hero image zoom lento */
.hero-img {
  animation: heroZoom 18s ease-in-out infinite alternate;
}
@keyframes heroZoom {
  from { transform: scale(1); }
  to   { transform: scale(1.06); }
}

.anim-hero-title {
  animation: fadeUp 0.9s ease both;
  animation-delay: 0.2s;
}
.anim-hero-subtitle {
  animation: fadeUp 0.9s ease both;
  animation-delay: 0.55s;
}
.anim-hero-btn {
  animation: fadeUp 0.9s ease both;
  animation-delay: 0.85s;
}

.anim-marker {
  opacity: 0;
  animation: markerAppear 0.7s ease forwards;
}

.pulse {
  animation: dotPulse 2s ease-in-out infinite;
}

/* ── 8. RESPONSIVE ── */
@media (max-width: 768px) {
  .hero {
    height: 100svh;
  }

  .label { font-size: 12px; }
  .line  { height: 20px; }
  .dot   { width: 6px; height: 6px; }

  .cascadas { top: 60%; left: 10%; }
  .rio      { top: 40%; left: 50%; }
  .prodigio { top: 25%; right: 10%; }

  .info-banner-brand { padding: 2rem 1.2rem; }
  .lugares-section   { margin-top: -40px; }
  .resenas-scroll    { grid-template-columns: 1fr !important; }
}

/* ── MODALES ── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  backdrop-filter: blur(3px);
}

.modal-custom {
  background: #fff;
  border-radius: 1.5rem;
  width: 100%;
  max-width: 480px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  animation: modalIn 0.3s ease;
}

.modal-custom--mapa {
  max-width: 650px;
}

@keyframes modalIn {
  from { opacity: 0; transform: translateY(20px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.modal-custom-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 1.5rem 1.5rem 1rem;
  border-bottom: 1px solid #f0f0f0;
}

.modal-custom-tag {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #198754;
}

.modal-custom-title {
  font-weight: 700;
  margin: 0.2rem 0 0;
  color: #1a1a1a;
}

.btn-cerrar-modal {
  background: #f5f5f5;
  border: none;
  border-radius: 50%;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #555;
  flex-shrink: 0;
  transition: background 0.2s;
}
.btn-cerrar-modal:hover { background: #e0e0e0; }

.modal-custom-body {
  padding: 1.5rem;
}

/* Clima */
.clima-main {
  display: flex;
  align-items: center;
  gap: 1.2rem;
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #f0f0f0;
}

.clima-main-icon {
  font-size: 3.5rem;
  color: #f5a623;
}

.clima-temp {
  font-size: 3rem;
  font-weight: 800;
  color: #1a1a1a;
  line-height: 1;
  display: block;
}

.clima-condicion {
  font-size: 1rem;
  color: #666;
  margin-top: 0.2rem;
  display: block;
}

.clima-stats {
  display: flex;
  justify-content: space-around;
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #f0f0f0;
}

.clima-stat {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.3rem;
}

.clima-stat i { font-size: 1.3rem; }

.clima-stat-val {
  font-weight: 700;
  font-size: 0.95rem;
  color: #1a1a1a;
}

.clima-stat-label {
  font-size: 0.75rem;
  color: #888;
}

.clima-horas-title {
  font-weight: 700;
  font-size: 0.85rem;
  color: #444;
  margin-bottom: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.clima-horas-scroll {
  display: flex;
  gap: 0.8rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
}

.clima-hora-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.3rem;
  min-width: 52px;
  background: #f8f9fa;
  border-radius: 0.75rem;
  padding: 0.6rem 0.4rem;
}

.hora-tiempo {
  font-size: 0.7rem;
  color: #888;
  font-weight: 600;
}

.hora-temp {
  font-size: 0.85rem;
  font-weight: 700;
  color: #1a1a1a;
}

.clima-fuente {
  font-size: 0.7rem;
  color: #bbb;
  text-align: right;
  margin-top: 1rem;
  margin-bottom: 0;
}

/* Mapa */
.mapa-iframe {
  width: 100%;
  height: 340px;
  border: none;
  display: block;
}

.mapa-info {
  padding: 1.2rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.mapa-dato {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.88rem;
  color: #444;
}

/* Botón banner */
.btn-banner-action {
  background: rgba(255,255,255,0.15);
  border: 1.5px solid rgba(255,255,255,0.6);
  color: #fff;
  border-radius: 999px;
  padding: 0.4rem 1.2rem;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
}
.btn-banner-action:hover {
  background: rgba(255,255,255,0.28);
  border-color: #fff;
}

</style>