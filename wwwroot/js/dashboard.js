// dashboard.js - Funciones específicas del dashboard

$(document).ready(function() {
    if ($('#dashboardContent').length) {
        loadDashboardStats();
    }
});

function loadDashboardStats() {
    // Cargar estadísticas del dashboard
    $.ajax({
        url: 'autores_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.authors) {
                $('#totalAuthors').text(result.authors.length);
            }
        }
    });

    $.ajax({
        url: 'libros_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.books) {
                $('#totalBooks').text(result.books.length);
            }
        }
    });

    $.ajax({
        url: 'prestamos_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.loans) {
                const activeLoans = result.loans.filter(loan => !loan.fecha_devolucion).length;
                $('#activeLoans').text(activeLoans);
            }
        }
    });
}