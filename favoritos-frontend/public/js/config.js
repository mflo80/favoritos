window.onload = function() {
    var boxes = document.getElementsByClassName('box');

    if (window.ajustes.tipo === 'imagen') {
        var imageUrl = window.location.origin + '/img/' + window.ajustes.imagenFondo;
        document.body.style.backgroundImage = 'url(' + imageUrl + ')';
        document.body.style.backgroundSize = 'cover';
        document.body.style.backgroundRepeat = 'no-repeat';
        document.body.style.backgroundAttachment = 'fixed';
    }

    if (window.ajustes.tipo === 'predef')  {
        var imageUrl = window.location.origin + '/img/' + window.ajustes.imagenFondo;
        document.body.style.backgroundImage = 'url(' + imageUrl + ')';
        document.body.style.backgroundSize = 'cover';
        document.body.style.backgroundRepeat = 'no-repeat';
        document.body.style.backgroundAttachment = 'fixed';
    }

    if (window.ajustes.tipo === 'color') {
        document.body.style.background = `linear-gradient(40deg, ${window.ajustes.colorFondoA}, ${window.ajustes.colorFondoB})`;
        document.body.style.backgroundSize = 'cover';
        document.body.style.backgroundRepeat = 'no-repeat';
        document.body.style.backgroundAttachment = 'fixed';
    }

    for (var i = 0; i < boxes.length; i++) {
        var box = boxes[i];

        if (window.ajustes) {
            box.style.border = '3px solid ' + window.ajustes.boxColor;
            box.style.width = window.ajustes.boxSize + 'px';
            box.style.height = (window.ajustes.boxSize - 20) + 'px';
        }

        // Ajustar el tamaño de la imagen
        var img = box.getElementsByTagName('img')[0];
        if (img) {
            img.style.width = (window.ajustes.boxSize - 5) + 'px';
            img.style.height = (window.ajustes.boxSize - 25) + 'px';
        }
    }
};
