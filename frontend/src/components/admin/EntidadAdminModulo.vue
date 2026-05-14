<template>
  <div>

    <!-- CARD PORTADA DE CATEGORÍA -->
    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden bg-white">
      <div class="row g-0">
        <div class="col-md-3 position-relative" style="height: 150px;">
          <img
            :src="imgPortada || 'https://via.placeholder.com/400x150'"
            class="img-fluid w-100 h-100"
            style="object-fit: cover;"
          />
          <div class="position-absolute bottom-0 end-0 p-2">
            <button
              class="btn btn-dark btn-sm rounded-pill shadow px-3 py-1"
              style="font-size: 0.8rem;"
              @click="inputPortada.click()">
              <i class="bi bi-camera-fill me-1"></i>Cambiar
            </button>
          </div>
        </div>
        <div class="col-md-9 d-flex align-items-center">
          <div class="card-body p-3">
            <h5 class="fw-bold mb-1">Gestión de {{ capitalizar(tipoData?.nombre ?? slug) }}</h5>
            <p class="text-muted mb-2 small">
              Personaliza la imagen que verán los usuarios en el catálogo principal.
            </p>
            <div class="d-flex gap-1 flex-wrap mb-2">
              <span v-for="fmt in ['JPG','PNG','WEBP']" :key="fmt"
                class="badge border fw-normal"
                style="font-size:0.68rem; color:#555; background:#f1f5f9;">{{ fmt }}</span>
              <span class="text-muted" style="font-size:0.72rem;">· Máx. 5 MB</span>
            </div>
            <p v-if="errorPortada" class="text-danger small mb-1">
              <i class="bi bi-exclamation-circle me-1"></i>{{ errorPortada }}
            </p>
            <input
              ref="inputPortada"
              type="file"
              accept="image/jpeg,image/png,image/webp"
              class="d-none"
              @change="seleccionarPortada"
            />
            <div v-if="portadaFile" class="mt-2 d-flex gap-2">
              <button class="btn btn-success btn-sm" @click="confirmarPortada">Confirmar cambio</button>
              <button class="btn btn-light btn-sm" @click="cancelarPortada">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <AdminStatCard
      :label="'Total ' + capitalizar(tipoData?.nombre ?? slug)"
      :valor="totalEntidades"
      icono="bi bi-shop-window"
      variante="default"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Activas"
      :valor="activas"
      icono="bi bi-check-circle-fill"
      variante="success"
    />
  </div>
  <div class="col-md-4">
    <AdminStatCard
      label="Inactivas"
      :valor="inactivas"
      icono="bi bi-x-circle-fill"
      variante="danger"
    />
  </div>
