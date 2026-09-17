@extends('layouts.app')
@section('content')
<main class="scroll">
  @if(session('status'))
    <div style="margin:16px 16px 0;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;font-size:14px;font-weight:600">
      {{ session('status') }}
    </div>
  @endif

  <!-- Encabezado verde -->
  <div class="green-header" style="background:linear-gradient(135deg,#f0faf0,#ffffff);border-bottom:1px solid #c8e6c9;padding:38px 24px 28px;position:relative;text-align:center">
    <img src="{{ asset('assets/logo_color.png') }}" alt="Shizen" style="position:absolute;top:30px;right:24px;height:32px;object-fit:contain;opacity:0.9">
    <div class="profile-avatar" style="display:flex;flex-direction:column;align-items:center">
      <div style="width:88px;height:88px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:12px;box-shadow:0 4px 16px rgba(0,0,0,0.15);font-size:32px;font-weight:bold;color:#4c9540">
        {{ strtoupper(substr($repartidor['nombre'] ?? 'S', 0, 1) . substr($repartidor['apellido'] ?? 'V', 0, 1)) }}
      </div>
      <h1 style="color:#1b3a1d;font-size:22px;font-weight:700;margin:0 0 2px 0">
        {{ $repartidor['nombre'] ?? 'Santiago' }} {{ $repartidor['apellido'] ?? 'Vargas' }}
      </h1>
      <p style="color:#555;font-size:13px;margin:0">
        {{ $repartidor['email'] ?? 'repartidor@shizen.test' }} · {{ $repartidor['vehiculo'] ?? 'Moto' }}
      </p>
      <div style="margin-top:12px;display:inline-flex;align-items:center;background:#e8f5e9;padding:4px 12px;border-radius:16px">
        <span style="width:8px;height:8px;background:#5cb85c;border-radius:50%;margin-right:6px"></span>
        <span style="color:#2d6a31;font-size:12px;font-weight:500">En línea</span>
      </div>
    </div>
  </div>

  <!-- Información personal (Editable) -->
  <div style="padding:20px 16px 0">
    <div class="card-container" style="background:white;border-radius:12px;border:1px solid #e8f5e9;overflow:hidden;position:relative">
      <div class="card__title-row" style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px">
        <h2 class="section-title" style="margin:0;font-size:17px;font-weight:700">Datos del Repartidor</h2>
      </div>

      <!-- Vista Solo Lectura -->
      <div class="card__details card__details--view">
        <button type="button" class="profile-row profile-row--editable" id="open-profile-modal-name" onclick="openModal()" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border:none;background:none;border-top:1px solid #f3f4f6;font-family:'Inter',sans-serif;cursor:pointer;text-align:left">
          <span class="profile-row__content" style="display:flex;align-items:center;gap:12px">
            <span class="profile-row__icon" style="font-size:18px">👤</span>
            <span>
              <small style="display:block;font-size:12px;color:#9ca3af">Nombre</small>
              <strong style="font-size:15px;font-weight:600;color:#111827">{{ $repartidor['nombre'] ?? 'Santiago' }} {{ $repartidor['apellido'] ?? 'Vargas' }}</strong>
            </span>
          </span>
          <span class="profile-row__edit" style="font-size:14px;font-weight:600;color:#3a8c3f">Editar</span>
        </button>

        <div class="profile-row" style="width:100%;display:flex;align-items:center;padding:14px 16px;border-top:1px solid #e5e7eb;cursor:default">
          <div style="display:flex;align-items:center;gap:12px">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <div style="text-align:left">
              <p style="font-size:12px;color:#9ca3af;margin:0">Correo electrónico</p>
              <p style="font-size:15px;font-weight:600;color:#111827;margin:0">{{ $repartidor['email'] ?? 'repartidor@shizen.test' }}</p>
            </div>
          </div>
        </div>

        <div class="profile-row" style="width:100%;display:flex;align-items:center;padding:14px 16px;border-top:1px solid #e5e7eb;cursor:default">
          <div style="display:flex;align-items:center;gap:12px">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C7 2 3 6 3 11V22H21V11C21 6 17 2 12 2Z"/>
            </svg>
            <div style="text-align:left">
              <p style="font-size:12px;color:#9ca3af;margin:0">Vehículo</p>
              <p style="font-size:15px;font-weight:600;color:#111827;margin:0">{{ $repartidor['vehiculo'] ?? 'Moto' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Documentos del vehículo -->
    <section class="documents-card" style="margin-top:20px">
      <h2 class="section-title" style="font-size:17px;font-weight:700;margin-bottom:12px">Documentos del vehículo</h2>
      <div class="vehicle-documents">
        <div style="background:white;border-radius:12px;border:1px solid #e8f5e9;padding:14px 16px;font-size:13px;color:#6b7280;text-align:center">
          Documentación en regla y verificada.
        </div>
      </div>
    </section>
  </div>

  <!-- Modal Editar Perfil -->
  <div class="profile-modal" id="profile-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div class="profile-modal__content" style="background:white;border-radius:16px;width:100%;max-width:360px;padding:20px;box-shadow:0 10px 25px rgba(0,0,0,0.2)">
      <div class="profile-modal__header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 id="profile-modal-title" style="font-size:18px;font-weight:700;margin:0">Editar perfil</h2>
        <button type="button" onclick="closeModal()" style="border:none;background:none;font-size:24px;cursor:pointer;color:#9ca3af">×</button>
      </div>
      <form action="{{ route('perfil.update') }}" method="POST" class="profile-form">
        @csrf
        <label class="field-group" style="display:block;margin-bottom:12px">
          <small style="display:block;margin-bottom:4px;color:#6b7280;font-size:13px">Nombre</small>
          <input class="field" type="text" name="nombre" value="{{ $repartidor['nombre'] ?? '' }}" maxlength="100" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-family:'Inter',sans-serif;font-size:15px">
        </label>
        <label class="field-group" style="display:block;margin-bottom:12px">
          <small style="display:block;margin-bottom:4px;color:#6b7280;font-size:13px">Apellido</small>
          <input class="field" type="text" name="apellido" value="{{ $repartidor['apellido'] ?? '' }}" maxlength="100" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-family:'Inter',sans-serif;font-size:15px">
        </label>
        <label class="field-group" style="display:block;margin-bottom:16px">
          <small style="display:block;margin-bottom:4px;color:#6b7280;font-size:13px">Correo electrónico (no editable)</small>
          <input class="field" type="email" value="{{ $repartidor['email'] ?? '' }}" readonly style="width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;background:#f9fafb;color:#6b7280;font-family:'Inter',sans-serif;font-size:15px">
        </label>
        <button type="submit" class="btn-green" style="display:flex;align-items:center;justify-content:center;background:#4c9540;color:white;padding:12px;border-radius:10px;font-weight:700;cursor:pointer;width:100%;border:none;font-family:'Inter',sans-serif;font-size:15px">
          Guardar cambios
        </button>
      </form>
    </div>
  </div>

  <!-- Estadísticas y Opciones -->
  <div style="padding:20px 16px 0">
    <h2 class="section-title" style="font-size:17px;font-weight:700;margin-bottom:12px">Actividad</h2>
    <div class="card-container" style="background:white;border-radius:12px;border:1px solid #e8f5e9;overflow:hidden">
      <!-- Entregas hoy -->
      <div class="profile-row" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #f3f4f6;cursor:default">
        <div style="display:flex;align-items:center;gap:12px">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2">
            <path d="M5 8h14M5 8a2 2 0 1 0 0-4h14a2 2 0 1 0 0 4M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8m-9 4h4"/>
          </svg>
          <p style="font-size:15px;font-weight:600;margin:0">Entregas hoy</p>
        </div>
        <div style="display:flex;align-items:center">
          <span style="font-size:16px;font-weight:700;color:#4c9540" id="profile-entregas-count">6</span>
        </div>
      </div>

      <!-- Ganancias -->
      <div class="profile-row" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #f3f4f6;cursor:default">
        <div style="display:flex;align-items:center;gap:12px">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
          <p style="font-size:15px;font-weight:600;margin:0">Ganancias</p>
        </div>
        <div style="display:flex;align-items:center">
          <span style="font-size:16px;font-weight:700;color:#4c9540" id="profile-ganancias-count">$25.800</span>
        </div>
      </div>

      <!-- Configuración link -->
      <a href="{{ route('configuracion') }}" class="profile-row" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:14px 16px;text-decoration:none;color:inherit;transition:background 0.15s">
        <div style="display:flex;align-items:center;gap:12px">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
          </svg>
          <p style="font-size:15px;font-weight:600;margin:0">Configuración</p>
        </div>
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2">
          <polyline points="9,18 15,12 9,6"/>
        </svg>
      </a>
    </div>
  </div>

  <!-- Cerrar sesión -->
  <div style="padding:20px 16px 32px">
    <form action="{{ route('logout') }}" method="post">
      @csrf
      <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;background:#fef2f2;border:none;border-radius:12px;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.2s">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16,17 21,12 16,7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span style="font-size:15px;font-weight:700;color:#dc2626">Cerrar Sesión</span>
      </button>
    </form>
  </div>
</main>
@endsection

@push('scripts')
<script>
function openModal() {
  document.getElementById('profile-modal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('profile-modal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  if (typeof getPerfil === 'function') {
    const p = getPerfil();
    if (p) {
      if (document.getElementById('profile-entregas-count')) {
        document.getElementById('profile-entregas-count').textContent = p.entregasHoy || '6';
      }
      if (document.getElementById('profile-ganancias-count') && typeof fmt === 'function') {
        document.getElementById('profile-ganancias-count').textContent = fmt(p.gananciasHoy || 25800);
      }
    }
  }
  if (typeof updateNavBadges === 'function') updateNavBadges();
  if (typeof iniciarBotonEstadoRepartidor === 'function') iniciarBotonEstadoRepartidor();
});
</script>
@endpush
