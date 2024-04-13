var error = document.getElementById('error');

window.onload = function() {
    var tipo = document.querySelector('input[name="tipoFondo"]:checked').value;
    mostrarInput(tipo);
};

document.getElementById('imagenFondo').onchange = function(e) {
    var reader = new FileReader();

    reader.onload = function(event) {
        document.getElementById('vistaPreviaLogo').src = event.target.result;
        document.getElementById('vistaPreviaLogo').hidden = false;
    }

    reader.readAsDataURL(e.target.files[0]);
};

document.getElementById('imagenFondoDef').addEventListener('change', function() {
    var selectedLogo = this.value;
    var previewImage = document.getElementById('vistaPreviaLogo');

    // Actualiza la fuente de la imagen de vista previa con la nueva imagen seleccionada
    previewImage.src = '/img/logos/' + selectedLogo;
    previewImage.hidden = false;
});

function checkFileSize(input) {
    var maxSize = 5 * 1024 * 1024; // 5MB
    if (input.files && input.files[0] && input.files[0].size > maxSize) {
        document.getElementById('error').textContent = 'El archivo es demasiado grande, debe ser menor a 5 MB.';
        input.value = '';
    } else {
        document.getElementById('error').textContent = '';
    }
}

function vistaPreviaLogo() {
    var text = this.value;
    var maxWidth = 150; // Ancho del contenedor
    var fontSize = 12; // Tamaño de la fuente
    var maxCharsPerLine = Math.floor(maxWidth / (fontSize * 0.8));

    if (!text) {
        text = 'Vista de Prueba'; // Texto por defecto
    }

    // Divide el texto en líneas
    var lines = [];
    while (text.length) {
        lines.push(text.slice(0, maxCharsPerLine));
        text = text.slice(maxCharsPerLine);
    }

    // Centra cada línea y la agrega al elemento de vista previa
    var logoVistaPrevia = document.getElementById('logoVistaPrevia');
    logoVistaPrevia.innerHTML = '';
    logoVistaPrevia.style.display = 'flex';
    logoVistaPrevia.style.flexDirection = 'column';
    logoVistaPrevia.style.justifyContent = 'center';
    logoVistaPrevia.style.alignItems = 'center';
    logoVistaPrevia.style.fontWeight = 'bold';

    // Aplica el degradado de colores
    var colorFondoA = document.getElementById('colorFondoA').value;
    var colorFondoB = document.getElementById('colorFondoB').value;
    logoVistaPrevia.style.background = `linear-gradient(40deg, ${colorFondoA}, ${colorFondoB})`;

    lines.forEach(function (line) {
        var span = document.createElement('span');
        span.textContent = line;
        span.style.display = 'block';
        span.style.textAlign = 'center';
        logoVistaPrevia.appendChild(span);
    });
}

document.getElementById('nombre').addEventListener('input', function () {

    vistaPreviaLogo.call(this);

    if (document.getElementById('imagen').checked) {
        var logoVistaPrevia = document.getElementById('logoVistaPrevia');
        logoVistaPrevia.style.display = 'none';
    }

    if (document.getElementById('predef').checked) {
        var logoVistaPrevia = document.getElementById('logoVistaPrevia');
        logoVistaPrevia.style.display = 'none';
    }

    if (document.getElementById('predefw').checked) {
        var logoVistaPrevia = document.getElementById('logoVistaPrevia');
        logoVistaPrevia.style.display = 'none';
    }

    // Limitar a 30 caracteres
    if (this.value.length > 30) {
        this.value = this.value.slice(0, 30);
    }

    // No permitir espacio como primer carácter
    if (this.value.charAt(0) === ' ') {
        this.value = this.value.trimStart();
    }

    // No permitir más de dos espacios seguidos
    this.value = this.value.replace(/ {2,}/g, ' ');

    error.style.display = 'none';
});

document.getElementById('nombre').onchange = function () {
    // Eliminar espacio al final al enviar el formulario
    this.value = this.value.replace(/\s+$/, '');
};

document.getElementById('url').oninput = function () {
    // Limitar a 255 caracteres
    if (this.value.length > 255) {
        this.value = this.value.slice(0, 255);
    }

    // No permitir espacio como primer carácter
    if (this.value.charAt(0) === ' ') {
        this.value = this.value.trimStart();
    }

    // No permitir espacios en la URL
    this.value = this.value.replace(/\s/g, '');

    error.style.display = 'none';
};

