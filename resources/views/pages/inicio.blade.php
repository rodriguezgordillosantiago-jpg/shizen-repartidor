@extends('layouts.app')
@section('content')
<main class="scroll" id="content">
  <header class="dashboard-hero">
    <p class="dashboard-kicker">Bienvenido de vuelta</p>
    <h1>{{ $repartidor['nombre'] ?? 'Santiago' }} {{ $repartidor['apellido'] ?? 'Vargas' }}</h1>
    <div class="stats-grid"><div class="stat-card"><small>Entregas hoy</small><strong>6</strong></div><div class="stat-card"><small>Ganancias</small><strong>$25.800</strong></div><div class="stat-card"><small>Calificación</small><strong>★ 4.8</strong></div></div>
  </header>
  <section class="card capacity-card"><span class="capacity-icon">▣</span><div><strong>Pedidos activos: <span id="active-count">0</span> / 4</strong><p class="muted">Puedes aceptar nuevos pedidos desde Pedidos.</p></div></section>
  <h2 style="font-size:15px">Tipos de entrega</h2>
  <div class="category-grid"><div class="card category-card"><span>🍔</span><small>Restaurantes</small></div><div class="card category-card"><span>🛒</span><small>Mercado</small></div><div class="card category-card"><span>📦</span><small>Courier</small></div><div class="card category-card"><span>💊</span><small>Farmacia</small></div></div>
  <p style="margin-top:22px"><a class="btn-green" style="display:block;padding:13px;text-align:center;text-decoration:none" href="{{ route('pedidos') }}">Ver pedidos disponibles <span aria-hidden="true">→</span></a></p>
</main>
@endsection
@push('scripts')
<script>document.getElementById('active-count').textContent = countActivos(); updateNavBadges(); iniciarBotonEstadoRepartidor();</script>
@endpush
