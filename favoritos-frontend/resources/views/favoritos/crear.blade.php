@extends('layouts.app')

@section('content')
    <style>
        html {
            overflow: hidden;
        }
    </style>

    <div class="contenedorCrear">
        <div class="formularioCrear">
            <div class="titulo">
                <h2>Agregar página web favorita</h2>
            </div>
            <form method="POST" action="{{ route('favoritos.guardar') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="url" class="form-label">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}" autofocus>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">
                </div>

                <div class="radioFondo">
                    <label for="tipoFondo" class="tipoFondo">Tipo de fondo:</label>
                    <label for="imagen">Imagen</label>
                    <input type="radio" id="imagen" name="tipoFondo" value="imagen" checked onclick="mostrarInput('imagen')">
                    <label for="imagen">Predef</label>
                    <input type="radio" id="predef" name="tipoFondo" value="predef" onclick="mostrarInput('predef')">
                    <label for="color">Color</label>
                    <input type="radio" id="color" name="tipoFondo" value="color" onclick="mostrarInput('color')">
                </div>

                <div id="logoFondo" class="logoFondo">
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

                <div id="fileFondo" class="fileFondo">
                    <img id="vistaPreviaLogo" name="vistaPreviaLogo" alt="Vista previa del logo">
                </div>

                <div id="logoColor" class="logoColor">
                    <label for="colorFondoA" class="form-label">Fondo 1:</label>
                    <input type="color" class="form-control" id="colorFondoA" name="colorFondoA" value="{{ old('colorFondoA', '#008f85') }}">
                    <label for="colorFondoB" class="form-label">Fondo 2:</label>
                    <input type="color" class="form-control" id="colorFondoB" name="colorFondoB" value="{{ old('colorFondoB', '#004070') }}">
                    <label for="colorTexto" class="form-label">Texto:</label>
                    <input type="color" class="form-control" id="colorTexto" name="colorTexto" value="{{ old('colorTexto', '#ffffff') }}">
                </div>

                <div id="logoVistaPrevia" class="logoVistaPrevia">
                    <div class="vistaPrevia">
                        Vista de prueba
                    </div>
                </div>

                <div class="formBoton">
                    <button type="submit" class="btn btn-large btn-block btn-primary">Crear</button>
                    <button type="button" class="btn btn-large btn-block btn-primary" onclick="window.location='{{ route('favoritos.crear') }}'">Limpiar</button>
                    <button type="button" class="btn btn-large btn-block btn-primary" onclick="window.location='{{ route('favoritos.index') }}'">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="error-grupo">
            <div class="error-mensaje">
                @foreach ($errors->all() as $message)
                    <p id="error">{{ $message }}</p>
                @break
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/crear.js') }}"></script>
@endsection
