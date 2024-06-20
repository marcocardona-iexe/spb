$(document).ready(function () {
    // Ejemplo de JavaScript para habilitar la validación Bootstrap personalizada
    (function () {
        "use strict";
        var forms = document.querySelectorAll(".needs-validation");

        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener(
                "submit",
                function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    // Validar selección de rol
                    var rolesSelected = $("input[name='rol[]']:checked").length;
                    if (rolesSelected === 0) {
                        $("#rol-feedback").removeClass("d-none").addClass("d-block");
                        $("input[name='rol[]']").addClass("is-invalid");
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        $("#rol-feedback").removeClass("d-block").addClass("d-none");
                        $("input[name='rol[]']").removeClass("is-invalid").addClass("is-valid");
                    }

                    form.classList.add("was-validated");
                },
                false
            );
        });
    })();

    $(".asignar").on("click", function () {
        const idusuario = $(this).attr("data-idusuario");

        const tipo = $(this).attr("data-tipo");

        function openFirstForm() {
            let formulario_asignacion = `
                        <div class="container-fluid">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="matricula" class="form-label">Matricula del alumnos</label>
                                    <input class="form-control" id="matricula">
                                </div>
                                <div id="error_message" class="text-danger mt-3" style="display: none;">Todos los campos son obligatorios.</div>
                            </div>
                        </div>`;
            $.alert({
                title: false,
                closeIcon: true,
                columnClass: "col-md-3 col-md-offset-6",
                content: formulario_asignacion,
                type: "blue",
                theme: "Modern",
                buttons: {
                    ok: {
                        text: `<i class="fa-solid fa-check-to-slot"></i> Aceptar`,
                        btnClass: "btn btn-info float-end btn-modal",
                        action: function () {
                            const matricula = $("#matricula").val();
                            if (matricula) {
                                $("#error_message").hide();
                                $.ajax({
                                    url: "verifica_financiero_alumno",
                                    type: "POST",
                                    data: {
                                        matricula,
                                    },
                                    dataType: "json",
                                    success: function (response) {
                                        console.log(response.exists);
                                        if (response.exists == 0) {
                                            // Asignamos el usuario
                                            $.ajax({
                                                type: "POST",
                                                url: `asignar_financiero_matricula`,
                                                data: {
                                                    matricula,
                                                    idusuario,
                                                },
                                                dataType: "json",
                                                success: function (response) {
                                                    console.log(response.message);
                                                    $("#toast-title").html(
                                                        `<i class="fa-solid fa-check"></i>  Mensaje del sistema`
                                                    );
                                                    $("#toast-body").html(
                                                        "El usuario fue asignado correctamente al alumnos"
                                                    );
                                                    $("#response-toast").toast("show");
                                                },
                                                error: function () {
                                                    $.confirm({
                                                        title: "Error",
                                                        content: "Error de lado del servidor",
                                                        type: "red",
                                                        buttons: {
                                                            ok: {
                                                                text: "OK",
                                                                btnClass: "btn btn-danger",
                                                                action: function () {},
                                                            },
                                                        },
                                                    });
                                                },
                                            });
                                        } else {
                                            $.confirm({
                                                title: false,
                                                content: `
                                                            <div class="container-fluid">
                                                                <div class="row g-3">
                                                                    <div class="col-md-12 text-center">
                                                                        <p><i class="fa-solid fa-2x fa-bell"></i></p>
                                                                        El alumno ya cuenta con un asesor asignado<br>¿Deseas sustituirlo?
                                                                    </div>
                                                                </div>
                                                            </div>`,
                                                type: "blue",
                                                theme: "Modern",
                                                columnClass: "col-md-4 col-md-offset-4",
                                                buttons: {
                                                    regresar: {
                                                        btnClass: "btn btn-sm float-end btn-modal",
                                                        text: '<i class="fa-solid fa-arrow-left"></i> Regresar',
                                                        action: function () {
                                                            openFirstForm();
                                                        },
                                                    },
                                                    continuar: {
                                                        btnClass: "btn btn-sm float-end btn-modal",
                                                        text: '<i class="fa-solid fa-check-to-slot"></i> Continuar',
                                                        action: function () {
                                                            // Your AJAX request to guardar_seguimiento
                                                            $.ajax({
                                                                type: "POST",
                                                                url: `asignar_financiero_matricula`,
                                                                data: {
                                                                    matricula,
                                                                    idusuario,
                                                                },
                                                                dataType: "json",
                                                                success: function (response) {
                                                                    console.log(response.message);
                                                                    $("#toast-title").html(
                                                                        `<i class="fa-solid fa-check"></i> Mensaje del sistema`
                                                                    );
                                                                    $("#toast-body").html(
                                                                        "Usuario reasignado correctamente"
                                                                    );
                                                                    $("#response-toast").toast("show");
                                                                },
                                                                error: function () {
                                                                    $.confirm({
                                                                        title: "Error",
                                                                        content: "Error de lado del servidor",
                                                                        type: "red",
                                                                        buttons: {
                                                                            ok: {
                                                                                text: "OK",
                                                                                btnClass: "btn btn-danger",
                                                                                action: function () {},
                                                                            },
                                                                        },
                                                                    });
                                                                },
                                                            });
                                                        },
                                                    },
                                                },
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        // Manejar errores en la solicitud AJAX
                                    },
                                });
                            } else {
                                if (matricula == "") {
                                    $("#matricula").addClass("is-invalid");
                                }
                                $("#error_message").show();
                                return false;
                            }
                        },
                    },
                },
            });
        }

        if (tipo == "financiero") {
            openFirstForm();
        } else if (tipo == "academico") {
            let formulario_asignacion = `
                        <div class="container-fluid">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Programas</label>
                                    <select class="form-select form-select-sm" aria-label="Default select example" id="programas">
                                    <option value=0>Seleccione un programa</option>
                                    <optgroup label="LICENCIATURAS" data-meses="cuatrimestre">
                                        <option value="LSP">LSP (Licenciatura en seguridad pública)</option>
                                        <option value="LD">LD (Licenciatura en derecho)</option>
                                        <option value="LAE">LAE (Licenciatura en administración de empresas)</option>
                                        <option value="LCPAP">LCPAP (Licenciatura en Ciencias Políticas y Administración Pública)</option>
                                    </optgroup>
                                    <optgroup label="MAESTRIAS" data-meses="trimestre">
                                        <option value="MAPP">MAPP (Maestría en administración y Políticas Públicas)</option>
                                        <option value="MEPP">MEPP (Maestría en Evaluación y Políticas Públicas)</option>
                                        <option value="MSPP">MSPP (Maestría en Seguridad y Políticas Públicas)</option>
                                        <option value="MIGE">MIGE (Maestría en Innovación y Gestión Educativa)</option>
                                        <option value="MAN">MBA (Maestría en administración de negocios)</option>
                                        <option value="MFP">MFP (Maestría en Finanzas Públicas)</option>
                                        <option value="MCDA">MCDA (Maestría en Ciencia de Datos Aplicada)</option>
                                        <option value="MCDIA">MCDIA (Maestría en Ciencia de Datos Aplicada)</option>
                                        <option value="MITI">MITI (Maestría en Administración de Tecnologías de la información)</option>
                                        <option value="MAIS">MAIS (Maestría en Instituciones de Salud)</option>
                                        <option value="MAG">MAG (Maestría en Auditoría Gubernamental)</option>
                                        <option value="MGPM">MGPM (Maestría en Gestión Pública Municipal)</option>
                                        <option value="MSPAJO">MSPAJO (Maestría en Sistema Penal Acusatorio y Juicio Oral)</option>
                                        <option value="MMPOP">MMPOP (Maestría en Marketing Político y Opinión Pública)</option>
                                    </optgroup>
                                    <optgroup label="DOCTORADOS" data-meses="cuatrimestre">
                                        <option value="DPP">DPP (Doctorado en Políticas Públicas)</option>
                                        <option value="DSP">DSP (Doctorado en Seguridad Pública)</option>
                                    </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Periodos activos</label>
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="periodos">
                                        <option selected>Seleccione primero un programa</option>
                                    </select>
                                </div>
                                <div id="error_message" class="text-danger mt-3" style="display: none;">Todos los campos son obligatorios.</div>

                            </div>
                        </div>`;
            $.alert({
                title: false,
                closeIcon: true,
                columnClass: "col-md-8 col-md-offset-2",
                content: formulario_asignacion,
                type: "blue",
                theme: "Modern",
                buttons: {
                    ok: {
                        text: `<i class="fa-solid fa-check-to-slot"></i> Aceptar`,
                        btnClass: "btn btn-info float-end btn-modal",
                        action: function () {
                            const programas = $("#programas").val();
                            const periodos = $("#periodos").val();

                            // Validar si programas y periodos son diferentes de 0
                            if (programas != "0" && periodos != "0") {
                                // Realizar la solicitud AJAX al controlador
                                $("#error_message").hide();

                                $.ajax({
                                    url: "asignar_consejera_masivo",
                                    type: "POST",
                                    data: {
                                        programa: programas,
                                        periodo: periodos,
                                        idusuario: idusuario, // Reemplaza con el valor adecuado
                                    },
                                    success: function (response) {
                                        // Manejar la respuesta del controlador
                                        // Puedes mostrar un mensaje de éxito, recargar la página, etc.

                                        $("#toast-title").html(`<i class="fa-solid fa-check"></i> Mensaje del sistema`);
                                        $("#toast-body").html("Usuario asignado correctamente");
                                        $("#response-toast").toast("show");
                                    },
                                    error: function (xhr, status, error) {
                                        // Manejar errores en la solicitud AJAX
                                    },
                                });
                            } else {
                                // Si alguno de los campos es 0, marcarlos como is-invalid
                                if (programas == "0") {
                                    $("#programas").addClass("is-invalid");
                                }
                                if (periodos == "0") {
                                    $("#periodos").addClass("is-invalid");
                                }
                                $("#error_message").show();
                                return false;
                            }
                        },
                    },
                },
            });
        }
    });

    $(document).on("change", "#periodos", function () {
        if ($(this).val() != "0") {
            $(this).addClass("is-valid").removeClass("is-invalid");
        } else {
            $(this).addClass("is-invalid").removeClass("is-valid");
        }
    });

    $(document).on("blur", "#matricula", function () {
        if ($(this).val() != "") {
            $(this).addClass("is-valid").removeClass("is-invalid");
        } else {
            $(this).addClass("is-invalid").removeClass("is-valid");
        }
    });

    $(document).on("change", "#programas", function () {
        const programa = $(this).val();
        if (programa != "0") {
            $(this).addClass("is-valid").removeClass("is-invalid");
        } else {
            $(this).addClass("is-invalid").removeClass("is-valid");
        }
        $.ajax({
            type: "POST",
            url: `obtener_periodos_activos_consejeras/${programa}`,
            dataType: "json",
            success: function (response) {
                let options = "<option value=0>Seleccione un periodo</option>";
                console.log(response);
                $("#periodos").empty();
                $.each(response, function (i, p) {
                    options += `<option>${p.periodo}</option>`;
                });
                $("#periodos").append(options);
            },
        });
    });

    $("#nuevo-usuario-form").on("submit", function (e) {
        e.preventDefault();

        var form = $(this)[0];
        if (!form.checkValidity()) {
            $(this).addClass("was-validated");
            return;
        }

        var rolesSelected = $("input[name='rol[]']:checked").length;
        if (rolesSelected === 0) {
            $("#rol-feedback").removeClass("d-none").addClass("d-block");
            $("input[name='rol[]']").addClass("is-invalid");
            return;
        } else {
            $("#rol-feedback").removeClass("d-block").addClass("d-none");
            $("input[name='rol[]']").removeClass("is-invalid").addClass("is-valid");
        }

        $.ajax({
            url: "agregar_usuario",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);
                var toastMessage = "";

                if (response.status === "success") {
                    $("#nuevo-usuario-form")[0].reset();
                    $("#nuevo-usuario-form").removeClass("was-validated");
                    $("input[name='rol[]']").removeClass("is-valid");
                    toastMessage = "Usuario creado correctamente.";
                    icon = '<i class="fa-solid fa-check"></i>';
                } else if (response.status === "exists") {
                    toastMessage = "El usuario ya existe.";
                    icon = '<i class="fa-solid fa-triangle-exclamation"></i>';
                } else {
                    toastMessage = `Ha ocurrido el siguiente error:${response.mesage}`;
                    icon = '<i class="fa-solid fa-circle-exclamation"></i>';
                }

                $("#toast-title").html(`${icon} Mensaje del sistema`);
                $("#toast-body").html(toastMessage);
                $("#response-toast").toast("show");
            },
            error: function (xhr, status, error) {
                icon = '<i class="fa-solid fa-circle-exclamation"></i>';

                $("#toast-title").html(`${icon} Mensaje del sistema`);
                $("#toast-body").html(
                    "Ha ocurrido un error. Intentalo de nuevo, si persiste el problema contacta al área de TI."
                );
                $("#response-toast").toast("show");
            },
        });
    });

    $("input[name='rol[]']").on("change", function () {
        var asesorFinanciero = $("#asesor_financiero");
        var asesorAcademico = $("#asesor_academico");

        if (this.id === "asesor_financiero" && this.checked) {
            asesorAcademico.prop("checked", false).removeClass("is-invalid is-valid");
        }

        if (this.id === "asesor_academico" && this.checked) {
            asesorFinanciero.prop("checked", false).removeClass("is-invalid is-valid");
        }
    });
});