// Actualiza los colores de la vista previa del logo

document.getElementById('url').onchange = function () {
    // Eliminar espacio al final al enviar el formulario
    this.value = this.value.replace(/\s+$/, '');
};

document.getElementById('colorTexto').oninput = function () {
    document.getElementById('logoVistaPrevia').style.color = this.value;
};

document.getElementById('colorTexto').oninput();

function updateGradient() {
    var colorFondoA = document.getElementById('colorFondoA').value;
    var colorFondoB = document.getElementById('colorFondoB').value;
    var colorTexto = document.getElementById('colorTexto').value;
    document.getElementById('logoVistaPrevia').style.background = `linear-gradient(40deg, ${colorFondoA}, ${colorFondoB})`;
    document.getElementById('logoVistaPrevia').style.color = colorTexto;
}

function updateText() {
    var text = document.getElementById('nombre').value;
    document.getElementById('vistaPrevia').textContent = text;
}

document.getElementById('colorFondoA').oninput = updateGradient;
document.getElementById('colorFondoB').oninput = updateGradient;
document.getElementById('colorTexto').oninput = updateGradient;
document.getElementById('nombre').oninput = updateText;

updateGradient();
updateText();

function mostrarInput(tipo) {
    var logoColor = document.getElementById('logoColor');
    var logoFondo = document.getElementById('logoFondo');
    var logoVistaPrevia = document.getElementById('logoVistaPrevia');
    var fileFondo = document.getElementById('fileFondo');
    var logoPersonalizado = document.getElementById('logoPersonalizado');
    var logoPredefinido = document.getElementById('logoPredefinido');

    if (tipo === 'color') {
        logoColor.classList.remove('ocultar');
        logoColor.classList.add('mostrarLogoColor');
        logoFondo.classList.remove('mostrarLogoFondo');
        logoFondo.classList.add('ocultar');
        logoVistaPrevia.classList.remove('ocultar');
        logoVistaPrevia.classList.add('mostrarLogoVistaPrevia');
        logoVistaPrevia.style.display = 'flex';
        fileFondo.style.display = 'none';
        fileFondo.classList.remove('mostrarFileFondo');
        fileFondo.classList.add('ocultar');
        logoPersonalizado.style.display = 'none';
        logoPredefinido.style.display = 'none';
    } else if (tipo === 'imagen') {
        logoColor.classList.remove('mostrarLogoColor');
        logoColor.classList.add('ocultar');
        logoFondo.classList.remove('ocultar');
        logoFondo.classList.add('mostrarLogoFondo');
        logoVistaPrevia.style.display = 'none';
        logoVistaPrevia.classList.remove('mostrarLogoVistaPrevia');
        logoVistaPrevia.classList.add('ocultar');
        fileFondo.classList.remove('ocultar');
        fileFondo.classList.add('mostrarFileFondo');
        fileFondo.style.display = 'flex';
        logoPersonalizado.style.display = 'flex';
        logoPredefinido.style.display = 'none';

        var previewImage = document.getElementById('vistaPreviaLogo');
        // Actualiza la fuente de la imagen de vista previa con la imagen seleccionada
        previewImage.src = '';
        previewImage.hidden = false;
    } else if (tipo === 'predef') {
        logoColor.classList.remove('mostrarLogoColor');
        logoColor.classList.add('ocultar');
        logoFondo.classList.remove('ocultar');
        logoFondo.classList.add('mostrarLogoFondo');
        logoVistaPrevia.style.display = 'none';
        logoVistaPrevia.classList.remove('mostrarLogoVistaPrevia');
        logoVistaPrevia.classList.add('ocultar');
        fileFondo.classList.remove('ocultar');
        fileFondo.classList.add('mostrarFileFondo');
        fileFondo.style.display = 'flex';
        logoPersonalizado.style.display = 'none';
        logoPredefinido.style.display = 'flex';

        var selectedLogo = document.getElementById('imagenFondoDef').value;
        var previewImage = document.getElementById('vistaPreviaLogo');
        // Actualiza la fuente de la imagen de vista previa con la imagen seleccionada por defecto
        previewImage.src = '/img/logos/' + selectedLogo;
        previewImage.hidden = false;
    }
}

document.getElementById('imagen').onchange = function() {
    mostrarInput(this.value);
};

document.getElementById('color').onchange = function() {
    mostrarInput(this.value);
};

document.getElementById('predef').onchange = function() {
    mostrarInput(this.value);
};


