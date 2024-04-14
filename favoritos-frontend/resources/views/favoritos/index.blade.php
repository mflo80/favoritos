@extends('layouts.app')

@section('content')
    <nav>
        <div class="navBoton">
            <button class="btn btn-primary" onclick="window.location='{{ route('ajustes.index') }}'">|||</button>
        </div>
    </nav>
    <div class="contenedor">
        @foreach($favoritos as $favorito)
            <div class="paginasWebs" data-id="{{ $favorito['id'] }}" id="favorito{{ $favorito['id'] }}" draggable="true" ondragstart="drag(event)" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div id="box" class="box" oncontextmenu="return showContextMenu(event, {{ $favorito['id'] }})" data-id="{{ $favorito['id'] }}">
                    <a href="{{ $favorito['url'] }}" target="_blank">
                        <img src="{{ asset('img/'.$favorito['imagenFondo']) }}"
                            alt="{{ $favorito['nombre'] }}" class="img-fluid">
                    </a>
                </div>

                <div id="contextMenu{{ $favorito['id'] }}" class="context-menu">
                    <ul>
                        <li><a href="{{ $favorito['url'] }}" target="_blank">Abrir página</a></li>
                        <li><a href="{{ route('favoritos.editar', $favorito['id']) }}">Modificar</a></li>
                        <li>
                            <form id="deleteForm" method="POST" action="{{ route('favoritos.eliminar', $favorito['id']) }}" data-id="{{ $favorito['id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-btn" data-toggle="modal" data-target="#confirmDeleteModal"
                                        data-id="{{ $favorito['id'] }}">Eliminar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach

        <div class="paginasWebs">
            <div class="agregarWeb">
                <a class="nav-link" href="{{ route('favoritos.crear') }}"><span>+</span></a>
            </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h3>
                    </div>
                    <div class="modal-body">
                        @if(isset($favorito))
                            <h3>{{ $favorito['nombre'] }}</h3>
                            <h5>¿Estás seguro de que quieres eliminar este favorito?</h5>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-large btn-secondary" data-dismiss="modal">No</button>
                        <a href="#" class="btn btn-large btn-primary" id="confirmDeleteButton">Si</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.ajustes = @json($ajustes);
    </script>

    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

@endsection
