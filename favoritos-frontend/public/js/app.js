// Añadir los eventos de arrastrar y soltar a los elementos .paginasWebs
document.querySelectorAll('.paginasWebs').forEach(function(elemento) {
    elemento.addEventListener('dragstart', drag);
    elemento.addEventListener('drop', drop);
    elemento.addEventListener('dragover', allowDrop);
});

function drag(event) {
    var elementoArrastrado = event.target.closest('.paginasWebs');
    if (elementoArrastrado) {
        var dataId = elementoArrastrado.getAttribute('data-id');
        if (dataId) {
            event.dataTransfer.setData("text/plain", dataId);
        } else {
            console.error('El elemento arrastrado no tiene un data-id:', elementoArrastrado);
        }
    } else {
        console.error('No se encontró ningún elemento .paginasWebs:', event.target);
    }
}

function allowDrop(event) {
    event.preventDefault();
}

function drop(event) {
    event.stopPropagation();
    event.preventDefault();
    var idCambiado1 = event.dataTransfer.getData("text/plain");
    var elementoObjetivo = event.target.closest('.paginasWebs');

    if (elementoObjetivo) {
        var idCambiado2 = elementoObjetivo.getAttribute('data-id');
        var elementoArrastrado = document.querySelector(`[data-id='${idCambiado1}']`);

        if (elementoArrastrado) {
            elementoObjetivo.parentNode.insertBefore(elementoArrastrado, elementoObjetivo);
            actualizarOrden(idCambiado1, idCambiado2);
            location.reload();
        } else {
            console.error('No se encontró ningún elemento con el data-id:', idCambiado1);
        }
    } else {
        console.error('No se encontró ningún elemento .paginasWebs:', event.target);
    }
}

function actualizarOrden(idCambiado1, idCambiado2) {
    $.ajax({
        url: '/favoritos/actualizarOrden/' + idCambiado1 + '/' + idCambiado2,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            _method: 'PUT',
            idCambiado1: idCambiado1,
            idCambiado2: idCambiado2,
        },
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
}


// Menú contextual

var visibleContextMenuId = null;

function showContextMenu(event, id) {
    event.preventDefault();

    if (visibleContextMenuId) {
        // Ocultar el menú contextual anteriormente visible
        document.getElementById("contextMenu" + visibleContextMenuId).style.display = "none";
    }

    var contextMenu = document.getElementById("contextMenu" + id);
    contextMenu.style.display = "block";
    contextMenu.style.left = event.pageX + "px";
    contextMenu.style.top = event.pageY + "px";

    visibleContextMenuId = id;

    return false;
}

window.onclick = function() {
    if (visibleContextMenuId) {
        // Ocultar el menú contextual visible
        document.getElementById("contextMenu" + visibleContextMenuId).style.display = "none";
        visibleContextMenuId = null;
    }
};

$(document).ready(function(){
    var confirmDeleteModal = $('#confirmDeleteModal');

    $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        confirmDeleteModal.find('#confirmDeleteButton').data('id', id);
        confirmDeleteModal.modal('show');
    });

    $('#confirmDeleteButton').on('click', function() {
        var id = $(this).data('id');
        $('form[data-id="' + id + '"]').submit();
    });
});


