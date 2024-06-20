$(function () {
    $.ajax({
        url: "obtener_datos_alumnos_ajax_dashboard",
        type: "GET",
        dataType: "json",
        success: function (data) {
            $("#gridContainer").dxDataGrid({
                dataSource: data,
                columns: [
                    {
                        dataField: "nombre_programa",
                        caption: "Programa Educativo",
                    },
                    {
                        dataField: "cantidad_alumnos",
                        caption: "Cantidad de Alumnos",
                    },
                ],
                showBorders: true,
            });

            $("#pieChart").dxPieChart({
                dataSource: data,

                size: {
                    width: 500,
                },
                palette: "bright",
                series: [
                    {
                        argumentField: "nombre_programa",
                        valueField: "cantidad_alumnos",
                        label: {
                            visible: true,
                            connector: {
                                visible: true,
                                width: 1,
                            },
                        },
                    },
                ],
                legend: {
                    horizontalAlignment: "center",
                    verticalAlignment: "bottom",
                },
                title: "Area of Countries",
                export: {
                    enabled: true,
                },
                onPointClick(e) {
                    const point = e.target;

                    toggleVisibility(point);
                },
                onLegendClick(e) {
                    const arg = e.target;

                    toggleVisibility(this.getAllSeries()[0].getPointsByArg(arg)[0]);
                },
            });

            $("#pieChart").dxPieChart({
                size: {
                    width: 500,
                },
                palette: "bright",
                dataSource: data,
                series: [
                    {
                        argumentField: "nombre_programa",
                        valueField: "cantidad_alumnos",
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
                title: "Alumnos pro programa",
                export: {
                    enabled: true,
                },
                onPointClick(e) {
                    const point = e.target;
                    toggleVisibility(point);
                },
                onLegendClick(e) {
                    const arg = e.target;
                    toggleVisibility(this.getAllSeries()[0].getPointsByArg(arg)[0]);
                },
            });


            //     dataSource: data,
            //     series: [
            //         {
            //             argumentField: "nombre_programa",
            //             valueField: "cantidad_alumnos",
            //             label: {
            //                 visible: true,
            //                 format: "fixedPoint",
            //                 customizeText: function (arg) {
            //                     return `${arg.valueText}`;
            //                 },
            //             },
            //         },
            //     ],
            //     title: "Distribución de Alumnos por Programa",
            //     legend: {
            //         horizontalAlignment: "center",
            //         verticalAlignment: "bottom",
            //     },
            // });
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener los datos:", error);
        },
    });
});
