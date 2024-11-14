document.addEventListener("DOMContentLoaded", () => {
    const base_url = "https://archivo.iexe.edu.mx/";

    const correoConsejera = $("#correo").val();
    const short_name = $("#clave").val();
    const url = `${base_url}consejeras/ver_curso?correo=${correoConsejera}&short_name=${short_name}`;

    fetch(url)
    .then((response) => response.json())
    .then((data) => {
        const {info_materia, actividades} = data;

        // Establece el título de la materia
        const tituloMateria = `${info_materia.nombre_materia} (${info_materia.clave_materia})`;
        document.getElementById("titulo-materia").textContent = tituloMateria;

        const fechaInicio = `${info_materia.fecha_inicio}`;
        document.getElementById("fecha_inicio_clase").textContent = fechaInicio;

        const fechaFin = `${info_materia.fecha_fin}`;
        document.getElementById("fecha_fin_clase").textContent = fechaFin;
        // Crear el tab de Información
        const infoTab = $(`
        <li class="nav-item">
            <a class="nav-link active" id="tab-info" data-bs-toggle="pill" href="#content-info" role="tab" aria-controls="content-info" aria-selected="true">
                Información
            </a>
        </li>
    `);
        $("#custom-tabs-four-tab").append(infoTab);

        // Crear el contenido para el tab de Información
        const infoContent = document.createElement("div");
        infoContent.className = "tab-pane fade show active";
        infoContent.id = "content-info";
        infoContent.role = "tabpanel";
        infoContent.ariaLabelledby = "tab-info";

        // Aquí puedes añadir la información de la materia como desees
        infoContent.innerHTML = `
                <div class="info-materia">
                    <div class="informacion-detal d-flex justify-content-between">
                    
                    <div class="card">
                        <div class=" d-flex align-items-center">
                            <div class="ms-3">
                            <h6 class="mb-0 fs-sm">Fecha Inicio</h6>
                            <span class="text-muted fs-sm">${info_materia.fecha_inicio}</span>
                            </div>
                        </div>
                        <img src="https://archivo.iexe.edu.mx/consejeras/${
                            info_materia.ruta_imagen
                        }" class="card-img-top" alt="imagen de inicio" />
                        <div class="card-body">
                            <p class="card-text">
                            
                            </p>
                        </div>
                        </div>
                        <div class="card">
                        <div class=" d-flex align-items-center">
                            <div class="ms-3">
                            <h6 class="mb-0 fs-sm">Fecha Fin</h6>
                            <span class="text-muted fs-sm">${info_materia.fecha_fin}</span>
                            </div>
                        </div>
                        <img src="https://archivo.iexe.edu.mx/consejeras/${
                            info_materia.ruta_imagen_cierre
                        }" class="card-img-top" alt="imagen de cierre" />
                        <div class="card-body">
                            <p class="card-text">

                            </p>
                        </div>
                    </div>
                    
                    </div>
                    <p><strong>Clave de la materia:</strong> ${info_materia.clave_materia}</p>
                    
                    <h3>Alumnos en la materia con tu asignación:</h3>
                    ${createTable(info_materia.matriculas)}

                </div>`;

        document.getElementById("custom-tabs-four-tabContent").appendChild(infoContent);

        // Genera los tabs y el contenido de cada actividad
        $(document).ready(function () {
            actividades.forEach(function (actividad, index) {
                // Crear el tab
                const tabItem = $(`
            <li class="nav-item">
                <a class="nav-link ${
                    index === 0 ? "" : ""
                }" id="tab-${index}" data-bs-toggle="pill" href="#content-${index}" role="tab" aria-controls="content-${index}" aria-selected="${
                    index === 0 ? "true" : "false"
                }">
                    ${actividad.nombre_actividad}
                </a>
            </li>
        `);
                $("#custom-tabs-four-tab").append(tabItem);

                // Crear el contenido de cada tab
                const tabContent = $(`
            <div class="tab-pane fade ${
                index === 0 ? "show " : ""
            }" id="content-${index}" role="tabpanel" aria-labelledby="tab-${index}">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Sin Entrega</h5>
                        ${createTable(actividad.matriculas_sin_entrega)}
                        <h5>Con Entrega</h5>
                        ${createTable(actividad.matriculas_con_entrega)}
                    </div>
                    <div class="col-md-6">
                        <img src="${base_url}/consejeras/${actividad.ruta_imagen}" class="img-fluid" alt="${
                    actividad.nombre_actividad
                }">
                    </div>
                </div>
            </div>
        `);
                $("#custom-tabs-four-tabContent").append(tabContent);
            });
        });
    })
    .catch((error) => console.error("Error al obtener los datos:", error));
});

// Función para crear tablas dinámicas de alumnos
function createTable(matriculas) {
    const rows = Object.values(matriculas)
    .map(
        (alumno) => `
        <tr>
            <td>${alumno.nombre} ${alumno.apellido_p} ${alumno.apellido_m}</td>
            <td>${alumno.pais}</td>
            <td>${alumno.telefono}</td>
        </tr>
    `
    )
    .join("");
    return `
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>País</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                ${rows}
            </tbody>
        </table>`;
}

// Función para crear tablas dinámicas de alumnos
function createTable(matriculas) {
    const rows = Object.values(matriculas)
    .map(
        (alumno) => `
        <tr>
            <td>${alumno.nombre} ${alumno.apellido_p} ${alumno.apellido_m}</td>
            <td>${alumno.pais}</td>
            <td>${alumno.telefono}</td>
        </tr>
    `
    )
    .join("");
    return `
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>País</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                ${rows}
            </tbody>
        </table>`;
}
