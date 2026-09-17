<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shizen Repartidor - Iniciar sesión</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="blob-wrap"><svg class="blob" viewBox="0 0 375 332" preserveAspectRatio="none"><path d="M0 0h375v220c-35 60-95 40-155 50S60 250 0 260V0Z" fill="#2d6a31"/></svg></div>
  <div class="logo-container"><img class="logo" src="{{ asset('assets/logo_blanco.png') }}" alt="Shizen repartidor"></div>
  <div class="welcome-text"><p class="welcome-title">Inicia sesión</p><p class="welcome-description">Ingresa tu correo electrónico</p></div>
  @if ($errors->any())<p class="login-error">{{ $errors->first() }}</p>@endif
  <form action="{{ route('login.submit') }}" method="post">
    @csrf
    <div class="input-container"><div class="input-box"><input name="email" type="email" placeholder="Correo electrónico" value="{{ old('email') }}" required></div></div>
    <div class="input-container"><div class="input-box"><input name="password" type="password" placeholder="Contraseña" required></div></div>
    <div class="button-container"><button type="submit" class="btn-green">Continuar</button></div>
  </form>
  <div class="create-account"><p>¿No tienes cuenta?<br><a href="{{ route('login') }}">Solicita acceso a Shizen</a></p></div>
</body>
</html>
