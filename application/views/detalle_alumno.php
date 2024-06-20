<!DOCTYPE html>
<html lang="en">

<body>



    <?php foreach ($historial_academico as $key => $materias) { ?>

        <div class="container-fluid mt-5 ps-5 pe-5">
            <div class="card">
                <div class="card-header" id="headingOne" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $key; ?>" aria-expanded="true" aria-controls="collapseOne" style="cursor: pointer;">
                    <i class="fa-solid fa-school"></i> <?php echo $key; ?>
                </div>
                <div id="collapseOne<?php echo $key; ?>" class="collapse show" aria-labelledby="headingOne">
                    <div class="card-body">
                        <?php foreach ($materias as $m) { ?>

                            <div class="d-flex text-muted pt-3">
                                <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <rect width="100%" height="100%" fill="#007bff"></rect><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text>
                                </svg>

                                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                                    <div class="d-flex justify-content-between">
                                        <strong class="text-gray-dark">ID: <?php echo $m->id; ?> (<?php echo $m->inicio_materia; ?>)</strong>
                                    </div>
                                    <span class="d-block"><i class="fa-solid fa-book"></i> <?php echo $m->fullname; ?> / <?php echo $m->finalgrade; ?></span>
                                </div>

                            </div>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Porcentaje</th>
                                        <th>Calificación</th>
                                        <th>Fecha Finaliza</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($m->actividades as $a) { ?>
                                            <tr>
                                                <td><?php echo $a->grade_item_id; ?></td>
                                                <td><?php echo $a->itemname; ?></td>
                                                <td><?php echo $a->itemmodule; ?></td>
                                                <td><?php echo $a->tipo; ?></td>
                                                <td><?php echo isset($a->message) ? $a->message : ""; ?></td>
                                                <td><?php echo $a->porcentaje; ?></td>
                                                <td><?php echo $a->calificacion_obtenida; ?></td>
                                                <td><?php echo $a->finalizacion; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    <?php } ?>



    <div class="container-fluid mt-3 ps-5 pe-5">
        <div class="accordion" id="accordionForm">
            <div class="card">
                <div class="card-header" id="advancedSearchHeader">
                    <h6 class="mb-0">
                        <i class="fa-solid fa-wallet"></i> Información Financiera
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if ($numero != 24) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Importe</th>
                                        <th scope="col">Forma de Pago</th>
                                        <th scope="col">Banco</th>
                                        <th scope="col">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $p) { ?>
                                        <!-- Aquí van las filas de datos -->
                                        <tr>
                                            <td><?php echo $p->Descripcion; ?></td>
                                            <td>$ <?php echo number_format($p->Importe, 2); ?></td>
                                            <td><?php echo $p->FormaPago; ?></td>
                                            <td><?php echo $p->banco; ?></td>
                                            <td><?php echo $p->f_pago; ?></td>
                                        </tr>
                                        <!-- Puedes agregar más filas aquí -->
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Concepto</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Moneda</th>
                                        <th scope="col">$ Monto</th>
                                        <th scope="col">$ Por pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos->datos as $d) { ?>
                                        <!-- Aquí van las filas de datos -->
                                        <tr>
                                            <td><?php echo $d->concepto . " " . $d->consecutivo_cobro; ?></td>
                                            <td><?php echo $d->fecha; ?></td>
                                            <td><?php echo $d->moneda; ?></td>
                                            <td><?php echo $d->monto; ?></td>
                                            <td><?php echo $d->adeudo; ?></td>
                                        </tr>
                                        <!-- Puedes agregar más filas aquí -->
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>


</body>

</html>