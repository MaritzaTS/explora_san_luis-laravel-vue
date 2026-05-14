<template>
  <div class="registro-comercio-view">
    <section class="container my-5">

      <!-- ENCABEZADO -->
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">¡Registra tu comercio!</h1>
        <p class="text-muted mx-auto" style="max-width: 600px;">
          Forma parte del directorio turístico de San Luis. Completa el formulario
          y un administrador revisará tu solicitud.
        </p>
      </div>

      <!-- ÉXITO -->
      <div v-if="enviado" class="exito-panel text-center mx-auto">
        <i class="bi bi-check-circle-fill text-success exito-icon"></i>
        <h4 class="fw-bold mt-3">¡Registro enviado!</h4>
        <p class="text-muted">Tu comercio será revisado por el administrador antes de ser publicado.</p>
        <button class="btn btn-success rounded-pill px-4 mt-2" @click="reiniciar">
          Registrar otro comercio
        </button>
      </div>

      <!-- FORMULARIO -->
      <div v-else class="form-wrapper mx-auto">

        <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
        <div class="form-seccion">
          <div class="form-seccion-header">
            <i class="bi bi-folder2-open"></i>
            <h5 class="mb-0">Información básica</h5>
          </div>
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label-custom">Nombre comercial *</label>
              <input v-model="form.nombre_comercial" type="text" class="input-custom"
                :class="{ 'input-error': errores.nombre_comercial }"
                placeholder="Ej: Restaurante El Maderero">
              <span class="error-msg" v-if="errores.nombre_comercial">{{ errores.nombre_comercial }}</span>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Razón social *</label>
              <input v-model="form.razon_social" type="text" class="input-custom"
                :class="{ 'input-error': errores.razon_social }"
                placeholder="Nombre legal del negocio">
              <span class="error-msg" v-if="errores.razon_social">{{ errores.razon_social }}</span>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">RUT *</label>
              <input v-model="form.rut" type="text" class="input-custom"
                :class="{ 'input-error': errores.rut }"
                placeholder="Número de identificación tributaria">
              <span class="error-msg" v-if="errores.rut">{{ errores.rut }}</span>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Tipo de comercio *</label>
              <select v-model="form.tipo_entidad_id" class="input-custom"
                :class="{ 'input-error': errores.tipo_entidad_id }"
                @change="onTipoChange">
                <option value="" disabled>Selecciona una categoría</option>
                <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
                  {{ tipo.nombre }}
                </option>
              </select>
              <span class="error-msg" v-if="errores.tipo_entidad_id">{{ errores.tipo_entidad_id }}</span>
            </div>

            <!-- Subtipos -->
            <div class="col-12" v-if="subtiposDisponibles.length > 0">
              <label class="form-label-custom">Categorías específicas</label>
              <div class="subtipos-grid">
                <div v-for="sub in subtiposDisponibles" :key="sub.id" class="subtipo-check">
                  <input type="checkbox" :id="'sub-' + sub.id" :value="sub.id" v-model="form.subtipos_ids">
                  <label :for="'sub-' + sub.id">{{ sub.nombre }}</label>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label-custom">Descripción del negocio</label>
              <textarea v-model="form.descripcion" class="input-custom" rows="3"
                placeholder="Cuéntanos brevemente qué ofreces..."></textarea>
            </div>
          </div>
        </div>

        <!-- SECCIÓN 2: CONTACTO -->
        <div class="form-seccion">
          <div class="form-seccion-header">
            <i class="bi bi-telephone"></i>
            <h5 class="mb-0">Información de contacto</h5>
          </div>
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label-custom">Teléfono / WhatsApp *</label>
              <input v-model="form.telefono" type="text" class="input-custom"
                :class="{ 'input-error': errores.telefono }"
                placeholder="Ej: 3001234567">
              <span class="error-msg" v-if="errores.telefono">{{ errores.telefono }}</span>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Sitio web / Redes sociales</label>
              <input v-model="form.sitio_web" type="url" class="input-custom"
                :class="{ 'input-error': errores.sitio_web }"
                placeholder="https://instagram.com/tu-negocio">
              <span class="error-msg" v-if="errores.sitio_web">{{ errores.sitio_web }}</span>
            </div>
            <div class="col-12">
              <label class="form-label-custom">Horario de atención *</label>
              <input v-model="form.hora_atencion" type="text" class="input-custom"
                :class="{ 'input-error': errores.hora_atencion }"
                placeholder="Ej: Lunes a Viernes 8:00 AM - 6:00 PM">
              <span class="error-msg" v-if="errores.hora_atencion">{{ errores.hora_atencion }}</span>
            </div>
          </div>
        </div>

        <!-- SECCIÓN 3: UBICACIÓN -->
        <div class="form-seccion">
          <div class="form-seccion-header">
            <i class="bi bi-geo-alt"></i>
            <h5 class="mb-0">Ubicación</h5>
          </div>
          <div class="row g-4">
            <div class="col-12">
              <label class="form-label-custom">Dirección *</label>
              <input v-model="form.direccion" type="text" class="input-custom"
                :class="{ 'input-error': errores.direccion }"
                placeholder="Ej: Calle 10 #15-20, Barrio Centro">
              <span class="error-msg" v-if="errores.direccion">{{ errores.direccion }}</span>
            </div>
          </div>
        </div>

        <!-- SECCIÓN 4: LOGO -->
        <div class="form-seccion">
          <div class="form-seccion-header">
            <i class="bi bi-images"></i>
            <h5 class="mb-0">Identidad visual</h5>
          </div>
          <div class="row g-4">
            <div class="col-12">
              <label class="form-label-custom">Logo del comercio</label>
              <div class="logo-dropzone" @click="$refs.logoInput.click()"
                :class="{ 'has-image': logoPreview }">
                <img v-if="logoPreview" :src="logoPreview" class="logo-preview" alt="Preview">
                <div v-else class="logo-placeholder">
                  <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                  <p class="small text-muted mt-2 mb-0">Haz clic para subir tu logo</p>
                  <p class="text-muted" style="font-size:0.75rem;">JPG, PNG o WEBP · Máx 5MB</p>
                </div>
              </div>
              <input ref="logoInput" type="file" accept="image/jpg,image/jpeg,image/png,image/webp"
                class="d-none" @change="onLogoChange">
            </div>
          </div>
        </div>

        <!-- ERROR GLOBAL -->
        <div v-if="errorGlobal" class="alert alert-danger rounded-3 mt-3">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ errorGlobal }}
        </div>

        <!-- BOTÓN ENVIAR -->
        <div class="d-grid mt-4">
          <button class="btn-enviar" @click="enviar" :disabled="enviando">
            <span v-if="enviando" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-send me-2"></i>
            {{ enviando ? 'Enviando...' : 'Enviar registro para aprobación' }}
          </button>
        </div>

      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/api/axios'
