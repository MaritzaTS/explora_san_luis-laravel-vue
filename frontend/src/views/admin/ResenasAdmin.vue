<template>
  <div>

    <!-- ALERTA -->
    <div v-if="alerta.visible"
      :class="`alert alert-${alerta.tipo} alert-dismissible fade show border-0 shadow-sm rounded-4`"
      role="alert">
      <strong>{{ alerta.titulo }}</strong> {{ alerta.mensaje }}
      <button type="button" class="btn-close" @click="alerta.visible = false"></button>
    </div>

    <!-- TARJETAS ESTADÍSTICAS -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3">Activas</h5>
          <h1 class="display-4 fw-bold mb-0 text-success">{{ totalActivas }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3">Inactivas</h5>
          <h1 class="display-4 fw-bold mb-0 text-danger">{{ totalInactivas }}</h1>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
          <h5 class="fw-bold mb-3">Total</h5>
          <h1 class="display-4 fw-bold mb-0">{{ totalGeneral }}</h1>
        </div>
      </div>
    </div>

    <!-- LISTA DE RESEÑAS -->
    <div class="card border border-secondary-subtle rounded-4 bg-white shadow-none">
      <div class="card-body p-4">

        <!-- FILTROS -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-4">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white px-2">
              <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
              <input type="text" v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar por comentario o autor..." />
            </div>
          </div>
          <div class="col-md-2">
            <select v-model="filtroEstado"
              class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos</option>
              <option value="1">Activas</option>
              <option value="0">Inactivas</option>
            </select>
          </div>
          <div class="col-md-6 text-end">
            <button class="btn btn-dark border-0 rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-plus-lg me-1"></i> Nueva Reseña
            </button>
          </div>
        </div>

        <!-- ITEMS -->
        <div class="d-flex flex-column gap-3">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando reseñas...</p>
          </div>

          <div v-else-if="resenasFiltradas.length === 0" class="text-center p-5 text-muted">
            <i class="bi bi-chat-left-dots fs-1 d-block mb-3"></i>
            <p>Aún no hay reseñas registradas.</p>
          </div>

          <div v-else v-for="item in resenasFiltradas" :key="item.id_resena"
            class="card border border-secondary-subtle rounded-4 shadow-none">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-start mb-2">

                <!-- AUTOR Y FECHA -->
                <div class="d-flex align-items-center gap-2">
                  <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill text-white"></i>
                  </div>
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <h6 class="fw-bold mb-0">{{ item.autor }}</h6>
                      <span v-if="item.estado == 1"
                        class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fw-bold"
                        style="font-size: 0.6rem;">VISIBLE</span>
                      <span v-else
                        class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fw-bold"
                        style="font-size: 0.6rem;">OCULTO</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">
                      <i class="bi bi-calendar3 me-1"></i> {{ formatFecha(item.fecha) }}
                    </small>
                  </div>
                </div>

                <!-- BOTONES -->
                <div class="d-flex gap-2">
                  <button
                    :class="item.estado == 1
                      ? 'btn btn-white border border-secondary-subtle rounded-3 btn-sm px-3 shadow-none fw-semibold d-flex align-items-center gap-1 text-danger'
                      : 'btn btn-white border border-secondary-subtle rounded-3 btn-sm px-3 shadow-none fw-semibold d-flex align-items-center gap-1 text-success'"
                    @click="toggleEstado(item)">
                    <i :class="item.estado == 1 ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    {{ item.estado == 1 ? 'Ocultar' : 'Mostrar' }}
                  </button>
                  <button
                    class="btn btn-white border border-secondary-subtle rounded-3 btn-sm px-3 shadow-none fw-semibold d-flex align-items-center gap-1"
                    @click="abrirModalEditar(item)">
                    <i class="bi bi-pencil-square"></i> Editar
                  </button>
                  <button
                    class="btn btn-white border border-secondary-subtle rounded-3 btn-sm px-3 shadow-none fw-semibold d-flex align-items-center gap-1 text-danger"
                    @click="eliminarResena(item)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>

              <!-- COMENTARIO -->
              <p class="text-dark mb-0 mt-2"
                style="font-size: 0.9rem; line-height: 1.5; border-left: 3px solid #eee; padding-left: 15px;">
                "{{ item.comentario }}"
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalAgregarResena" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold d-flex align-items-center">
              <i class="bi bi-chat-left-quote fs-4 me-2"></i> Nueva Reseña
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarResena">
              <div class="row g-4">
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Usuario *</label>
                  <select v-model="formNueva.id_usuario"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none" required>
                    <option value="" disabled>Seleccione un usuario</option>
                    <option v-for="u in usuarios" :key="u.id" :value="u.id">
                      {{ u.nombre }}
                    </option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Comentario *</label>
                  <textarea v-model="formNueva.comentario" rows="4"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Escribe la reseña..." required></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado *</label>
                  <select v-model="formNueva.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option value="1">Visible</option>
                    <option value="0">Oculta</option>
                  </select>
                </div>
              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none">
                  Publicar Reseña
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEditarResena" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-pencil-fill me-2"></i> Editar Reseña
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-4">
                <div class="col-12">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Autor</label>
                  <p class="border-bottom pb-2 fw-medium text-dark mb-0">
                    <i class="bi bi-person-fill me-1"></i>{{ formEditar.autor }}
                  </p>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Comentario *</label>
                  <textarea v-model="formEditar.comentario" rows="4"
                    class="form-control border-0 bg-light rounded-3 shadow-none" required></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado</label>
                  <select v-model="formEditar.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option value="1">🟢 Visible</option>
                    <option value="0">🔴 Oculta</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer border-0 pt-4 d-flex justify-content-center">
                <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-5 rounded-3 fw-bold shadow-sm">
                  <i class="bi bi-save me-2"></i>Actualizar Reseña
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

// ── Estado ────────────────────────────────────────────────
const resenas      = ref([])
const usuarios     = ref([])
const cargando     = ref(true)
const busqueda     = ref('')
const filtroEstado = ref('')
const formEditar   = ref(null)
const alerta       = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

const formNueva = ref({
  id_usuario: '', comentario: '', estado: 1
})

// ── Computed ──────────────────────────────────────────────
const totalGeneral   = computed(() => resenas.value.length)
const totalActivas   = computed(() => resenas.value.filter(r => r.estado == 1).length)
const totalInactivas = computed(() => resenas.value.filter(r => r.estado == 0).length)

const resenasFiltradas = computed(() =>
  resenas.value.filter(r => {
    const q = busqueda.value.toLowerCase()
    const coincideBusqueda = r.comentario.toLowerCase().includes(q) || r.autor.toLowerCase().includes(q)
    const coincideEstado   = filtroEstado.value === '' || r.estado == filtroEstado.value
    return coincideBusqueda && coincideEstado
  })
)

// ── Carga inicial ─────────────────────────────────────────
onMounted(async () => {
  await cargarResenas()
  await cargarUsuarios()
})

async function cargarResenas() {
  try {
    cargando.value = true
    const { data } = await axios.get('/api/admin/resenas')
    resenas.value = data
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudieron cargar las reseñas.')
  } finally {
    cargando.value = false
  }
}

async function cargarUsuarios() {
  try {
    const { data } = await axios.get('/api/admin/usuarios')
    usuarios.value = data
  } catch {}
}

// ── Helpers ───────────────────────────────────────────────
function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  return `${d.getDate().toString().padStart(2,'0')}/${(d.getMonth()+1).toString().padStart(2,'0')}/${d.getFullYear()}`
}

