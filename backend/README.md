Explora San Luis — API Backend
API REST para la plataforma de turismo del municipio de San Luis, Antioquia (Colombia). Construida con Laravel 13.

Requisitos

PHP 8.2+
Composer
MySQL 8.0+
Node.js 18+ (solo para el frontend, no para el backend)


Instalación
bash# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/explora-san-luis-api.git
cd explora-san-luis-api

# 2. Instalar dependencias
composer install

# 3. Copiar archivo de entorno
cp .env.example .env

# 4. Configurar variables de entorno (ver sección de configuración)

# 5. Generar clave de la aplicación
php artisan key:generate

# 6. Generar clave JWT
php artisan jwt:secret

# 7. Crear el symlink de storage
php artisan storage:link

# 8. Ejecutar migraciones y seeders
php artisan migrate --seed

# 9. Levantar el servidor
php artisan serve
El servidor estará disponible en http://localhost:8000.

Configuración del .env
Base de datos
envDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bd_explora_san_luis
DB_USERNAME=root
DB_PASSWORD=
JWT
envJWT_SECRET=            # se genera con php artisan jwt:secret
JWT_TTL=1440           # 24 horas en minutos
JWT_REFRESH_TTL=20160  # 2 semanas en minutos
JWT_ALGO=HS256
Google OAuth
envGOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=http://localhost:8000/api/auth/google/callback
Mail (SMTP)
envMAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@explorasanluis.com"
MAIL_FROM_NAME="Explora San Luis"
Para desarrollo se recomienda Mailtrap (gratis). Para producción, configurar Gmail con App Passwords u otro proveedor SMTP.
Frontend y CORS
envFRONTEND_URL=http://localhost:5173
Timezone
envAPP_TIMEZONE=America/Bogota

Stack tecnológico
TecnologíaUsoLaravel 13Framework PHPtymon/jwt-authAutenticación JWTlaravel/socialiteGoogle OAuth 2.0Laravel MailEnvío de emails (SMTP)MySQL 8Base de datosLaravel StorageGestión de archivos/imágenes

Arquitectura
El proyecto sigue una arquitectura por capas con separación de responsabilidades:
Request HTTP
    ↓
FormRequest          → Validación de datos de entrada
    ↓
Controller           → Recibe la petición y delega
    ↓
DTO                  → Empaqueta datos limpios e inmutables
    ↓
Service              → Orquesta la lógica de negocio
    ↓
Repository           → Acceso a la base de datos (Eloquent)
    ↓
Model                → Representación de la tabla
    ↓
Resource             → Formatea la respuesta JSON
    ↓
JSON Response        → { status, message, data }
Capas adicionales
CapaResponsabilidadEvents / ListenersDesacoplar acciones secundarias (ej: enviar email después de registrar)ExceptionsExcepciones por dominio con factory methods (AuthException, EntidadException, etc.)EnumsValores fijos del dominio (RolEnum)TraitsFuncionalidad reutilizable (ApiResponse)IntegrationsServicios externos (GoogleAuthService)

