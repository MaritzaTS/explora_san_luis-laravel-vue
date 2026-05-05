<template>
  <footer class="bg-dark text-white pt-5 pb-3">
    <div class="container">
      <!-- Logo Superior -->
      <div class="row mb-4">
        <div class="col-12">
          <img src="@/assets/img/logo.png" alt="Explora San Luis" width="150"
           class="img-fluid logo-blanco" style="max-height: 90px; width: auto;">
        </div>
      </div>

      <hr class="border-secondary mb-5">

      <!-- BLOQUES DEL FOOTER PROPORCIONALES -->
      <div class="row g-4 justify-content-between">
        
        <!-- Bloque 1: San Luis (col-md-3 asegura el 25% del ancho) -->
        <div class="col-6 col-md-3">
          <h5 class="fw-bold mb-4">San Luis</h5>
          <p class="text-white-100 mb-1" style="font-size: 0.95rem;"> Descubre la magia </p>
          <p class="text-white-100 mb-1" style="font-size: 0.95rem">de San Luis, Antioquia. </p>
          <p class="text-white-100 mb-1" style="font-size: 0.95rem">Donde la Naturaleza</p> 
          <p class="text-white-100 mb-1" style="font-size: 0.95rem">y la cultura se encuentran.</p>
          
        </div>

        <!-- Bloque 2: Explorar -->
        <div class="col-6 col-md-3">
          <h5 class="fw-bold mb-4">Explorar</h5>
          <ul class="list-unstyled small text-secondary">
            <li class="mb-2">
              <router-link to="/SitiosTuristicosView" class="footer-link">Lugares turísticos</router-link>
            </li>
            <li class="mb-2">
              <router-link to="/AgenciasTuristicasView" class="footer-link">Actividades</router-link>
            </li>
            <li>
              <router-link to="/Alojamientos" class="footer-link">Hospedajes</router-link>
            </li>
          </ul>
        </div>

        <!-- Bloque 3: Contáctanos -->
        <div class="col-6 col-md-3">
          <h5 class="fw-bold mb-4">Contáctanos</h5>
          <p class="text-white-100 mb-1" style="font-size: 0.95rem">info@explorasanluis.com</p>
          <p class="text-white-100 mb-1" style="font-size: 0.95rem">San Luis, Antioquia</p>
        </div>

        <!-- Bloque 4: Registro y Redes -->
        <div class="col-6 col-md-3 text-md-center">
          <h5 class="fw-bold mb-4">¿Eres comerciante?</h5>
          <router-link to="/registro" class="btn btn-success rounded-pill px-4 py-2 w-100 mb-3">
            Regístrate aquí
          </router-link>
          
          <div class="d-flex justify-content-md-center gap-3 mt-2">
            <i class="bi bi-whatsapp fs-5 text-secondary"></i>
            <i class="bi bi-instagram fs-5 text-secondary"></i>
            <i class="bi bi-facebook fs-5 text-secondary"></i>
          </div>
        </div>

      </div>

      <hr class="border-secondary mt-5 mb-3">
      <div class="text-center text-secondary small">
        © 2026 Explora San Luis - Antioquia. Todos los derechos reservados.
      </div>
    </div>
  </footer>
</template>

<style scoped >
.logo-blanco {
    /* brightness(0) lo vuelve negro, invert(1) lo vuelve blanco puro */
    filter: brightness(0) invert(1);
    
    /* Opcional: un toque de opacidad para que no sea un blanco tan "agresivo" */
    opacity: 0.9;
}

/* Estilo base para los enlaces del footer */
.footer-link {
    color: rgba(255, 255, 255, 0.7); /* Blanco con 70% de opacidad */
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s ease; /* Transición suave de color */
}

/* Efecto al pasar el mouse */
.footer-link:hover {
    color: #a3e635; /* El verde lima/claro que usas en el botón */
    text-decoration: underline; /* Opcional: subraya para reforzar que es un link */
}

</style>





