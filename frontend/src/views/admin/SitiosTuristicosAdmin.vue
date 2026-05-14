<template>
  <div>

    <!-- TARJETAS ESTADÍSTICAS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <AdminStatCard
      label="Total Sitios"
      :valor="totalGeneral"
      icono="bi bi-geo-alt-fill"
      variante="default"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Activos"
      :valor="totalActivos"
      icono="bi bi-check-circle-fill"
      variante="success"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Inactivos"
      :valor="totalInactivos"
      icono="bi bi-x-circle-fill"
      variante="danger"
    />
  </div>
</div>

    <!-- LISTA DE SITIOS -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <!-- FILTROS -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-4">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar sitio..." />
            </div>
          </div>
          <div class="col-md-3">
            <select v-model="filtroEstado"
              class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos</option>
              <option value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
          </div>
          <div class="col-md-5 text-end">
            <button class="btn btn-dark border-0 rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-plus-lg me-1"></i> Agregar Sitio
            </button>
          </div>
        </div>

        <!-- ITEMS -->
        <div class="d-flex flex-column gap-3">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando sitios turísticos...</p>
          </div>

          <div v-else-if="sitiosFiltrados.length === 0"
            class="text-center p-5 text-muted bg-light rounded-4">
            <i class="bi bi-geo fs-1 d-block mb-3"></i>
            <p>No hay sitios turísticos registrados aún.</p>
          </div>

          <div v-else v-for="item in sitiosFiltrados" :key="item.id"
            class="card border border-secondary-subtle rounded-4 shadow-none">
            <div class="card-body d-flex align-items-center justify-content-between p-3">

              <!-- INFO -->
              <div class="d-flex align-items-center">
                <img :src="item.imagenes?.[0] || '/assets/img/placeholder.png'"
                  class="rounded-3 border border-dark-subtle me-3 shadow-sm"
                  style="width: 75px; height: 75px; object-fit: cover;" />
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold mb-0">{{ item.nombre }}</h5>
                    <span
                      :class="item.estado
                        ? 'badge bg-success-subtle text-success rounded-pill px-3 py-1'
                        : 'badge bg-danger-subtle text-danger rounded-pill px-3 py-1'"
                      style="font-size: 0.7rem;">
                      {{ item.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                  <p class="text-muted small mb-0 mt-1">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ item.lugar }}
                  </p>
                  <p class="text-secondary small mb-0 text-truncate" style="max-width: 350px;">
                    {{ item.descripcion }}
                  </p>
                </div>
              </div>

              <!-- ACCIONES -->
              <div class="d-flex gap-2">
                <button
                  :class="item.estado
                    ? 'btn btn-outline-danger border rounded-3 px-3 d-flex align-items-center gap-2 shadow-none fw-semibold btn-sm'
                    : 'btn btn-outline-success border rounded-3 px-3 d-flex align-items-center gap-2 shadow-none fw-semibold btn-sm'"
                  @click="toggleEstado(item)">
                  <i :class="item.estado ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  {{ item.estado ? 'Desactivar' : 'Activar' }}
                </button>
                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-3 d-flex align-items-center gap-2 shadow-none fw-semibold btn-sm"
                  @click="verDetalle(item)">
                  <i class="bi bi-eye"></i> Ver
                </button>
                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-3 d-flex align-items-center gap-2 shadow-none fw-semibold btn-sm"
                  @click="abrirModalEditar(item)">
                  <i class="bi bi-pencil-square"></i> Editar
                </button>
              </div>

            </div>
          </div>
          <AdminPaginacion
  :pagina-actual="paginaActual"
  :total-paginas="totalPaginas"
  :total="totalRegistros"
  @cambiar="cargarSitios"
