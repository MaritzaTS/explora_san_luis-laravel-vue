export const AUTH = {
  LOGIN:           '/auth/login',
  REGISTER:        '/auth/register',
  LOGOUT:          '/auth/logout',
  ME:              '/auth/me',
  VERIFICAR:       '/auth/verificar-codigo',
  CHECK_EMAIL:     '/auth/check-email',
  GOOGLE_REDIRECT: '/auth/google/redirect',
}

export const PUBLICO = {
  TIPOS:            '/tipos',
  ENTIDADES:        (slug) => `/entidades/${slug}`,
  SITIOS:           '/sitios-turisticos',
  EVENTOS:          '/eventos',
  RESENAS:          '/resenas',
}

export const ADMIN = {
  DASHBOARD_STATS:  '/admin/dashboard/stats',

  ENTIDADES:        '/admin/entidades',
  ENTIDAD:          (id) => `/admin/entidades/${id}`,
  ENTIDAD_ESTADO:   (id) => `/admin/entidades/${id}/estado`,

  SITIOS:           '/admin/sitios-turisticos',
  SITIO:            (id) => `/admin/sitios-turisticos/${id}`,
  SITIO_ESTADO:     (id) => `/admin/sitios-turisticos/${id}/estado`,

  EVENTOS:          '/admin/eventos',
  EVENTO:           (id) => `/admin/eventos/${id}`,
  EVENTO_ESTADO:  (id) => `/admin/eventos/${id}/estado`,

  USUARIOS:         '/admin/usuarios',
  USUARIO_ESTADO:   (id) => `/admin/usuarios/${id}/estado`,

  RESENAS:          '/admin/resenas',
  RESENA_ESTADO:    (id) => `/admin/resenas/${id}/estado`,

  TIPO_IMAGEN:      (id) => `/admin/tipos/${id}/imagen`,
}

export const COMERCIO = {
  REGISTRAR: '/comercios/registrar',
}
