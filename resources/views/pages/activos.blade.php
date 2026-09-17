@extends('layouts.app')
@section('content')
<main class="scroll">
  <h1 style="margin:0 0 4px;font-size:20px">Pedidos activos</h1>
  <p style="margin:0 0 18px;color:#9ca3af;font-size:12px">Gestiona las entregas en curso</p>
  <div id="orders"></div>
</main>
@endsection

@push('scripts')
<script>
function fmt(n) {
  return '$' + Number(n || 0).toLocaleString('es-CO');
}

async function render() {
  try {
    const result = await cargarEntregas('active');
    const list = result.items || [];
    document.querySelector('#orders').innerHTML = list.length
      ? list.map(p => {
          const moving = p.entrega_estado === 'En camino';
          const codigoR = p.codigo_entrega || '';
          const codigoC = p.codigo_c || '';
          let card = `<article class="card order">
            <div class="order-head">
              <div class="order-main">
                <p class="name">${p.cliente_nombre || 'Cliente'} ${p.cliente_apellido || ''}</p>
                <p class="muted" style="color:${moving?'#f97316':'#4c9540'};font-weight:700">
                  ${moving ? '🛵 En camino al destino' : '⏳ Pedido aceptado'}
                </p>
              </div>
              <div class="earn"><strong>${fmt(p.total)}</strong></div>
            </div>
            <div class="route">📍 Recoger: ${p.negocio_nombre || 'Restaurante'}<br>🏁 Entregar: ${p.direccion_entrega || 'Dirección cliente'}</div>`;

          if (!moving) {
            card += `
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 14px;margin:10px 0;font-size:13px">
              <div style="font-weight:700;color:#1e40af;margin-bottom:4px">🔑 Tu código para el negocio:</div>
              <div style="font-size:22px;font-weight:900;letter-spacing:4px;color:#1d4ed8;text-align:center">${codigoR}</div>
              <div style="font-size:11px;color:#6b7280;margin-top:4px;text-align:center">Muéstraselo al negocio/cocina para que confirmen</div>
            </div>
            <div class="actions">
              <button class="btn-green" onclick="pickUp('${p.id_entrega}')">🛵 Recogí el pedido</button>
            </div>`;
          } else {
            card += `
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin:10px 0;font-size:13px">
              <div style="font-weight:700;color:#166534;margin-bottom:6px">✅ Ingresa el código que te dio el cliente:</div>
              <div style="display:flex;gap:8px">
                <input id="codigo-c-${p.id_entrega}" type="text" inputmode="numeric" maxlength="6"
                       style="flex:1;padding:8px 10px;border:2px solid #4ade80;border-radius:8px;font-size:18px;font-weight:700;letter-spacing:3px;text-align:center"
                       placeholder="000000">
                <button class="btn-green" style="white-space:nowrap" onclick="deliverWithClientCode('${p.id_entrega}')">🏁 Entregar</button>
              </div>
              ${codigoC ? `<div style="font-size:11px;color:#6b7280;margin-top:4px">Código de prueba del cliente: <strong style="color:#166534">${codigoC}</strong></div>` : ''}
            </div>`;
          }

          card += `</article>`;
          return card;
        }).join('')
      : `<div class="card" style="padding:32px;text-align:center;color:#9ca3af;font-size:13px">No tienes pedidos activos</div>`;
  } catch (e) {
    console.error(e);
  }
}

async function pickUp(id) {
  try {
    await actualizarEntrega('advance', id);
    await render();
    if (typeof updateNavBadges === 'function') updateNavBadges();
  } catch (e) {
    alert(e.message);
  }
}

async function deliverWithClientCode(id) {
  const input = document.getElementById('codigo-c-' + id);
  const codigo = (input ? input.value.trim() : '');
  if (!codigo) {
    alert('Ingresa el código que te dio el cliente.');
    return;
  }
  try {
    const body = new URLSearchParams({ action: 'deliver_client_code', id_entrega: String(id), codigo_cliente: codigo });
    const resp = await fetch(window.repartidorApiUrl || '/api/entregas', { method: 'POST', body, credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', Accept: 'application/json' } });
    const result = await resp.json();
    if (!resp.ok || result.error) throw new Error(result.error || 'Error al finalizar.');
    alert('¡Entrega finalizada correctamente!');
    await render();
    if (typeof updateNavBadges === 'function') updateNavBadges();
  } catch (e) {
    alert(e.message);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  render();
  iniciarBotonEstadoRepartidor();
  if (typeof updateNavBadges === 'function') updateNavBadges();
});
</script>
@endpush