/>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalAgregarSitio" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold d-flex align-items-center">
              <i class="bi bi-geo-fill me-2"></i>Registrar Nuevo Sitio Turístico
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarSitio">
              <div class="row g-4">

                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Nombre del Sitio Turístico *</label>
                  <input v-model="formNuevo.nombre" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Cascada La Cuba" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado Inicial</label>
                  <select v-model="formNuevo.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option :value="true">Activo / Visible</option>
                    <option :value="false">Inactivo / Oculto</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Descripción *</label>
                  <textarea v-model="formNuevo.descripcion"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    rows="2" maxlength="255"
                    placeholder="Una breve reseña que atraiga al turista..." required></textarea>
                  <small class="text-muted">Máximo 255 caracteres.</small>
                </div>

                <div class="col-12">
                  <hr class="my-2" />
                  <h6 class="fw-bold mb-3">
                    <i class="bi bi-images me-2"></i>Galería de Imágenes <span class="text-danger">*</span>
                    <small class="text-muted fw-normal ms-1">(las 3 son obligatorias)</small>
                  </h6>
                  <div class="row g-3">
                    <div class="col-md-4" v-for="n in 3" :key="n">
                      <label class="form-label small fw-bold mb-1">
                        Imagen {{ n }} <span class="text-danger">*</span>
                      </label>
                      <img v-if="previews[n-1]" :src="previews[n-1]"
                        class="img-thumbnail rounded-3 w-100 mb-2"
                        style="height: 120px; object-fit: cover;" />
                      <ImagenInput
                        :key="`nuevo-${n}-${resetKeyNuevo}`"
                        :required="true"
                        @change="file => onImagenNueva(file, n-1)" />
                    </div>
                  </div>
                </div>

              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Publicar Sitio
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL VER DETALLE ===== -->
    <div class="modal fade" id="modalDetalleSitio" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">{{ detalle?.nombre }}</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="detalle">

            <!-- GALERÍA -->
            <div class="row g-2 mb-4">
              <div class="col-8">
                <img :src="detalle.imagenes?.[0] || '/assets/img/placeholder.png'"
                  class="rounded-3 w-100 shadow-sm"
                  style="height: 300px; object-fit: cover;" />
              </div>
              <div class="col-4 d-flex flex-column gap-2">
                <img v-if="detalle.imagenes?.[1]" :src="detalle.imagenes[1]"
                  class="rounded-3 w-100 shadow-sm flex-grow-1"
                  style="height: 146px; object-fit: cover;" />
                <img v-if="detalle.imagenes?.[2]" :src="detalle.imagenes[2]"
                  class="rounded-3 w-100 shadow-sm flex-grow-1"
                  style="height: 146px; object-fit: cover;" />
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <p class="text-muted mb-2">
                  <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                  <span class="fw-bold text-dark">{{ detalle.lugar }}</span>
                </p>
                <hr class="my-3 border-secondary-subtle" />
                <h6 class="fw-bold">Descripción</h6>
                <p class="text-secondary" style="white-space: pre-line;">{{ detalle.descripcion }}</p>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn btn-dark px-4 fw-bold rounded-3"
                data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEditarSitio" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-pencil-square me-2"></i>Editar Sitio Turístico
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-4">

                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Nombre del Sitio *</label>
                  <input v-model="formEditar.nombre" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado</label>
                  <select v-model="formEditar.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option :value="true">Activo / Visible</option>
                    <option :value="false">Inactivo / Oculto</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Descripción *</label>
                  <textarea v-model="formEditar.descripcion"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    rows="2" maxlength="255" required></textarea>
                </div>

                <div class="col-12">
                  <hr class="my-3" />
                  <h6 class="fw-bold mb-3">
                    <i class="bi bi-images me-2"></i>Gestión de Imágenes
                  </h6>
                  <p class="small text-muted mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Si no seleccionas archivos, se conservarán las imágenes actuales.
                  </p>
                  <div class="row g-3">
                    <div class="col-md-4 text-center" v-for="n in 3" :key="n">
                      <label class="form-label small fw-bold d-block">
                        {{ n === 1 ? 'Imagen Principal (1)' : `Imagen ${n}` }}
                      </label>
                      <img :src="previewsEditar[n-1] || formEditar.imagenes?.[n-1] || '/assets/img/placeholder.png'"
                        class="rounded-3 border shadow-sm w-100 mb-2"
                        style="height: 100px; object-fit: cover;" />
                      <ImagenInput @change="file => onImagenEditar(file, n-1)" />
                    </div>
                  </div>
                </div>

              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Actualizar Sitio
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
import { ADMIN } from '@/api/endpoints'
import ImagenInput from '@/components/ui/ImagenInput.vue'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'
import AdminPaginacion from '@/components/admin/AdminPaginacion.vue'

const toast = useToast()
const { confirmar, alertaError } = useConfirm()

const sitios       = ref([])
const cargando     = ref(true)
const guardando    = ref(false)
const busqueda     = ref('')
const filtroEstado = ref('')
const detalle      = ref(null)
const formEditar   = ref(null)
const paginaActual   = ref(1)
const totalPaginas   = ref(1)
const totalRegistros = ref(0)


const previews       = ref([null, null, null])
const previewsEditar = ref([null, null, null])
const archivosNuevo  = ref([null, null, null])
const archivosEditar = ref([null, null, null])
const resetKeyNuevo  = ref(0)

const formNuevo = ref({ nombre: '', descripcion: '', estado: true })

const totalGeneral   = computed(() => totalRegistros.value)
const totalActivos   = computed(() => sitios.value.filter(s => s.estado).length)
const totalInactivos = computed(() => sitios.value.filter(s => !s.estado).length)
const sitiosFiltrados = computed(() =>
  sitios.value.filter(s => {
    const okNombre = !busqueda.value || s.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
    const okEstado = filtroEstado.value === '' || String(s.estado ? '1' : '0') === filtroEstado.value
    return okNombre && okEstado
  })
)

