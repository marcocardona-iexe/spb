<!DOCTYPE html>
<html lang="en">

<body>


    <div class="container-fluid ps-3 pe-3 mt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="accordion" id="accordionForm2">
                    <div class="card">
                        <div class="card-header" id="">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-upload"></i> Carga masiva de asesores financieros
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="formularios_gral needs-validation" novalidate="">
                                <!-- Primera fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="fileInput" class="form-label">Archivo excel</label>
                                        <input type="file" class="form-control form-control-sm" id="excel_consejera" name="excel_financiero" accept=".xlsx, .xls" required="">
                                        <div class="invalid-feedback">Por favor, seleccione un archivo Excel válido.</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-modal btn-sm" id="subir_archivo"><i class="fa-solid fa-upload"></i> Subir archivo</button>
                            </div>
                            <div id="form-alert2-container"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="accordion" id="accordionForm1">
                    <div class="card">
                        <div class="card-header" id="">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-address-card"></i> Asignacion masiva
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="needs-validation formularios_gral" novalidate="">
                                <!-- Primera fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre1" class="form-label">Programa</label>
                                        <select class="form-select" id="programas">
                                            <option value="0">Seleccionar Programa</option>
                                            <optgroup label="LICENCIATURAS" data-meses="cuatrimestre">
                                                <option value="LSP">LSP (Licenciatura en seguridad pública)</option>
                                                <option value="LD">LD (Licenciatura en derecho)</option>
                                                <option value="LAE">LAE (Licenciatura en administración de empresas)</option>
                                                <option value="LCPAP">LCPAP (Licenciatura en Ciencias Políticas y Administración Pública)</option>
                                                <option value="LCPAP">LCE (Licenciatura en Ciencias de la Educación)</option>
                                            </optgroup>
                                            <optgroup label="MAESTRIAS" data-meses="trimestre">
                                                <option value="MAPP">MAPP (Maestría en administración y Políticas Públicas)</option>
                                                <option value="MEPP">MEPP (Maestría en Evaluación y Políticas Públicas)</option>
                                                <option value="MSPP">MSPP (Maestría en Seguridad y Políticas Públicas)</option>
                                                <option value="MIGE">MIGE (Maestría en Innovación y Gestión Educativa)</option>
                                                <option value="MAN">MBA (Maestría en administración de negocios)</option>
                                                <option value="MFP">MFP (Maestría en Finanzas Públicas)</option>
                                                <option value="MCDA">MCDA (Maestría en Ciencia de Datos Aplicada)</option>
                                                <option value="MCDIA">MCDIA (Maestría en Ciencia de Datos Aplicada)</option>
                                                <option value="MITI">MITI (Maestría en Administración de Tecnologías de la información)</option>
                                                <option value="MAIS">MAIS (Maestría en Instituciones de Salud)</option>
                                                <option value="MAG">MAG (Maestría en Auditoría Gubernamental)</option>
                                                <option value="MGPM">MGPM (Maestría en Gestión Pública Municipal)</option>
                                                <option value="MSPAJO">MSPAJO (Maestría en Sistema Penal Acusatorio y Juicio Oral)</option>
                                                <option value="MMPOP">MMPOP (Maestría en Marketing Político y Opinión Pública)</option>
                                            </optgroup>
                                            <optgroup label="DOCTORADOS" data-meses="cuatrimestre">
                                                <option value="DPP">DPP (Doctorado en Políticas Públicas)</option>
                                                <option value="DSP">DSP (Doctorado en Seguridad Pública)</option>
                                            </optgroup>
                                        </select>
                                        <div class="invalid-feedback">Selecciona un programa</div>
                                    </div>
                                    <div class="col">
                                        <label for="nombre" class="form-label">Periodo</label>
                                        <select class="form-select form-select-sm" id="periodos">
                                            <option value="0">Selecciona un programa primero</option>
                                        </select>
                                        <div class="invalid-feedback">Seleccione un periodo</div>
                                    </div>
                                    <div class="col">
                                        <label for="nombre" class="form-label">Consejera</label>
                                        <select class="form-select form-select-sm" id="consejeras">
                                            <option value="0">Selecciona un programa primero</option>
                                            <?php foreach ($usuarios as $u) { ?>
                                                <option value="<?php echo $u->id; ?>"><?php echo $u->nombre; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="invalid-feedback">Seleccione consejeras</div>
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
                                <button id="asignar_consejera" class="btn btn-primary btn-modal btn-sm"><i class="fa-solid fa-plus"></i> Asignar</button>
                                <!--<button type="submit" class="btn btn-primary btn-modal btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>-->
                            </div>
                            <div id="form-alert1" class="alert d-none mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div class="container-fluid mt-3 ps-2 pe-2">
        <div class="accordion" id="accordionForm">
            <div class="card">
                <div class="card-header" id="">
                    <h6 class="mb-0">
                        <i class="fa-solid fa-list"></i> Lista de asignaciones
                    </h6>
                </div>
                <div class="card-body">
                    <table ble id="tbl_asignaciones" class="order-column table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Programa</th>
                                <th class="text-center">Periodo</th>
                                <th class="text-center">Asesor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($programasperiodos as $p) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $p->nombre_programa; ?></td>
                                    <td><?php echo $p->periodo; ?></td>
                                    <td><?php echo $p->consejera; ?></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</body>

</html>