import { PUBLICO, COMERCIO } from '@/api/endpoints'

// Estado
const tipos              = ref([])
const subtiposDisponibles = ref([])
const logoPreview        = ref(null)
const enviando           = ref(false)
const enviado            = ref(false)
const errorGlobal        = ref('')
const logoInput          = ref(null)

const form = reactive({
  tipo_entidad_id : '',
  lugar_id        : 1,
  nombre_comercial: '',
  razon_social    : '',
  rut             : '',
  descripcion     : '',
  telefono        : '',
  sitio_web       : '',
  hora_atencion   : '',
  direccion       : '',
  subtipos_ids    : [],
  logo            : null,
})

const errores = reactive({
  tipo_entidad_id : '',
  nombre_comercial: '',
  razon_social    : '',
  rut             : '',
  telefono        : '',
  hora_atencion   : '',
  direccion       : '',
  sitio_web       : '',
})

// Cargar tipos al montar
async function cargarTipos() {
  try {
    const { data } = await api.get(PUBLICO.TIPOS)
    tipos.value = data.data ?? []
  } catch {
    tipos.value = []
  }
}

// Al cambiar tipo, cargar subtipos
function onTipoChange() {
  form.subtipos_ids = []
  const tipo = tipos.value.find(t => t.id === form.tipo_entidad_id)
  subtiposDisponibles.value = tipo?.subtipos ?? tipo?.tipos_especificos ?? []
}

// Preview del logo
function onLogoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  form.logo   = file
  logoPreview.value = URL.createObjectURL(file)
}

// Validación local
function validar() {
  let ok = true
  Object.keys(errores).forEach(k => errores[k] = '')

  if (!form.tipo_entidad_id)  { errores.tipo_entidad_id  = 'Selecciona el tipo de comercio.'; ok = false }
  if (!form.nombre_comercial) { errores.nombre_comercial = 'El nombre comercial es obligatorio.'; ok = false }
  if (!form.razon_social)     { errores.razon_social     = 'La razón social es obligatoria.'; ok = false }
  if (!form.rut)              { errores.rut              = 'El RUT es obligatorio.'; ok = false }
  if (!form.telefono)         { errores.telefono         = 'El teléfono es obligatorio.'; ok = false }
  if (!form.hora_atencion)    { errores.hora_atencion    = 'El horario de atención es obligatorio.'; ok = false }
  if (!form.direccion)        { errores.direccion        = 'La dirección es obligatoria.'; ok = false }
  if (form.sitio_web && !form.sitio_web.startsWith('http')) {
    errores.sitio_web = 'El sitio web debe ser una URL válida (https://...).'
    ok = false
  }
  return ok
}

