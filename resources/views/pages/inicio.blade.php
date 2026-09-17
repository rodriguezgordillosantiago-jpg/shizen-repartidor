@extends('layouts.app')
@section('content')
<div class="scroll" id="content">
  <!-- Header filled by JS -->
</div>
@endsection

@push('scripts')
<script>
async function render() {
  const perfil = (typeof getPerfil === 'function') ? getPerfil() : { nombre: 'Santiago', apellido: 'Vargas', entregasHoy: 6, gananciasHoy: 25800, calificacion: 4.8 };
  let activos = [];
  let disponibles = [];
  try {
    if (typeof cargarEntregas === 'function') {
      const [activeResult, availableResult] = await Promise.all([cargarEntregas("active"), cargarEntregas("available")]);
      activos = (activeResult && activeResult.items) || [];
      disponibles = (availableResult && availableResult.items) || [];
    }
  } catch (e) {
    activos = (typeof getActivos === 'function') ? getActivos() : [];
    disponibles = (typeof getDisponibles === 'function') ? getDisponibles() : [];
  }
  const ca = activos.length;
  const lleno = ca >= 4;

  document.getElementById("content").innerHTML = `
    <!-- Green header -->
    <div style="background:linear-gradient(135deg,#4c9540,#2d6b22);padding:40px 20px 24px">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div>
          <p style="margin:0 0 2px;font-size:13px;color:rgba(255,255,255,.75);font-weight:600">Bienvenido de vuelta</p>
          <h1 style="margin:0;font-size:22px;font-weight:800;color:white">${perfil.nombre} ${perfil.apellido}</h1>
        </div>
        <img src="{{ asset('assets/logo_blanco.png') }}" alt="Shizen"
          style="height:64px;width:128px;object-fit:contain;filter:brightness(0) invert(1)">
      </div>
      <!-- Stats row -->
      <div style="display:flex;gap:10px">
        <div style="flex:1;background:white;border-radius:14px;padding:10px;text-align:center">
          <p style="margin:0 0 2px;font-size:10px;color:#9ca3af;font-weight:600">Entregas hoy</p>
          <p style="margin:0;font-size:14px;font-weight:800;color:#4c9540">${perfil.entregasHoy}</p>
        </div>
        <div style="flex:1;background:white;border-radius:14px;padding:10px;text-align:center">
          <p style="margin:0 0 2px;font-size:10px;color:#9ca3af;font-weight:600">Ganancias</p>
          <p style="margin:0;font-size:14px;font-weight:800;color:#f97316">${typeof fmt === 'function' ? fmt(perfil.gananciasHoy) : '$' + perfil.gananciasHoy}</p>
        </div>
        <div style="flex:1;background:white;border-radius:14px;padding:10px;text-align:center">
          <p style="margin:0 0 2px;font-size:10px;color:#9ca3af;font-weight:600">Calificación</p>
          <p style="margin:0;font-size:14px;font-weight:800;color:#4c9540">⭐ ${perfil.calificacion}</p>
        </div>
      </div>
    </div>

    <!-- Cupo banner -->
    <div style="margin:16px 16px 0">
      <div style="border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;
        background:${lleno ? "#fef2f2" : "#f0fdf4"};border:1px solid ${lleno ? "#fecaca" : "#bbf7d0"}">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="${lleno ? "#ef4444" : "#4c9540"}" stroke-width="2" style="flex-shrink:0">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <div>
          <p style="margin:0 0 2px;font-size:13px;font-weight:700;color:${lleno ? "#dc2626" : "#166534"}">
            ${lleno ? "Cupo lleno — máx. 4 pedidos" : `Pedidos activos: ${ca} / 4`}
          </p>
          <p style="margin:0;font-size:11px;color:#6b7280">
            ${lleno ? "Entrega los actuales para aceptar más" : `Puedes aceptar ${4 - ca} más`}
          </p>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <div style="padding:20px 16px 0">
      <h2 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#1a1a1a">Tipos de entrega</h2>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px">
        ${[
          { nombre: "Restaurantes", icono: "🍔", bg: "#fff7ed" },
          { nombre: "Mercado", icono: "🛒", bg: "#f0fdf4" },
          { nombre: "Courier", icono: "📦", bg: "#eff6ff" },
          { nombre: "Farmacia", icono: "💊", bg: "#faf5ff" },
        ]
          .map(
            (c) => `
          <div style="display:flex;flex-direction:column;align-items:center;gap:6px;cursor:pointer">
            <div style="width:56px;height:56px;border-radius:16px;background:${c.bg};
              display:flex;align-items:center;justify-content:center;font-size:24px;
              box-shadow:0 2px 6px rgba(0,0,0,.08)">${c.icono}</div>
            <p style="margin:0;font-size:10px;color:#4b5563;font-weight:600;text-align:center;line-height:1.3">${c.nombre}</p>
          </div>`
          )
          .join("")}
      </div>
    </div>

    <!-- Available orders preview -->
    <div style="padding:20px 16px 0">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h2 style="margin:0;font-size:15px;font-weight:800;color:#1a1a1a">Nuevos pedidos</h2>
        <a href="{{ route('pedidos') }}" style="font-size:12px;color:#f97316;font-weight:700;text-decoration:none">Ver todos →</a>
      </div>
      ${
        disponibles.length === 0
          ? `
        <div style="background:white;border-radius:16px;border:1px solid rgba(0,0,0,.07);padding:24px;text-align:center">
          <p style="margin:0;color:#9ca3af;font-size:13px">No hay pedidos disponibles ahora</p>
        </div>`
          : disponibles
              .slice(0, 2)
              .map(
                (p) => `
        <div class="card" style="padding:14px;margin-bottom:12px">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
            <img src="${p.avatar || 'https://i.pravatar.cc/150?img=3'}" alt="${p.cliente || 'Cliente'}" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0">
            <div style="flex:1;min-width:0">
              <p style="margin:0 0 2px;font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${p.cliente_nombre || p.cliente || 'Cliente'}</p>
              <p style="margin:0;font-size:11px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${p.negocio_nombre || p.origen || 'Restaurante'}</p>
            </div>
            <div style="text-align:right;flex-shrink:0">
              <p style="margin:0 0 2px;font-size:13px;font-weight:800;color:#4c9540">${typeof fmt === 'function' ? fmt(p.total || p.ganancia || 0) : '$' + p.total}</p>
              <p style="margin:0;font-size:10px;color:#9ca3af">Pedido #${p.id_pedido || p.id || '101'}</p>
            </div>
          </div>
          <div style="display:flex;gap:8px">
            <button class="btn-green" style="flex:1;height:36px;font-size:12px" ${lleno ? 'disabled style="flex:1;height:36px;font-size:12px;background:#a0c99b;cursor:not-allowed"' : ""} onclick="window.location.href='{{ route('pedidos') }}'">
              ${lleno ? "Cupo lleno" : "Aceptar"}
            </button>
          </div>
        </div>`
              )
              .join("")
      }
    </div>
  `;
}

document.addEventListener('DOMContentLoaded', function() {
  render();
  if (typeof iniciarBotonEstadoRepartidor === 'function') iniciarBotonEstadoRepartidor();
  if (typeof initChat === 'function') initChat();
});
</script>
@endpush
