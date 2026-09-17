@extends('layouts.app')
@section('content')
<main class="scroll"><h1 style="margin:0 0 4px;font-size:20px">Pedidos disponibles</h1><p class="muted" style="margin-bottom:18px">Acepta los pedidos que quieras entregar</p><div id="orders"></div></main>
@endsection
@push('scripts')
<script>
function renderOrders(){const list=getDisponibles();document.getElementById('orders').innerHTML=list.length?list.map(p=>`<article class="card order"><div class="order-head"><img class="avatar" src="${p.avatar}" alt="${p.cliente}"><div class="order-main"><p class="name">${p.cliente}</p><p class="muted">${p.tipo==='asap'?'Entrega inmediata':'Programado · '+p.hora}</p></div><div class="earn"><strong>${fmt(p.ganancia)}</strong><span>${p.distancia}</span></div></div><div class="route">📍 Recoger: ${p.origen}<br>🏁 Entregar: ${p.destino}</div><div class="actions"><button class="btn-gray" onclick="rejectOrder('${p.id}')">Rechazar</button><button class="btn-green" onclick="acceptOrder('${p.id}')">Aceptar</button></div></article>`).join(''):'<div class="card" style="padding:32px;text-align:center;color:#9ca3af">No hay pedidos disponibles ahora</div>'} function acceptOrder(id){if(!aceptarPedido(id))return alert('Tienes el cupo de pedidos lleno');renderOrders();updateNavBadges()} function rejectOrder(id){rechazarPedido(id);renderOrders();updateNavBadges()} renderOrders();updateNavBadges();iniciarBotonEstadoRepartidor();
</script>
@endpush
