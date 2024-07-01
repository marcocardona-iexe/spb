$(document).ready(function () {
	$("#subir_archivo").click(function () {
		// Resetear el botón
		$("#subir_archivo").html(
			`<div class="spinner-border spinner-border-sm text-light" role="status"></div> Procesando archivo`
		);

		var inputFile = $("#excel_financiero")[0].files[0];

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
				url: "financiero_masivo",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function (response) {
					console.log(response);
					$("#subir_archivo").html(
						`<i class="fa-solid fa-upload"></i> Subir archivo`
					);
					// Manejar la respuesta del servidor
					var responseObject = JSON.parse(response);
					console.log(responseObject);
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
					console.error(error);
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

	$("#asignar_financiero").on("click", function () {
		const idusuario = $("#financiero").val();
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
								cerrar: {
									btnClass: "btn btn-sm float-end btn-modal",
									text: '<i class="fa-solid fa-rectangle-xmark"></i> Cerrar',
									action: function () {},
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
	});

	// const table = $("#tbl_alumnos").DataTable({
	//     processing: true,
	//     serverSide: true,
	//     ajax: {
	//         url: "ajax_lista_alumnos",
	//         type: "GET",
	//     },
	//     columns: [
	//         {
	//             data: "nombre",
	//         },
	//         {
	//             data: "periodo",
	//         },
	//         {
	//             data: "programa",
	//         },
	//         {
	//             data: "periodo_mensual",
	//         },
	//         {
	//             data: "matricula",
	//         },
	//         {
	//             data: "correo",
	//         },
	//         {
	//             data: "probabilidad_baja",
	//             render: function (data, type, row) {
	//                 let badgeClass = "bg-secondary";

	//                 if (data === "Alta R1") {
	//                     badgeClass = "bg-danger";
	//                 } else if (data === "Media R2") {
	//                     badgeClass = "bg-warning text-dark";
	//                 } else if (data === "Baja R3") {
	//                     badgeClass = "bg-success";
	//                 }

	//                 return `<div class="text-center"><span class="badge ${badgeClass} status_probabilidad" onclick="probabilidad_baja('${row.matricula}')">${data}</span></div>`;
	//             },
	//         },
	//         {
	//             data: "estatus_plataforma",
	//             render: function (data, type, row) {
	//                 let btnClass = "";

	//                 if (data === "Bloqueado") {
	//                     btnClass = "btn-outline-warning";
	//                 } else if (data === "Desbloqueado") {
	//                     btnClass = "btn-outline-success";
	//                 } else if (data === "Baja temporal") {
	//                     btnClass = "btn-outline-danger";
	//                 } else if (data === "Baja definitiva") {
	//                     btnClass = "btn-outline-danger";
	//                 } else if (data === "Activo") {
	//                     btnClass = "btn-outline-secondary";
	//                 }

	//                 return `<div class="text-center"><button type="button" class="btn btn-estatus ${btnClass}">${data}</button></div>`;
	//             },
	//         },
	//         {
	//             data: "nombre_consejera",
	//             render: function (data, type, row) {
	//                 console.log(row);
	//                 if (row.nombre_consejera == null) {
	//                     return `<td class="text-center"><span class="badge bg-warning text-dark warning " onclick="asignar_asesor('${row.id}', 'c')">Sin consejera asiganada</span></td>`;
	//                 } else {
	//                     return `<div class="text-center">${row.nombre_consejera}</div>`;
	//                 }
	//             },
	//         },
	//         {
	//             data: "nombre_financiero",
	//         },
	//         {
	//             data: null,
	//             render: function (data, type, row) {
	//                 return `
	//                 <div class="text-center">
	//                     <div class="dropdown">
	//                         <button class="btn btn-modal btn-sm dropdown-toggle "  type="button" id="dropdownMenuButton${row.id}" data-bs-toggle="dropdown" aria-expanded="false">
	//                             <i class="fa-solid fa-gears"></i> Acciones
	//                         </button>
	//                         <ul class="dropdown-menu" id="element_acciones" aria-labelledby="dropdownMenuButton${row.id}">
	//                             <li><a class="dropdown-item" onclick="consultar('${row.id}')"><i class="fa-solid fa-chalkboard-user"></i> Consultar</a></li>
	//                             <li><a class="dropdown-item" onclick="seguimiento('${row.id}', '${row.periodo}')"><i class="fa-brands fa-rocketchat"></i> Seguimiento</a></li>
	//                         </ul>
	//                     </div>
	//                 </div>`;
	//             },
	//         },
	//     ],
	//     order: [[0, "asc"]], // Orden inicial por la primera columna

	//     responsive: true,

	//     pageLength: 10,
	//     language: {
	//         lengthMenu: "Mostrar _MENU_ alumnos por página",
	//         zeroRecords: "No se encontraron resultados",
	//         info: "Alumnos del _START_ al _END_ de _TOTAL_ alumnos",
	//         infoEmpty: "No hay registros disponibles",
	//         infoFiltered: "(filtrado de _MAX_ registros totales)",
	//         search: "Buscar:",
	//         paginate: {
	//             first: "Primero",
	//             last: "Último",
	//             next: "Siguiente",
	//             previous: "Anterior",
	//         },

	//         processing: '<div class="spinner"></div><div class="loading-message">Cargando datos...</div>',
	//     },
	// });

	// window.probabilidad_baja = (matricula) => {
	//     $.ajax({
	//         type: "POST",
	//         url: `probabilidad_baja/${matricula}`,
	//         dataType: "json",
	//         success: function (response) {
	//             console.log(response);
	//             let actividades = "";
	//             const icon_financiero =
	//                 response.financiera.variable_financiera == 0
	//                     ? '<i class="fa-regular fa-circle-check"></i>'
	//                     : '<i class="fa-solid fa-triangle-exclamation"></i>';

	//             const clase_card_financiero =
	//                 response.financiera.variable_financiera == 0 ? "bg-success text-white" : "bg-danger text-white";
	//             $.each(response.academica.materia[0].actividades, function (i, a) {
	//                 if (a.opcional == 1) {
	//                     icon = '<i class="fa-regular fa-star"></i>';
	//                     fecha_establecida = "";
	//                 } else {
	//                     icon =
	//                         a.notificacion == 0
	//                             ? '<i class="fa-regular fa-circle-check"></i>'
	//                             : '<i class="fa-solid fa-triangle-exclamation"></i>';

	//                     fecha_establecida = `(${a.finalizacion})`;
	//                 }
	//                 actividades += `<li>${icon} ${a.itemname} ${fecha_establecida}</li>`;
	//             });

	//             let body = `
	//             <div class="container-fluid">
	//                 <div class="row g-3">
	//                     <div class="col-md-12">
	//                         <div class="card">
	//                             <div class="card-header text-start">
	//                                 <i class="fa-solid fa-graduation-cap"></i> Historial Académico
	//                             </div>
	//                             <div class="card-body text-start">
	//                                 <p>Materia: ${response.academica.materia[0].fullname}</p>
	//                                 <p>
	//                                 <ul>
	//                                     ${actividades}
	//                                 </ul>
	//                                 </p>
	//                                 <div class="container">
	//                                     <div class="row justify-content-center">
	//                                         <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
	//                                             <i class="fa-regular fa-star"></i>
	//                                             <span>Actividad Optativa</span>
	//                                         </div>
	//                                         <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
	//                                             <i class="fa-regular fa-circle-check"></i>
	//                                             <span>Actividad Completada</span>
	//                                         </div>
	//                                         <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
	//                                             <i class="fa-solid fa-triangle-exclamation"></i>
	//                                             <span>Actividad Incompleta</span>
	//                                         </div>
	//                                     </div>
	//                                 </div>
	//                             </div>
	//                         </div>
	//                     </div>
	//                     <div class="col-md-12">
	//                         <div class="card">
	//                             <div class="card-header text-start ${clase_card_financiero}">
	//                                 <i class="fa-solid fa-wallet"></i> Información Financiera
	//                             </div>
	//                             <div class="card-body text-start">
	//                                 <p>${response.financiera.message}</p>
	//                                 <p>
	//                                 <ul>
	//                                     <li>${icon_financiero} Día de pago del alumno ${response.financiera.moroso} de cada mes</li>
	//                                 </ul>
	//                                 </p>
	//                                 <div class="container">
	//                                     <div class="row justify-content-center">
	//                                         <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
	//                                             <i class="fa-regular fa-circle-check"></i>
	//                                             <span>Pago al día</span>
	//                                         </div>
	//                                         <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
	//                                             <i class="fa-solid fa-triangle-exclamation"></i>
	//                                             <span>Retraso en pago</span>
	//                                         </div>
	//                                     </div>
	//                                 </div>
	//                             </div>
	//                         </div>
	//                     </div>

	//                 </div>
	//             </div>`;

	//             $.alert({
	//                 title: false,
	//                 closeIcon: true,
	//                 columnClass: "col-md-8 col-md-offset-2",
	//                 content: body,
	//                 type: "blue",
	//                 theme: "Modern",

	//                 buttons: {
	//                     ok: {
	//                         text: "Aceptar",
	//                         btnClass: "btn btn-info btn-modal",
	//                         action: function () {},
	//                     },
	//                 },
	//             });
	//         },
	//     });
	// };
});
