<template>
  <div>

    <!-- TARJETAS ESTADÍSTICAS -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-dark border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-muted small text-uppercase mb-0">Total Eventos</h6>
            <i class="bi bi-calendar-event-fill fs-4 text-dark"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-dark">{{ totalEventos }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-success border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-success small text-uppercase mb-0">Activos</h6>
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-success">{{ activos }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-secondary border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-secondary small text-uppercase mb-0">Inactivos</h6>
            <i class="bi bi-x-circle-fill fs-4 text-secondary"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-secondary">{{ inactivos }}</h1>
        </div>
      </div>
    </div>

    <!-- ALERTA -->
    <div v-if="alerta.visible"
      :class="`alert alert-${alerta.tipo} alert-dismissible fade show border-0 shadow-sm rounded-4`"
      role="alert">
      <strong>{{ alerta.titulo }}</strong> {{ alerta.mensaje }}
      <button type="button" class="btn-close" @click="alerta.visible = false"></button>
    </div>

    <!-- TABLA DE EVENTOS -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <!-- ENCABEZADO -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6">
            <h4 class="fw-bold mb-0">Gestión de Eventos y Festividades</h4>
            <p class="text-muted small mb-0">Administra los eventos registrados en el sistema</p>
          </div>
          <div class="col-md-6 text-end">
            <button class="btn btn-dark border-0 rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-calendar-plus me-1"></i> Crear Evento
            </button>
          </div>
        </div>

        <!-- TABLA -->
        <div class="table-responsive">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando eventos...</p>
          </div>

          <table v-else class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4 py-3 text-muted small fw-semibold text-uppercase">Evento</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Fecha Inicio</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Fecha Fin</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Estado</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase text-end pe-4">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="eventos.length === 0">
                <td colspan="5" class="text-center text-muted py-5">
                  <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                  No hay eventos registrados.
                </td>
              </tr>
              <tr v-for="ev in eventos" :key="ev.id">
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-3">
                    <img v-if="ev.url_poster"
                      :src="ev.url_poster"
                      class="rounded-3 border shadow-sm flex-shrink-0"
                      style="width:54px;height:54px;object-fit:cover;" />
                    <div v-else
                      class="rounded-3 bg-light border d-flex align-items-center justify-content-center flex-shrink-0"
                      style="width:54px;height:54px;">
                      <i class="bi bi-image text-muted"></i>
                    </div>
                    <div>
                      <div class="fw-semibold">{{ ev.nombre }}</div>
                      <small class="text-muted text-truncate d-inline-block" style="max-width:200px;">
                        {{ ev.descripcion }}
                      </small>
                    </div>
                  </div>
                </td>
                <td class="text-muted small">{{ ev.fecha_inicio }}</td>
                <td class="text-muted small">{{ ev.fecha_fin ?? '---' }}</td>
                <td>
                  <span v-if="ev.estado"
                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                    Activo
                  </span>
                  <span v-else
                    class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">
                    Inactivo
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light border shadow-none"
                    @click="abrirModalEditar(ev)">
                    <i class="bi bi-pencil"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalEventoAgregar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-0">
            <h5 class="modal-title fw-bold">Nuevo Evento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarEvento">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Nombre del Evento *</label>
                  <input v-model="formNuevo.nombre" type="text"
                    class="form-control" placeholder="Ej: Fiestas del Campesino" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Inicio *</label>
                  <input v-model="formNuevo.fecha_inicio" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Finalización *</label>
                  <input v-model="formNuevo.fecha_fin" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Estado</label>
                  <select v-model="formNuevo.estado" class="form-select">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Descripción</label>
                  <textarea v-model="formNuevo.descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">
                    Imagen Poster <span class="text-muted fw-normal small">(JPG, PNG, WEBP · máx 5MB)</span>
                  </label>
                  <ImagenInput
                    :key="`poster-nuevo-${resetKeyNuevo}`"
                    @change="posterNuevoFile = $event" />
                </div>
              </div>
              <div class="text-end mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4" :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Guardar Evento
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEventoEditar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-0">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-pencil-fill me-2"></i>Editar Evento
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Nombre del Evento *</label>
                  <input v-model="formEditar.nombre" type="text" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Inicio *</label>
                  <input v-model="formEditar.fecha_inicio" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Finalización *</label>
                  <input v-model="formEditar.fecha_fin" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Estado</label>
                  <select v-model="formEditar.estado" class="form-select">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Descripción</label>
                  <textarea v-model="formEditar.descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Imagen Poster</label>
                  <div v-if="formEditar.url_poster" class="mb-2">
                    <img :src="formEditar.url_poster"
                      class="rounded-3 border shadow-sm"
                      style="height:80px;object-fit:cover;" />
                    <small class="text-muted d-block mt-1">Imagen actual — selecciona una nueva para reemplazarla.</small>
                  </div>
                  <ImagenInput
                    :key="`poster-editar-${formEditar.id}`"
                    @change="posterEditarFile = $event" />
                </div>
              </div>
              <div class="text-end mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4" :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  <i v-else class="bi bi-save me-1"></i>Actualizar Evento
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

