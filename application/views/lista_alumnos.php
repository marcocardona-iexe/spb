<!DOCTYPE html>
<html lang="en">
<?php
$esAdministrador = false;
foreach ($sesion['roles'] as $rol) {
    if ($rol->rol === 'Administrador') {
        $esAdministrador = true;
        break; // Salir del bucle una vez encontrado el rol de Administrador
    }
} ?>


<body>
    <div class="content">
        <div class="container-fluid ps-3 pe-3">
            <div class="row mt-3">
                <div class="col">
                    <div class="button-list border-bottom pb-3">
                        <button type="button" class="btn btn-sm btn-danger" onclick="bloqueados()">
                            <span class="badge bg-primary notification-badge"><?php echo $bloqueados; ?></span> Bloqueados
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="buscar_baja('r3')">
                            <span class="badge bg-primary notification-badge"><?php echo $total_r3; ?></span> Baja R3
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="buscar_baja('r2')">
                            <span class="badge bg-primary notification-badge"><?php echo $total_r2; ?></span> Baja R2
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="buscar_baja('r1')">
                            <span class="badge bg-primary notification-badge"><?php echo $total_r1; ?></span> Baja R1
                        </button>
                        <button type="button" class="btn btn-sm btn-info" onclick="buscar_seguimiento('Cerrado')">
                            <span class="badge bg-primary notification-badge"><?php echo $total_cerrados; ?></span> Sin Seguimiento
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="buscar_seguimiento('Abierto')">
                            <span class="badge bg-primary notification-badge"><?php echo $total_abiertos; ?></span> Seguimiento Abierto
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-3 ps-2 pe-2">
            <div class="accordion" id="accordionForm">
                <div class="card">
                    <div class="card-header" id="advancedSearchHeader">
                        <h5 class="mb-0">
                            <button class="btn btn-modal btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="true" aria-controls="collapseForm">
                                <i class="fa-solid fa-magnifying-glass"></i> Búsqueda Avanzada
                            </button>
                        </h5>
                    </div>
                    <div id="collapseForm" class="collapse " aria-labelledby="advancedSearchHeader" data-bs-parent="#accordionForm">
                        <div class="card-body">
                            <div id="formulario_busqueda_avanzada">
                                <!-- Primera fila -->
                                <div class="row">
                                    <div class="col">
                                        <div class="alert alert-danger" role="alert" id="alert-busqueda" style="display: none !important;">
                                            <div>
                                                <i class="fa-solid fa-circle-exclamation"></i> Debe seleccionar al menos un elemento de busqueda
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                                    </div>
                                    <div class="col">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" placeholder="Apellidos">
                                    </div>
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" placeholder="name@example.com">
                                    </div>
                                    <div class="col">
                                        <label for="matricula" class="form-label">Matrícula</label>
                                        <input type="text" class="form-control" id="matricula" placeholder="Matrícula">
                                    </div>
                                </div>
                                <!-- Segunda fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="programa" class="form-label">Programa</label>
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
                                                <option value="MCD">MCDA (Maestría en Ciencia de Datos Aplicada)</option>
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
                                    </div>
                                    <div class="col">
                                        <label for="periodo" class="form-label">Periodo</label>
                                        <input type="text" class="form-control" id="periodos" placeholder="Periodo">

                                    </div>
                                    <div class="col">
                                        <label for="periodo-mensual" class="form-label">Trimestre / Cuatrimestre</label>
                                        <input type="text" class="form-control" id="periodos_mensuales" placeholder="Trimestre/Cuatrimestre">
                                    </div>
                                    <div class="col">
                                        <label for="estatus-plataforma" class="form-label">Estatus de Plataforma</label>
                                        <select class="form-select" id="estatus-plataforma">
                                            <option value="0">Selecciona un estatus</option>
                                            <option>Activo</option>
                                            <option>Bloqueado</option>
                                            <option>Desbloqueado</option>
                                            <option>Baja temporal</option>
                                            <option>Baja definitiva</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Tercera fila -->
                                <?php if ($esAdministrador) { ?>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="asesor" class="form-label">Consejera</label>
                                            <select class="form-select" id="consejera">
                                                <option value="0">Selecciona una consejera</option>
                                                <?php foreach ($consejeras as $c) { ?>
                                                    <option value=<?php echo $c->id; ?>><?php echo $c->nombre; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="asesor" class="form-label">Asesor financiero</label>
                                            <select class="form-select" id="financiero">
                                                <option value="">Selecciona un asesor financiero</option>
                                                <?php foreach ($financieros as $f) { ?>
                                                    <option><?php echo $f->nombre; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <button id="busqueda_avanzada" class="btn btn-sm btn-modal" onclick="busqueda_avanzada();"><i class="fa-solid fa-glasses"></i> Realizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3 ps-2 pe-2">
            <div class="accordion" id="accordionForm">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-list"></i> Lista de alumnos activos
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-end align-items-center">
                            <div class="col-auto">
                                <input type="date" class="form-control form-control-sm text-end" aria-label="First name" id="fecha_inicial">
                            </div>
                            <div class="col-auto">
                                <input type="date" class="form-control form-control-sm text-end" aria-label="Last name" id="fecha_final">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-modal" id="descargar_seguimientos"><i class="fa-regular fa-file-excel"></i> Descargar seguimientos</button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-modal" onclick="window.location.href='<?php echo base_url('descarga_excel'); ?>'"><i class="fa-regular fa-file-excel"></i> Descargar todos</button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-modal" id="ver_todos"><i class="fa-solid fa-eye"></i> Ver todos</button>
                            </div>
                        </div>



                        <!-- <div id="loading" class="text-center" style="display: none;">
                        <div class="loading-text">Cargando información...</div>
                        <div class="spinner-grow text-primary" role="status">
                        </div>
                        <div class="spinner-grow text-primary" role="status">
                        </div>
                        <div class="spinner-grow text-primary" role="status">
                        </div>
                        <div class="spinner-grow text-primary" role="status">
                        </div>
                    </div> -->
                        <div id="contenedor_tabla_alumnos" class="mt-4">
                            <table id="tbl_alumnos" class="order-column table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center cabecera_table">Nombre</th>
                                        <th class="text-center cabecera_table">Periodo</th>
                                        <th class="text-center cabecera_table">Programa</th>
                                        <th class="text-center cabecera_table">Periodo Mensual</th>
                                        <th class="text-center cabecera_table">Matrícula</th>
                                        <th class="text-center cabecera_table">Correo</th>
                                        <th class="text-center cabecera_table">Teléfono</th>
                                        <th class="text-center cabecera_table">Ultima conexión</th>
                                        <th class="text-center cabecera_table">Probabilidad de Baja</th>
                                        <th class="text-center cabecera_table">Estatus Plataforma</th>
                                        <th class="text-center cabecera_table">Consejera</th>
                                        <th class="text-center cabecera_table">Asesor financiero</th>
                                        <th class="text-center cabecera_table">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>