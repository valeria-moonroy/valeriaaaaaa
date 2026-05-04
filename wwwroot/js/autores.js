// autores.js - Gestión de autores

$(document).ready(function() {
    // Cargar datos iniciales
    loadAuthors();
});

function mostrarError(selector, mensaje) {
    const alertBox = $(selector);
    if (mensaje) {
        alertBox.text(mensaje).removeClass('d-none');
    } else {
        alertBox.text('').addClass('d-none');
    }
}

function mostrarDashboardMessage(message, type = 'info') {
    const alertBox = $('#dashboardMessage');
    alertBox.removeClass('d-none alert-info alert-success alert-danger alert-warning');
    alertBox.addClass('alert-' + type);
    alertBox.text(message);
}

function escapeHtml(value) {
    return $('<div>').text(value).html();
}

function validarCampos(campos) {
    for (let i = 0; i < campos.length; i++) {
        const campo = campos[i];
        if (!campo.element.val().trim()) {
            mostrarError(campo.errorSelector, campo.message);
            campo.element.focus();
            return false;
        }
    }
    return true;
}

function loadAuthors() {
    mostrarError('#authorsError', '');
    $.ajax({
        url: 'autores_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (!result.success) {
                mostrarError('#authorsError', result.message || 'Error al cargar autores.');
                return;
            }
            let rows = '';
            if (Array.isArray(result.authors) && result.authors.length) {
                result.authors.forEach(function(author) {
                    rows += '<tr><td>' + escapeHtml(author.id_autor) + '</td><td>' + escapeHtml(author.nombre) + '</td></tr>';
                });
            } else {
                rows = '<tr><td colspan="2" class="text-center text-muted">No hay autores registrados.</td></tr>';
            }
            $('#authorsTable tbody').html(rows);
        },
        error: function() {
            mostrarError('#authorsError', 'Error de conexión. Intente de nuevo.');
        }
    });
}

function createAuthor() {
    const name = $('#authorName');
    if (!validarCampos([{ element: name, errorSelector: '#authorsError', message: 'Complete el nombre del autor.' }])) {
        return;
    }
    mostrarError('#authorsError', '');
    $.ajax({
        url: 'autores_api.php',
        type: 'POST',
        dataType: 'json',
        data: { nombre: name.val().trim() },
        success: function(result) {
            if (!result.success) {
                mostrarError('#authorsError', result.message || 'Error al guardar autor.');
                return;
            }
            name.val('');
            mostrarDashboardMessage('Autor guardado correctamente.', 'success');
            loadAuthors();
        },
        error: function() {
            mostrarError('#authorsError', 'Error de conexión. Intente de nuevo.');
        }
    });
}