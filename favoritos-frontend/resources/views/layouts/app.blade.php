<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Páginas Web Favoritas</title>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/crear.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/editar.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/ajustes.css') }}" rel="stylesheet">
    <script src="{{ asset('js/externos/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/externos/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/externos/bootstrap.min.js') }}"></script>
</head>
<body>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
