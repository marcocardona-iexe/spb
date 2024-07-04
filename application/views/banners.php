<div class="container">
    <main class="pt-5">

        <div class="card">
            <div class="card-header">
                Creacion de banners
            </div>
            <div class="card-body">

                <div class="row g-5">



                    <div class="col-md-5 col-lg-4 order-md-last">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary">Lista de banners</span>
                            <span class="badge bg-primary rounded-pill">No.</span>
                        </h4>
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">Banner</h6>
                                    <small class="text-muted">Inicio - Fin</small>
                                </div>
                                <span class="text-muted"><i class="fa-solid fa-trash"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">Second product</h6>
                                    <small class="text-muted">Brief description</small>
                                </div>
                                <span class="text-muted">$8</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">Third item</h6>
                                    <small class="text-muted">Brief description</small>
                                </div>
                                <span class="text-muted">$5</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <div class="text-success">
                                    <h6 class="my-0">Promo code</h6>
                                    <small>EXAMPLECODE</small>
                                </div>
                                <span class="text-success">−$5</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-7 col-lg-8">

                        <form id="formulario" class="needs-validation" novalidate enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="finicio" class="form-label">Fecha de inicio</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="finicio" name="finicio" required>
                                        <div class="invalid-feedback">
                                            Debe ingresar la fecha
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="ffinal" class="form-label">Fecha de final</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="ffinal" name="ffinal" required>
                                        <div class="invalid-feedback">
                                            Debe ingresar la fecha
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="banner" class="form-label">Banner</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                        <input type="file" class="form-control" id="banner" name="banner" required>
                                        <div class="invalid-feedback">
                                            Debe ingresar el banner
                                        </div>
                                    </div>
                                    <img id="preview" src="#" alt="Vista previa" style="display:none; margin-top: 10px; max-width: 100%; height: auto;">

                                </div>

                                <div class="col-6">
                                    <label for="banner" class="form-label">Archivo</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                        <input type="file" class="form-control" id="archivo_banner" name="archivo_banner" required>
                                        <div class="invalid-feedback">
                                            Debe seleccionar el archivo del banner
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="matriculas" class="form-label">Ingrese las matriculas separadas por comas (,) <small>Ejemplo MAPP24A001, MAPP24A002, ...</small></label>
                                    <textarea class="form-control" id="matriculas" rows="3" name="matriculas" required></textarea>
                                    <div class="invalid-feedback">
                                        Debe ingresar al menos una matricula
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <button class="w-100 btn btn-primary btn-lg" type="submit">Agregar banner</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </main>

</div>