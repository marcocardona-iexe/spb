$(document).ready(function () {
	$("#tbl_asignaciones").DataTable();

	$("#programas").on("change", function () {
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
				$("#periodos").empty();
				$.each(response, function (i, p) {
					options += `<option>${p.periodo}</option>`;
				});
				$("#periodos").append(options);
			},
		});
	});

	$("#periodos").on("change", function () {
		const periodos = $(this).val();
		if (periodos != "0") {
			$(this).addClass("is-valid").removeClass("is-invalid");
		} else {
			$(this).addClass("is-invalid").removeClass("is-valid");
		}
	});

	$("#consejeras").on("change", function () {
		const consejeras = $(this).val();
		if (consejeras != "0") {
			$(this).addClass("is-valid").removeClass("is-invalid");
		} else {
			$(this).addClass("is-invalid").removeClass("is-valid");
		}
	});

	$("#asignar_consejera").click(function () {
		// Obtener los valores de los campos de entrada
		const programa = $("#programas").val();
		const periodo = $("#periodos").val();
		const consejera = $("#consejeras").val();

		// Validar si los campos son diferentes de 0
		if (programa !== "0" && periodo !== "0" && consejera !== "0") {
			// Lógica para avanzar al evento AJAX
			// ...
			// Por ejemplo:
			$.ajax({
				url: "asignar_consejera_masivo",
				type: "POST",
				data: {
					programa: programa,
					periodo: periodo,
					idusuario: consejera, // Reemplaza con el valor adecuado
				},
				success: function (response) {
					// Manejar la respuesta del controlador
					// Puedes mostrar un mensaje de éxito, recargar la página, etc.

					$("#toast-title").html(
						`<i class="fa-solid fa-check"></i> Mensaje del sistema`
					);
					$("#toast-body").html("Usuario asignado correctamente");
					$("#response-toast").toast("show");
				},
				error: function (xhr, status, error) {
					// Manejar errores en la solicitud AJAX
				},
			});
		} else {
			// Marcar los campos como inválidos utilizando Bootstrap
			if (programa === "0") {
				$("#programas").addClass("is-invalid");
			}
			if (periodo === "0") {
				$("#periodos").addClass("is-invalid");
			}
			if (consejera === "0") {
				$("#consejeras").addClass("is-invalid");
			}
		}
	});

	$("#subir_archivo").click(function () {
		// Resetear el botón
		$("#subir_archivo").html(
			`<div class="spinner-border spinner-border-sm text-light" role="status"></div> Procesando archivo`
		);

		var inputFile = $("#excel_consejera")[0].files[0];

		// Validar que se haya seleccionado un archivo
		if (inputFile) {
			// Validar el tamaño del archivo (en bytes)
			var maxSize = 2 * 1024 * 1024; // 2 MB en bytes
			if (inputFile.size > maxSize) {
				var alertHtml = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> El archivo seleccionado supera el tamaño máximo permitido de 2 MB.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
				$("#form-alert2-container").html(alertHtml);
				$("#subir_archivo").html(
					`<i class="fa-solid fa-upload"></i> Subir archivo`
				);
				return; // Detener el proceso si el archivo es demasiado grande
			}

			var formData = new FormData();
			formData.append("file", inputFile);

			// Realizar la petición AJAX
			$.ajax({
				url: "consejera_masiva",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function (response) {
					$("#subir_archivo").html(
						`<i class="fa-solid fa-upload"></i> Subir archivo`
					);
					// Manejar la respuesta del servidor
					var responseObject = response;
					if (responseObject.status === "error") {
						var errors = responseObject.errors
						.map((error) => `<li>${error}</li>`)
						.join("");
						var alertHtml = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Error:</strong> ${responseObject.message}<ul>${errors}</ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;
						$("#form-alert2-container").html(alertHtml);
					} else {
						var alertHtml = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Éxito:</strong> ${responseObject.message}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;
						$("#form-alert2-container").html(alertHtml);
					}
				},
				error: function (xhr, status, error) {
					$("#subir_archivo").html(
						`<i class="fa-solid fa-upload"></i> Subir archivo`
					);
					// Manejar el error
					var alertHtml = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Error:</strong> Error al procesar el archivo.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
					$("#form-alert2-container").html(alertHtml);
					console.log(error);
				},
			});
		} else {
			// Mostrar mensaje de error si no se seleccionó ningún archivo
			var alertHtml = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> Por favor, seleccione un archivo Excel válido.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
			$("#form-alert2-container").html(alertHtml);
			$("#subir_archivo").html(
				`<i class="fa-solid fa-upload"></i> Subir archivo`
			);
		}
	});
});
