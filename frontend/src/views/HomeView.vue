<template>
  <!-- ==========================================
       SECCIÓN 1: CAROUSEL PRINCIPAL
  =========================================== -->



  <!-- ==========================================
       SECCIÓN 2: CARDS DE HISTORIA
  =========================================== -->
  <section class="container mb-5" v-reveal>
    <div class="row g-4 mb-5">
      <div
        class="col-md-4"
        v-for="card in cardsHistoria"
        :key="card.titulo">
        <div class="card h-100 border-0 shadow-sm overflow-hidden text-center historia-card">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-1">{{ card.titulo }}</h4>
            <p v-if="card.subtitulo" class="text-muted small mb-3">{{ card.subtitulo }}</p>
            <p class="small">{{ card.descripcion }}</p>
            <router-link to="/historia" class="stretched-link"></router-link>
          </div>
          <img
            :src="card.imagen"
            class="card-img-bottom historia-card-img"
            :alt="card.titulo">
        </div>
      </div>
    </div>

    <div class="mt-5">
      <h2 class="fw-bold mb-1">Historia del municipio</h2>
      <p class="text-secondary fs-5">Conoce la rica historia y patrimonio de San Luis, Antioquia</p>
      <hr class="text-muted opacity-25">
    </div>
  </section>


  <!-- ==========================================
       SECCIÓN 3: CAROUSEL DE CATEGORÍAS
  =========================================== -->
  <section class="container py-5" v-reveal>
    <div class="mb-5">
      <h2 class="fw-bold mb-1">Explora por categorías</h2>
      <p class="text-secondary fs-5">Descubre todo lo que San Luis tiene para ofrecerte</p>
    </div>

    <div id="carouselCategorias" class="carousel slide"
         data-bs-ride="carousel"
         data-bs-interval="3000"
         data-bs-pause="hover">

      <div class="carousel-indicators mb-n4">
        <button
          v-for="(_, index) in slidesCategorias"
          :key="'cat-ind-' + index"
          type="button"
          data-bs-target="#carouselCategorias"
          :data-bs-slide-to="index"
          :class="['bg-success', { active: index === 0 }]"
          :aria-current="index === 0 ? 'true' : undefined"
          :aria-label="'Slide ' + (index + 1)">
        </button>
      </div>

      <div class="carousel-inner">
        <div
          v-for="(grupo, index) in slidesCategorias"
          :key="'cat-slide-' + index"
          class="carousel-item"
          :class="{ active: index === 0 }">
          <div class="d-flex justify-content-center gap-3">
            <div
              v-for="cat in grupo"
              :key="cat.nombre"
              class="card border-0 shadow-sm flex-fill categoria-card"
              :class="cat.claseResponsive">
              <img
                :src="cat.imagen"
                class="card-img-top categoria-card-img"
                :alt="cat.nombre">
              <div class="card-body text-center py-2">
                <h5 class="fw-bold small mb-0">{{ cat.nombre }}</h5>
              </div>
              <router-link :to="cat.ruta" class="stretched-link"></router-link>
            </div>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev w-auto" type="button"
              data-bs-target="#carouselCategorias" data-bs-slide="prev">
        <span class="bg-dark rounded-circle p-2">
          <i class="bi bi-chevron-left text-white fs-4"></i>
        </span>
      </button>
      <button class="carousel-control-next w-auto" type="button"
              data-bs-target="#carouselCategorias" data-bs-slide="next">
        <span class="bg-dark rounded-circle p-2">
          <i class="bi bi-chevron-right text-white fs-4"></i>
        </span>
      </button>
    </div>
  </section>


  <!-- ==========================================
       SECCIÓN 4: LUGARES IMPERDIBLES
  =========================================== -->
  <section class="container mb-5 px-4 px-lg-5" v-reveal>
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="fw-bold mb-1">Lugares imperdibles</h2>
        <p class="text-secondary fs-5 mb-0">Los destinos que no puedes dejar de visitar</p>
      </div>
      <div class="d-flex gap-2">
        <button class="scroll-nav-btn" @click="scrollLugares(-1)" aria-label="Anterior">
          <i class="bi bi-chevron-left"></i>
        </button>
        <button class="scroll-nav-btn" @click="scrollLugares(1)" aria-label="Siguiente">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>
    </div>

    <div v-if="sitiosDestacados.length === 0" class="text-center py-5 text-muted">
      <div class="spinner-border text-success" role="status"></div>
    </div>

    <div v-else ref="lugaresScrollRef" class="lugares-scroll">
      <div
        v-for="sitio in sitiosDestacados"
        :key="sitio.id"
        class="lugar-card flex-shrink-0">

        <div class="position-relative overflow-hidden lugar-card-inner">
          <!-- Imagen ciclando -->
          <transition name="fade-img" mode="out-in">
            <img
              :key="imageIndices[sitio.id]"
              :src="imagenActual(sitio)"
              class="lugar-card-img" />
          </transition>

          <!-- Gradiente y contenido superpuesto -->
          <div class="lugar-card-overlay">
            <span class="badge bg-success bg-opacity-90 rounded-pill px-3 py-1 mb-2 d-inline-block">
              <i class="bi bi-geo-alt-fill me-1"></i>{{ sitio.lugar ?? 'San Luis' }}
            </span>
            <h5 class="fw-bold text-white mb-1 lh-sm">{{ sitio.nombre }}</h5>
            <p class="text-white opacity-75 small mb-0 lugar-desc-clamp">{{ sitio.descripcion }}</p>
          </div>
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


  <!-- ==========================================
       SECCIÓN 6: PRÓXIMOS EVENTOS
  =========================================== -->
  <section class="container mb-5 pb-5" v-reveal>
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="fw-bold mb-1">Próximos eventos</h2>
        <p class="text-secondary fs-5 mb-0">No te pierdas las celebraciones y festividades locales</p>
      </div>
      <router-link to="/eventos" class="btn btn-outline-success btn-sm fw-bold">
        Ver todos <i class="bi bi-arrow-right ms-1"></i>
      </router-link>
    </div>

    <div class="row g-4">
      <div
        class="col-md-3"
        v-for="(evento, idx) in eventos"
        :key="evento.nombre"
        v-reveal="idx * 100">
        <div class="card h-100 border-0 shadow-sm text-center overflow-hidden evento-card">
          <img
            :src="evento.url_poster"
            class="card-img-top evento-card-img"
            :alt="evento.nombre">
          <div class="card-body py-3">
            <h6 class="fw-bold mb-2">{{ evento.nombre }}</h6>
            <p class="small text-muted mb-0">{{ evento.descripcion }}</p>
            <router-link to="/eventos" class="stretched-link"></router-link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()

