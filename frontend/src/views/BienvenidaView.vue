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
       SECCIÓN 5: TESTIMONIOS
  =========================================== -->
  <section class="testimonios-section">
    <div class="container">
      <h2 class="brand-section-title" style="letter-spacing: 3px;">
        Lo que dicen nuestros visitantes
      </h2>
      <p class="brand-section-subtitle mb-5" style="letter-spacing: 3px;">
        Experiencias auténticas de quienes han vivido la magia de San Luis
      </p>

      <div class="row g-4 align-items-stretch">
        <div
          class="col-md-4 d-flex"
          v-for="(testimonio, idx) in testimonios"
          :key="testimonio.nombre"
          v-reveal="idx * 130">
          <div class="testimonial-card w-100">
            <div class="card-body">
              <p class="testimonial-text">"{{ testimonio.texto }}"</p>
              <hr class="divider-brand">
              <div class="d-flex align-items-center mt-3">
                <img
                  :src="testimonio.avatar"
                  class="visitor-avatar me-3"
                  :alt="testimonio.nombre">
                <div>
                  <h6 class="visitor-name">{{ testimonio.nombre }}</h6>
                  <p class="visitor-location mb-0">
                    <i class="bi bi-geo-alt-fill me-1 location-icon"></i>
                    {{ testimonio.ciudad }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       SECCIÓN 6: CTA RESEÑA
  =========================================== -->
  <section class="cta-section" v-reveal>
    <div class="container">
      <h2 class="brand-section-title mb-3" style="letter-spacing: 2px;">
        ¿Ya visitaste San Luis? comparte tu experiencia
      </h2>
      <router-link to="/resenas" class="btn-resena">
        Escribir reseña
      </router-link>
    </div>
  </section>
</template>


<script setup>
import imgHero      from '@/assets/img/principal/hero.webp'
import imgPlanta    from '@/assets/img/principal/planta.webp'
import imgCastellon from '@/assets/img/principal/cerro_castellon.webp'
import imgSamana    from '@/assets/img/principal/rio_samana.webp'

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

const testimonios = [
  {
    texto: 'Paisajes imponentes y una caminata brutal. El pozo natural al final es la mejor recompensa para el calor. Un tesoro de San Luis que debes conocer.',
    nombre: 'Diana Gonzales',
    ciudad: 'Rionegro',
    avatar: 'https://i.pravatar.cc/150?u=diana1'
  },
  {
    texto: 'Pura paz y sonidos de la naturaleza. Ideal para relajarse y respirar aire puro. Por favor, cuidemos este paraíso y no dejemos basura.',
    nombre: 'Andres Perez',
    ciudad: 'Canada',
    avatar: 'https://i.pravatar.cc/150?u=andres1'
  },
  {
    texto: 'San Luis me sorprendió completamente. La naturaleza es espectacular y la gente es muy amable.',
    nombre: 'Marcela Moncada',
    ciudad: 'Medellín',
    avatar: 'https://i.pravatar.cc/150?u=marcela1'
  }
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

/* ── 5. TESTIMONIOS ── */
.testimonios-section {
  padding: 3rem 0 4rem;
}

.brand-section-title {
  font-family: var(--font-display);
  font-weight: 700;
  color: var(--negro);
  font-size: clamp(1.5rem, 3vw, 2.2rem);
  letter-spacing: 3px;
  margin-bottom: 0.4rem;
}

.brand-section-subtitle {
  font-family: var(--font-body);
  color: var(--negro);
  font-size: 1.05rem;
  letter-spacing: 3px;
  opacity: 0.70;
  margin-bottom: 2.5rem;
}

.testimonial-card {
  background: #ffffff;
  border-radius: 12px;
  border: none;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.20);
  transition: transform 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.testimonial-card:hover {
  transform: translateY(-5px);
}

.testimonial-card .card-body {
  padding: 1.6rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.testimonial-text {
  font-size: 0.92rem;
  letter-spacing: 2px;
  color: #333333;
  line-height: 1.7;
  font-style: italic;
  flex: 1;
}

.divider-brand {
  border-top: 1px solid #e0e0e0;
  opacity: 1;
  margin: 1rem 0;
  margin-top: auto;
}

.visitor-name {
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 0.92rem;
  letter-spacing: 2px;
  color: var(--negro);
  margin-bottom: 0.15rem;
}

.visitor-location {
  color: var(--azul-link);
  font-weight: 500;
  font-size: 0.82rem;
}

.location-icon {
  color: var(--verde-lima);
}

.visitor-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #A8CF45;
}

/* ── 6. CTA ── */
.cta-section {
  padding: 3rem 0 5rem;
  text-align: center;
}
/*text-decoration: none me quita el sunrayado del texto */
.btn-resena {
  color: #ffffff;
  background-color: seagreen;
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 1rem;
  letter-spacing: 3px;
  border: none;
  border-radius: 20px;
  padding: 0.9rem 2.8rem;
  cursor: pointer;
  transition: background-color 0.2s ease, transform 0.2s ease;
  text-decoration: none; 
}
.btn-resena:hover {
  background-color: #3d5a40;
  color: #ffffff;
  opacity: 1;
  transform: translateY(-2px);
}

/* ── 7. ANIMACIONES DE ENTRADA (hero — se ejecutan al cargar) ── */
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
  .brand-section-title    { font-size: 1.4rem; }
  .brand-section-subtitle { font-size: 0.9rem; letter-spacing: 1.5px; }
}
</style>