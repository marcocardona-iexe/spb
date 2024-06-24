<!DOCTYPE html>
<html lang="en">

<body>
    <div class="content">


        <div class="container-fluid ps-3 pe-3 mt-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion" id="accordionForm2">
                        <div class="card">
                            <div class="card-header" id="">
                                <h6 class="mb-0">
                                    <i class="fa-solid fa-chart-pie"></i> Alumnos por programa
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="pieChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="accordion" id="accordionForm2">
                        <div class="card">
                            <div class="card-header" id="">
                                <h6 class="mb-0">
                                    <i class="fa-solid fa-list"></i> Alumnos por programa
                                </h6>
                            </div>
                            <div class="card-body">
                                <table id="tbl_alumnos" class="order-column table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center cabecera_table">PROGRAMA</th>
                                            <th class="text-center cabecera_table">MATRICULA</th>
                                            <th class="text-center cabecera_table">CANTIDAD</th>
                                        </tr>
                                    </thead>
                                    <tbody id="programas">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>