Estructura de carpetas
app/
├── DTOs/
│   ├── Auth/
│   │   ├── RegisterDTO.php
│   │   ├── LoginDTO.php
│   │   └── VerificarCodigoDTO.php
│   ├── EntidadDTO.php
│   ├── SitioTuristicoDTO.php
│   └── EventoDTO.php
│
├── Enums/
│   └── RolEnum.php
│
├── Events/Auth/
│   └── UsuarioRegistrado.php
│
├── Exceptions/
│   ├── AuthException.php
│   ├── EntidadException.php
│   ├── EventoException.php
│   ├── ImagenException.php
│   ├── SitioTuristicoException.php
│   └── UsuarioException.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthController.php
│   │   │   └── GoogleAuthController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── EntidadController.php
│   │   │   ├── EventoController.php
│   │   │   ├── SitioTuristicoController.php
│   │   │   └── UsuarioController.php
│   │   ├── CatalogoController.php
│   │   ├── EntidadPublicaController.php
│   │   ├── EventoController.php
│   │   └── SitioTuristicoController.php
│   │
│   ├── Middleware/
│   │   └── IsAdmin.php
│   │
│   ├── Requests/
│   │   ├── Auth/
│   │   │   ├── CheckEmailRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   └── VerificarCodigoRequest.php
│   │   ├── Admin/
│   │   │   ├── CambiarEstadoRequest.php
│   │   │   ├── StoreEntidadRequest.php
│   │   │   ├── StoreEventoRequest.php
│   │   │   ├── StoreSitioRequest.php
│   │   │   ├── UpdateEntidadRequest.php
│   │   │   ├── UpdateEventoRequest.php
│   │   │   └── UpdateSitioRequest.php
│   │   └── Shared/
│   │       └── FiltrarEntidadesRequest.php
│   │
│   └── Resources/
│       ├── EntidadResource.php
│       ├── EventoResource.php
│       ├── ImagenEventoResource.php
│       ├── SitioTuristicoResource.php
│       ├── TipoEntidadResource.php
│       ├── TipoEspecificoResource.php
│       └── UsuarioResource.php
│
├── Integrations/Google/
│   └── GoogleAuthService.php
│
├── Listeners/Auth/
│   └── EnviarEmailVerificacionListener.php
│
├── Mail/
│   └── VerificacionMail.php
│
├── Models/
│   ├── Entidad.php
│   ├── Evento.php
│   ├── ImagenEntidad.php
│   ├── ImagenEvento.php
│   ├── Lugar.php
│   ├── Resena.php
│   ├── Rol.php
│   ├── SitioTuristico.php
│   ├── TipoEntidad.php
│   ├── TipoEspecifico.php
│   └── Usuario.php
│
├── Providers/
│   └── AppServiceProvider.php
│
├── Repositories/
│   ├── Contracts/
│   │   ├── EntidadRepositoryInterface.php
│   │   ├── EventoRepositoryInterface.php
│   │   ├── SitioTuristicoRepositoryInterface.php
│   │   └── UsuarioRepositoryInterface.php
│   ├── EntidadRepository.php
│   ├── EventoRepository.php
│   ├── SitioTuristicoRepository.php
│   └── UsuarioRepository.php
│
├── Services/
│   ├── Auth/
│   │   ├── AuthService.php
│   │   └── JwtService.php
│   ├── EntidadService.php
│   ├── EventoService.php
│   ├── ImagenService.php
│   ├── SitioTuristicoService.php
│   └── UsuarioService.php
│
└── Traits/
    └── ApiResponse.php

database/
├── migrations/     (12 migraciones)
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── RolSeeder.php
│   ├── LugarSeeder.php
│   ├── TipoEntidadSeeder.php
│   ├── TipoEspecificoSeeder.php
│   └── UsuarioSeeder.php
└── factories/
    └── UsuarioFactory.php

resources/views/emails/
└── verificacion.blade.php

Base de datos
Tablas (12)
TablaDescripciónSoftDeleterolesadmin, usuarioNousuariosUsuarios del sistemaSílugaresMunicipios (San Luis)Sítipos_entidadCategorías (gastronomía, alojamiento, etc.)Notipos_especificosSubtipos por categoríaNoentidadesNegocios registradosSíentidad_tipo_especificoPivote entidad ↔ subtipoNoimagenes_entidadesLogos de entidadesNositios_turisticosSitios naturales/patrimonialesSíeventosEventos culturales/festivosSíimagenes_eventosGalería de fotos de eventosNoresenasReseñas de usuariosSí
Datos iniciales (seeders)
Los seeders crean los datos mínimos para que el sistema funcione:

2 roles: admin, usuario
1 lugar: San Luis, Antioquia
5 tipos de entidad: Gastronomía, Recreación, Alojamiento, Transporte, Agencias Turísticas
13 subtipos: distribuidos entre los tipos (restaurante, hostal, glamping, etc.)
1 usuario admin: admin@admin.com / 123456

Resetear la base de datos
bashphp artisan migrate:fresh --seed

