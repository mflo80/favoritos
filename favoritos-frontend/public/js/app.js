// Arrastrar y soltar

function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text/plain", event.target.id);
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
