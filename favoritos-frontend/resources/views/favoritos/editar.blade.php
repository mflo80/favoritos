@extends('layouts.app')

@section('content')
    <style>
        html {
            overflow: hidden;
        }
    </style>

    <div class="contenedorEditar">
        <div class="formularioEditar">
            <div class="titulo">
                <h2>Editar página web favorita</h2>
            </div>
            <form method="POST" action="{{ route('favoritos.modificar', $favorito['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="url" class="form-label">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" value="{{ $favorito['url'] }}" autofocus>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $favorito['nombre'] }}">
                </div>

                <div class="radioFondo">
                    <label for="tipoFondo" class="tipoFondo">Tipo de fondo:</label>
                    <label for="imagen">Imagen</label>
                    <input type="radio" id="imagen" name="tipoFondo" value="imagen" onclick="mostrarInput('imagen')" {{ $favorito['tipo'] == 'imagen' ? 'checked' : '' }}>
                    <label for="imagen">Predef</label>
                    <input type="radio" id="predef" name="tipoFondo" value="predef" onclick="mostrarInput('predef')" {{ $favorito['tipo'] == 'predef' ? 'checked' : '' }}>
                    <label for="color">Color</label>
                    <input type="radio" id="color" name="tipoFondo" value="color" onclick="mostrarInput('color')" {{ $favorito['tipo'] == 'color' ? 'checked' : '' }}>
                </div>

                <div id="logoFondo" class="logoFondo">
                    <img src="{{ asset('img/'.$favorito['imagenFondo']) }}" alt="{{ $favorito['nombre'] }}" class="img-fluid">
                    <img id="vistaPreviaLogo" alt="Nueva imagen">
                </div>

                <div id="fileFondo" class="fileFondo">
                    <div class="logoPersonalizado" id="logoPersonalizado">
                        <label for="imagenFondo" class="form-label">Logo Personalizado:</label>
                        <input type="file" class="form-control" id="imagenFondo" name="imagenFondo" value="{{ old('imagenFondo') }}" onchange="checkFileSize(this)">
                    </div>
                    <div class="logoPredefinido" id="logoPredefinido">
                        <label for="imagenFondo" class="form-label">Logo Predefinido:</label>
                        <select class="form-control" id="imagenFondoDef" name="imagenFondoDef">
                            @foreach ($logos as $logo)
                                <option value="{{ $logo }}">{{ $logo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="logoColor" class="logoColor">
                    <label for="colorFondoA" class="form-label">Fondo 1:</label>
                    <input type="color" class="form-control" id="colorFondoA" name="colorFondoA" value="{{ $favorito['colorFondoA'] }}">
                    <label for="colorFondoB" class="form-label">Fondo 2:</label>
                    <input type="color" class="form-control" id="colorFondoB" name="colorFondoB" value="{{ $favorito['colorFondoB'] }}">
                    <label for="colorTexto" class="form-label">Texto:</label>
                    <input type="color" class="form-control" id="colorTexto" name="colorTexto" value="{{ $favorito['colorTexto'] }}">
                </div>

                <div id="logoVistaPrevia" class="logoVistaPrevia">
                    <div class="vistaPrevia">
                        {{ $favorito['nombre'] }}
                    </div>
                </div>

                <div class=formBoton>
                    <button type="submit" class="btn btn-large btn-primary">Guardar</button>
                    <button type="button" class="btn btn-large btn-primary" onclick="window.location='{{ route('favoritos.index') }}'">Salir</button>
                </div>
            </form>
        </div>

        <div class="error-grupo" id="error-grupo">
            <div class="error-mensaje">
                @foreach ($errors->all() as $message)
                    <p id="error">{{ $message }}</p>
                @break
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/editar.js') }}"></script>
@endsection
