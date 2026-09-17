@extends('layouts.app')
@section('content')
<main class="scroll"><div class="card" style="padding:16px;margin-bottom:16px"><a href="{{ route('perfil') }}" style="color:#2d6a31;text-decoration:none">← Volver al perfil</a><h1 style="font-size:20px;margin:20px 0 4px">Configuración</h1><p class="muted">Administra tus preferencias de entrega</p></div>
@if(session('status'))<div class="card" style="padding:12px;margin-bottom:12px;color:#2d6a31">{{ session('status') }}</div>@endif
<form action="{{ route('configuracion.guardar') }}" method="post"><div class="card" style="overflow:hidden;margin-bottom:16px">@csrf
<label style="display:flex;justify-content:space-between;padding:16px;border-bottom:1px solid #e8f5e9">Notificaciones push<input type="checkbox" name="notificaciones_push" {{ $preferencias['notificaciones_push'] ? 'checked' : '' }}></label>
<label style="display:flex;justify-content:space-between;padding:16px">Sonido de nuevos pedidos<input type="checkbox" name="sonido_pedidos" {{ $preferencias['sonido_pedidos'] ? 'checked' : '' }}></label></div><button class="btn-green" style="width:100%;padding:12px" type="submit">Guardar preferencias</button></form></main>
@endsection
