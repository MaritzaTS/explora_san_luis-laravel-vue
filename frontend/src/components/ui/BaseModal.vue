<template>
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 p-4 shadow-lg">
        <div class="modal-header border-0 justify-content-center">
          <h2 class="fw-bold modal-title">Iniciar sesión</h2>
        </div>

        <div class="modal-body">
          <div class="d-grid gap-2 mb-4">
            <!-- Botones de Redes Sociales -->
            <button class="btn btn-outline-dark py-2 d-flex align-items-center justify-content-center gap-2">
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="18">
              Continuar con Google
            </button>
            <!-- ... otros botones ... -->
          </div>

          <!-- Formulario manejado con Vue -->
          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label class="small fw-bold">Correo electrónico</label>
              <input v-model="form.email" type="email" class="form-control" placeholder="juan@mail.com" required>
            </div>

            <div class="mb-4">
              <label class="small fw-bold">Contraseña</label>
              <div class="input-group">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="form-control" required>
                <button class="btn btn-outline-dark" type="button" @click="showPassword = !showPassword">
                  <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-lg fw-bold border-0 py-2" style="background-color: #a3e635;">
                Iniciar sesión
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const showPassword = ref(false);
const form = reactive({
  email: '',
  password: ''
});

const handleLogin = () => {
  console.log("Datos enviados:", form);
  // Aquí llamarías a tu API de Laravel
};
</script>