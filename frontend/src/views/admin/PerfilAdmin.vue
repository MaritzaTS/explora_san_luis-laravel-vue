<template>
  <div>

    <div class="row g-4">

      <!-- ── COLUMNA IZQUIERDA: AVATAR Y RESUMEN ── -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 text-center">

          <!-- Avatar grande -->
          <div class="rounded-circle bg-dark bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
            style="width: 130px; height: 130px;">
            <i class="bi bi-person-fill text-dark" style="font-size: 70px;"></i>
          </div>

          <h4 class="fw-bold mb-1">{{ perfil.nombre || 'Cargando...' }}</h4>
          <p class="text-muted small mb-3">
            <i class="bi bi-envelope me-1"></i>{{ perfil.email }}
          </p>

          <span class="badge bg-dark text-white rounded-pill px-3 py-2 mb-3 mx-auto"
            style="width: fit-content;">
            <i class="bi bi-shield-check me-1"></i> Administrador
          </span>

          <hr class="my-3">

          <div class="text-start">
            <p class="mb-2 small">
              <i class="bi bi-patch-check-fill me-2"
                :class="perfil.verificado ? 'text-success' : 'text-warning'"></i>
              <span class="fw-semibold">Cuenta verificada:</span>
              {{ perfil.verificado ? 'Sí' : 'Pendiente' }}
            </p>
            <p class="mb-2 small">
              <i class="bi bi-calendar3 me-2 text-muted"></i>
              <span class="fw-semibold">Miembro desde:</span>
              {{ formatFecha(perfil.created_at) }}
            </p>
            <p class="mb-0 small" v-if="perfil.updated_at">
              <i class="bi bi-clock-history me-2 text-muted"></i>
              <span class="fw-semibold">Última actualización:</span>
              {{ formatFecha(perfil.updated_at) }}
            </p>
          </div>

        </div>
      </div>

      <!-- ── COLUMNA DERECHA: FORMULARIOS ── -->
      <div class="col-lg-8">

        <!-- ─── DATOS PERSONALES ─── -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-person-vcard me-2"></i>Información Personal
            </h5>
            <button v-if="!editandoDatos"
              class="btn btn-outline-dark btn-sm rounded-3 fw-semibold"
              @click="activarEdicion">
              <i class="bi bi-pencil me-1"></i> Editar
            </button>
          </div>

          <form @submit.prevent="guardarDatos">
            <div class="row g-3">
              <div class="col-md-12">
                <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                  Nombre Completo
                </label>
                <input v-model="formDatos.nombre" type="text"
                  class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                  :disabled="!editandoDatos" required />
              </div>
              <div class="col-md-12">
                <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                  Correo Electrónico
                </label>
                <input v-model="formDatos.email" type="email"
                  class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                  :disabled="!editandoDatos" required />
              </div>
            </div>

            <div v-if="editandoDatos" class="d-flex justify-content-end gap-2 mt-4">
              <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                @click="cancelarEdicion">
                Cancelar
              </button>
              <button type="submit" class="btn btn-dark px-4 rounded-3 fw-bold shadow-sm">
                <i class="bi bi-save me-2"></i>Guardar Cambios
              </button>
            </div>
          </form>
        </div>

        <!-- ─── CAMBIO DE CONTRASEÑA ─── -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-shield-lock me-2"></i>Seguridad
            </h5>
            <button v-if="!cambiandoPassword"
              class="btn btn-outline-dark btn-sm rounded-3 fw-semibold"
              @click="cambiandoPassword = true">
              <i class="bi bi-key me-1"></i> Cambiar contraseña
            </button>
          </div>

          <p v-if="!cambiandoPassword" class="text-muted small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Mantén tu cuenta segura usando una contraseña única y robusta.
          </p>

          <form v-else @submit.prevent="cambiarPassword">
            <div class="row g-3">
              <div class="col-12">
                <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                  Contraseña Actual *
                </label>
                <input v-model="formPassword.actual" type="password"
                  class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                  placeholder="Ingresa tu contraseña actual" required />
              </div>
              <div class="col-md-6">
                <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                  Nueva Contraseña *
                </label>
                <input v-model="formPassword.nueva" type="password"
                  class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                  placeholder="Mínimo 8 caracteres" required minlength="8" />
              </div>
              <div class="col-md-6">
                <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">
                  Confirmar Nueva Contraseña *
                </label>
                <input v-model="formPassword.confirmar" type="password"
                  class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                  placeholder="Repite la nueva contraseña" required minlength="8" />
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                @click="cancelarPassword">
                Cancelar
              </button>
              <button type="submit" class="btn btn-dark px-4 rounded-3 fw-bold shadow-sm">
                <i class="bi bi-check-circle me-2"></i>Actualizar Contraseña
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api/axios'
import { AUTH } from '@/api/endpoints'
import { useAuthStore } from '@/stores/auth.store'
import { useToast } from '@/composables/useToast'

const toast = useToast()

const authStore = useAuthStore()
const perfil   = ref({})
const formDatos = ref({ nombre: '', email: '' })
const formPassword = ref({ actual: '', nueva: '', confirmar: '' })
const editandoDatos     = ref(false)
const cambiandoPassword = ref(false)


onMounted(cargarPerfil)

async function cargarPerfil() {
  try {
    const { data } = await api.get(AUTH.ME)
    perfil.value = data.data ?? data
    formDatos.value = { nombre: perfil.value.nombre, email: perfil.value.email }
  } catch {
    perfil.value = authStore.usuario ?? {}
    formDatos.value = { nombre: perfil.value.nombre ?? '', email: perfil.value.email ?? '' }
  }
}

function activarEdicion() { editandoDatos.value = true }

function cancelarEdicion() {
  editandoDatos.value = false
  formDatos.value = { nombre: perfil.value.nombre, email: perfil.value.email }
}

async function guardarDatos() {
  toast.warning('La edición de perfil no está habilitada aún.')
  editandoDatos.value = false
}

function cancelarPassword() {
  cambiandoPassword.value = false
  formPassword.value = { actual: '', nueva: '', confirmar: '' }
}

async function cambiarPassword() {
  toast.warning('El cambio de contraseña no está habilitado aún.')
  cancelarPassword()
}

function formatFecha(fecha) {
  if (!fecha) return '—'
  const d = new Date(fecha)
  const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  return `${d.getDate().toString().padStart(2,'0')} ${meses[d.getMonth()]} ${d.getFullYear()}`
}


</script>