onMounted(cargarSitios)
async function cargarSitios(pagina = 1) {
  cargando.value = true
  try {
    const { data } = await api.get(ADMIN.SITIOS, { params: { page: pagina } })
    const payload = data.data
    sitios.value         = payload?.sitios ?? (Array.isArray(payload) ? payload : [])
    paginaActual.value   = payload?.pagina_actual ?? 1
    totalPaginas.value   = payload?.total_paginas ?? 1
    totalRegistros.value = payload?.total ?? sitios.value.length
  } finally { cargando.value = false }
}

function onImagenNueva(file, index) {
  archivosNuevo.value[index] = file
  previews.value[index] = file ? URL.createObjectURL(file) : null
}

function onImagenEditar(file, index) {
  archivosEditar.value[index] = file
  previewsEditar.value[index] = file ? URL.createObjectURL(file) : null
}

async function toggleEstado(item) {
  const accion = item.estado ? 'desactivar' : 'activar'
  const ok = await confirmar({
    titulo: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} sitio turístico?`,
    texto: `"${item.nombre}" será ${item.estado ? 'ocultado del sitio público' : 'visible en el sitio público'}.`,
    textoBoton: `Sí, ${accion}`,
  })
  if (!ok) return
  try {
    await api.patch(ADMIN.SITIO_ESTADO(item.id), { estado: !item.estado })
    item.estado = !item.estado
    toast.exito(`Sitio ${item.estado ? 'activado' : 'desactivado'}.`)
  } catch {
    alertaError('No se pudo cambiar el estado del sitio.')
  }
}

function verDetalle(item) {
  detalle.value = item
  new Modal(document.getElementById('modalDetalleSitio')).show()
}

function abrirModalAgregar() {
  formNuevo.value = { nombre: '', descripcion: '', estado: true }
  previews.value = [null, null, null]
  archivosNuevo.value = [null, null, null]
  resetKeyNuevo.value++
  new Modal(document.getElementById('modalAgregarSitio')).show()
}

async function guardarSitio() {
  const faltantes = archivosNuevo.value.map((f, i) => !f ? `Imagen ${i + 1}` : null).filter(Boolean)
  if (faltantes.length) {
    alertaError(`Faltan: ${faltantes.join(', ')}.`, '¡Imágenes requeridas!')
    return
  }
  guardando.value = true
  try {
    const fd = new FormData()
    fd.append('nombre', formNuevo.value.nombre)
    fd.append('descripcion', formNuevo.value.descripcion)
    fd.append('estado', formNuevo.value.estado ? 1 : 0)
    fd.append('lugar_id', 1)
    archivosNuevo.value.forEach((file, i) => { if (file) fd.append(`url_imagen_${i + 1}`, file) })
    await api.post(ADMIN.SITIOS, fd,{
      headers:{
        'Content-Type': 'multipart/form-data'
      }
    })
    Modal.getInstance(document.getElementById('modalAgregarSitio')).hide()
    toast.exito('Sitio turístico registrado correctamente.')
    await cargarSitios()
  } catch (err) {
    const errors = err.response?.data?.errors
    const msg = errors ? Object.values(errors).flat().join(' · ') : (err.response?.data?.message ?? 'No se pudo guardar.')
    alertaError(msg)
  } finally { guardando.value = false }
}

function abrirModalEditar(item) {
  formEditar.value = { ...item }; previewsEditar.value = [null, null, null]; archivosEditar.value = [null, null, null]
  new Modal(document.getElementById('modalEditarSitio')).show()
}

async function guardarEdicion() {
  guardando.value = true
  try {
    const fd = new FormData()
    fd.append('nombre', formEditar.value.nombre)
    fd.append('descripcion', formEditar.value.descripcion)
    fd.append('estado', formEditar.value.estado ? 1 : 0)
    fd.append('lugar_id', formEditar.value.lugar?.id ?? 1)
    fd.append('_method', 'PUT')
    archivosEditar.value.forEach((file, i) => { if (file) fd.append(`url_imagen_${i + 1}`, file) })
    await api.post(ADMIN.SITIO(formEditar.value.id), fd, {
  headers: { 'Content-Type': 'multipart/form-data' }
})
    Modal.getInstance(document.getElementById('modalEditarSitio')).hide()
    toast.exito('Sitio turístico actualizado correctamente.')
    await cargarSitios()
  } catch (err) {
    const errors = err.response?.data?.errors
    const msg = errors ? Object.values(errors).flat().join(' · ') : (err.response?.data?.message ?? 'No se pudo actualizar.')
    alertaError(msg)
  } finally { guardando.value = false }
}

</script>
