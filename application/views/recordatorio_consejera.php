<!DOCTYPE html>
<html lang="en">

<body>
    <div class="content">


        <div class="container-fluid mt-3 ps-2 pe-2">
            <div class="accordion" id="accordionForm">
                <div class="card">
                    <div class="card-header" id="">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-headset"></i>Consejera: <label id="label_correo" data-correo='<?php echo $correo; ?>'><?php echo $correo; ?></label>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="botones"></div>
                        <div class="mt-3">
                            <table id="tablaCursos" class="order-column table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Clave</th>
                                        <th>Nombre de materia</th>
                                        <th>F. Inicio</th>
                                        <th>F. Fin</th>
                                        <th>Periodo</th>
                                        <!-- <th>Único</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>