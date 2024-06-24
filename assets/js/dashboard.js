$(function () {
	$.ajax({
		url: "obtener_datos_alumnos_ajax_dashboard",
		type: "GET",
		dataType: "json",
		success: function (data) {
			let fila = "";
			let total = 0;
			$.each(data.tabla, function (i, t) {
				fila += `
                 <tr>
                    <td>${t.programa}</td>
                    <td>${t.matricula}</td>
                    <td>${t.val}</td>
                 </tr>`;
				total += t.val;
			});

			$("#programas").append(fila);
			$("#programas").append(
				`<tr><td colspan=2>Total: </td><td>${total}</td></tr>`
			);

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
