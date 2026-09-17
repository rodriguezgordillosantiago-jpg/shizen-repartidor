@extends('layouts.app')
@section('content')
<main class="scroll"><h1 style="margin:0 0 4px;font-size:20px">Historial</h1><p class="muted" style="margin-bottom:18px">Tus últimas entregas</p><div id="history"></div></main>
@endsection
@push('scripts')
<script>const historyItems=getHistorial();document.getElementById('history').innerHTML=historyItems.length?historyItems.map(p=>`<article class="card order"><div class="order-head"><img class="avatar" src="${p.avatar}" alt="${p.cliente}"><div class="order-main"><p class="name">${p.cliente}</p><p class="muted">${p.origen}</p></div><div class="earn"><strong>${fmt(p.ganancia)}</strong><span>${p.estado}</span></div></div></article>`).join(''):'<div class="card" style="padding:32px;text-align:center;color:#9ca3af">Sin historial aún</div>';updateNavBadges();iniciarBotonEstadoRepartidor();</script>
@endpush
