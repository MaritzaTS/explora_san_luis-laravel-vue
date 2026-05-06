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
              <tr v-for="ev in eventos" :key="ev.id_evento">
                <td>
                  <div class="fw-bold text-dark">{{ ev.nombre }}</div>
                  <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">
                    {{ ev.descripcion }}
                  </small>
                </td>
                <td>
                  <span class="badge btn-outline-dark border text-dark fw-medium">
                    {{ ev.nombre_lugar }}
                  </span>
                </td>
                <td>{{ formatFecha(ev.fecha_inicio) }}</td>
                <td>{{ ev.fecha_fin ? formatFecha(ev.fecha_fin) : '---' }}</td>
                <td>
                  <span v-if="ev.estado == 1"
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
                  <button class="btn btn-sm btn-light border text-danger shadow-none"
                    @click="eliminarEvento(ev)">
                    <i class="bi bi-trash"></i>
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
                  <label class="form-label fw-bold">Lugar del Evento</label>
                  <select v-model="formNuevo.id_lugar" class="form-select" required>
                    <option value="" disabled>Seleccione un lugar de la lista...</option>
                    <option v-for="lug in lugares" :key="lug.id_lugar" :value="lug.id_lugar">
                      {{ lug.nombre }}
                    </option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Descripción</label>
                  <textarea v-model="formNuevo.descripcion" class="form-control" rows="3"></textarea>
                </div>
              </div>
              <div class="text-end mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4">Guardar Evento</button>
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
                  <label class="form-label fw-bold">Lugar del Evento</label>
                  <select v-model="formEditar.id_lugar" class="form-select" required>
                    <option v-for="lug in lugares" :key="lug.id_lugar" :value="lug.id_lugar">
                      {{ lug.nombre }}
                    </option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Estado</label>
                  <select v-model="formEditar.estado" class="form-select">
                    <option value="1">🟢 Activo</option>
                    <option value="0">🔴 Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Descripción</label>
                  <textarea v-model="formEditar.descripcion" class="form-control" rows="3"></textarea>
                </div>
              </div>
              <div class="text-end mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4">
                  <i class="bi bi-save me-1"></i>Actualizar Evento
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
import axios from 'axios'

// ── Estado ───────────────────────────────────────────────
const eventos  = ref([])
const lugares  = ref([])
const cargando = ref(true)
const detalle  = ref(null)
const formEditar = ref(null)
const alerta   = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

const formNuevo = ref({
  nombre: '', fecha_inicio: '', fecha_fin: '', id_lugar: '', descripcion: '', estado: 1
})

// ── Computed ─────────────────────────────────────────────
const totalEventos = computed(() => eventos.value.length)
const activos      = computed(() => eventos.value.filter(e => e.estado == 1).length)
const inactivos    = computed(() => eventos.value.filter(e => e.estado == 0).length)

// ── Carga inicial ─────────────────────────────────────────
onMounted(async () => {
  await cargarEventos()
  await cargarLugares()
})

async function cargarEventos() {
  try {
    cargando.value = true
    const { data } = await axios.get('/api/admin/eventos')
    eventos.value = data
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudieron cargar los eventos.')
  } finally {
    cargando.value = false
  }
}

async function cargarLugares() {
  try {
    const { data } = await axios.get('/api/admin/lugares')
    lugares.value = data
  } catch {}
}

// ── Formato fecha ─────────────────────────────────────────
function formatFecha(fecha) {
  if (!fecha) return '---'
  const [y, m, d] = fecha.split('-')
  return `${d}/${m}/${y}`
}

// ── Agregar ───────────────────────────────────────────────
function abrirModalAgregar() {
  formNuevo.value = { nombre: '', fecha_inicio: '', fecha_fin: '', id_lugar: '', descripcion: '', estado: 1 }
  new bootstrap.Modal(document.getElementById('modalEventoAgregar')).show()
}

async function guardarEvento() {
  try {
    await axios.post('/api/admin/eventos', formNuevo.value)
    bootstrap.Modal.getInstance(document.getElementById('modalEventoAgregar')).hide()
    mostrarAlerta('success', '¡Guardado!', 'Evento creado correctamente.')
    await cargarEventos()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo guardar el evento.')
  }
}

// ── Editar ────────────────────────────────────────────────
function abrirModalEditar(ev) {
  formEditar.value = { ...ev }
  new bootstrap.Modal(document.getElementById('modalEventoEditar')).show()
}

async function guardarEdicion() {
  try {
    await axios.put(`/api/admin/eventos/${formEditar.value.id_evento}`, formEditar.value)
    bootstrap.Modal.getInstance(document.getElementById('modalEventoEditar')).hide()
    mostrarAlerta('success', '¡Actualizado!', 'Evento editado correctamente.')
    await cargarEventos()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo actualizar el evento.')
  }
}

// ── Eliminar ──────────────────────────────────────────────
async function eliminarEvento(ev) {
  if (!confirm(`¿Estás seguro de eliminar el evento "${ev.nombre}"?`)) return
  try {
    await axios.delete(`/api/admin/eventos/${ev.id_evento}`)
    mostrarAlerta('success', '¡Eliminado!', 'Evento eliminado correctamente.')
    await cargarEventos()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo eliminar el evento.')
  }
}

// ── Alerta ────────────────────────────────────────────────
function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>