// Enviar
async function enviar() {
  errorGlobal.value = ''
  if (!validar()) return

  enviando.value = true
  try {
    const formData = new FormData()
    formData.append('tipo_entidad_id',  form.tipo_entidad_id)
    formData.append('lugar_id',         form.lugar_id)
    formData.append('nombre_comercial', form.nombre_comercial)
    formData.append('razon_social',     form.razon_social)
    formData.append('rut',              form.rut)
    formData.append('telefono',         form.telefono)
    formData.append('hora_atencion',    form.hora_atencion)
    formData.append('direccion',        form.direccion)
    if (form.descripcion) formData.append('descripcion', form.descripcion)
    if (form.sitio_web)   formData.append('sitio_web',   form.sitio_web)
    if (form.logo)        formData.append('logo',        form.logo)
    form.subtipos_ids.forEach(id => formData.append('subtipos_ids[]', id))

    await api.post(COMERCIO.REGISTRAR, formData, {
  headers: { 'Content-Type': 'multipart/form-data' }
})
    enviado.value = true
  } catch (e) {
    const data = e.response?.data
    if (data?.errors) {
      Object.keys(data.errors).forEach(k => {
        if (errores[k] !== undefined) errores[k] = data.errors[k][0]
      })
    } else {
      errorGlobal.value = data?.message ?? 'Ocurrió un error. Intenta de nuevo.'
    }
  } finally {
    enviando.value = false
  }
}

// Reiniciar formulario
function reiniciar() {
  Object.keys(form).forEach(k => {
    if (Array.isArray(form[k])) form[k] = []
    else if (k === 'lugar_id') form[k] = 1
    else form[k] = ''
  })
  logoPreview.value        = null
  enviado.value            = false
  subtiposDisponibles.value = []
}

onMounted(cargarTipos)
</script>

<style scoped>
.form-wrapper {
  max-width: 850px;
  background: #fff;
  border: 1.5px solid #e9ecef;
  border-radius: 1.5rem;
  padding: 2.5rem;
  box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}

.form-seccion {
  margin-bottom: 2.5rem;
  padding-bottom: 2.5rem;
  border-bottom: 1px solid #f0f0f0;
}
.form-seccion:last-of-type {
  border-bottom: none;
  margin-bottom: 0;
}

.form-seccion-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  color: #1a1a1a;
  font-size: 1.1rem;
}
.form-seccion-header i {
  font-size: 1.3rem;
  color: #198754;
}

.form-label-custom {
  font-size: 0.85rem;
  font-weight: 700;
  color: #444;
  margin-bottom: 0.4rem;
  display: block;
}

.input-custom {
  width: 100%;
  border: none;
  border-bottom: 1.5px solid #ced4da;
  border-radius: 0;
  padding: 0.5rem 0;
  font-size: 0.92rem;
  background: transparent;
  outline: none;
  transition: border-color 0.2s;
  color: #1a1a1a;
}
.input-custom:focus {
  border-bottom-color: #198754;
}
.input-custom.input-error {
  border-bottom-color: #dc3545;
}

.error-msg {
  font-size: 0.78rem;
  color: #dc3545;
  margin-top: 0.25rem;
  display: block;
}

/* Subtipos */
.subtipos-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  background: #f8f9fa;
  border-radius: 0.75rem;
  padding: 1rem;
}
.subtipo-check {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}
.subtipo-check input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #198754;
  cursor: pointer;
}
.subtipo-check label {
  font-size: 0.88rem;
  cursor: pointer;
  color: #333;
}

/* Logo dropzone */
.logo-dropzone {
  border: 2px dashed #ced4da;
  border-radius: 1rem;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
  min-height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo-dropzone:hover {
  border-color: #198754;
  background: #f8fffe;
}
.logo-dropzone.has-image {
  border-color: #198754;
  padding: 0.5rem;
}
.logo-preview {
  max-height: 120px;
  max-width: 100%;
  object-fit: contain;
  border-radius: 0.5rem;
}

/* Botón enviar */
.btn-enviar {
  background: #198754;
  color: #fff;
  border: none;
  border-radius: 0.75rem;
  padding: 1rem;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}
.btn-enviar:hover:not(:disabled) {
  background: #157347;
  transform: translateY(-2px);
}
.btn-enviar:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Éxito */
.exito-panel {
  max-width: 480px;
  background: #f8fffe;
  border: 1.5px solid #b7dfc9;
  border-radius: 1.5rem;
  padding: 3rem 2rem;
}
.exito-icon {
  font-size: 4rem;
}
</style>