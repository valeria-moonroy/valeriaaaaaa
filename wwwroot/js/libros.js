// libros.js - Gestión de libros

$(document).ready(function() {
    // Cargar datos iniciales
    loadBooks();
    loadAuthorsForSelect();
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

function loadBooks() {
    mostrarError('#booksError', '');
    $.ajax({
        url: 'libros_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (!result.success) {
                mostrarError('#booksError', result.message || 'Error al cargar libros.');
                return;
            }
            let rows = '';
            if (Array.isArray(result.books) && result.books.length) {
                result.books.forEach(function(book) {
                    rows += '<tr>' +
                        '<td>' + escapeHtml(book.id_libro) + '</td>' +
                        '<td>' + escapeHtml(book.nombre) + '</td>' +
                        '<td>' + escapeHtml(book.autor_nombre) + '</td>' +
                        '<td>' + (book.disponible ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>') + '</td>' +
                        '</tr>';
                });
            } else {
                rows = '<tr><td colspan="4" class="text-center text-muted">No hay libros registrados.</td></tr>';
            }
            $('#booksTable tbody').html(rows);
            populateBookSelect(result.books);
            loadAuthorsForSelect();
        },
        error: function() {
            mostrarError('#booksError', 'Error de conexión. Intente de nuevo.');
        }
    });
}

function loadAuthorsForSelect() {
    $.ajax({
        url: 'autores_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.authors) {
                populateAuthorSelect(result.authors);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar autores para select:', status, error);
        }
    });
}

function populateAuthorSelect(authors) {
    let options = '<option value="">Selecciona un autor</option>';
    if (Array.isArray(authors)) {
        authors.forEach(function(author) {
            options += '<option value="' + escapeHtml(author.id_autor) + '">' + escapeHtml(author.nombre) + '</option>';
        });
    }
    $('#bookAuthor').html(options);
}

function createBook() {
    const name = $('#bookName');
    const author = $('#bookAuthor');
    if (!validarCampos([
        { element: name, errorSelector: '#booksError', message: 'Complete el nombre del libro.' },
        { element: author, errorSelector: '#booksError', message: 'Seleccione un autor.' }
    ])) {
        return;
    }
    mostrarError('#booksError', '');
    $.ajax({
        url: 'libros_api.php',
        type: 'POST',
        dataType: 'json',
        data: { nombre: name.val().trim(), autor_id: author.val() },
        success: function(result) {
            if (!result.success) {
                mostrarError('#booksError', result.message || 'Error al guardar libro.');
                return;
            }
            name.val('');
            author.val('');
            mostrarDashboardMessage('Libro guardado correctamente.', 'success');
            loadBooks();
        },
        error: function() {
            mostrarError('#booksError', 'Error de conexión. Intente de nuevo.');
        }
    });
}