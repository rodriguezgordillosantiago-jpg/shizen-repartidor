@extends('layouts.app')
@section('content')
<main class="scroll">
  <h1 style="margin:0 0 4px;font-size:20px">Pedidos disponibles</h1>
  <p style="margin:0 0 18px;color:#9ca3af;font-size:12px">Acepta los pedidos que quieras entregar</p>
  <div id="orders"></div>
</main>
@endsection

@push('scripts')
<script>
async function render() {
  try {
    const result = await cargarEntregas("available");
    const list = result.items || [];
    const full = false;
    document.querySelector("#orders").innerHTML = list.length
      ? list
          .map(
            (p) =>
              `<article class="card order"><div class="order-head"><div class="order-main"><p class="name">${p.cliente_nombre || 'Cliente'} ${p.cliente_apellido || ''}</p><p class="muted">Pedido #${p.id_pedido}</p></div><div class="earn"><strong>${fmt(p.total)}</strong></div></div><div class="route">📍 Recoger: ${p.negocio_nombre || 'Restaurante'}<br>🏁 Entregar: ${p.direccion_entrega || 'Dirección cliente'}</div><div class="actions"><button class="btn-green" ${full ? 'disabled style="opacity:.55"' : ""} onclick="acceptOrder('${p.id_entrega}')">Aceptar</button></div></article>`
          )
          .join("")
      : `<div class="card" style="padding:32px;text-align:center;color:#9ca3af;font-size:13px">No hay pedidos disponibles ahora</div>`;
  } catch (e) {
    console.error(e);
  }
}

async function acceptOrder(id) {
  try {
    await actualizarEntrega("accept", id);
    await render();
    if (typeof updateNavBadges === 'function') updateNavBadges();
  } catch (error) {
    alert(error.message);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  render();
  iniciarBotonEstadoRepartidor();
  if (typeof updateNavBadges === 'function') updateNavBadges();
});
</script>
@endpush