Endpoints API
Autenticación (públicas)
MétodoRutaDescripciónPOST/api/auth/registerRegistro con email + código verificaciónPOST/api/auth/verificar-codigoVerificar código 6 dígitos + retorna JWTPOST/api/auth/loginLogin email + contraseña + retorna JWTGET/api/auth/check-emailVerificar si email existeGET/api/auth/google/redirectURL de redirección a GoogleGET/api/auth/google/callbackCallback de Google + retorna JWT
Autenticación (protegidas — Authorization: Bearer {token})
MétodoRutaDescripciónGET/api/auth/meUsuario autenticado actualPOST/api/auth/logoutCerrar sesión (invalida token)
Públicas — Catálogo
MétodoRutaDescripciónGET/api/tiposTipos de entidad con subtiposGET/api/entidades/{slug}Entidades por tipo con filtros y paginaciónGET/api/sitios-turisticosSitios turísticos activosGET/api/eventosEventos vigentes
Admin (protegidas — auth:api + is_admin)
MétodoRutaDescripciónGET/api/admin/dashboard/statsEstadísticas del dashboardGET/api/admin/entidadesListar todas las entidadesPOST/api/admin/entidadesCrear entidadPUT/api/admin/entidades/{id}Actualizar entidadPATCH/api/admin/entidades/{id}/estadoActivar/desactivar entidadGET/api/admin/sitios-turisticosListar sitiosPOST/api/admin/sitios-turisticosCrear sitio (3 imágenes obligatorias)PUT/api/admin/sitios-turisticos/{id}Actualizar sitioPATCH/api/admin/sitios-turisticos/{id}/estadoActivar/desactivar sitioGET/api/admin/eventosListar eventosPOST/api/admin/eventosCrear eventoPUT/api/admin/eventos/{id}Actualizar eventoGET/api/admin/usuariosListar usuariosPATCH/api/admin/usuarios/{id}/estadoActivar/desactivar usuarioPOST/api/admin/tipos/{id}/imagenCambiar portada de categoría
Ver todas las rutas
bashphp artisan route:list --path=api

Autenticación
El sistema usa JWT (JSON Web Token) con la librería tymon/jwt-auth.
Flujo del token

El usuario hace login o verifica código.
El backend genera un JWT y lo retorna en data.token.
El frontend guarda el token en localStorage.
En cada petición protegida, el frontend envía: Authorization: Bearer {token}.
Para logout, el backend invalida el token y el frontend lo borra de localStorage.

Roles
RolIDAccesoadmin1Todo (público + admin)usuario2Solo rutas públicas
El middleware is_admin protege las rutas /api/admin/*. Verifica que rol_id === 1.

Formato de respuesta
Todas las respuestas siguen este formato:
json{
    "status": "success" | "error",
    "message": "Descripción legible",
    "data": { ... } | null,
    "error": "CODIGO_ERROR",       // solo en errores
    "errors": { "campo": [...] }   // solo en validación (422)
}
Implementado con el Trait ApiResponse que usan todos los controllers.

Imágenes
Las imágenes se almacenan en storage/app/public/ y son accesibles via URL gracias al symlink.
TipoCarpetaURL públicaLogos entidadesentidades/logos//storage/entidades/logos/{uuid}.jpgPortadas tipostipos/portadas//storage/tipos/portadas/{uuid}.pngSitios turísticossitios//storage/sitios/{uuid}.webpPosters eventoseventos/posters//storage/eventos/posters/{uuid}.jpgGalería eventoseventos/galeria//storage/eventos/galeria/{uuid}.jpg
Formatos aceptados: JPG, JPEG, PNG, WEBP. Tamaño máximo: 5MB.
Los nombres de archivo son UUID para evitar colisiones.

Comandos útiles
bash# Levantar servidor
php artisan serve

# Resetear BD completa
php artisan migrate:fresh --seed

# Limpiar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Ver rutas registradas
php artisan route:list --path=api

# Consola interactiva
php artisan tinker

# Crear symlink de storage
php artisan storage:link

Testing
bash# Ejecutar todos los tests
php artisan test

# Ejecutar con Pest
./vendor/bin/pest

Reglas de negocio importantes

Registro: Requiere verificación por código de 6 dígitos enviado al email. El código expira en 30 minutos con máximo 3 intentos.
Google OAuth: Si el email ya existe por registro manual, se vincula el id_google automáticamente sin crear duplicado.
Tipos de entidad: Son dinámicos — el admin puede crear nuevos. Cada tipo tiene un slug generado automáticamente para URLs.
Subtipos: Pertenecen a un tipo padre. Una entidad puede tener múltiples subtipos (relación many-to-many).
Filtro de entidades: Si no se envían subtipos, muestra todas las del tipo. Si se envían, filtra solo las que tengan al menos uno de los subtipos seleccionados.
Paginación pública: Fija en 6 entidades por página.
Paginación admin: 15 registros por página.
Sitios turísticos: Requieren exactamente 3 imágenes al crear. En update son opcionales.
Admin protegido: El usuario con id=1 no puede ser desactivado.
SoftDeletes: Entidades, sitios, eventos, usuarios y reseñas usan borrado lógico (deleted_at). El campo estado (activo/inactivo) es independiente y controlado por el admin.
