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
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-dark border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-muted small text-uppercase mb-0">Total Usuarios</h6>
            <i class="bi bi-people-fill fs-4 text-dark"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-dark">{{ totalUsuarios }}</h1>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-success border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-success small text-uppercase mb-0">Activos</h6>
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-success">{{ activos }}</h1>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-danger border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-danger small text-uppercase mb-0">Inactivos</h6>
            <i class="bi bi-x-circle-fill fs-4 text-danger"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-danger">{{ inactivos }}</h1>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white border-start border-primary border-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-primary small text-uppercase mb-0">Verificados</h6>
            <i class="bi bi-patch-check-fill fs-4 text-primary"></i>
          </div>
          <h1 class="display-5 fw-bold mb-0 text-primary">{{ verificados }}</h1>
        </div>
      </div>
    </div>

    <!-- TABLA DE USUARIOS -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <!-- ENCABEZADO Y FILTROS -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-4">
            <h4 class="fw-bold mb-0">Gestión de Usuarios</h4>
            <p class="text-muted small mb-0">Administra los usuarios registrados en el sistema</p>
          </div>
          <div class="col-md-3">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar por nombre o correo" />
            </div>
          </div>
          <div class="col-md-2">
            <select v-model="filtroRol"
              class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos los roles</option>
              <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                {{ capitalizar(rol.nombre) }}
              </option>
            </select>
          </div>
          <div class="col-md-3 text-end">
            <button class="btn btn-dark border-0 rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
            </button>
          </div>
        </div>

        <!-- TABLA -->
        <div class="table-responsive">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando usuarios...</p>
          </div>

          <table v-else class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th class="ps-4 py-3 text-muted small fw-semibold text-uppercase">#</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Usuario</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Correo</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Rol</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Verificado</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Estado</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase">Registro</th>
                <th class="py-3 text-muted small fw-semibold text-uppercase text-end pe-4">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="usuariosFiltrados.length === 0">
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="bi bi-people fs-1 d-block mb-3"></i>
                  No se encontraron usuarios.
                </td>
              </tr>
              <tr v-for="user in usuariosFiltrados" :key="user.id">
                <td class="ps-4 text-muted small">{{ user.id }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center"
                      style="width: 38px; height: 38px;">
                      <i class="bi bi-person-fill text-dark"></i>
                    </div>
                    <span class="fw-semibold">{{ user.nombre }}</span>
                  </div>
                </td>
                <td class="text-muted small">{{ user.email }}</td>
                <td>
                  <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle rounded-pill px-3 py-2">
                    {{ obtenerNombreRol(user.rol_id) }}
                  </span>
                </td>
                <td>
                  <span v-if="user.verificado == 1"
                    class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-patch-check-fill me-1"></i>Sí
                  </span>
                  <span v-else
                    class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="bi bi-exclamation-circle me-1"></i>Pendiente
                  </span>
                </td>
                <td>
                  <span v-if="user.estado == 1"
                    class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    Activo
                  </span>
                  <span v-else
                    class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                    Inactivo
                  </span>
                </td>
                <td class="text-muted small">{{ formatFecha(user.created_at) }}</td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-sm btn-light border shadow-none"
                      title="Ver detalle"
                      @click="verDetalle(user)">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-light border shadow-none"
                      title="Editar"
                      @click="abrirModalEditar(user)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button
                      :class="user.estado == 1
                        ? 'btn btn-sm btn-light border text-danger shadow-none'
                        : 'btn btn-sm btn-light border text-success shadow-none'"
                      :title="user.estado == 1 ? 'Desactivar' : 'Activar'"
                      @click="toggleEstado(user)">
                      <i :class="user.estado == 1 ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold d-flex align-items-center">
              <i class="bi bi-person-plus fs-4 me-2"></i> Nuevo Usuario
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarUsuario">
              <div class="row g-4">
                <div class="col-md-12">
                  <label class="form-label fw-bold mb-0">Nombre Completo *</label>
                  <input v-model="formNuevo.nombre" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Juan Pérez García" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Correo Electrónico *</label>
                  <input v-model="formNuevo.email" type="email"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="usuario@ejemplo.com" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Contraseña *</label>
                  <input v-model="formNuevo.contrasena" type="password"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Mínimo 8 caracteres" required minlength="8" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Rol *</label>
                  <select v-model="formNuevo.rol_id"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none" required>
                    <option value="" disabled>Seleccione un rol</option>
                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                      {{ capitalizar(rol.nombre) }}
                    </option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado Inicial *</label>
                  <select v-model="formNuevo.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input v-model="formNuevo.verificado" type="checkbox"
                      class="form-check-input"
                      id="checkVerificado"
                      :true-value="1" :false-value="0" />
                    <label class="form-check-label fw-semibold" for="checkVerificado">
                      Marcar correo como verificado (omitir verificación)
                    </label>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none">
                  Crear Usuario
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL VER DETALLE ===== -->
    <div class="modal fade" id="modalDetalleUsuario" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Detalle del Usuario</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="detalle">
            <div class="row g-4 align-items-start">
              <div class="col-md-4 text-center">
                <div class="p-4 rounded-4 border bg-white shadow-sm mb-3">
                  <div class="rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 100px; height: 100px;">
                    <i class="bi bi-person-fill text-dark" style="font-size: 50px;"></i>
                  </div>
                  <h6 class="fw-bold mb-0">{{ detalle.nombre }}</h6>
                  <p class="text-muted small mb-0">ID #{{ detalle.id }}</p>
                </div>
                <div class="p-3 rounded-4 border bg-light">
                  <span class="d-block small text-muted mb-1">Estado actual</span>
                  <span :class="detalle.estado == 1
                    ? 'badge bg-success rounded-pill px-3 py-2 w-100'
                    : 'badge bg-danger rounded-pill px-3 py-2 w-100'">
                    {{ detalle.estado == 1 ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
              </div>
              <div class="col-md-8">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Correo Electrónico</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      <i class="bi bi-envelope me-1"></i>{{ detalle.email }}
                    </p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Rol</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ obtenerNombreRol(detalle.rol_id) }}
                    </p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Verificado</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ detalle.verificado == 1 ? 'Sí ✓' : 'Pendiente' }}
                    </p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Registrado con Google</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ detalle.id_google ? 'Sí' : 'No' }}
                    </p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Fecha de Registro</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ formatFecha(detalle.created_at) }}
                    </p>
                  </div>
                  <div class="col-12" v-if="detalle.updated_at">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Última actualización</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ formatFecha(detalle.updated_at) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0 d-flex justify-content-center pb-4">
            <button type="button" class="btn btn-light border px-5 rounded-3 fw-bold"
              data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-pencil-fill me-2"></i> Editar Usuario
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-4">
                <div class="col-12">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Nombre Completo</label>
                  <input v-model="formEditar.nombre" type="text"
                    class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fw-bold shadow-none" required />
                </div>
                <div class="col-md-6">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Correo Electrónico</label>
                  <input v-model="formEditar.email" type="email"
                    class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                </div>
                <div class="col-md-6">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Rol</label>
                  <select v-model="formEditar.rol_id"
                    class="form-select border-0 border-bottom rounded-0 px-0 shadow-none small" required>
                    <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                      {{ capitalizar(rol.nombre) }}
                    </option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Estado</label>
                  <select v-model="formEditar.estado"
                    class="form-select border-0 border-bottom rounded-0 px-0 shadow-none small">
                    <option value="1">🟢 Activo</option>
                    <option value="0">🔴 Inactivo</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Verificación</label>
                  <select v-model="formEditar.verificado"
                    class="form-select border-0 border-bottom rounded-0 px-0 shadow-none small">
                    <option value="1">Verificado</option>
                    <option value="0">Pendiente</option>
                  </select>
                </div>
                <div class="col-12">
                  <hr class="my-2">
                  <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                    Cambiar Contraseña (opcional)
                  </label>
                  <input v-model="formEditar.contrasena" type="password"
                    class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small"
                    placeholder="Dejar vacío para mantener la actual" />
                  <small class="text-muted">Solo completa este campo si deseas cambiar la contraseña.</small>
                </div>
              </div>
              <div class="modal-footer border-0 pt-4 d-flex justify-content-center">
                <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-5 rounded-3 fw-bold shadow-sm">
                  <i class="bi bi-save me-2"></i>Actualizar Usuario
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
const usuarios   = ref([])
const roles      = ref([])
const cargando   = ref(true)
const busqueda   = ref('')
const filtroRol  = ref('')
const detalle    = ref(null)
const formEditar = ref(null)
const alerta     = ref({ visible: false, tipo: 'success', titulo: '', mensaje: '' })

