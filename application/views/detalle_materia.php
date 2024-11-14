<!DOCTYPE html>
<html lang="en">
<input id="correo" value="<?php echo $correo; ?>" hidden>
<input id="clave" value="<?php echo $clave; ?>" hidden>

<body>
    <div class="content">

        <div class="container mt-3 ps-2 pe-2">
            <div class="accordion" id="accordionForm">
                <div class="card">
                    <div class="card-header" id="">
                        <h5 class="mb-0" id="titulo-materia">
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="callout callout-info">
                                    <h5><i class="fas fa-info"></i> Información de la clase:</h5>
                                    <strong>Fecha de inicio:</strong> <span id="fecha_inicio_clase"> </span> <br />
                                    <strong>Fecha de fin: </strong> <span id="fecha_fin_clase"> </span>
                                </div>
                            </div>
                        </div>
                        

                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist"></ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>


</body>

</html>