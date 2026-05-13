import { ref } from 'vue'

// Coordenadas de San Luis, Antioquia
const LAT = 6.0386
const LNG = -74.9758

export function useClima() {
  const clima       = ref(null)
  const cargando    = ref(false)
  const error       = ref(null)

  async function cargarClima() {
    cargando.value = true
    error.value    = null
    try {
      const url = `https://api.open-meteo.com/v1/forecast?latitude=${LAT}&longitude=${LNG}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&hourly=temperature_2m&forecast_days=1&timezone=America%2FBogota`
      const res  = await fetch(url)
      const data = await res.json()

      const code = data.current.weather_code
      clima.value = {
        temperatura : Math.round(data.current.temperature_2m),
        humedad     : data.current.relative_humidity_2m,
        viento      : Math.round(data.current.wind_speed_10m),
        condicion   : interpretarCodigo(code),
        icono       : iconoPorCodigo(code),
        horas       : data.hourly.time.slice(0, 8).map((h, i) => ({
          hora : h.split('T')[1].slice(0, 5),
          temp : Math.round(data.hourly.temperature_2m[i])
        }))
      }
    } catch (e) {
      error.value = 'No se pudo cargar el clima.'
    } finally {
      cargando.value = false
    }
  }

  function interpretarCodigo(code) {
    if (code === 0)               return 'Despejado'
    if (code <= 2)                return 'Parcialmente nublado'
    if (code === 3)               return 'Nublado'
    if (code >= 51 && code <= 67) return 'Lluvia'
    if (code >= 80 && code <= 82) return 'Chubascos'
    if (code >= 95)               return 'Tormenta'
    return 'Variable'
  }

  function iconoPorCodigo(code) {
    if (code === 0)               return 'bi-sun-fill'
    if (code <= 2)                return 'bi-cloud-sun-fill'
    if (code === 3)               return 'bi-clouds-fill'
    if (code >= 51 && code <= 67) return 'bi-cloud-rain-fill'
    if (code >= 80 && code <= 82) return 'bi-cloud-drizzle-fill'
    if (code >= 95)               return 'bi-cloud-lightning-rain-fill'
    return 'bi-cloud-fill'
  }

  return { clima, cargando, error, cargarClima }
}