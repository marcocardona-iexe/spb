<!DOCTYPE html>
<html lang="en">

<body>

    <div id="content">
        <div class="container-fluid mt-5 ps-5 pe-5">
            <div class="accordion" id="accordionForm1">
                <div class="card">
                    <div class="card-header" id="advancedSearchHeader1">
                        <h5 class="mb-0">
                            <button class="btn btn-sm btn-link btn-modal text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm1" aria-expanded="true" aria-controls="collapseForm1">
                                <i class="fa-solid fa-plus"></i> Ingresar por matricula
                            </button>
                        </h5>
                    </div>
                    <div id="collapseForm1" class="collapse" aria-labelledby="advancedSearchHeader1" data-bs-parent="#accordionForm1">
                        <div class="card-body">
                            <div id="nuevo-usuario-form1" class="needs-validation" novalidate="">
                                <!-- Primera fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre1" class="form-label">Matricula</label>
                                        <input type="text" class="form-control form-control-sm" id="matricula" name="matricula" placeholder="Nombre" required="">
                                        <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                                    </div>
                                    <div class="col">
                                        <label for="nombre" class="form-label">Asesor financiero</label>
                                        <select class="form-select form-select-sm" id="financiero">
                                            <option value="0">Selecciona un asesor financiero</option>
                                            <?php foreach ($usuarios as $u) { ?>
                                                <option value="<?php echo $u->id; ?>"><?php echo $u->nombre; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                                    </div>
                                </div>
                                <div class="toast-container">
                                    <div id="response-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-header">
                                            <strong class="me-auto" id="toast-title">Mensaje del sistema</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                        <div class="toast-body" id="toast-body">
                                            Hello, world! This is a toast message.
                                        </div>
                                    </div>
                                </div>
                                <button id="asignar_financiero" class="btn btn-primary btn-modal btn-sm"><i class="fa-solid fa-plus"></i> Asignar</button>
                                <!--<button type="submit" class="btn btn-primary btn-modal btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>-->
                            </div>
                            <div id="form-alert1" class="alert d-none mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HTML -->
        <div class="container-fluid mt-5 ps-5 pe-5">
            <div class="accordion" id="accordionForm2">
                <div class="card">
                    <div class="card-header" id="advancedSearchHeader2">
                        <h5 class="mb-0">
                            <button class="btn btn-sm btn-link btn-modal text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm2" aria-expanded="true" aria-controls="collapseForm2">
                                <i class="fa-solid fa-plus"></i> Ingreso masivo
                            </button>
                        </h5>
                    </div>
                    <div id="collapseForm2" class="collapse" aria-labelledby="advancedSearchHeader2" data-bs-parent="#accordionForm2">
                        <div class="card-body">
                            <form id="nuevo-usuario-form2" class="needs-validation" novalidate="">
                                <!-- Primera fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fileInput" class="form-label">Archivo excel</label>
                                        <input type="file" class="form-control form-control-sm" id="excel_financiero" name="excel_financiero" accept=".xlsx, .xls" required="">
                                        <div class="invalid-feedback">Por favor, seleccione un archivo Excel válido.</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-modal btn-sm" id="subir_archivo"><i class="fa-solid fa-upload"></i> Subir archivo</button>
                            </form>
                            <div id="form-alert2-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid mt-5 ps-5 pe-5">
            <div class="accordion rounded-section " id="accordionForm">
                <table ble id="tbl_alumnos" class="order-column table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Periodo</th>
                            <th class="text-center">Programa</th>
                            <th class="text-center">Periodo Mensual</th>
                            <th class="text-center">Matrícula</th>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Probabilidad de Baja</th>
                            <th class="text-center">Estatus Plataforma</th>
                            <th class="text-center">Asesor financiero</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</body>

</html>