// ── Imágenes estáticas (fallback por slug si el backend no tiene imagen) ──
import imgFundacion       from '@/assets/img/home/fundacion.webp'
import imgCultura         from '@/assets/img/home/cultura.webp'
import imgEconomia        from '@/assets/img/home/economia.webp'
import imgHospedaje       from '@/assets/img/principal/Hospedaje.webp'
import imgPiscina         from '@/assets/img/piscina.webp'
import imgTransporte      from '@/assets/img/principal/transporte.webp'
import imgGastronomia     from '@/assets/img/principal/gastronomia.webp'
import imgAgencia         from '@/assets/img/principal/agencia_turistica.webp'

const imgFallbackPorSlug = {
  'alojamientos':        imgHospedaje,
  'recreacion':          imgPiscina,
  'transportes':         imgTransporte,
  'gastronomia':         imgGastronomia,
  'agencias-turisticas': imgAgencia,
}

// ── Estado API ───────────────────────────────────
const eventos          = ref([])
const tipos            = ref([])
const sitiosDestacados  = ref([])
const resenas           = ref([])
const cargandoResenas   = ref(true)
const nuevoComentario   = ref('')
const enviandoResena    = ref(false)
const resenaEnviada     = ref(false)
const errorResena       = ref('')
const imageIndices      = ref({})
const lugaresScrollRef  = ref(null)
let   cicloInterval     = null

