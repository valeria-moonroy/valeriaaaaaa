// dashboard.js - Funciones específicas del dashboard

$(document).ready(function() {
    loadDashboardStats();
});

function loadDashboardStats() {
    console.log('Cargando estadísticas del dashboard...');
    console.log('Elementos encontrados - Autores:', $('#totalAuthors').length, 'Libros:', $('#totalBooks').length, 'Préstamos:', $('#activeLoans').length);
    // Cargar estadísticas del dashboard
    $.ajax({
        url: 'autores_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.authors) {
                const count = result.authors.length;
                $('#totalAuthors').text(count);
                console.log('Autores cargados:', count);
            } else {
                console.error('Error cargando autores:', result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX autores:', status, error);
        }
    });

    $.ajax({
        url: 'libros_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            if (result.success && result.books) {
                const count = result.books.length;
                $('#totalBooks').text(count);
                console.log('Libros cargados:', count);
            } else {
                console.error('Error cargando libros:', result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX libros:', status, error);
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
                console.log('Préstamos activos:', activeLoans);
            } else {
                console.error('Error cargando préstamos:', result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX préstamos:', status, error);
        }
    });
}