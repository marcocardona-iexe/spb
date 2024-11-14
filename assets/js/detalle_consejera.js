// Función para obtener parámetros de la URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}
const base_url = "https://archivo.iexe.edu.mx";
// Leer la variable correo_consejera
//const correoConsejera = getQueryParam("correo_consejera");
const correoConsejera = $("#label_correo").attr("data-correo");
//document.getElementById("correoConsejera").innerText = `Consejera: ${correoConsejera}`;

// Función para construir la tabla
function construirTabla(cursos) {
    const tbody = document.getElementById("tablaCursos").querySelector("tbody");
    tbody.innerHTML = ""; // Limpiar la tabla antes de llenarla

    cursos.forEach((curso) => {
        const row = document.createElement("tr");

        // Crear un link para la clave_materia
        const link = document.createElement("a");
        link.href = `../detalle_materia/${correoConsejera}/${curso.shortname}`;
        link.textContent = curso.clave_materia + " | " + curso.shortname;

        // Insertar cada celda en la fila
        row.innerHTML = `
        <td></td>
        <td>${curso.fullname}</td>
        <td>${curso.fecha_inicio}</td>
        <td>${curso.fecha_fin}</td>
        <td>${curso.periodo}</td>
      `;
        row.children[0].appendChild(link); // Añadir el link a la primera celda

        tbody.appendChild(row);
    });
}

// Hacer la petición a la API
fetch(`${base_url}/consejeras/consulta?correo_consejera=${correoConsejera}`)
.then((response) => {
    if (!response.ok) throw new Error("Error en la solicitud");
    return response.json();
})
.then((data) => {
    if (data.status === "ok") {
        construirTabla(data.cursos);

        // if (data.ruta_zip_asesora) {
        //     const downloadButton = document.createElement("button");
        //     downloadButton.className = "btn btn-primary mt-3"; // Estilos de Bootstrap
        //     downloadButton.textContent = "Descargar ZIP";
        //     downloadButton.onclick = () => {
        //         window.location.href = `${base_url}/consejeras${data.ruta_zip_asesora}`;
        //     };
        //     document.getElementById("correoConsejera").insertAdjacentElement("afterend", downloadButton);
        // }

        if (data.ruta_zip_asesora) {
            // Crear un botón de descarga dinámicamente con los atributos y clases requeridas
            const downloadButton = $("<button>")
            .attr({
                id: "btn_zip_global",
                class: "btn btn-primary btn-modal btn-sm",
            })
            .html('Descarga Zip Global <i class="fa-solid fa-file-zipper"></i>') // Agrega el ícono y el texto
            .click(function () {
                window.location.href = `${base_url}/consejeras${data.ruta_zip_asesora}`;
            });

            // Insertar el botón después del elemento con ID "correoConsejera"
            $("#botones").after(downloadButton);
        }
        if (data.ruta_zip_asesora_diario) {
            // Crear un botón de descarga dinámicamente con los atributos y clases requeridas
            const downloadButton = $("<button>")
            .attr({
                id: "btn_zip_global",
                class: "btn btn-primary btn-modal btn-sm",
            })
            .html('Descarga Zip Diario <i class="fa-solid fa-file-zipper"></i>') // Agrega el ícono y el texto
            .click(function () {
                window.location.href = `${base_url}/consejeras${data.ruta_zip_asesora_diario}`;
            });

            // Insertar el botón después del elemento con ID "correoConsejera"
            $("#botones").after(downloadButton);
        }

    } else {
        document.getElementById("correoConsejera").innerText = "No se encontraron materias para la consejera.";
    }
})
.catch((error) => {
    console.error("Error:", error);
    document.getElementById("correoConsejera").innerText = "Error al obtener los datos.";
});
