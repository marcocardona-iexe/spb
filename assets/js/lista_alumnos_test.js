$(document).ready(function () {
	$(
		"#nombre, #apellidos, #correo, #matricula, #programas, #periodos, #periodos_mensuales, #estatus-plataforma, #consejera, #financiero"
	).on("keypress", function (e) {
		if (e.which === 13) {
			// 13 es el código de la tecla Enter
			window.busqueda_avanzada();
		}
	});
	$("#ver_todos").on("click", function () {
		$("#loading").show();
		$("#contenedor_tabla_alumnos").hide();
		let URL = `data_tabla_inicial`;

		// Destruir la tabla DataTable actual si ya existe
		if ($.fn.DataTable.isDataTable("#tbl_alumnos")) {
			$("#tbl_alumnos").DataTable().destroy();
		}

		// Configurar la tabla con la URL específica
		const table = configurarTablaAlumnos(URL);

		// Mostrar la tabla y ocultar el loading después de cargar los datos
		table.on("draw", function () {
			$("#loading").hide();
			$("#contenedor_tabla_alumnos").show();
		});
	});

	window.consultar = (idmoodle) => {
		window.open(`detalle-del-alumno/${idmoodle}`, "_blank");
	};

	$("#descargar_seguimientos").on("click", function () {
		let f_inicial = $("#fecha_inicial").val();
		let f_final = $("#fecha_final").val();
		window.open(
			`https://archivo.iexe.edu.mx/asesores/reporte?fecha_inicial=${f_inicial}&fecha_final=${f_final}`,
			"_blank"
		);
	});

	window.probabilidad_baja = (matricula) => {
		$.confirm({
			title: false,
			closeIcon: true,
			columnClass: "col-md-8 col-md-offset-2",
			type: "blue",
			theme: "Modern",
			buttons: {
				ok: {
					text: "Aceptar",
					btnClass: "btn btn-info btn-modal",
					action: function () {},
				},
			},
			content: function () {
				var self = this;
				return $.ajax({
					url: `probabilidad_baja/${matricula}`, // Reemplaza con la URL de tu JSON
					dataType: "json",
					method: "POST",
				})
				.done(function (response) {
					console.log(response);
					let actividades = "";
					const icon_financiero =
						response.financiera.variable_financiera == 0
							? '<i class="fa-regular fa-circle-check"></i>'
							: '<i class="fa-solid fa-triangle-exclamation"></i>';

					console.log(
						"La variable financiera es " +
							response.financiera.variable_financiera
					);

					console.log(
						"La variable academica es " + response.academica.variable_academica
					);

					const clase_card_financiero =
						response.financiera.variable_financiera == "0"
							? "modal-success"
							: "modal-danger";
					const clase_card_academica =
						response.academica.variable_academica == "0"
							? "modal-success"
							: "modal-danger";
					$.each(response.academica.materia[0].actividades, function (i, a) {
						if (a.opcional == 1) {
							icon = '<i class="fa-regular fa-star"></i>';
							fecha_establecida = "";
						} else {
							icon =
								a.notificacion == 0
									? '<i class="fa-regular fa-circle-check"></i>'
									: '<i class="fa-solid fa-triangle-exclamation"></i>';

							fecha_establecida = `(${a.finalizacion})`;
						}
						actividades += `<li>${icon} ${a.itemname} ${fecha_establecida}</li>`;
					});

					let body = `
						<div class="container-fluid">
							<div class="row g-3">
								<div class="col-md-12">
									<div class="card">
										<div class="card-header text-start ${clase_card_academica}">
											<i class="fa-solid fa-graduation-cap"></i> Historial Académico
										</div>
										<div class="card-body text-start">
											<p>Materia: ${response.academica.materia[0].fullname}</p>
											<p>
											<ul>
												${actividades}
											</ul>
											</p>
											<div class="container">
												<div class="row justify-content-center">
													<div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
														<i class="fa-regular fa-star"></i>
														<span>Actividad Optativa</span>
													</div>
													<div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
														<i class="fa-regular fa-circle-check"></i>
														<span>Actividad Completada</span>
													</div>
													<div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
														<i class="fa-solid fa-triangle-exclamation"></i>
														<span>Actividad Incompleta</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="card">
										<div class="card-header text-start ${clase_card_financiero}">
											<i class="fa-solid fa-wallet"></i> Información Financiera
										</div>
										<div class="card-body text-start">
											<p>${response.financiera.message}</p>
											<p>
											<ul>
												<li>${icon_financiero} Día de pago del alumno ${response.financiera.moroso} de cada mes</li>
											</ul>
											</p>
											<div class="container">
												<div class="row justify-content-center">
													<div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
														<i class="fa-regular fa-circle-check"></i>
														<span>Pago al día</span>
													</div>
													<div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3">
														<i class="fa-solid fa-triangle-exclamation"></i>
														<span>Retraso en pago</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>`;
					self.setContent(body);
				})
				.fail(function () {
					self.setContent("Error al cargar los datos.");
				});
			},
		});
	};

	window.buscar_seguimiento = function (tipo) {
		$("#loading").show();
		$("#contenedor_tabla_alumnos").hide();
		let URL = `alumnos_buscar_seguimientos/${tipo}`;

		// Destruir la tabla DataTable actual si ya existe
		if ($.fn.DataTable.isDataTable("#tbl_alumnos")) {
			$("#tbl_alumnos").DataTable().destroy();
		}

		// Configurar la tabla con la URL específica
		const table = configurarTablaAlumnos(URL);

		// Mostrar la tabla y ocultar el loading después de cargar los datos
		table.on("draw", function () {
			$("#loading").hide();
			$("#contenedor_tabla_alumnos").show();
		});
	};

	window.seguimiento = function (id, periodo) {
		$.ajax({
			type: "POST",
			url: `verificar_seguimientos/${id}`,
			dataType: "json",
			success: function (response) {
				let fila = "";
				let idseguimento = 0;
				let historial = "";
				if (response.status == "active") {
					console.log(response.seguimiento[0]);
					idseguimento = response.seguimiento[0]["idseguimiento"];

					$.each(response.historial, function (i, h) {
						fila += `
                        <tr>
                        <td>${i + 1}</td>
                        <td>${h.metodo_contacto}</td>
                        <td>${h.estatus_seguimiento}</td>
                        <td>${h.estatus_acuerdo}</td>
                        <td>${h.comentarios}</td>
                        <td>${h.insert_date}</td>
                        <td>${h.asesor}</td>
                        </tr>`;
					});
					historial = `
                    <h6>HISTORIAL DE SEGUIMIENTOS</h6>
                    <table class="table table-striped table-hover table-bordered table-sm" id="tabla_historial_seguimientos">
                        <thead style="background:#08384d; color:white;">
                            <tr>
                                <td>No</td>
                                <td>Metodo de Contacto</td>
                                <td>Estatus de Seguimiento</td>
                                <td>Acuerdo de Seguimiento</td>
                                <td>Comentarios</td>
                                <td>Fecha</td>
                                <td>Asesor</td>
                            </tr>
                        </thead>
                        <tbody>
                            ${fila}
                        </tbody>
                    </table>`;
				}
				primerConfirm(idseguimento, id, periodo, historial);
			},
		});
	};

	window.cambiarVerde = (id) => {
		$("#" + id).removeClass("is-invalid");
		$("#" + id).addClass("is-valid");
	};

	const primerConfirm = (idseguimento, id, periodo, historial) => {
		let formulario_registro = `
                    <div class="container-fluid">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Método de contacto</label>
                                <select onchange="cambiarVerde('m_contacto')" class="form-control form-control-sm" id="m_contacto">
                                    <option value="1">Seleccione una opcion</option>
                                    <option value="Correo electrónico">Correo electrónico</option>
                                    <option value="Llamada">Llamada</option>
                                    <option value="Whatsapp">Whatsapp</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estatus del seguimiento</label>
                                <select onchange="cambiarVerde('estatus_seguimiento')" class="form-control form-control-sm" id="estatus_seguimiento">
                                    <option value="1">Seleccione una opcion</option>
                                    <option value="Respondio">Respondió</option>
                                    <option value="No respondio">No respondió</option>
                                    <option value="Buzón directo">Buzón directo</option>
                                    <option value="Respondio y colgo">Respondió y colgó</option>
                                    <option value="Dejo en visto">Dejó en visto</option>
                                    <option value="No le entran los mensajes">No le entran los mensajes</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estatus de acuerdo</label>
                                <select onchange="cambiarVerde('estatus_acuerdo')" class="form-control form-control-sm" id="estatus_acuerdo">
                                    <option value="1">Seleccione una opcion</option>
                                    <option value="Interesado en continuar">Interesado en continuar</option>
                                    <option value="Solicita baja">Solicita baja</option>
                                    <option value="Baja por falta de respuesta">Baja por falta de respuesta</option>
                                    <option value="No respondió">No respondió</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <h6>Comentarios</h6>
                            </div>
                            <div class="col-md-12">
                                <textarea onchange="cambiarVerde('comentarios')" class="form-control" id="comentarios" rows="3"></textarea>
                            </div>
                            
                            <div class="col-md-12 pt-2 text-center">
                                ${historial}
                            </div>
                        </div>
                        <div id="error_message" class="text-danger mt-3" style="display: none;">Todos los campos son obligatorios.</div>
                    </div>`;

		$.confirm({
			title: false,

			closeIcon: true, // explicitly show the close icon
			escapeKey: true,
			content: formulario_registro,
			theme: "Modern",
			columnClass: "col-md-8 col-md-offset-2",
			type: "blue",
			buttons: {
				pendiente: {
					btnClass: "btn btn-sm float-end btn-modal",
					text: '<i class="fa-solid fa-clock"></i> Pendiente de contacto',
					action: function () {
						// Validar los campos
						let valid = true;
						let metodo_contacto = $("#m_contacto").val();
						let estatus_seguimiento = $("#estatus_seguimiento").val();
						let estatus_acuerdo = $("#estatus_acuerdo").val();
						let comentarios = $("#comentarios").val().trim();

						if (metodo_contacto == "1") {
							$("#m_contacto").addClass("is-invalid");
							valid = false;
						} else {
							$("#m_contacto").removeClass("is-invalid");
						}

						if (estatus_seguimiento == "1") {
							$("#estatus_seguimiento").addClass("is-invalid");
							valid = false;
						} else {
							$("#estatus_seguimiento").removeClass("is-invalid");
						}

						if (estatus_acuerdo == "1") {
							$("#estatus_acuerdo").addClass("is-invalid");
							valid = false;
						} else {
							$("#estatus_acuerdo").removeClass("is-invalid");
						}

						if (comentarios == "") {
							$("#comentarios").addClass("is-invalid");
							valid = false;
						} else {
							$("#comentarios").removeClass("is-invalid");
						}

						if (!valid) {
							$("#error_message").show();
							return false;
						} else {
							$("#error_message").hide();
							// Aquí manejamos la acción si la validación es correcta
							$.ajax({
								type: "POST",
								url: `guardar_seguimiento/${idseguimento}/activo`,
								data: {
									metodo_contacto: metodo_contacto,
									estatus_seguimiento: estatus_seguimiento,
									estatus_acuerdo: estatus_acuerdo,
									comentarios: comentarios,
									idalumno: id,
									periodo: periodo,
								},
								dataType: "json",
								success: function (response) {
									console.log(response.message);
									$.confirm({
										title: false,
										content: `
                                            <div class="container-fluid">
                                                <div class="row g-3">
                                                    <div class="col-md-12 text-center">
                                                        <p><i class="fa-regular fa-face-smile fa-2x"></i></p>
                                                            ${response.message}
                                                    </div>
                                                </div>
                                            </div>`,
										type: "blue",
										buttons: {
											continuar: {
												text: `<i class="fa-solid fa-check"></i> Continuar`,
												btnClass: "btn btn-modal",
												action: function () {},
											},
										},
									});
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
						}
					},
				},
				cerrar: {
					btnClass: "btn btn-sm float-end btn-modal",
					text: '<i class="fa-solid fa-check-to-slot"></i> Finalizar seguimiento',
					action: function () {
						// Validar los campos
						let valid = true;
						let metodo_contacto = $("#m_contacto").val();
						let estatus_seguimiento = $("#estatus_seguimiento").val();
						let estatus_acuerdo = $("#estatus_acuerdo").val();
						let comentarios = $("#comentarios").val().trim();

						if (metodo_contacto == "1") {
							$("#m_contacto").addClass("is-invalid");
							valid = false;
						} else {
							$("#m_contacto").removeClass("is-invalid");
						}

						if (estatus_seguimiento == "1") {
							$("#estatus_seguimiento").addClass("is-invalid");
							valid = false;
						} else {
							$("#estatus_seguimiento").removeClass("is-invalid");
						}

						if (estatus_acuerdo == "1") {
							$("#estatus_acuerdo").addClass("is-invalid");
							valid = false;
						} else {
							$("#estatus_acuerdo").removeClass("is-invalid");
						}

						if (comentarios == "") {
							$("#comentarios").addClass("is-invalid");
							valid = false;
						} else {
							$("#comentarios").removeClass("is-invalid");
						}

						if (!valid) {
							$("#error_message").show();
							return false;
						} else {
							$("#error_message").hide();
							// Aquí manejamos la acción si la validación es correcta

							segundoConfirm(
								metodo_contacto,
								estatus_seguimiento,
								estatus_acuerdo,
								comentarios,
								id,
								idseguimento,
								periodo
							);
						}
					},
				},
			},
		});
	};

	const segundoConfirm = (
		metodo_contacto,
		estatus_seguimiento,
		estatus_acuerdo,
		comentarios,
		id,
		idseguimento,
		periodo
	) => {
		$.confirm({
			title: false,
			content: `
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12 text-center">
                    <p><i class="fa-solid fa-2x fa-bell"></i></p>
                        Estás a punto de finalizar el seguimiento<br>¿Deseas continuar?
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
						seguimiento(id, periodo);
					},
				},
				finalizar: {
					btnClass: "btn btn-sm float-end btn-modal",
					text: '<i class="fa-solid fa-check-to-slot"></i> Continuar',
					action: function () {
						$.ajax({
							type: "POST",
							url: `guardar_seguimiento/${idseguimento}/cerrado`,
							data: {
								metodo_contacto: metodo_contacto,
								estatus_seguimiento: estatus_seguimiento,
								estatus_acuerdo: estatus_acuerdo,
								comentarios: comentarios,
								idalumno: id,
								periodo: periodo,
							},
							dataType: "json",
							success: function (response) {
								console.log(response.message);
								$.confirm({
									title: false,
									content: `
                                            <div class="container-fluid">
                                                <div class="row g-3">
                                                    <div class="col-md-12 text-center">
                                                        <p><i class="fa-regular fa-face-smile fa-2x"></i></p>
                                                            ${response.message}
                                                    </div>
                                                </div>
                                            </div>`,
									type: "blue",
									buttons: {
										continuar: {
											text: `<i class="fa-solid fa-check"></i> Continuar`,
											btnClass: "btn btn-modal",
											action: function () {},
										},
									},
								});
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
	};

	function configurarTablaAlumnos(url = "data_tabla_inicial", data = null) {
		let ajaxConfig = {
			url: url,
			type: "POST", // o "GET" dependiendo de tu aplicación
		};
		if (data !== null) {
			ajaxConfig = {
				url: url,
				type: "POST", // o "GET" dependiendo de tu aplicación
				data: data,
			};
		}
		return $("#tbl_alumnos").DataTable({
			processing: true,
			serverSide: true,
			searching: false,
			pagingType: "simple_numbers",
			ajax: ajaxConfig,
			columns: [
				{
					data: "nombre",
					searchable: true,
					render: function (data, type, row) {
						return capitalizeFirstLetters(data);
					},
				},
				{ data: "periodo", searchable: true },
				{ data: "programa", searchable: true },
				{ data: "periodo_mensual", searchable: true },
				{ data: "matricula", searchable: true },
				{ data: "correo", searchable: true },
				{ data: "telefono", searchable: true },
				{
					data: "ultimo_acceso",
					render: function (data, type, row) {
						let tiempo = calcularTiempoRelativo(data);
						let fecha = unixToDate(data);
						return `${fecha} <b>(${tiempo})</b>`;
					},
				},
				{
					data: "probabilidad_baja",
					render: function (data, type, row) {
						let badgeClass = "bg-secondary";
						let estatus = "";
						if (data === "Alta R1") {
							badgeClass = "bg-danger";
							estatus = "Baja R1";
						} else if (data === "Media R2") {
							badgeClass = "bg-warning text-dark";
							estatus = "Baja R2";
						} else if (data === "Baja R3") {
							badgeClass = "bg-success";
							estatus = "Baja R3";
						}
						return `<div class="text-center"><span class="badge ${badgeClass} status_probabilidad" onclick="probabilidad_baja('${row.matricula}')">${estatus}</span></div>`;
					},
					searchable: true,
				},
				{
					data: "estatus_plataforma",
					render: function (data, type, row) {
						let badgeClass = "";
						if (data === "Bloqueado") {
							badgeClass = "bg-warning text-dark";
						} else if (data === "Desbloqueado") {
							badgeClass = "bg-success";
						} else if (data === "Baja temporal" || data === "Baja definitiva") {
							badgeClass = "bg-danger";
						} else if (data === "Activo") {
							badgeClass = "bg-info text-white";
						} else {
							badgeClass = "bg-secondary";
						}
						return `<div class="text-center"><span class="badge ${badgeClass} status_plataforma">${data}</span></div>`;
					},
					searchable: true,
				},
				{
					data: "nombre_consejera",
					render: function (data, type, row) {
						if (!data) {
							return `Sin asignación`;
						} else {
							return data;
						}
					},
					searchable: true,
				},
				{
					data: "nombre_financiero",
					render: function (data, type, row) {
						if (!data) {
							return `Sin asignación`;
						} else {
							return data;
						}
					},
					searchable: true,
				},
				{
					data: null,
					render: function (data, type, row) {
						const notiId = `noti_${row.id}`;

						//tiene_seguimiento = verifica_seguimiento_abierto(row.id);
						// Render inicial con un marcador de posición
						setTimeout(() => {
							verifica_seguimiento_abierto(row.id)
							.then((noti) => {
								// Actualizar el contenido del dropdown basado en la respuesta AJAX
								document.getElementById(notiId).innerHTML = noti;
							})
							.catch((error) => {
								console.error("Error verificando seguimiento:", error);
							});
						}, 0);
						return `
                        <div class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-modal btn-sm dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-gears"></i> Acciones	
                                </button>
								<div class="text-end" id="${notiId}"></div>
                                <ul class="dropdown-menu" id="element_acciones" aria-labelledby="dropdownMenuButton${row.id}">
                                    <li><a class="dropdown-item" onclick="consultar('${row.matricula}')"><i class="fa-solid fa-chalkboard-user"></i> Consultar</a></li>
                                    <li><a class="dropdown-item" onclick="seguimiento('${row.id}', '${row.periodo}')"><i class="fa-brands fa-rocketchat"></i> Seguimiento</a></li>
                                </ul>
                            </div>
                        </div>`;
					},
					searchable: true,
				},
			],
			order: [[0, "asc"]], // Orden inicial por la primera columna
			responsive: true,
			paging: true,
			pageLength: 10,
			language: {
				decimal: ",",
				emptyTable: "No hay datos disponibles en la tabla",
				info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
				infoEmpty: "Mostrando 0 a 0 de 0 registros",
				infoFiltered: "(filtrado de _MAX_ registros totales)",
				infoPostFix: "",
				thousands: ".",
				lengthMenu: "Mostrar _MENU_ registros por página",
				loadingRecords: "Cargando...",
				processing: "Procesando...",
				search: "Buscar:",
				zeroRecords: "No se encontraron registros",
				paginate: {
					first: "Primero",
					last: "Último",
					next: "Siguiente",
					previous: "Anterior",
				},
				aria: {
					sortAscending: ": activar para ordenar la columna ascendente",
					sortDescending: ": activar para ordenar la columna descendente",
				},
			},
		});
	}

	configurarTablaAlumnos();
	function capitalizeFirstLetters(str) {
		return str
		.split(" ")
		.map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
		.join(" ");
	}
	window.calcularTiempoRelativo = (timestamp) => {
		const ahora = new Date();
		const fecha = new Date(timestamp * 1000);
		const diferencia = ahora.getTime() - fecha.getTime();
		const segundos = Math.floor(diferencia / 1000);

		let tiempoRelativo = "";

		if (segundos < 60) {
			tiempoRelativo = `Hace ${segundos} segundos`;
		} else if (segundos < 3600) {
			const minutos = Math.floor(segundos / 60);
			tiempoRelativo = `Hace ${minutos} minutos`;
		} else if (segundos < 86400) {
			const horas = Math.floor(segundos / 3600);
			tiempoRelativo = `Hace ${horas} horas`;
		} else {
			const dias = Math.floor(segundos / 86400);
			tiempoRelativo = `Hace ${dias} días`;
		}

		return tiempoRelativo;
	};

	window.unixToDate = (unixTimestamp) => {
		const date = new Date(unixTimestamp * 1000); // Convertir el timestamp de segundos a milisegundos
		const year = date.getFullYear();
		const month = String(date.getMonth() + 1).padStart(2, "0"); // Los meses son 0-11, por lo que sumamos 1
		const day = String(date.getDate()).padStart(2, "0");
		return `${year}-${month}-${day}`;
	};
	window.bloqueados = function () {
		// Destruir la tabla DataTable actual si ya existe
		if ($.fn.DataTable.isDataTable("#tbl_alumnos")) {
			$("#tbl_alumnos").DataTable().destroy();
		}
		// Configurar la tabla con la URL específica
		const table = configurarTablaAlumnos("alumnos-bloqueados");

		// Mostrar la tabla y ocultar el loading después de cargar los datos
		table.on("draw", function () {
			$("#loading").hide();
			$("#contenedor_tabla_alumnos").show();
		});
	};

	window.buscar_baja = function (probabilidad) {
		$("#loading").show();
		$("#contenedor_tabla_alumnos").hide();
		let URL = "";

		if (probabilidad === "r3") {
			URL = "alumnos_probabilidad_baja/r3";
		} else if (probabilidad === "r2") {
			URL = "alumnos_probabilidad_baja/r2";
		} else if (probabilidad === "r1") {
			URL = "alumnos_probabilidad_baja/r1";
		} else {
			console.error("Nivel de probabilidad no válido");
			return;
		}

		// Destruir la tabla DataTable actual si ya existe
		if ($.fn.DataTable.isDataTable("#tbl_alumnos")) {
			$("#tbl_alumnos").DataTable().destroy();
		}

		// Configurar la tabla con la URL específica
		const table = configurarTablaAlumnos(URL);

		// Mostrar la tabla y ocultar el loading después de cargar los datos
		table.on("draw", function () {
			$("#loading").hide();
			$("#contenedor_tabla_alumnos").show();
		});
	};

	window.busqueda_avanzada = function () {
		// Obtener los valores de los campos
		var nombre = $("#nombre").val().trim();
		var apellidos = $("#apellidos").val().trim();
		var correo = $("#correo").val().trim();
		var matricula = $("#matricula").val().trim();
		var programa = $("#programas").val();
		var periodo = $("#periodos").val();
		var periodoMensual = $("#periodos_mensuales").val();
		var estatusPlataforma = $("#estatus-plataforma").val();
		var consejera = $("#consejera").val();
		var financiero = $("#financiero").val(); // Asegúrate de usar un id único para este campo

		if (
			nombre === "" &&
			apellidos === "" &&
			correo === "" &&
			matricula === "" &&
			programa === "0" &&
			periodo === "0" &&
			periodoMensual === "0" &&
			estatusPlataforma === "0" &&
			consejera === "0" &&
			financiero === "0"
		) {
			$("#alert-busqueda").show();
			$("#alert-busqueda").fadeOut(3000);
		} else {
			$("#loading").show();
			$("#contenedor_tabla_alumnos").hide();

			// Destruir la tabla DataTable actual si ya existe
			if ($.fn.DataTable.isDataTable("#tbl_alumnos")) {
				$("#tbl_alumnos").DataTable().destroy();
			}

			// Configurar la tabla con la URL específica
			let data = {
				nombre,
				apellidos,
				correo,
				matricula,
				programa,
				periodo,
				periodoMensual,
				estatusPlataforma,
				consejera,
				financiero,
			};
			const table = configurarTablaAlumnos("busuqeda_avanzada", data);

			// Mostrar la tabla y ocultar el loading después de cargar los datos
			table.on("draw", function () {
				$("#loading").hide();
				$("#contenedor_tabla_alumnos").show();
			});
		}
	};

	window.verifica_seguimiento_abierto = (idAlumno) => {
		return new Promise((resolve, reject) => {
			$.ajax({
				type: "POST",
				url: "exist_seuimiento_abierto_por_alumno/" + idAlumno,
				dataType: "json",
				success: function (response) {
					let noti = "";
					if (response.count > 0) {
						noti = `
                    <span class="position-absolute top-0 translate-middle p-2 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>`;
					}
					resolve(noti);
				},
				error: function (error) {
					reject(error);
				},
			});
		});
	};
});
