<template>
  <div>

    <!-- CARD PORTADA DE CATEGORÍA -->
    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden bg-white">
      <div class="row g-0">
        <div class="col-md-3 position-relative" style="height: 150px;">
          <img
            :src="imgPortada || 'https://via.placeholder.com/400x150'"
            class="img-fluid w-100 h-100"
            style="object-fit: cover;"
          />
          <div class="position-absolute bottom-0 end-0 p-2">
            <button
              class="btn btn-dark btn-sm rounded-pill shadow px-3 py-1"
              style="font-size: 0.8rem;"
              @click="inputPortada.click()">
              <i class="bi bi-camera-fill me-1"></i>Cambiar
            </button>
          </div>
        </div>
        <div class="col-md-9 d-flex align-items-center">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1">Gestión de {{ capitalizar(tipoData?.nombre ?? slug) }}</h5>
            <p class="text-muted mb-2 small">
              Personaliza la imagen que verán los usuarios en el catálogo principal.
            </p>
            <input
              ref="inputPortada"
              type="file"
              accept="image/*"
              class="d-none"
              @change="seleccionarPortada"
            />
            <div v-if="portadaFile" class="mt-2 d-flex gap-2">
              <button class="btn btn-success btn-sm" @click="confirmarPortada">Confirmar cambio</button>
              <button class="btn btn-light btn-sm" @click="cancelarPortada">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ALERTA -->
    <div v-if="alerta.visible"
      :class="`alert alert-${alerta.tipo} alert-dismissible fade show`"
      role="alert">
      <strong>{{ alerta.titulo }}</strong> {{ alerta.mensaje }}
      <button type="button" class="btn-close" @click="alerta.visible = false"></button>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3 text-muted">Total {{ capitalizar(tipoData?.nombre ?? slug) }}</h5>
          <h1 class="display-4 fw-bold mb-0 text-dark">{{ totalEntidades }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3 text-success">Activas</h5>
          <h1 class="display-4 fw-bold mb-0 text-success">{{ activas }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3 text-danger">Inactivas</h5>
          <h1 class="display-4 fw-bold mb-0 text-danger">{{ inactivas }}</h1>
        </div>
      </div>
    </div>

    <!-- TABLA / LISTA DE ENTIDADES -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <!-- FILTROS Y BOTÓN AGREGAR -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-4">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-search"></i>
              </span>
              <input
                type="text"
                v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar Establecimiento"
              />
            </div>
          </div>
          <div class="col-md-3">
            <select v-model="filtroSubtipo" class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos los Tipos</option>
              <option v-for="s in subtipos" :key="s.id" :value="s.id">{{ s.nombre }}</option>
            </select>
          </div>
          <div class="col-md-2">
            <select v-model="filtroEstado" class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos</option>
              <option value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
          </div>
          <div class="col-md-3 text-end">
            <button
              class="btn btn-outline-dark border border-secondary-subtle rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-plus-lg me-1"></i> Agregar
            </button>
          </div>
        </div>

        <!-- LISTA -->
        <div class="d-flex flex-column gap-3">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando establecimientos...</p>
          </div>

          <div v-else-if="entidadesFiltradas.length === 0" class="text-center p-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p>No hay establecimientos registrados en {{ capitalizar(tipoData?.nombre ?? slug) }}.</p>
          </div>

          <div
            v-else
            v-for="entidad in entidadesFiltradas"
            :key="entidad.id"
            class="card border border-secondary-subtle rounded-4 shadow-none">
            <div class="card-body d-flex align-items-center justify-content-between p-3">

              <!-- INFO -->
              <div class="d-flex align-items-center">
                <img
                  :src="entidad.imagenes?.[0]?.url_completa || 'https://via.placeholder.com/60'"
                  class="rounded-3 border border-dark me-3"
                  style="width: 60px; height: 60px; object-fit: cover;"
                />
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold mb-0">{{ entidad.nombre_comercial }}</h5>
                    <span
                      :class="entidad.estado
                        ? 'badge bg-success-subtle text-success rounded-pill px-3 py-1'
                        : 'badge bg-danger-subtle text-danger rounded-pill px-3 py-1'"
                      style="font-size: 0.7rem;">
                      {{ entidad.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                  <p class="text-muted small mb-0 mt-1">
                    <i class="bi bi-geo-alt me-1"></i>{{ entidad.direccion }}
                  </p>
                </div>
              </div>

              <!-- ACCIONES -->
              <div class="d-flex gap-3">
                <button
                  :class="entidad.estado
                    ? 'btn btn-outline-danger border rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold'
                    : 'btn btn-outline-success border rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold'"
                  @click="toggleEstado(entidad)">
                  <i :class="entidad.estado ? 'bi bi-eye-slash fs-5' : 'bi bi-eye fs-5'"></i>
                  {{ entidad.estado ? 'Desactivar' : 'Activar' }}
                </button>

                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold"
                  @click="verDetalle(entidad)">
                  <i class="bi bi-eye fs-5"></i> Ver
                </button>

                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold"
                  @click="abrirModalEditar(entidad)">
                  <i class="bi bi-pencil-square fs-5"></i> Editar
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold d-flex align-items-center">
              <i class="bi bi-pencil-square fs-4 me-2"></i> Agregar Registro
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarNueva">
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Nombre Comercial *</label>
                  <input v-model="formNueva.nombre_comercial" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Restaurante El Sabor" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Razón Social *</label>
                  <input v-model="formNueva.razon_social" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Inversiones S.A.S" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">RUT (Opcional)</label>
                  <input v-model="formNueva.rut" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">WhatsApp / Teléfono *</label>
                  <input v-model="formNueva.telefono" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="300 123 4567" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Horario de Atención *</label>
                  <input v-model="formNueva.hora_atencion" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Lunes a Sábado 8am - 6pm" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Sitio Web o Facebook</label>
                  <input v-model="formNueva.sitio_web" type="url"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="https://facebook.com/tu-pagina" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado Inicial *</label>
                  <select v-model="formNueva.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Dirección *</label>
                  <input v-model="formNueva.direccion" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Carrera 10 # 5-20, Centro" required />
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Descripción del Negocio</label>
                  <textarea v-model="formNueva.descripcion"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    rows="2" placeholder="Describe brevemente los servicios..."></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-1">
                    <i class="bi bi-person-badge me-1"></i> Logo del Comercio *
                  </label>
                  <small class="text-muted d-block mb-2">Imagen cuadrada o logo distintivo.</small>
                  <input type="file" ref="inputLogoNueva" accept="image/*"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none" required />
                </div>
              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Guardar Registro
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL VER DETALLE ===== -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Detalle del Establecimiento</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="detalle">
            <div class="row g-4 align-items-start">
              <div class="col-md-4 text-center">
                <div class="p-4 rounded-4 border bg-white shadow-sm mb-3">
                  <img :src="detalle.imagenes?.[0]?.url_completa || 'https://via.placeholder.com/150'"
                    class="img-fluid rounded-4 mb-3 border"
                    style="width: 150px; height: 150px; object-fit: cover;" />
                  <h6 class="fw-bold mb-1 small">Identidad Visual</h6>
                  <p class="text-muted small mb-0">Logo registrado</p>
                </div>
                <div class="p-3 rounded-4 border bg-light">
                  <span class="d-block small text-muted mb-1">Estado actual</span>
                  <span :class="detalle.estado
                    ? 'badge bg-success rounded-pill px-3 py-2 w-100'
                    : 'badge bg-danger rounded-pill px-3 py-2 w-100'">
                    {{ detalle.estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
              </div>
              <div class="col-md-8">
                <h3 class="fw-bold text-dark mb-1">{{ detalle.nombre_comercial }}</h3>
                <p class="text-primary fw-semibold mb-3 small">
                  <i class="bi bi-tag-fill me-1"></i> Establecimiento Verificado
                </p>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Razón Social</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">{{ detalle.razon_social }}</p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">NIT / RUT</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">{{ detalle.rut || '—' }}</p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">WhatsApp / Tel</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">{{ detalle.telefono }}</p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Horario</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">{{ detalle.hora_atencion }}</p>
                  </div>
                  <div class="col-12">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Dirección</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">{{ detalle.direccion }}</p>
                  </div>
                  <div class="col-12">
                    <div class="p-3 rounded-4 border bg-white shadow-sm">
                      <label class="fw-bold mb-1 small text-muted text-uppercase" style="font-size:0.7rem">Descripción</label>
                      <p class="small text-secondary mb-0">{{ detalle.descripcion }}</p>
                    </div>
                  </div>
                  <div class="col-12" v-if="detalle.sitio_web">
                    <a :href="detalle.sitio_web" target="_blank"
                      class="btn btn-dark btn-sm rounded-pill px-4 shadow-sm fw-bold">
                      <i class="bi bi-globe me-2"></i>Visitar Sitio Web
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0 d-flex justify-content-center pb-4">
            <button type="button" class="btn btn-light border px-5 rounded-3 fw-bold" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-fill me-2"></i> Editar Establecimiento</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-4 align-items-start">
                <div class="col-md-4 text-center">
                  <div class="p-4 rounded-4 border bg-white shadow-sm mb-3">
                    <img :src="logoPreview || 'https://via.placeholder.com/150'"
                      class="img-fluid rounded-4 mb-3 border"
                      style="width: 150px; height: 150px; object-fit: cover;" />
                    <label class="fw-bold mb-1 small d-block">Cambiar Logo</label>
                    <input type="file" ref="inputLogoEditar" accept="image/*"
                      class="form-control form-control-sm"
                      @change="previewLogoEditar" />
                  </div>
                  <div class="p-3 rounded-4 border bg-light text-start">
                    <label class="fw-bold mb-1 small text-muted text-uppercase" style="font-size:0.7rem">Estado</label>
                    <select v-model="formEditar.estado"
                      class="form-select form-select-sm border-0 shadow-none bg-transparent fw-bold text-primary">
                      <option :value="true">Activo</option>
                      <option :value="false">Inactivo</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-4">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Nombre Comercial</label>
                    <input v-model="formEditar.nombre_comercial" type="text"
                      class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fw-bold shadow-none" required />
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Razón Social</label>
                      <input v-model="formEditar.razon_social" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">NIT / RUT</label>
                      <input v-model="formEditar.rut" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">WhatsApp / Tel</label>
                      <input v-model="formEditar.telefono" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Horario</label>
                      <input v-model="formEditar.hora_atencion" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Dirección</label>
                      <input v-model="formEditar.direccion" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Descripción</label>
                      <textarea v-model="formEditar.descripcion" rows="3"
                        class="form-control border-0 bg-light rounded-3 shadow-none small"></textarea>
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Sitio Web / Redes</label>
                      <input v-model="formEditar.sitio_web" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer border-0 pt-4 d-flex justify-content-center">
                <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-5 rounded-3 fw-bold shadow-sm"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  <i v-else class="bi bi-save me-2"></i>Actualizar Información
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import api from '@/api/axios'
import { ADMIN, PUBLICO } from '@/api/endpoints'

const props = defineProps({ slug: String })

const entidades   = ref([])
const tipoData    = ref(null)
const cargando    = ref(true)
const guardando   = ref(false)
const busqueda    = ref('')
const filtroSubtipo = ref('')
const filtroEstado  = ref('')
const imgPortada    = ref(null)
const portadaFile   = ref(null)
const detalle       = ref(null)
const formEditar    = ref(null)
const logoPreview   = ref(null)
const inputLogoNueva  = ref(null)
const inputLogoEditar = ref(null)
const inputPortada    = ref(null)
const alerta = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

const formNueva = ref({
  nombre_comercial: '', razon_social: '', rut: '', telefono: '',
  hora_atencion: '', sitio_web: '', estado: true, direccion: '', descripcion: ''
})

const subtipos = computed(() => tipoData.value?.tipos_especificos ?? [])
const totalEntidades = computed(() => entidades.value.length)
const activas        = computed(() => entidades.value.filter(e => e.estado).length)
const inactivas      = computed(() => entidades.value.filter(e => !e.estado).length)

const entidadesFiltradas = computed(() => {
  return entidades.value.filter(e => {
    const nombre = e.nombre_comercial?.toLowerCase() ?? ''
    const okBusqueda = !busqueda.value || nombre.includes(busqueda.value.toLowerCase())
    const okEstado   = filtroEstado.value === '' || String(e.estado ? '1' : '0') === filtroEstado.value
    const okSubtipo  = !filtroSubtipo.value || e.subtipos?.some(s => s.id == filtroSubtipo.value)
    return okBusqueda && okEstado && okSubtipo
  })
})

onMounted(async () => {
  await cargarTipo()
  await cargarEntidades()
})

async function cargarTipo() {
  try {
    const { data } = await api.get(PUBLICO.TIPOS)
    tipoData.value = data.data?.find(t => t.slug === props.slug) ?? null
    imgPortada.value = tipoData.value?.url_imagen ?? null
  } catch (err) {
    console.error('Error al cargar tipo:', err)
  }
}

async function cargarEntidades() {
  cargando.value = true
  try {
    const params = tipoData.value ? { tipo_entidad_id: tipoData.value.id } : {}
    const { data } = await api.get(ADMIN.ENTIDADES, { params })
    const payload = data.data
    entidades.value = payload?.data ?? (Array.isArray(payload) ? payload : [])
  } finally {
    cargando.value = false
  }
}

function seleccionarPortada(e) {
  portadaFile.value = e.target.files[0]
  if (portadaFile.value) imgPortada.value = URL.createObjectURL(portadaFile.value)
}

async function confirmarPortada() {
  if (!portadaFile.value || !tipoData.value) return
  const fd = new FormData()
  fd.append('imagen', portadaFile.value)
  await api.post(ADMIN.TIPO_IMAGEN(tipoData.value.id), fd, { headers: { 'Content-Type': 'multipart/form-data' } })
  portadaFile.value = null
  mostrarAlerta('success', '¡Listo!', 'Imagen de portada actualizada.')
}

function cancelarPortada() {
  portadaFile.value = null
  cargarTipo()
}

async function toggleEstado(entidad) {
  const accion = entidad.estado ? 'desactivar' : 'activar'
  if (!confirm(`¿Deseas ${accion} "${entidad.nombre_comercial}"?`)) return
  await api.patch(ADMIN.ENTIDAD_ESTADO(entidad.id), { estado: !entidad.estado })
  entidad.estado = !entidad.estado
  mostrarAlerta('success', '¡Actualizado!', `Establecimiento ${entidad.estado ? 'activado' : 'desactivado'}.`)
}

function verDetalle(e) {
  detalle.value = e
  new Modal(document.getElementById('modalDetalle')).show()
}

function abrirModalAgregar() {
  formNueva.value = {
    nombre_comercial: '', razon_social: '', rut: '', telefono: '',
    hora_atencion: '', sitio_web: '', estado: true, direccion: '', descripcion: ''
  }
  new Modal(document.getElementById('modalAgregar')).show()
}

async function guardarNueva() {
  guardando.value = true
  try {
    const fd = new FormData()
    Object.entries(formNueva.value).forEach(([k, v]) => fd.append(k, v))
    fd.append('lugar_id', 1)
    if (tipoData.value) fd.append('tipo_entidad_id', tipoData.value.id)
    if (inputLogoNueva.value?.files[0]) fd.append('logo', inputLogoNueva.value.files[0])
    await api.post(ADMIN.ENTIDADES, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    Modal.getInstance(document.getElementById('modalAgregar')).hide()
    mostrarAlerta('success', '¡Guardado!', 'Establecimiento agregado.')
    await cargarEntidades()
  } catch (err) {
    mostrarAlerta('danger', '¡Error!', err.response?.data?.message ?? 'No se pudo guardar.')
  } finally {
    guardando.value = false
  }
}

function abrirModalEditar(e) {
  formEditar.value = { ...e }
  logoPreview.value = e.imagenes?.[0]?.url_completa ?? null
  new Modal(document.getElementById('modalEditar')).show()
}

function previewLogoEditar(e) {
  const f = e.target.files[0]
  if (f) logoPreview.value = URL.createObjectURL(f)
}

async function guardarEdicion() {
  guardando.value = true
  try {
    const fd = new FormData()
    const campos = ['nombre_comercial', 'razon_social', 'rut', 'telefono', 'hora_atencion', 'sitio_web', 'estado', 'direccion', 'descripcion']
    campos.forEach(k => fd.append(k, formEditar.value[k] ?? ''))
    fd.append('lugar_id', formEditar.value.lugar?.id ?? 1)
    fd.append('_method', 'PUT')
    if (inputLogoEditar.value?.files[0]) fd.append('logo', inputLogoEditar.value.files[0])
    await api.post(ADMIN.ENTIDAD(formEditar.value.id), fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    Modal.getInstance(document.getElementById('modalEditar')).hide()
    mostrarAlerta('success', '¡Actualizado!', 'Establecimiento editado.')
    await cargarEntidades()
  } catch (err) {
    mostrarAlerta('danger', '¡Error!', err.response?.data?.message ?? 'No se pudo actualizar.')
  } finally {
    guardando.value = false
  }
}

function capitalizar(texto) {
  if (!texto) return ''
  return texto.charAt(0).toUpperCase() + texto.slice(1)
}

function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>
