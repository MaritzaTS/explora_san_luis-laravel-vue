<template>
  <div>

    <!-- TARJETAS ESTADÍSTICAS -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-dark border-4">
          <h5 class="fw-bold mb-3 text-muted">Total Eventos</h5>
          <h1 class="display-4 fw-bold mb-0">{{ totalEventos }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-success border-4">
          <h5 class="fw-bold mb-3 text-success">Activos</h5>
          <h1 class="display-4 fw-bold mb-0 text-success">{{ activos }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-primary border-4">
          <h5 class="fw-bold mb-3 text-primary">Inactivos</h5>
          <h1 class="display-4 fw-bold mb-0 text-primary">{{ inactivos }}</h1>
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
          </div>
          <div class="col-md-6 text-end">
            <button class="btn btn-dark border-0 rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-calendar-event me-1"></i> Crear Evento
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
                <th>Evento</th>
                <th>Ubicación (Lugar)</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="eventos.length === 0">
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                  No hay eventos registrados.
                </td>
              </tr>
              <tr v-for="ev in eventos" :key="ev.id">
                <td>
                  <div class="fw-bold text-dark">{{ ev.nombre }}</div>
                  <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">
                    {{ ev.descripcion }}
                  </small>
                </td>
                <td>
                  <span class="badge btn-outline-dark border text-dark fw-medium">
                    {{ ev.lugar?.nombre }}
                  </span>
                </td>
                <td>{{ formatFecha(ev.fecha_inicio) }}</td>
                <td>{{ ev.fecha_fin ? formatFecha(ev.fecha_fin) : '---' }}</td>
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
                <td class="text-end d-flex justify-content-end gap-2">
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
                  <label class="form-label fw-bold">Nombre del Evento</label>
                  <input v-model="formNuevo.nombre" type="text"
                    class="form-control"
                    placeholder="Ej: Fiestas del Campesino" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Inicio</label>
                  <input v-model="formNuevo.fecha_inicio" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Finalización</label>
                  <input v-model="formNuevo.fecha_fin" type="date" class="form-control" />
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Descripción</label>
                  <textarea v-model="formNuevo.descripcion" class="form-control" rows="3"></textarea>
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
                  <label class="form-label fw-bold">Nombre del Evento</label>
                  <input v-model="formEditar.nombre" type="text" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Inicio</label>
                  <input v-model="formEditar.fecha_inicio" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Fecha de Finalización</label>
                  <input v-model="formEditar.fecha_fin" type="date" class="form-control" />
                </div>
                <div class="col-12">
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

const eventos   = ref([])
const cargando  = ref(true)
const guardando = ref(false)
const formEditar = ref(null)
const alerta    = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

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

function formatFecha(fecha) {
  if (!fecha) return '---'
  const [y, m, d] = fecha.split('-')
  return `${d}/${m}/${y}`
}

function abrirModalAgregar() {
  formNuevo.value = { nombre: '', fecha_inicio: '', fecha_fin: '', descripcion: '', estado: true }
  new Modal(document.getElementById('modalEventoAgregar')).show()
}

async function guardarEvento() {
  guardando.value = true
  try {
    await api.post(ADMIN.EVENTOS, { ...formNuevo.value, lugar_id: 1 })
    Modal.getInstance(document.getElementById('modalEventoAgregar')).hide()
    mostrarAlerta('success', '¡Guardado!', 'Evento creado.')
    await cargarEventos()
  } catch (err) {
    mostrarAlerta('danger', '¡Error!', err.response?.data?.message ?? 'No se pudo guardar.')
  } finally { guardando.value = false }
}

function abrirModalEditar(ev) {
  formEditar.value = { ...ev, lugar_id: ev.lugar?.id ?? 1 }
  new Modal(document.getElementById('modalEventoEditar')).show()
}

async function guardarEdicion() {
  guardando.value = true
  try {
    const { nombre, fecha_inicio, fecha_fin, descripcion, estado, lugar_id } = formEditar.value
    await api.put(ADMIN.EVENTO(formEditar.value.id), { nombre, fecha_inicio, fecha_fin, descripcion, estado, lugar_id })
    Modal.getInstance(document.getElementById('modalEventoEditar')).hide()
    mostrarAlerta('success', '¡Actualizado!', 'Evento editado.')
    await cargarEventos()
  } catch (err) {
    mostrarAlerta('danger', '¡Error!', err.response?.data?.message ?? 'No se pudo actualizar.')
  } finally { guardando.value = false }
}

function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>