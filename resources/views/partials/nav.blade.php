<nav class="bottom-nav" aria-label="Navegación principal">
  <a href="{{ route('inicio') }}" class="nav-item {{ request()->routeIs('inicio') ? 'is-active' : '' }}">
    <span aria-hidden="true">⌂</span><span>Inicio</span>
  </a>
  <a href="{{ route('activos') }}" class="nav-item {{ request()->routeIs('activos') ? 'is-active' : '' }}">
    <span aria-hidden="true">▣</span><span>Activos</span><b class="badge" id="badge-activos"></b>
  </a>
  <button id="delivery-status-toggle" class="nav-status-toggle" type="button" aria-pressed="false">
    <span class="nav-status-icon" aria-hidden="true">↕</span><span class="nav-status-label">Activo</span>
  </button>
  <a href="{{ route('pedidos') }}" class="nav-item {{ request()->routeIs('pedidos') ? 'is-active' : '' }}">
    <span aria-hidden="true">▢</span><span>Pedidos</span><b class="badge" id="badge-pedidos"></b>
  </a>
  <a href="{{ route('perfil') }}" class="nav-item {{ request()->routeIs('perfil', 'configuracion') ? 'is-active' : '' }}">
    <span aria-hidden="true">♙</span><span>Perfil</span>
  </a>
</nav>
