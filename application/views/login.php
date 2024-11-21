<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Kodinger">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SPB - Iexe Universidad</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/login.css">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.iexe.edu.mx/wp-content/themes/iexe-unicorn/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.iexe.edu.mx/wp-content/themes/iexe-unicorn/assets/img/favicon-16x16.png">
</head>

<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="<?php echo base_url(); ?>assets/img/iexe_login.jpg" alt="logo">
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Sistema de Prediccion de Bajas</h4>
                            <div>
                                <div class="form-group">
                                    <label for="email">Correo</label>
                                    <input id="correo" type="email" class="form-control" name="correo" value="" required>
                                    <div id="error-message" class="invalid-feedback">
                                        Debe ingresar un correo electrónico válido.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Contraseña
                                        <a href="forgot.html" class="float-right">
                                            ¿Ha olvidado su contraseña?
                                        </a>
                                    </label>
                                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                                    <div class="invalid-feedback" id="msj_password">
                                        Password is required
                                    </div>
                                </div>


                                <div class="form-group m-0 text-center">
                                    <div id="error_login" class="mt-2 mb-3"></div>

                                    <button id="iniciar_sesion" class="btn btn-primary btn-block">
                                        Inicar sesión
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="footer">
                        Powered by IEXE Universidad | Todos los derechos reservados 2024
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {


            $(document).ready(function() {
                // Agregar validación al evento blur
                $('#correo, #password').on('blur', function() {
                    // Validar el campo de correo electrónico
                    if ($(this).attr('id') === 'correo') {
                        var correo = $(this).val().trim();
                        var correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                        if (!correoValido.test(correo)) {
                            $(this).removeClass('is-valid').addClass('is-invalid');
                            $('#error-message').text('Debe ingresar un correo electrónico válido.');
                        } else {
                            $(this).removeClass('is-invalid').addClass('is-valid');
                            $('#error-message').text('');
                        }
                    }

                    // Validar el campo de contraseña
                    if ($(this).attr('id') === 'password') {
                        var password = $(this).val().trim();

                        if (password === '') {
                            $(this).removeClass('is-valid').addClass('is-invalid');
                            $('#error-message').text('Debe ingresar una contraseña.');
                        } else {
                            $(this).removeClass('is-invalid').addClass('is-valid');
                            $('#error-message').text('');
                        }
                    }
                });

                // Remover clases is-valid e is-invalid al cambiar el contenido del campo de entrada
                $('#correo, #password').on('input', function() {
                    $(this).removeClass('is-valid is-invalid');
                    $('#error-message').text('');
                });

                $('#iniciar_sesion').click(function(e) {
                    $("#iniciar_sesion").html(`
                        <div class="spinner-grow spinner-grow-sm text-light" role="status">
                        </div>
                        <div class="spinner-grow spinner-grow-sm text-light" role="status">
                        </div>
                        <div class="spinner-grow spinner-grow-sm text-light" role="status">
                        </div>
                        <div class="spinner-grow spinner-grow-sm text-light" role="status">
                        </div>`)
                    $('#error-message').text('').hide();

                    e.preventDefault(); // Evitar el envío del formulario por defecto

                    var correo = $('#correo').val().trim();
                    var password = $('#password').val().trim();

                    // Expresión regular para validar correo electrónico
                    var correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    // Validar correo electrónico
                    if (!correoValido.test(correo)) {
                        $('#correo').addClass('is-invalid');
                        $('#error-message').text('Debe ingresar un correo electrónico válido.');
                        $("#iniciar_sesion").html(`Inicar sesión`)
                        return false;
                    } else {
                        $('#correo').removeClass('is-invalid').addClass('is-valid');
                        $('#error-message').text('');
                    }

                    // Validar contraseña
                    if (password === '') {
                        $('#password').addClass('is-invalid');
                        $('#error-message').text('Debe ingresar una contraseña.');
                        $("#iniciar_sesion").html(`Inicar sesión`)
                        return false;
                    } else {
                        $('#password').removeClass('is-invalid').addClass('is-valid');
                        $('#error-message').text('');
                    }

                    // Enviar datos por AJAX
                    $.ajax({
                        url: 'validar_usuario',
                        method: 'POST',
                        data: {
                            correo: correo,
                            password: password
                        },
                        dataType: 'json', // Esperamos una respuesta JSON del servidor
                        success: function(response) {
                            // Manejar la respuesta del servidor
                            console.log(response.error_code);
                            // Si hay un error, mostrar mensaje de error
                            if (response.code == "is_not_ok") {
                                $('#error_login').text(response.error_message).show();
                                $('#error_login').fadeOut(5000);
                                $("#iniciar_sesion").html(`Inicar sesión`)


                            } else if (response.code == "is_ok") {
                                // Esperar 5 segundos antes de redirigir
                                $("#iniciar_sesion").html(`<i class="fa-solid fa-circle-check"></i> Usuario validado correctamente`);
                                setTimeout(function() {
                                    // Realizar la redirección después de 5 segundos
                                    window.location.href = 'lista-alumnos';
                                }, 2000); // 5000 milisegundos = 5 segundos

                                // Si no hay error, redirigir o realizar otra acción
                                //window.location.href = 'inicio';
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejar errores de la solicitud AJAX
                            console.error(error);
                        }
                    });
                });

                $('#correo, #password').keydown(function(e) {
                    if (e.key === "Enter") {  // Verifica si la tecla presionada es Enter
                        e.preventDefault();  // Evita que el formulario se envíe al presionar Enter
                        $('#iniciar_sesion').click();  // Dispara el evento de clic del botón "Iniciar sesión"
                    }
                });

            });


            // Remover clases is-valid e is-invalid al cambiar el contenido del campo de entrada
            $('#correo, #password').on('input', function() {
                $(this).removeClass('is-valid is-invalid');
                $('#error-message').text('').hide();
            });


        });
    </script>
</body>

</html>