</div>

    <!-- TABLA / LISTA DE ENTIDADES -->
    <div class="card border-1 shadow-sm rounded-4 bg-white border-dark-subtle">
      <div class="card-body p-4">

        <!-- FILTROS Y BOTÓN AGREGAR -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-4">
            <div class="input-group border border-secondary-subtle rounded-3 bg-white">
              <span class="input-group-text bg-transparent border-0">
                <i class="bi bi-search"></i>
              </span>
              <input
                type="text"
                v-model="busqueda"
                class="form-control border-0 shadow-none py-2"
                placeholder="Buscar Establecimiento"
              />
            </div>
          </div>
          <div class="col-md-3">
            <select v-model="filtroSubtipo" class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos los Tipos</option>
              <option v-for="s in subtipos" :key="s.id" :value="s.id">{{ s.nombre }}</option>
            </select>
          </div>
          <div class="col-md-2">
            <select v-model="filtroEstado" class="form-select border border-secondary-subtle rounded-3 py-2 shadow-none fw-semibold">
              <option value="">Todos</option>
              <option value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
          </div>
          <div class="col-md-3 text-end">
            <button
              class="btn btn-outline-dark border border-secondary-subtle rounded-3 py-2 px-4 fw-bold shadow-none"
              @click="abrirModalAgregar">
              <i class="bi bi-plus-lg me-1"></i> Agregar
            </button>
          </div>
        </div>

        <!-- LISTA -->
        <div class="d-flex flex-column gap-3">
          <div v-if="cargando" class="text-center p-5 text-muted">
            <div class="spinner-border text-dark mb-3" role="status"></div>
            <p>Cargando establecimientos...</p>
          </div>

          <div v-else-if="entidadesFiltradas.length === 0" class="text-center p-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p>No hay establecimientos registrados en {{ capitalizar(tipoData?.nombre ?? slug) }}.</p>
          </div>

          <div
            v-else
            v-for="entidad in entidadesFiltradas"
            :key="entidad.id"
            class="card border border-secondary-subtle rounded-4 shadow-none">
            <div class="card-body d-flex align-items-center justify-content-between p-3">

              <!-- INFO -->
              <div class="d-flex align-items-center">
                <img
                  :src="entidad.imagenes?.[0]?.url_completa || 'https://via.placeholder.com/60'"
                  class="rounded-3 border border-dark me-3"
                  style="width: 60px; height: 60px; object-fit: cover;"
                />
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold mb-0">{{ entidad.nombre_comercial }}</h5>
                    <span
                      :class="entidad.estado
                        ? 'badge bg-success-subtle text-success rounded-pill px-3 py-1'
                        : 'badge bg-danger-subtle text-danger rounded-pill px-3 py-1'"
                      style="font-size: 0.7rem;">
                      {{ entidad.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                  <p class="text-muted small mb-0 mt-1">
                    <i class="bi bi-geo-alt me-1"></i>{{ entidad.direccion }}
                  </p>
                  <div v-if="entidad.subtipos?.length" class="d-flex flex-wrap gap-1 mt-1">
                    <span v-for="s in entidad.subtipos" :key="s.id"
                      class="badge rounded-pill border text-dark fw-normal"
                      style="font-size: 0.68rem; background: #f1f5f9;">
                      {{ formatNombre(s.nombre) }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- ACCIONES -->
              <div class="d-flex gap-3">
                <button
                  :class="entidad.estado
                    ? 'btn btn-outline-danger border rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold'
                    : 'btn btn-outline-success border rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold'"
                  @click="toggleEstado(entidad)">
                  <i :class="entidad.estado ? 'bi bi-eye-slash fs-5' : 'bi bi-eye fs-5'"></i>
                  {{ entidad.estado ? 'Desactivar' : 'Activar' }}
                </button>

                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold"
                  @click="verDetalle(entidad)">
                  <i class="bi bi-eye fs-5"></i> Ver
                </button>

                <button
                  class="btn btn-white border border-secondary-subtle rounded-3 px-4 d-flex align-items-center gap-2 shadow-none fw-semibold"
                  @click="abrirModalEditar(entidad)">
                  <i class="bi bi-pencil-square fs-5"></i> Editar
                </button>
              </div>


            </div>
          </div>

          <!-- PAGINACIÓN -->
<AdminPaginacion
  :pagina-actual="paginaActual"
  :total-paginas="totalPaginas"
  :total="totalRegistros"
  @cambiar="cargarEntidades"
/>
        </div>

      </div>
    </div>

    <!-- ===== MODAL AGREGAR ===== -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold d-flex align-items-center">
              <i class="bi bi-pencil-square fs-4 me-2"></i> Agregar Registro
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="guardarNueva">
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Nombre Comercial *</label>
                  <input v-model="formNueva.nombre_comercial" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Restaurante El Sabor" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Razón Social *</label>
                  <input v-model="formNueva.razon_social" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Inversiones S.A.S" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">RUT (Opcional)</label>
                  <input v-model="formNueva.rut" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">WhatsApp / Teléfono *</label>
                  <input v-model="formNueva.telefono" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="300 123 4567" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Horario de Atención *</label>
                  <input v-model="formNueva.hora_atencion" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Ej: Lunes a Sábado 8am - 6pm" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Sitio Web o Facebook</label>
                  <input v-model="formNueva.sitio_web" type="url"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="https://facebook.com/tu-pagina" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-0">Estado Inicial *</label>
                  <select v-model="formNueva.estado"
                    class="form-select border-0 border-bottom border-dark rounded-0 px-0 shadow-none">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Dirección *</label>
                  <input v-model="formNueva.direccion" type="text"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    placeholder="Carrera 10 # 5-20, Centro" required />
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold mb-0">Descripción del Negocio</label>
                  <textarea v-model="formNueva.descripcion"
                    class="form-control border-0 border-bottom border-dark rounded-0 px-0 shadow-none"
                    rows="2" placeholder="Describe brevemente los servicios..."></textarea>
                </div>
                <div v-if="subtipos.length" class="col-12">
                  <label class="form-label fw-bold mb-2">Categoría específica</label>
                  <div class="d-flex flex-wrap gap-2">
                    <div v-for="s in subtipos" :key="s.id" class="form-check form-check-inline m-0">
                      <input class="form-check-input shadow-none" type="checkbox"
                        :id="'nuevo-sub-' + s.id" :value="s.id" v-model="formNueva.subtipos_ids" />
                      <label class="form-check-label small fw-semibold" :for="'nuevo-sub-' + s.id">
                        {{ formatNombre(s.nombre) }}
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold mb-1">
                    <i class="bi bi-person-badge me-1"></i> Logo del Comercio *
                  </label>
                  <small class="text-muted d-block mb-2">Imagen cuadrada o logo distintivo.</small>
                  <ImagenInput ref="inputLogoNuevaComp" :required="true" @change="logoNuevaFile = $event" />
                </div>
              </div>
              <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-4 py-2 fw-bold rounded-3 shadow-none"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Guardar Registro
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL VER DETALLE ===== -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Detalle del Establecimiento</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="detalle">
            <div class="row g-4 align-items-start">

              <!-- COLUMNA IZQUIERDA -->
              <div class="col-md-4 text-center">
                <img :src="detalle.imagenes?.[0]?.url_completa || detalle.imagen || 'https://via.placeholder.com/150'"
                  class="rounded-4 border shadow-sm mb-3"
                  style="width: 150px; height: 150px; object-fit: cover;" />

                <span :class="detalle.estado
                  ? 'badge bg-success rounded-pill px-3 py-2 d-block mb-3'
                  : 'badge bg-danger rounded-pill px-3 py-2 d-block mb-3'">
                  {{ detalle.estado ? '● Activo' : '● Inactivo' }}
                </span>

                <div v-if="detalle.subtipos?.length" class="d-flex flex-wrap gap-1 justify-content-center mb-3">
                  <span v-for="s in detalle.subtipos" :key="s.id"
                    class="badge rounded-pill border text-dark fw-normal"
                    style="font-size: 0.7rem; background: #f1f5f9;">
                    {{ formatNombre(s.nombre) }}
                  </span>
                </div>

                <p class="text-muted small mb-1">
                  <i class="bi bi-calendar3 me-1"></i> Registrado: {{ detalle.created_at }}
                </p>
              </div>

              <!-- COLUMNA DERECHA -->
              <div class="col-md-8">
                <h3 class="fw-bold text-dark mb-0">{{ detalle.nombre_comercial }}</h3>
                <p class="text-muted small mb-4">{{ detalle.razon_social }}</p>

                <div class="row g-3">
                  <div class="col-md-6">
                    <p class="text-muted mb-0" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">NIT / RUT</p>
                    <p class="border-bottom pb-2 small mb-0">{{ detalle.rut || '—' }}</p>
                  </div>

                  <div class="col-md-6">
                    <p class="text-muted mb-0" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">Horario</p>
                    <p class="border-bottom pb-2 small mb-0">
                      <i class="bi bi-clock me-1"></i>{{ detalle.hora_atencion }}
                    </p>
                  </div>

                  <div class="col-md-6">
                    <p class="text-muted mb-0" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">Teléfono / WhatsApp</p>
                    <p class="border-bottom pb-2 small mb-0">
                      <a :href="'tel:' + detalle.telefono" class="text-dark text-decoration-none">
                        <i class="bi bi-telephone me-1"></i>{{ detalle.telefono }}
                      </a>
                    </p>
                  </div>

                  <div class="col-md-6">
                    <p class="text-muted mb-0" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">Sitio Web / Redes</p>
                    <p class="border-bottom pb-2 small mb-0">
                      <a v-if="detalle.sitio_web" :href="detalle.sitio_web" target="_blank"
                        class="text-dark text-decoration-none text-truncate d-block">
                        <i class="bi bi-globe me-1"></i>{{ detalle.sitio_web }}
                      </a>
                      <span v-else class="text-muted">—</span>
                    </p>
                  </div>

                  <div class="col-12">
                    <p class="text-muted mb-0" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">Dirección</p>
                    <p class="border-bottom pb-2 small mb-0">
                      <i class="bi bi-geo-alt me-1"></i>{{ detalle.direccion }}
                    </p>
                  </div>

                  <div class="col-12" v-if="detalle.descripcion">
                    <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:700;">Descripción</p>
                    <div class="bg-light rounded-3 p-3 small text-secondary">{{ detalle.descripcion }}</div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer border-0 pt-2 d-flex justify-content-center pb-4">
            <button type="button" class="btn btn-light border px-5 rounded-3 fw-bold" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-dark px-5 rounded-3 fw-bold" data-bs-dismiss="modal"
              @click="abrirModalEditar(detalle)">
              <i class="bi bi-pencil me-2"></i>Editar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODAL EDITAR ===== -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-fill me-2"></i> Editar Establecimiento</h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" v-if="formEditar">
            <form @submit.prevent="guardarEdicion">
              <div class="row g-4 align-items-start">
                <div class="col-md-4 text-center">
                  <div class="p-4 rounded-4 border bg-white shadow-sm mb-3">
                    <img :src="logoPreview || 'https://via.placeholder.com/150'"
                      class="img-fluid rounded-4 mb-3 border"
                      style="width: 150px; height: 150px; object-fit: cover;" />
                    <label class="fw-bold mb-1 small d-block">Cambiar Logo</label>
                    <ImagenInput ref="inputLogoEditarComp" @change="onLogoEditarChange" />
                  </div>
                  <div class="p-3 rounded-4 border bg-light text-start">
                    <label class="fw-bold mb-1 small text-muted text-uppercase" style="font-size:0.7rem">Estado</label>
                    <select v-model="formEditar.estado"
                      class="form-select form-select-sm border-0 shadow-none bg-transparent fw-bold text-primary">
                      <option :value="true">Activo</option>
                      <option :value="false">Inactivo</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-4">
                    <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Nombre Comercial</label>
                    <input v-model="formEditar.nombre_comercial" type="text"
                      class="form-control form-control-lg border-0 border-bottom rounded-0 px-0 fw-bold shadow-none" required />
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Razón Social</label>
                      <input v-model="formEditar.razon_social" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">NIT / RUT</label>
                      <input v-model="formEditar.rut" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">WhatsApp / Tel</label>
                      <input v-model="formEditar.telefono" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-md-6">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Horario</label>
                      <input v-model="formEditar.hora_atencion" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Dirección</label>
                      <input v-model="formEditar.direccion" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" required />
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Descripción</label>
                      <textarea v-model="formEditar.descripcion" rows="3"
                        class="form-control border-0 bg-light rounded-3 shadow-none small"></textarea>
                    </div>
                    <div class="col-12">
                      <label class="fw-bold mb-0 small text-muted text-uppercase" style="font-size:0.7rem">Sitio Web / Redes</label>
                      <input v-model="formEditar.sitio_web" type="text"
                        class="form-control border-0 border-bottom rounded-0 px-0 shadow-none small" />
                    </div>
                    <div v-if="subtipos.length" class="col-12">
                      <label class="fw-bold mb-1 small text-muted text-uppercase" style="font-size:0.7rem">Categoría específica</label>
                      <div class="d-flex flex-wrap gap-2 mt-1">
                        <div v-for="s in subtipos" :key="s.id" class="form-check form-check-inline m-0">
                          <input class="form-check-input shadow-none" type="checkbox"
                            :id="'editar-sub-' + s.id" :value="s.id" v-model="formEditar.subtipos_ids" />
                          <label class="form-check-label small fw-semibold" :for="'editar-sub-' + s.id">
                            {{ formatNombre(s.nombre) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer border-0 pt-4 d-flex justify-content-center">
                <button type="button" class="btn btn-light border px-4 rounded-3 fw-bold"
                  data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark px-5 rounded-3 fw-bold shadow-sm"
                  :disabled="guardando">
                  <span v-if="guardando" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  <i v-else class="bi bi-save me-2"></i>Actualizar Información
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
import { ADMIN, PUBLICO } from '@/api/endpoints'
import ImagenInput from '@/components/ui/ImagenInput.vue'
import { useToast } from '@/composables/useToast'
import { useConfirm } from '@/composables/useConfirm'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'
import AdminPaginacion from '@/components/admin/AdminPaginacion.vue'

const toast = useToast()
const { confirmar, alertaError } = useConfirm()

const props = defineProps({ slug: String })

const entidades   = ref([])
const tipoData    = ref(null)
const cargando    = ref(true)
const guardando   = ref(false)
const busqueda    = ref('')
const filtroSubtipo = ref('')
const filtroEstado  = ref('')
const imgPortada    = ref(null)
const portadaFile   = ref(null)
const detalle       = ref(null)
const formEditar    = ref(null)
const logoPreview        = ref(null)
const logoNuevaFile      = ref(null)
const logoEditarFile     = ref(null)
const inputLogoNuevaComp = ref(null)
const inputLogoEditarComp= ref(null)
const inputPortada       = ref(null)
const errorPortada       = ref(null)
const paginaActual  = ref(1)
const totalPaginas  = ref(1)
const totalRegistros = ref(0)


const formNueva = ref({
  nombre_comercial: '', razon_social: '', rut: '', telefono: '',
  hora_atencion: '', sitio_web: '', estado: true, direccion: '', descripcion: '',
  subtipos_ids: []
})

const subtipos = computed(() => tipoData.value?.tipos_especificos ?? [])

// Base: solo entidades de este tipo (para stats y lista)
// const entidadesDelTipo = computed(() =>
//   entidades.value.filter(e => !props.slug || e.tipo_entidad?.slug === props.slug)
// )

const totalEntidades = computed(() => totalRegistros.value)
const activas        = computed(() => entidades.value.filter(e => e.estado).length)
const inactivas      = computed(() => entidades.value.filter(e => !e.estado).length)

const entidadesFiltradas = computed(() => {
  return entidades.value.filter(e => {
    const nombre = e.nombre_comercial?.toLowerCase() ?? ''
    const okBusqueda = !busqueda.value || nombre.includes(busqueda.value.toLowerCase())
    const okEstado   = filtroEstado.value === '' || String(e.estado ? '1' : '0') === filtroEstado.value
    const okSubtipo  = !filtroSubtipo.value || e.subtipos?.some(s => s.id == filtroSubtipo.value)
    return okBusqueda && okEstado && okSubtipo
  })
})

onMounted(async () => {
  await cargarTipo()
  await cargarEntidades()
})

async function cargarTipo() {
  try {
    const { data } = await api.get(PUBLICO.TIPOS)
    tipoData.value = data.data?.find(t => t.slug === props.slug) ?? null
    imgPortada.value = tipoData.value?.url_imagen ?? null
  } catch (err) {
    console.error('Error al cargar tipo:', err)
  }
}

async function cargarEntidades(pagina = 1) {
  cargando.value = true
  try {
    const params = { page: pagina }
    if (tipoData.value) params.tipo_entidad_id = tipoData.value.id
    const { data } = await api.get(ADMIN.ENTIDADES, { params })
    const payload = data.data
    entidades.value    = payload?.entidades ?? (Array.isArray(payload) ? payload : [])
    paginaActual.value = payload?.pagina_actual ?? 1
    totalPaginas.value = payload?.total_paginas ?? 1
    totalRegistros.value = payload?.total ?? entidades.value.length
  } finally {
    cargando.value = false
  }
}

const TIPOS_VALIDOS  = ['image/jpeg', 'image/png', 'image/webp']
const MAX_BYTES      = 5 * 1024 * 1024

function seleccionarPortada(e) {
  const file = e.target.files[0]
  errorPortada.value = null
  if (!file) return
  if (!TIPOS_VALIDOS.includes(file.type)) {
    errorPortada.value = 'Formato no válido. Usa: JPG, PNG o WEBP.'
    e.target.value = ''
    return
  }
  if (file.size > MAX_BYTES) {
    errorPortada.value = `La imagen pesa ${(file.size / 1024 / 1024).toFixed(1)} MB. El máximo es 5 MB.`
    e.target.value = ''
    return
  }
  portadaFile.value = file
  imgPortada.value  = URL.createObjectURL(file)
}

async function confirmarPortada() {
  if (!portadaFile.value || !tipoData.value) return
  const fd = new FormData()
  fd.append('imagen', portadaFile.value)
  await api.post(ADMIN.TIPO_IMAGEN(tipoData.value.id), fd, { headers: { 'Content-Type': 'multipart/form-data' } })
  portadaFile.value = null
  toast.exito('Imagen de portada actualizada.')
}

function cancelarPortada() {
  portadaFile.value = null
  cargarTipo()
}

async function toggleEstado(entidad) {
  const accion = entidad.estado ? 'desactivar' : 'activar'
  const ok = await confirmar({
    titulo: `¿${capitalizar(accion)} establecimiento?`,
    texto: `"${entidad.nombre_comercial}" será ${entidad.estado ? 'ocultado del sitio público' : 'visible en el sitio público'}.`,
    textoBoton: `Sí, ${accion}`,
  })
  if (!ok) return
  try {
    await api.patch(ADMIN.ENTIDAD_ESTADO(entidad.id), { estado: !entidad.estado })
    entidad.estado = !entidad.estado
    toast.exito(`Establecimiento ${entidad.estado ? 'activado' : 'desactivado'}.`)
  } catch {
    alertaError('No se pudo cambiar el estado del establecimiento.')
  }
}

function verDetalle(e) {
  detalle.value = e
  new Modal(document.getElementById('modalDetalle')).show()
}

function abrirModalAgregar() {
  formNueva.value = {
    nombre_comercial: '', razon_social: '', rut: '', telefono: '',
    hora_atencion: '', sitio_web: '', estado: true, direccion: '', descripcion: '',
    subtipos_ids: []
  }
  logoNuevaFile.value = null
  inputLogoNuevaComp.value?.reset()
  new Modal(document.getElementById('modalAgregar')).show()
}

async function guardarNueva() {
  guardando.value = true
  try {
    const fd = new FormData()
    const camposTexto = ['nombre_comercial', 'razon_social', 'rut', 'telefono', 'hora_atencion', 'sitio_web', 'direccion', 'descripcion']
    camposTexto.forEach(k => fd.append(k, formNueva.value[k] ?? ''))
    fd.append('estado', formNueva.value.estado ? 1 : 0)
    fd.append('lugar_id', 1)
    if (tipoData.value) fd.append('tipo_entidad_id', tipoData.value.id)
    formNueva.value.subtipos_ids.forEach(id => fd.append('subtipos_ids[]', id))
    if (logoNuevaFile.value) fd.append('logo', logoNuevaFile.value)
    await api.post(ADMIN.ENTIDADES, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    Modal.getInstance(document.getElementById('modalAgregar')).hide()
    toast.exito('Establecimiento agregado correctamente.')
    await cargarEntidades()
  } catch (err) {
    const msg = err.response?.data?.message ?? 'No se pudo guardar el establecimiento.'
    alertaError(msg)
  } finally {
    guardando.value = false
  }
}

function abrirModalEditar(e) {
  formEditar.value   = { ...e, subtipos_ids: e.subtipos?.map(s => s.id) ?? [], lugar_id: e.lugar_id ?? 1 }
  logoPreview.value  = e.imagenes?.[0]?.url_completa ?? e.imagen ?? null
  logoEditarFile.value = null
  inputLogoEditarComp.value?.reset()
  new Modal(document.getElementById('modalEditar')).show()
}

function onLogoEditarChange(file) {
  logoEditarFile.value = file
  if (file) logoPreview.value = URL.createObjectURL(file)
}

async function guardarEdicion() {
  guardando.value = true
  try {
    const fd = new FormData()
    const camposTexto = ['nombre_comercial', 'razon_social', 'rut', 'telefono', 'hora_atencion', 'sitio_web', 'direccion', 'descripcion']
    camposTexto.forEach(k => fd.append(k, formEditar.value[k] ?? ''))
    fd.append('estado', formEditar.value.estado ? 1 : 0)
    fd.append('lugar_id', formEditar.value.lugar_id ?? 1)
    fd.append('_method', 'PUT')
    ;(formEditar.value.subtipos_ids ?? []).forEach(id => fd.append('subtipos_ids[]', id))
    if (logoEditarFile.value) fd.append('logo', logoEditarFile.value)
    await api.post(ADMIN.ENTIDAD(formEditar.value.id), fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    Modal.getInstance(document.getElementById('modalEditar')).hide()
    toast.exito('Establecimiento actualizado correctamente.')
    await cargarEntidades()
  } catch (err) {
    const msg = err.response?.data?.message ?? 'No se pudo actualizar el establecimiento.'
    alertaError(msg)
  } finally {
    guardando.value = false
  }
}

function capitalizar(texto) {
  if (!texto) return ''
  return texto.charAt(0).toUpperCase() + texto.slice(1)
}

function formatNombre(txt) {
  return String(txt).replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

</script>