// ── Toggle estado ─────────────────────────────────────────
async function toggleEstado(item) {
  const nuevoEstado = item.estado == 1 ? 0 : 1
  const accion = nuevoEstado == 1 ? 'mostrar' : 'ocultar'
  if (!confirm(`¿Deseas ${accion} esta reseña?`)) return
  try {
    await axios.patch(`/api/admin/resenas/${item.id_resena}/estado`, { estado: nuevoEstado })
    item.estado = nuevoEstado
    mostrarAlerta('success', '¡Listo!', 'Estado actualizado correctamente.')
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo cambiar el estado.')
  }
}

// ── Agregar ───────────────────────────────────────────────
function abrirModalAgregar() {
  formNueva.value = { id_usuario: '', comentario: '', estado: 1 }
  new bootstrap.Modal(document.getElementById('modalAgregarResena')).show()
}

async function guardarResena() {
  try {
    await axios.post('/api/admin/resenas', formNueva.value)
    bootstrap.Modal.getInstance(document.getElementById('modalAgregarResena')).hide()
    mostrarAlerta('success', '¡Publicada!', 'Reseña creada correctamente.')
    await cargarResenas()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo crear la reseña.')
  }
}

// ── Editar ────────────────────────────────────────────────
function abrirModalEditar(item) {
  formEditar.value = { ...item }
  new bootstrap.Modal(document.getElementById('modalEditarResena')).show()
}

async function guardarEdicion() {
  try {
    await axios.put(`/api/admin/resenas/${formEditar.value.id_resena}`, formEditar.value)
    bootstrap.Modal.getInstance(document.getElementById('modalEditarResena')).hide()
    mostrarAlerta('success', '¡Actualizada!', 'Reseña editada correctamente.')
    await cargarResenas()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo actualizar la reseña.')
  }
}

// ── Eliminar ──────────────────────────────────────────────
async function eliminarResena(item) {
  if (!confirm(`¿Estás seguro de eliminar esta reseña de "${item.autor}"?`)) return
  try {
    await axios.delete(`/api/admin/resenas/${item.id_resena}`)
    mostrarAlerta('success', '¡Eliminada!', 'Reseña eliminada correctamente.')
    await cargarResenas()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo eliminar la reseña.')
  }
}

// ── Alerta ────────────────────────────────────────────────
function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>