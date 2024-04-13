@extends('layouts.app')

@section('content')
    <style>
        html {
            overflow: hidden;
        }
    </style>

    <div class="contenedor">
        <div class="error500">
            <h1>500</h1>
            <h2>Error interno del servidor</h2>
            <p>Ha ocurrido un error interno en el servidor. Por favor, inténtalo de nuevo más tarde.</p>
        </div>
    </div>

@endsection