function scrollLugares(dir) {
  lugaresScrollRef.value?.scrollBy({ left: dir * 320, behavior: 'smooth' })
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

function iniciales(nombre) {
  return (nombre ?? '?').split(' ').slice(0, 2).map(p => p[0]).join('').toUpperCase()
}

function formatFecha(fecha) {
  if (!fecha) return ''
  // formato Y-m-d que devuelve el Resource
  const [y, m, d] = fecha.split('-')
  const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic']
  return `${parseInt(d)} ${meses[parseInt(m) - 1]} ${y}`
}

async function cargarEventos() {
  try {
    const { data } = await api.get(PUBLICO.EVENTOS)
    const payload = data.data
    const lista = payload?.data ?? (Array.isArray(payload) ? payload : [])
    eventos.value = lista.slice(0, 4)
  } catch { /* muestra sección vacía si falla */ }
}

async function cargarSitiosDestacados() {
  try {
    const { data } = await api.get(PUBLICO.SITIOS, { params: { page: 1 } })
    const payload = data.data
    let lista = []
    if (payload?.data)    lista = payload.data
    else if (payload?.sitios) lista = payload.sitios
    else if (Array.isArray(payload)) lista = payload
    sitiosDestacados.value = lista.filter(s => s.estado !== false)
    sitiosDestacados.value.forEach(s => { imageIndices.value[s.id] = 0 })
    iniciarCiclo()
  } catch {}
}

function imagenActual(sitio) {
  const imgs = (sitio.imagenes ?? []).filter(Boolean)
  return imgs[imageIndices.value[sitio.id] ?? 0] || null
}

function iniciarCiclo() {
  if (cicloInterval) clearInterval(cicloInterval)
  cicloInterval = setInterval(() => {
    sitiosDestacados.value.forEach(s => {
      const imgs = (s.imagenes ?? []).filter(Boolean)
      if (imgs.length > 1)
        imageIndices.value[s.id] = ((imageIndices.value[s.id] ?? 0) + 1) % imgs.length
    })
  }, 3500)
}

async function cargarTipos() {
  try {
    const { data } = await api.get(PUBLICO.TIPOS)
    const lista = data.data ?? (Array.isArray(data) ? data : [])
    tipos.value = lista
  } catch { /* mantiene array vacío */ }
}

// Agrupa los tipos en slides de 3 para el carousel
const slidesCategorias = computed(() => {
  const cats = tipos.value.map((t) => ({
    nombre: t.nombre,
    ruta:   '/' + t.slug,
    imagen: t.url_imagen ?? imgFallbackPorSlug[t.slug] ?? imgHospedaje,
  }))
  const grupos = []
  for (let i = 0; i < cats.length; i += 3) {
    grupos.push(cats.slice(i, i + 3))
  }
  return grupos
})

onMounted(() => Promise.all([cargarEventos(), cargarTipos(), cargarSitiosDestacados(), cargarResenas()]))
onUnmounted(() => { if (cicloInterval) clearInterval(cicloInterval) })

// ── Datos estáticos ──────────────────────────────
const cardsHistoria = [
  { titulo: 'Fundación', subtitulo: '1875', descripcion: 'Fundado por el padre Clemente Giraldo, su nombre se dio en honor a San Luis Gonzaga patrono del pueblo.', imagen: imgFundacion },
  { titulo: 'Cultura y tradición', subtitulo: null, descripcion: 'San Luis conserva vivas sus raíces campesinas y religiosas, con fiestas populares, música típica y gran sentido comunitario.', imagen: imgCultura },
  { titulo: 'Economía', subtitulo: null, descripcion: 'La economía local se basa en la agricultura, la ganadería y la producción de madera, con crecimiento en el ecoturismo y productos artesanales.', imagen: imgEconomia },
]

</script>


<style scoped>
/* ── CAROUSEL PRINCIPAL ── */

.carousel-img {
  width: 100%;
  height: 600px;
  object-fit: cover;
}

@media (max-width: 768px) {
  .carousel-img {
    height: 300px;
  }
}

/* ── HISTORIA ── */
.historia-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}
.historia-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12) !important;
}
.historia-card-img {
  height: 200px;
  object-fit: cover;
}

/* ── CATEGORÍAS ── */
.categoria-card {
  min-width: 250px;
  transition: transform 0.3s ease;
  cursor: pointer;
}
.categoria-card:hover {
  transform: translateY(-4px);
}
.categoria-card-img {
  height: 150px;
  object-fit: cover;
}

/* ── LUGARES IMPERDIBLES ── */
.scroll-nav-btn {
  width: 40px; height: 40px;
  border-radius: 50%;
  border: 1.5px solid #dee2e6;
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.scroll-nav-btn:hover { background: #198754; border-color: #198754; color: #fff; }

.lugares-scroll {
  display: flex;
  gap: 1.25rem;
  overflow-x: auto;
  padding-bottom: 0.75rem;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: #c8e6c9 transparent;
}
.lugares-scroll::-webkit-scrollbar { height: 4px; }
.lugares-scroll::-webkit-scrollbar-track { background: transparent; }
.lugares-scroll::-webkit-scrollbar-thumb { background: #a5d6a7; border-radius: 99px; }

.lugar-card {
  width: 300px;
  min-width: 300px;
  scroll-snap-align: start;
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.10);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.lugar-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.18);
}

.lugar-card-inner { height: 340px; border-radius: 1.25rem; }

.lugar-card-img {
  position: absolute;
  inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}
.lugar-card:hover .lugar-card-img { transform: scale(1.05); }

.lugar-card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.15) 55%, transparent 100%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 1.25rem;
}

.lugar-desc-clamp {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.fade-img-enter-active,
.fade-img-leave-active {
  transition: opacity 0.9s ease;
  position: absolute;
  inset: 0;
}
.fade-img-enter-from,
.fade-img-leave-to { opacity: 0; }

/* ── EVENTOS ── */
.evento-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}
.evento-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12) !important;
}
.evento-card-img {
  height: 150px;
  object-fit: cover;
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .carousel-img {
    height: 300px;
  }
  .lugar-card {
    min-width: 260px;
  }
  .resenas-scroll {
    grid-template-columns: 1fr !important;
  }
}

/* ── RESEÑAS ── */
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
</style>
