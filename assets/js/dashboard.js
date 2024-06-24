$(function () {
	$.ajax({
		url: "obtener_datos_alumnos_ajax_dashboard",
		type: "GET",
		dataType: "json",
		success: function (data) {
			let fila = "";
			$.each(data.tabla, function (i, t) {
				fila += `
                 <tr>
                    <td>${t.programa}</td>
                    <td>${t.matricula}</td>
                    <td>${t.val}</td>
                 </tr>`;
			});
			$("#programas").append(fila);

			$("#pieChart").dxPieChart({
				type: "doughnut",
				palette: "Soft Pastel",
				tooltip: {
					enabled: true,
					format: "millions",
					customizeTooltip(arg) {
						return {
							text: `${(arg.percent * 100).toFixed(2)}%`,
						};
					},
				},
				legend: {
					horizontalAlignment: "right",
					verticalAlignment: "top",
					margin: 0,
				},
				dataSource: data.grafica,
				series: [
					{
						argumentField: "programa",
						valueField: "val",
						label: {
							visible: true,
							connector: {
								visible: true,
								width: 1,
							},
							customizeText: function (arg) {
								return arg.valueText;
							},
						},
					},
				],
				export: {
					enabled: true,
				},
			});
		},
		error: function (xhr, status, error) {
			console.error("Error al obtener los datos:", error);
		},
	});
});
