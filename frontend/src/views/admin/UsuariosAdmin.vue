<template>
  <div>

    <!-- TARJETAS ESTADÍSTICAS -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <AdminStatCard
      label="Total Usuarios"
      :valor="totalUsuarios"
      icono="bi bi-people-fill"
      variante="default"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Activos"
      :valor="activos"
      icono="bi bi-check-circle-fill"
      variante="success"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Inactivos"
      :valor="inactivos"
      icono="bi bi-x-circle-fill"
      variante="danger"
    />
  </div>
  <div class="col-md-3">
    <AdminStatCard
      label="Verificados"
      :valor="verificados"
      icono="bi bi-patch-check-fill"
      variante="primary"
    />
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
          <div class="col-md-3 text-end"></div>
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
                    {{ obtenerNombreRol(user) }}
                  </span>
                </td>
                <td>
                  <span v-if="user.verificado"
                    class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-patch-check-fill me-1"></i>Sí
                  </span>
                  <span v-else
                    class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="bi bi-exclamation-circle me-1"></i>Pendiente
                  </span>
                </td>
                <td>
                  <span v-if="user.estado"
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
                    <button
                      :class="user.estado
                        ? 'btn btn-sm btn-light border text-danger shadow-none'
                        : 'btn btn-sm btn-light border text-success shadow-none'"
                      :title="user.estado ? 'Desactivar' : 'Activar'"
                      @click="toggleEstado(user)">
                      <i :class="user.estado ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
                  <span :class="detalle.estado
                    ? 'badge bg-success rounded-pill px-3 py-2 w-100'
                    : 'badge bg-danger rounded-pill px-3 py-2 w-100'">
                    {{ detalle.estado ? 'Activo' : 'Inactivo' }}
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
                      {{ obtenerNombreRol(detalle) }}
                    </p>
                  </div>
                  <div class="col-md-6">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Verificado</label>
                    <p class="border-bottom pb-2 fw-medium text-dark small">
                      {{ detalle.verificado ? 'Sí ✓' : 'Pendiente' }}
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

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import api from '@/api/axios'
import { ADMIN } from '@/api/endpoints'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'

const toast = useToast()
const { confirmar, alertaError } = useConfirm()

const usuarios  = ref([])
const cargando  = ref(true)
const busqueda  = ref('')
const filtroRol = ref('')
const detalle   = ref(null)


const totalUsuarios = computed(() => usuarios.value.length)
const activos       = computed(() => usuarios.value.filter(u => u.estado).length)
const inactivos     = computed(() => usuarios.value.filter(u => !u.estado).length)
const verificados   = computed(() => usuarios.value.filter(u => u.verificado).length)

const usuariosFiltrados = computed(() =>
  usuarios.value.filter(u => {
    const q = busqueda.value.toLowerCase()
    const okBusqueda = !q || u.nombre.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
    const okRol = !filtroRol.value || u.rol?.id == filtroRol.value
    return okBusqueda && okRol
  })
)

onMounted(cargarUsuarios)

async function cargarUsuarios() {
  cargando.value = true
  try {
    const { data } = await api.get(ADMIN.USUARIOS)
    const payload = data.data
    usuarios.value = payload?.usuarios ?? (Array.isArray(payload) ? payload : [])
  } finally { cargando.value = false }
}

async function toggleEstado(user) {
  const accion = user.estado ? 'desactivar' : 'activar'
  const ok = await confirmar({
    titulo: `¿${capitalizar(accion)} usuario?`,
    texto: `La cuenta de "${user.nombre}" será ${user.estado ? 'desactivada' : 'activada'}.`,
    textoBoton: `Sí, ${accion}`,
  })
  if (!ok) return
  try {
    await api.patch(ADMIN.USUARIO_ESTADO(user.id), { estado: !user.estado })
    user.estado = !user.estado
    toast.exito(`Usuario ${user.estado ? 'activado' : 'desactivado'}.`)
  } catch {
    alertaError('No se pudo cambiar el estado del usuario.')
  }
}

function verDetalle(user) {
  detalle.value = user
  new Modal(document.getElementById('modalDetalleUsuario')).show()
}

function capitalizar(texto) { return texto ? texto.charAt(0).toUpperCase() + texto.slice(1) : '' }

function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  return `${d.getDate().toString().padStart(2,'0')} ${meses[d.getMonth()]} ${d.getFullYear()}`
}

function obtenerNombreRol(user) { return capitalizar(user.rol?.nombre ?? 'Sin rol') }


</script>
