<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Shizen Repartidor' }}</title>
  <link rel="stylesheet" href="{{ asset('css/repartidor.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
  @stack('styles')
</head>
<body>
  @yield('content')
  @include('partials.nav')
  <script src="{{ asset('js/data.js') }}"></script>
  <div id="chat-root"></div>
  <script src="{{ asset('js/chat.js') }}"></script>
  <script>if (typeof initChat === 'function') initChat();</script>
  @stack('scripts')
</body>
</html>
