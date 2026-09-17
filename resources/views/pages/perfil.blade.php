@extends('layouts.app')
@section('content')
<main class="scroll">
  @if(session('status'))<div class="card" style="padding:12px;margin:0 16px 12px;color:#2d6a31">{{ session('status') }}</div>@endif
  <header class="profile-header">
    <img class="profile-header__logo" src="{{ asset('assets/logo_blanco.png') }}" alt="Shizen">
    <div class="profile-header__avatar">{{ strtoupper(substr($repartidor['nombre'] ?? 'S', 0, 1).substr($repartidor['apellido'] ?? 'V', 0, 1)) }}</div>
    <h1 class="profile-header__name">{{ $repartidor['nombre'] ?? 'Santiago' }} {{ $repartidor['apellido'] ?? 'Vargas' }}</h1>
    <p class="profile-header__meta">{{ $repartidor['email'] ?? 'repartidor@shizen.test' }} · {{ $repartidor['vehiculo'] ?? 'Moto' }}</p>
  </header>
  <section class="card" style="padding:16px;margin:0 16px 16px">
    <h2 style="font-size:17px;margin:0 0 14px">Datos del repartidor</h2>
    <form action="{{ route('perfil.update') }}" method="post">
      @csrf
      <label class="field-group"><small>Nombre</small><input class="field" name="nombre" value="{{ $repartidor['nombre'] ?? '' }}" required></label>
      <label class="field-group"><small>Apellido</small><input class="field" name="apellido" value="{{ $repartidor['apellido'] ?? '' }}" required></label>
      <label class="field-group"><small>Correo electrónico</small><input class="field" value="{{ $repartidor['email'] ?? '' }}" readonly></label>
      <label class="field-group"><small>Vehículo</small><select class="field" name="vehiculo"><option>Moto</option><option>Bicicleta</option><option>Carro</option><option>Monopatín</option></select></label>
      <button class="btn-green" style="width:100%;padding:12px;margin-top:12px" type="submit">Guardar cambios</button>
    </form>
  </section>
  <a class="card" style="display:block;padding:16px;margin:0 16px 12px;color:#2d6a31;text-decoration:none" href="{{ route('configuracion') }}">⚙ Configuración</a>
  <form action="{{ route('logout') }}" method="post" style="margin:0 16px">@csrf<button class="btn-gray" style="width:100%;padding:12px" type="submit">Cerrar sesión</button></form>
</main>
@endsection
