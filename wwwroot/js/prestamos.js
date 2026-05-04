// prestamos.js - Gestión de préstamos

$(document).ready(function() {
    // Cargar datos iniciales
    loadLoans();
    loadBooksForLoan();
    loadUserInfo();
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

function loadUserInfo() {
    // Cargar información del usuario desde sessionStorage o hacer una petición
    const userName = sessionStorage.getItem('userName') || 'Usuario';
    $('#userName').text(userName);
}

function loadBooksForLoan() {
    // Cargar libros disponibles para préstamo
    $.ajax({
        url: 'libros_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.books) {
                populateBookSelect(result.books);
            }
        },
        error: function() {
            console.error('Error al cargar libros para préstamo');
        }
    });
}

function populateBookSelect(books) {
    let options = '<option value="">Selecciona un libro disponible</option>';
    if (Array.isArray(books)) {
        books.forEach(function(book) {
            if (book.disponible) {
                options += '<option value="' + escapeHtml(book.id_libro) + '">' + escapeHtml(book.nombre) + ' — ' + escapeHtml(book.autor_nombre) + '</option>';
            }
        });
    }
    $('#loanBook').html(options);
}

function loadLoans() {
    mostrarError('#loansError', '');
    $.ajax({
        url: 'prestamos_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (!result.success) {
                mostrarError('#loansError', result.message || 'Error al cargar préstamos.');
                return;
            }
            let rows = '';
            if (Array.isArray(result.loans) && result.loans.length) {
                result.loans.forEach(function(loan) {
                    rows += '<tr>' +
                        '<td>' + escapeHtml(loan.id_prestamo) + '</td>' +
                        '<td>' + escapeHtml(loan.libro_nombre) + '</td>' +
                        '<td>' + escapeHtml(loan.autor_nombre) + '</td>' +
                        '<td>' + escapeHtml(loan.fecha_prestamo) + '</td>' +
                        '<td>' + (loan.fecha_devolucion ? escapeHtml(loan.fecha_devolucion) : '<span class="text-muted">No devuelto</span>') + '</td>' +
                        '<td>' + (loan.fecha_devolucion ? '<span class="text-muted">-</span>' : '<button class="btn btn-sm btn-outline-success" type="button" onclick="returnLoan(' + escapeHtml(loan.id_prestamo) + ')">Devolver</button>') + '</td>' +
                        '</tr>';
                });
            } else {
                rows = '<tr><td colspan="6" class="text-center text-muted">No hay préstamos registrados.</td></tr>';
            }
            $('#loansTable tbody').html(rows);
        },
        error: function() {
            mostrarError('#loansError', 'Error de conexión. Intente de nuevo.');
        }
    });
}

function createLoan() {
    const book = $('#loanBook');
    if (!validarCampos([{ element: book, errorSelector: '#loansError', message: 'Seleccione un libro disponible.' }])) {
        return;
    }
    mostrarError('#loansError', '');
    $.ajax({
        url: 'prestamos_api.php',
        type: 'POST',
        dataType: 'json',
        data: { libro_id: book.val() },
        success: function(result) {
            if (!result.success) {
                mostrarError('#loansError', result.message || 'Error al crear préstamo.');
                return;
            }
            book.val('');
            mostrarDashboardMessage('Préstamo registrado correctamente.', 'success');
            loadBooksForLoan(); // Recargar libros disponibles
            loadLoans();
        },
        error: function() {
            mostrarError('#loansError', 'Error de conexión. Intente de nuevo.');
        }
    });
}

function returnLoan(loanId) {
    $.ajax({
        url: 'prestamos_api.php',
        type: 'POST',
        dataType: 'json',
        data: { return_id: loanId },
        success: function(result) {
            if (!result.success) {
                mostrarError('#loansError', result.message || 'Error al devolver libro.');
                return;
            }
            mostrarDashboardMessage('Libro devuelto correctamente.', 'success');
            loadBooksForLoan(); // Recargar libros disponibles
            loadLoans();
        },
        error: function() {
            mostrarError('#loansError', 'Error de conexión. Intente de nuevo.');
        }
    });
}