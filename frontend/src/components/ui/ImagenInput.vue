<template>
  <div>
    <!-- Hint de formatos y peso -->
    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
      <span v-for="fmt in formatos" :key="fmt"
        class="badge border fw-normal"
        style="font-size: 0.7rem; color: #555; background: #f1f5f9;">
        {{ fmt }}
      </span>
      <span class="text-muted" style="font-size: 0.72rem;">· Máx. {{ maxSizeMb }} MB</span>
    </div>

    <input
      ref="inputRef"
      type="file"
      :accept="acceptAttr"
      :required="required && !tieneArchivo"
      :class="['form-control shadow-none', errorMsg ? 'is-invalid border-danger' : '', inputClass]"
      @change="validar"
    />

    <!-- Error -->
    <div v-if="errorMsg" class="invalid-feedback d-block mt-1" style="font-size: 0.78rem;">
      <i class="bi bi-exclamation-circle me-1"></i>{{ errorMsg }}
    </div>

    <!-- Preview -->
    <div v-if="previewUrl && !errorMsg" class="mt-2">
      <img :src="previewUrl" class="rounded-3 border shadow-sm"
        style="height: 90px; width: auto; object-fit: cover;" alt="preview" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  required:   { type: Boolean, default: false },
  maxSizeMb:  { type: Number,  default: 5 },
  inputClass: { type: String,  default: '' },
  // formatos aceptados: extensiones visibles + MIME types para el atributo accept
  mimeTypes:  {
    type: Array,
    default: () => ['image/jpeg', 'image/png', 'image/webp'],
  },
})

const emit = defineEmits(['change'])

const inputRef   = ref(null)
const errorMsg   = ref(null)
const previewUrl = ref(null)
const tieneArchivo = ref(false)

const formatos = computed(() =>
  props.mimeTypes.map((m) => m.split('/')[1].toUpperCase().replace('JPEG', 'JPG'))
)

const acceptAttr = computed(() => props.mimeTypes.join(','))

function validar(event) {
  const file = event.target.files?.[0]
  errorMsg.value   = null
  previewUrl.value = null
  tieneArchivo.value = false

  if (!file) { emit('change', null); return }

  // Validar tipo
  if (!props.mimeTypes.includes(file.type)) {
    errorMsg.value = `Formato no válido. Usa: ${formatos.value.join(', ')}`
    event.target.value = ''
    emit('change', null)
    return
  }

  // Validar tamaño
  const maxBytes = props.maxSizeMb * 1024 * 1024
  if (file.size > maxBytes) {
    errorMsg.value = `La imagen pesa ${(file.size / 1024 / 1024).toFixed(1)} MB. El máximo es ${props.maxSizeMb} MB.`
    event.target.value = ''
    emit('change', null)
    return
  }

  // Todo bien → preview
  tieneArchivo.value = true
  previewUrl.value = URL.createObjectURL(file)
  emit('change', file)
}

function reset() {
  if (inputRef.value) inputRef.value.value = ''
  errorMsg.value     = null
  previewUrl.value   = null
  tieneArchivo.value = false
}

defineExpose({ reset })
</script>
