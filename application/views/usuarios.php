<!DOCTYPE html>
<html lang="en">

<body>
    <!-- Navbar -->
    <div class="content">
        <div class="container-fluid mt-3 ps-2 pe-2">
            <div class="accordion" id="accordionForm">
                <div class="card">
                    <div class="card-header" id="advancedSearchHeader">
                        <h5 class="mb-0">
                            <button class="btn btn-sm btn-link btn-sm btn-modal text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="true" aria-controls="collapseForm">
                                <i class="fa-solid fa-plus"></i> Registro
                            </button>
                        </h5>

                    </div>
                    <div id="collapseForm" class="collapse " aria-labelledby="advancedSearchHeader" data-bs-parent="#accordionForm">
                        <div class="card-body">
                            <form id="nuevo-usuario-form" class="needs-validation formularios_gral " novalidate>
                                <!-- Primera fila -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                                        <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                                    </div>
                                    <div class="col">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                                        <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                                    </div>
                                    <div class="col">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electronico institucional">
                                        <div class="invalid-feedback">Por favor, ingrese un correo válido.</div>
                                    </div>
                                    <div class="col">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" autocomplete="new-password">
                                        <div class="invalid-feedback">Por favor, ingrese la contraseña.</div>
                                    </div>
                                </div>
                                <!-- Segunda fila (roles) -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="admin" name="rol[]" value="1">
                                            <label class="form-check-label" for="admin">Administrador</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="asesor_academico" name="rol[]" value="2">
                                            <label class="form-check-label" for="asesor_academico">Consejera</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="asesor_financiero" name="rol[]" value="3">
                                            <label class="form-check-label" for="asesor_financiero">Asesor Financiero</label>
                                        </div>
                                        <div id="rol-feedback" class="invalid-feedback d-none">Por favor, seleccione al menos un rol.</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-modal"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                            </form>
                            <div id="form-alert" class="alert d-none mt-3"></div>
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
                            <i class="fa-solid fa-list"></i> Lista de usuarios
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="contenedor_tabla_alumnos" class="mt-4">
                            <table class="table table-striped" id="tbl_usuarios">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">No.</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Rol(es)</th>
                                        <th scope="col"></th> <!-- Columna vacía -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($usuarios as &$usuarios) { ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $usuarios->nombre . " " . $usuarios->apellidos; ?></td>
                                            <td><?php echo $usuarios->correo; ?></td>
                                            <td><?php echo $usuarios->estatus; ?></td>
                                            <td><?php echo $usuarios->badge; ?></td>
                                            <td><?php echo $usuarios->acciones; ?></td>
                                        </tr>
                                    <?php $i++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
    </div>

</body>

</html>