const formNuevo = ref({
  nombre: '', email: '', contrasena: '', rol_id: '', estado: 1, verificado: 0
})

// ── Computed ──────────────────────────────────────────────
const totalUsuarios = computed(() => usuarios.value.length)
const activos       = computed(() => usuarios.value.filter(u => u.estado == 1).length)
const inactivos     = computed(() => usuarios.value.filter(u => u.estado == 0).length)
const verificados   = computed(() => usuarios.value.filter(u => u.verificado == 1).length)

const usuariosFiltrados = computed(() =>
  usuarios.value.filter(u => {
    const q = busqueda.value.toLowerCase()
    const coincideBusqueda = u.nombre.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
    const coincideRol      = filtroRol.value === '' || u.rol_id == filtroRol.value
    return coincideBusqueda && coincideRol
  })
)

// ── Carga inicial ─────────────────────────────────────────
onMounted(async () => {
  await cargarRoles()
  await cargarUsuarios()
})

async function cargarUsuarios() {
  try {
    cargando.value = true
    const { data } = await axios.get('/api/admin/usuarios')
    usuarios.value = data
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudieron cargar los usuarios.')
  } finally {
    cargando.value = false
  }
}

async function cargarRoles() {
  try {
    const { data } = await axios.get('/api/admin/roles')
    roles.value = data
  } catch {}
}

