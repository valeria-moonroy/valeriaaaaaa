console.log('Script cargado correctamente.');


$(document).ready(function() {
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });
});

function mostrarError(selector, mensaje) {
    const alertBox = $(selector);
    if (mensaje) {
        alertBox.text(mensaje).removeClass('d-none');
    } else {
        alertBox.text('').addClass('d-none');
    }
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

function registroUsuarios(){
    let name = $("#nombre");
    let email = $("#email");
    let password = $("#pwd");

    if (!validarCampos([
        { element: name, errorSelector: '#registroError', message: 'Complete el nombre completo.' },
        { element: email, errorSelector: '#registroError', message: 'Complete el email.' },
        { element: password, errorSelector: '#registroError', message: 'Complete la contraseña.' }
    ])) {
        return;
    }

    mostrarError('#registroError', '');
    let formData = new FormData();
    formData.append("nombre", name.val());
    formData.append("email", email.val());
    formData.append("pwd", password.val());
    $.ajax({
        url: "usuarios.php",
        data: formData,
        processData: false,
        contentType: false,
        type: "POST",
        dataType: 'json',
        cache: false,
        success: function(result){
            if (!result.success) {
                mostrarError('#registroError', result.message || 'Error en el registro.');
                return;
            }
            window.location.href = result.redirect || 'index.html';
        },
        error: function (xhr, status) {
            mostrarError('#registroError', 'Error de conexión. Intente de nuevo.');
        }
    });
}

function login(){
    let email = $("#email");
    let password = $("#pwd");

    // Si algún campo está vacio mostrar el mensaje de error y regresar sin hacer nada más
    if (!validarCampos([
        { element: email, errorSelector: '#loginError', message: 'Complete el email.' },
        { element: password, errorSelector: '#loginError', message: 'Complete la contraseña.' }
    ])) {
        return;
    }

    mostrarError('#loginError', '');
    let formData = new FormData();
    formData.append("email", email.val());
    formData.append("pwd", password.val());
    $.ajax({
        url: "login.php",
        data: formData,
        processData: false,
        contentType: false,
        type: "POST",
        dataType: 'json',
        cache: false,
        success: function(result){
            if (!result.success) {
                mostrarError('#loginError', result.message || 'Email o contraseña incorrectos.');
                return;
            }
            window.location.href = result.redirect || 'dashboard.php';
        },
        error: function (xhr, status) {
            console.error('Error en la solicitud AJAX:', status, xhr);
            mostrarError('#loginError', 'Error de conexión. Intente de nuevo.');
        }
    });
}
