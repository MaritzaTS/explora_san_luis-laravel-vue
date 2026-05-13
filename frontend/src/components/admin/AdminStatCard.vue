<template>
  <div class="stat-card" :class="`stat-card--${variante}`">
    <div class="stat-header">
      <span class="stat-label">{{ label }}</span>
      <div class="stat-icon-wrap" :class="`stat-icon--${variante}`">
        <i :class="icono" class="stat-icon"></i>
      </div>
    </div>
    <h2 class="stat-valor">{{ valor }}</h2>
    <p v-if="descripcion" class="stat-desc">{{ descripcion }}</p>
  </div>
</template>

<script setup>
defineProps({
  label:       { type: String, required: true },
  valor:       { type: [Number, String], required: true },
  icono:       { type: String, default: 'bi bi-bar-chart-fill' },
  variante:    { type: String, default: 'default' },   // default, success, danger, warning, primary
  descripcion: { type: String, default: '' },
})
</script>

<style scoped>
.stat-card {
  background: #fff;
  border-radius: 14px;
  padding: 20px 22px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
  border: 1px solid #f0f0f0;
  display: flex;
  flex-direction: column;
  gap: 10px;
  transition: box-shadow 0.2s ease;
}

.stat-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.stat-label {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  color: #6b7280;
}

.stat-icon-wrap {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon {
  font-size: 1.1rem;
}

.stat-valor {
  font-size: 1.85rem;
  font-weight: 800;
  margin: 0;
  line-height: 1;
  color: #111827;
}

.stat-desc {
  font-size: 0.78rem;
  color: #9ca3af;
  margin: 0;
}

/* Variantes */
.stat-card--default  { border-left: 3px solid #374151; }
.stat-icon--default  { background: #f3f4f6; }
.stat-icon--default .stat-icon { color: #374151; }

.stat-card--success  { border-left: 3px solid #16a34a; }
.stat-icon--success  { background: #f0fdf4; }
.stat-icon--success .stat-icon { color: #16a34a; }
.stat-card--success .stat-valor { color: #16a34a; }

.stat-card--danger   { border-left: 3px solid #dc2626; }
.stat-icon--danger   { background: #fef2f2; }
.stat-icon--danger .stat-icon { color: #dc2626; }
.stat-card--danger .stat-valor { color: #dc2626; }

.stat-card--warning  { border-left: 3px solid #d97706; }
.stat-icon--warning  { background: #fffbeb; }
.stat-icon--warning .stat-icon { color: #d97706; }
.stat-card--warning .stat-valor { color: #d97706; }

.stat-card--primary  { border-left: 3px solid #2563eb; }
.stat-icon--primary  { background: #eff6ff; }
.stat-icon--primary .stat-icon { color: #2563eb; }
.stat-card--primary .stat-valor { color: #2563eb; }
</style>
