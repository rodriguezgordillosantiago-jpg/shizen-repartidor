@extends('layouts.app')
@section('content')
<main class="scroll">
  <h1 style="margin:0 0 4px;font-size:20px">Pedidos activos</h1>
  <p class="muted" style="margin-bottom:18px">Gestiona las entregas en curso</p>
  <div id="orders"></div>
</main>
@endsection
@push('scripts')
<script>
function renderActive() {
  const list = getActivos();
  document.getElementById('orders').innerHTML = list.length
    ? list.map(p => {
        const moving = p.estado === 'en_camino';
        const codR = p.codigo_r || '123456';
        const codC = p.codigo_c || '654321';

        let card = `<article class="card order">
          <div class="order-head">
            <img class="avatar" src="${p.avatar}" alt="${p.cliente}">
            <div class="order-main">
              <p class="name">${p.cliente}</p>
              <p class="muted" style="color:${moving?'#f97316':'#4c9540'};font-weight:700">
                ${moving ? '🛵 En camino al destino' : '⏳ Pedido aceptado'}
              </p>
            </div>
            <div class="earn"><strong>${fmt(p.ganancia)}</strong><span>${p.distancia}</span></div>
          </div>
          <div class="route">📍 Recoger: ${p.origen}<br>🏁 Entregar: ${p.destino}</div>`;

        if (!moving) {
          card += `
          <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 14px;margin:10px 0;font-size:13px">
            <div style="font-weight:700;color:#1e40af;margin-bottom:4px">🔑 Tu código para el negocio:</div>
            <div style="font-size:22px;font-weight:900;letter-spacing:4px;color:#1d4ed8;text-align:center">${codR}</div>
            <div style="font-size:11px;color:#6b7280;margin-top:4px;text-align:center">Muéstraselo al negocio/cocina para que confirmen</div>
          </div>
          <div class="actions">
            <button class="btn-gray" onclick="cancelActive('${p.id}')">Cancelar</button>
            <button class="btn-green" onclick="advanceActive('${p.id}')">🛵 Recogí el pedido</button>
          </div>`;
        } else {
          card += `
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin:10px 0;font-size:13px">
            <div style="font-weight:700;color:#166534;margin-bottom:6px">✅ Ingresa el código que te dio el cliente:</div>
            <div style="display:flex;gap:8px">
              <input id="input-codc-${p.id}" type="text" inputmode="numeric" maxlength="6"
                     style="flex:1;padding:8px 10px;border:2px solid #4ade80;border-radius:8px;font-size:18px;font-weight:700;letter-spacing:3px;text-align:center"
                     placeholder="000000">
              <button class="btn-green" style="white-space:nowrap" onclick="deliverClientCode('${p.id}')">🏁 Entregar</button>
            </div>
            <div style="font-size:11px;color:#6b7280;margin-top:4px">Código de prueba del cliente: <strong style="color:#166534">${codC}</strong></div>
          </div>
          <div class="actions">
            <button class="btn-gray" onclick="cancelActive('${p.id}')">Cancelar</button>
          </div>`;
        }

        card += `</article>`;
        return card;
      }).join('')
    : '<div class="card" style="padding:32px;text-align:center;color:#9ca3af">No tienes pedidos activos</div>';
}

function advanceActive(id) {
  avanzarEstado(id);
  renderActive();
  updateNavBadges();
}

function deliverClientCode(id) {
  const input = document.getElementById('input-codc-' + id);
  const val = input ? input.value.trim() : '';
  if (!val) return alert('Ingresa el código proporcionado por el cliente.');
  const res = finalizarConCodigoCliente(id, val);
  if (res.error) {
    alert(res.error);
  } else {
    alert('¡Entrega finalizada con éxito!');
    renderActive();
    updateNavBadges();
  }
}

function cancelActive(id) {
  cancelarPedido(id);
  renderActive();
  updateNavBadges();
}

renderActive();
updateNavBadges();
iniciarBotonEstadoRepartidor();
</script>
@endpush