// ── Helpers ───────────────────────────────────────────────
function capitalizar(texto) {
  if (!texto) return ''
  return texto.charAt(0).toUpperCase() + texto.slice(1)
}

function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  return `${d.getDate().toString().padStart(2,'0')} ${meses[d.getMonth()]} ${d.getFullYear()}`
}

function obtenerNombreRol(rol_id) {
  const rol = roles.value.find(r => r.id == rol_id)
  return rol ? capitalizar(rol.nombre) : 'Sin rol'
}

// ── Toggle estado ─────────────────────────────────────────
async function toggleEstado(user) {
  const nuevoEstado = user.estado == 1 ? 0 : 1
  const accion = nuevoEstado == 1 ? 'activar' : 'desactivar'
  if (!confirm(`¿Deseas ${accion} la cuenta de "${user.nombre}"?`)) return
  try {
    await axios.patch(`/api/admin/usuarios/${user.id}/estado`, { estado: nuevoEstado })
    user.estado = nuevoEstado
    mostrarAlerta('success', '¡Listo!', `Usuario ${accion === 'activar' ? 'activado' : 'desactivado'} correctamente.`)
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo cambiar el estado.')
  }
}

// ── Ver detalle ───────────────────────────────────────────
function verDetalle(user) {
  detalle.value = user
  new bootstrap.Modal(document.getElementById('modalDetalleUsuario')).show()
}

// ── Agregar ───────────────────────────────────────────────
function abrirModalAgregar() {
  formNuevo.value = { nombre: '', email: '', contrasena: '', rol_id: '', estado: 1, verificado: 0 }
  new bootstrap.Modal(document.getElementById('modalAgregarUsuario')).show()
}

async function guardarUsuario() {
  try {
    await axios.post('/api/admin/usuarios', formNuevo.value)
    bootstrap.Modal.getInstance(document.getElementById('modalAgregarUsuario')).hide()
    mostrarAlerta('success', '¡Creado!', 'Usuario registrado correctamente.')
    await cargarUsuarios()
  } catch (err) {
    const msg = err.response?.data?.message || 'No se pudo crear el usuario.'
    mostrarAlerta('danger', '¡Error!', msg)
  }
}

// ── Editar ────────────────────────────────────────────────
function abrirModalEditar(user) {
  formEditar.value = { ...user, contrasena: '' }
  new bootstrap.Modal(document.getElementById('modalEditarUsuario')).show()
}

async function guardarEdicion() {
  try {
    const datos = { ...formEditar.value }
    if (!datos.contrasena) delete datos.contrasena
    await axios.put(`/api/admin/usuarios/${formEditar.value.id}`, datos)
    bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide()
    mostrarAlerta('success', '¡Actualizado!', 'Usuario editado correctamente.')
    await cargarUsuarios()
  } catch {
    mostrarAlerta('danger', '¡Error!', 'No se pudo actualizar el usuario.')
  }
}

// ── Alerta ────────────────────────────────────────────────
function mostrarAlerta(tipo, titulo, mensaje) {
  alerta.value = { visible: true, tipo, titulo, mensaje }
  setTimeout(() => alerta.value.visible = false, 4000)
}
</script>