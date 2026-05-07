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
  <section class="container mb-5" v-reveal>
    <div class="info-banner-brand p-4 p-md-5">
      <div class="row g-4 text-center align-items-center">

        <div class="col-md-4" v-reveal="0">
          <i class="bi bi-cloud-sun brand-icon mb-3"></i>
          <h4 class="brand-subtitle mb-3">Clima</h4>
          <p class="mb-1 small" v-for="item in clima" :key="item">{{ item }}</p>
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
        <h2 class="fw-bold mb-1">Lo que dicen nuestros visitantes</h2>
        <p class="text-secondary fs-5">Experiencias reales de quienes exploraron San Luis</p>
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
          <div class="resena-comillas">
            <i class="bi bi-quote"></i>
          </div>
          <p class="resena-texto">{{ resena.comentario }}</p>
          <div class="resena-autor">
            <div class="resena-avatar">{{ iniciales(resena.usuario?.nombre) }}</div>
            <div>
              <div class="fw-bold small">{{ resena.usuario?.nombre ?? 'Anónimo' }}</div>
              <div class="text-muted" style="font-size:0.75rem;">{{ formatFecha(resena.created_at) }}</div>
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
  </section>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'

import imgHero      from '@/assets/img/principal/hero.webp'
import imgPlanta    from '@/assets/img/principal/planta.webp'
import imgCastellon from '@/assets/img/principal/cerro_castellon.webp'
import imgSamana    from '@/assets/img/principal/rio_samana.webp'

const authStore        = useAuthStore()
const resenas          = ref([])
const cargandoResenas  = ref(true)
const nuevoComentario  = ref('')
const enviandoResena   = ref(false)
const resenaEnviada    = ref(false)
const errorResena      = ref('')

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
  {
    nombre: 'Cascada la planta',
    descripcion: 'Considerado el charco natural más grande del Oriente antioqueño.',
    imagen: imgPlanta
  },
  {
    nombre: 'Cerro el Castellón',
    descripcion: 'Un lugar lleno de historia y paisajes que enamoran.',
    imagen: imgCastellon
  },
  {
    nombre: 'Rio Samaná',
    descripcion: 'El único río libre de Antioquia.',
    imagen: imgSamana
  }
]

const clima = [
  'Temperatura entre 23°C y 36°C',
  'Rara vez baja de 21°C',
  'o sube más de 38°C'
]

const consejos = [
  'Ropa cómoda y repelente',
  'Calzado antideslizante',
  'Hidratación constante'
]

const ubicacion = [
  'A 124 Km de Medellín',
  'Autopista Medellín - Bogotá',
  'Corazón del Oriente'
]


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
  font-family: var(--font-body);
  font-weight: 500;
  font-size: clamp(1rem, 2vw, 1.3rem);
  letter-spacing: 2px;
  padding: 1rem 2.8rem;
  border-radius: 999px;
  background: #4F7352;
  color: #ffffff;
  border: 2px solid #ffffff;
  transition: background 0.25s, color 0.25s, border-color 0.25s, transform 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.btn-aventura:hover {
  background: var(--verde-lima);
  border-color: var(--verde-lima);
  color: #1a2e0a;
  transform: translateY(-2px);
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
  background: linear-gradient(135deg, #f0faf4 0%, #e8f5e9 100%);
}

.resenas-scroll {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.resena-card {
  background: #fff;
  border-radius: 1.25rem;
  padding: 1.75rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.07);
  display: flex;
  flex-direction: column;
  gap: 1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.resena-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.resena-comillas {
  font-size: 2.5rem;
  line-height: 1;
  color: #198754;
  opacity: 0.35;
}

.resena-texto {
  font-size: 0.92rem;
  color: #444;
  line-height: 1.65;
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
  padding-top: 0.75rem;
}

.resena-form-wrapper {
  max-width: 620px;
  background: #fff;
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08);
}

.resena-textarea {
  border: 1.5px solid #dee2e6;
  border-radius: 0.75rem;
  resize: none;
  font-size: 0.92rem;
  transition: border-color 0.2s;
}
.resena-textarea:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.2rem rgba(25,135,84,0.15);
}

.resena-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #198754;
  color: #fff;
  font-size: 0.8rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
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
</style>