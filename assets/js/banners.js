$("#banner").on("change", function () {
	const file = this.files[0];
	const fileType = file.type;
	const validImageTypes = ["image/jpeg"];

	if (!validImageTypes.includes(fileType)) {
		this.value = ""; // Clear the input
		alert("Solo se permiten archivos JPG.");
		return;
	}

	if (file) {
		const reader = new FileReader();
		reader.onload = function (e) {
			$("#preview").attr("src", e.target.result).show();
		};
		reader.readAsDataURL(file);
	}
});

$("#formulario").on("submit", function (event) {
	event.preventDefault(); // Prevenir el envío del formulario por defecto

	// Validar formulario
	var form = this;
	if (form.checkValidity() === false) {
		event.stopPropagation();
		form.classList.add("was-validated");
		return;
	}
	// Validar que la fecha de inicio sea menor o igual que la fecha de final
	var finicio = new Date($("#finicio").val());
	var ffinal = new Date($("#ffinal").val());
	if (finicio > ffinal) {
		$("#ffinal").addClass("is-invalid");
		$("#ffinal")
		.next(".invalid-feedback")
		.text("La fecha de final debe ser mayor o igual que la fecha de inicio")
		.show();
		return;
	} else {
		$("#ffinal").removeClass("is-invalid");
	}

	// Si la validación del lado del cliente es exitosa, proceder con la llamada AJAX
	var formData = new FormData(form);

	console.log(formData);

	$.ajax({
		url: "insert_banner", // Reemplaza con la URL a la que deseas enviar los datos
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		success: function (response) {
			// Manejar la respuesta del servidor aquí
			console.log(response);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores aquí
			alert("Ocurrió un error al enviar los datos");
		},
	});
});
