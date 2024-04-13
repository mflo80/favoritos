@extends('layouts.app')

@section('content')
    <style>
        html {
            overflow: hidden;
        }
    </style>

    <div class="contenedorAjustes">
        <div class="formularioAjustes">
            <div class="titulo">
                <h2>Ajustes</h2>
            </div>
            <form method="POST" action="{{ route('ajustes.guardar', $ajustes['clienteIP']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="formAjustesURL">
                    <label for="url" class="form-label">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}" autofocus>
                </div>

                <div class="formAjustesNombre">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">
                </div>

                <div class="radioFondo">
                    <label for="tipoFondo" class="tipoFondo">Tipo de fondo:</label>
                    <label for="imagen">Imagen</label>
                    <input type="radio" id="imagen" name="tipoFondo" value="imagen" onclick="mostrarInput('imagen')" {{ $ajustes['tipo'] == 'imagen' ? 'checked' : '' }}>
                    <label for="imagen">Predef</label>
                    <input type="radio" id="predef" name="tipoFondo" value="predef" onclick="mostrarInput('predef')" {{ $ajustes['tipo'] == 'predef' ? 'checked' : '' }}>
                    <label for="color">Color</label>
                    <input type="radio" id="color" name="tipoFondo" value="color" onclick="mostrarInput('color')" {{ $ajustes['tipo'] == 'color' ? 'checked' : '' }}>
                </div>

                <div id="logoFondo" class="logoFondo">
                    <div class="logoPersonalizado" id="logoPersonalizado">
                        <label for="imagenFondo" class="form-label">Fondo:</label>
                        <input type="file" class="form-control" id="imagenFondo" name="imagenFondo" value="{{ old('imagenFondo') }}" onchange="checkFileSize(this)">
                    </div>
                    <div class="logoPredefinido" id="logoPredefinido">
                        <label for="imagenFondo" class="form-label">Fondo Predefinido:</label>
                        <select class="form-control" id="imagenFondoDef" name="imagenFondoDef">
                            @foreach ($fondos as $fondo)
                                <option value="{{ $fondo }}">{{ $fondo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="fileFondo" class="fileFondo">
                    <img id="vistaPreviaLogo" name="vistaPreviaLogo" alt="Vista previa del fondo">
                </div>

                <div id="logoColor" class="logoColor">
                    <label for="colorFondoA" class="form-label">Fondo 1:</label>
                    <input type="color" class="form-control" id="colorFondoA" name="colorFondoA" value="{{ $ajustes['colorFondoA'] }}">
                    <label for="colorFondoB" class="form-label">Fondo 2:</label>
                    <input type="color" class="form-control" id="colorFondoB" name="colorFondoB" value="{{ $ajustes['colorFondoB'] }}">
                    <div class="formAjustesColorTexto">
                        <label for="colorTexto" class="form-label">Texto:</label>
                        <input type="color" class="form-control" id="colorTexto" name="colorTexto" value="{{ old('colorTexto', '#ffffff') }}">
                    </div>
               </div>

                <div id="logoVistaPrevia" class="logoVistaPrevia">
                    <div class="vistaPrevia">
                    </div>
                </div>

                <div class="boxes">
                    <label for="boxSize" class="form-label">Box Size:</label>
                    <input type="number" class="form-control" id="boxSize" name="boxSize" min="100" max="200" value="{{ $ajustes['boxSize'] }}">
                    <label for="boxColor" class="form-label">Box Border:</label>
                    <input type="color" class="form-control" id="boxColor" name="boxColor" value="{{ $ajustes['boxColor'] }}">
               </div>

                <div class=formBoton>
                    <button type="submit" class="btn btn-large btn-primary">Guardar</button>
                    <button type="button" class="btn btn-large btn-primary" onclick="window.location='{{ route('favoritos.index') }}'">Salir</button>
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

    <script src="{{ asset('js/ajustes.js') }}"></script>
@endsection