const eventos        = ref([])
const cargando       = ref(true)
const guardando      = ref(false)
const formEditar     = ref(null)
const posterNuevoFile  = ref(null)
const posterEditarFile = ref(null)
const resetKeyNuevo  = ref(0)
const alerta         = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

const formNuevo = ref({ nombre: '', fecha_inicio: '', fecha_fin: '', descripcion: '', estado: true })

const totalEventos = computed(() => eventos.value.length)
const activos      = computed(() => eventos.value.filter(e => e.estado).length)
const inactivos    = computed(() => eventos.value.filter(e => !e.estado).length)

onMounted(cargarEventos)

async function cargarEventos() {
  cargando.value = true
  try {
    const { data } = await api.get(ADMIN.EVENTOS)
    const payload = data.data
    eventos.value = payload?.eventos ?? (Array.isArray(payload) ? payload : [])
  } finally { cargando.value = false }
}

function formatFechaInput(fecha) {
  if (!fecha) return ''
  // fecha viene como "dd/mm/yyyy" del backend, necesitamos "yyyy-mm-dd" para el input date
  const partes = fecha.split('/')
  if (partes.length === 3) return `${partes[2]}-${partes[1]}-${partes[0]}`
  return fecha
}

function abrirModalAgregar() {
  formNuevo.value = { nombre: '', fecha_inicio: '', fecha_fin: '', descripcion: '', estado: true }
  posterNuevoFile.value = null
  resetKeyNuevo.value++
  new Modal(document.getElementById('modalEventoAgregar')).show()
}

async function guardarEvento() {
  guardando.value = true
  try {
    const fd = new FormData()
    fd.append('nombre',       formNuevo.value.nombre)
    fd.append('descripcion',  formNuevo.value.descripcion ?? '')
    fd.append('fecha_inicio', formNuevo.value.fecha_inicio)
    fd.append('fecha_fin',    formNuevo.value.fecha_fin)
    fd.append('estado',       formNuevo.value.estado ? 1 : 0)
    fd.append('lugar_id',     1)
    if (posterNuevoFile.value) fd.append('poster', posterNuevoFile.value)

    await api.post(ADMIN.EVENTOS, fd)
    Modal.getInstance(document.getElementById('modalEventoAgregar')).hide()
    mostrarAlerta('success', '¡Guardado!', 'Evento creado.')
    await cargarEventos()
  } catch (err) {
    const errors = err.response?.data?.errors
    const msg = errors ? Object.values(errors).flat().join(' · ') : (err.response?.data?.message ?? 'No se pudo guardar.')
    mostrarAlerta('danger', '¡Error!', msg)
  } finally { guardando.value = false }
}

function abrirModalEditar(ev) {
  formEditar.value = {
    ...ev,
    fecha_inicio: formatFechaInput(ev.fecha_inicio),
    fecha_fin:    formatFechaInput(ev.fecha_fin),
  }
  posterEditarFile.value = null
  new Modal(document.getElementById('modalEventoEditar')).show()
}

async function guardarEdicion() {
  guardando.value = true
  try {
    const fd = new FormData()
    fd.append('nombre',       formEditar.value.nombre)
    fd.append('descripcion',  formEditar.value.descripcion ?? '')
    fd.append('fecha_inicio', formEditar.value.fecha_inicio)
    fd.append('fecha_fin',    formEditar.value.fecha_fin)
    fd.append('estado',       formEditar.value.estado ? 1 : 0)
    fd.append('lugar_id',     1)
    fd.append('_method',      'PUT')
    if (posterEditarFile.value) fd.append('poster', posterEditarFile.value)

    await api.post(ADMIN.EVENTO(formEditar.value.id), fd)
    Modal.getInstance(document.getElementById('modalEventoEditar')).hide()
    mostrarAlerta('success', '¡Actualizado!', 'Evento editado.')
    await cargarEventos()
  } catch (err) {
    const errors = err.response?.data?.errors
    const msg = errors ? Object.values(errors).flat().join(' · ') : (err.response?.data?.message ?? 'No se pudo actualizar.')
    mostrarAlerta('danger', '¡Error!', msg)
  } finally { guardando.value = false }
}

function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>
