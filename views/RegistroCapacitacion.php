<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de inscripción de capacitación presencial en el Gobierno Regional de Loreto para los trabajadores de la institución">
    <meta name="keywords" content="Registro, capacitación, gorel, gobierno regional de loreto, incripcion">
    <meta name="autor" content="Journii">
    <link rel='icon' type='image/x-icon' href='<?= _ROOT_ASSETS . 'img/favicon.png' ?>'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= _RTADMINLTE3 . 'plugins/fontawesome-free/css/all.min.css' ?>">
    <link rel="stylesheet" href="<?= _RTADMINLTE3 . 'plugins/select2/css/select2.min.css' ?>">
    <link rel="stylesheet" href="<?= _RTADMINLTE3 . 'plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ?>">
    <link rel="stylesheet" href="<?= _RTADMINLTE3 . 'plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css' ?>">
    <link rel="stylesheet" href="<?= _RTADMINLTE3 . 'dist/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href='<?= _ROOT_ASSETS . 'css/style.css' ?>'>
    <title>SIScap</title>
</head>
<body>
    <div class="context">
        <section class="content">
            <div class="container-fluid d-flex justify-content-center mt-3">
                <div class="card card-default mt-4">
                    <div class="card-header bg-danger">
                        <h3 class="card-title">Inscripción para capacitación</h3>
                    </div>
                    <div class="card-body">
                        <div class="row rows-col-1">
                            <div class="col">
                                <div class="form-group">
                                    <label>Ingrese su DNI</label>
                                    <input type="text" class="form-control" id="dni" aria-describedby="dniHelp">
                                    <div class="mt-2 mb-2">
                                        <button type="button" id="searchDNI" class="btn btn-secondary"><i class="fa fa-search"></i> Buscar DNI</button>
                                    </div>
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" id="nombre" aria-describedby="nombreHelp">
                                    <label>Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellidoPaterno" aria-describedby="apellidoPaternoHelp">
                                    <label>ApellidoMaterno</label>
                                    <input type="text" class="form-control" id="apellidoMaterno" aria-describedby="apellidoMaternoHelp">
                                    <label>Correo Institucional</label>
                                    <input type="email" class="form-control" id="correo" aria-describedby="correoHelp">
                                    <label>Celular</label>
                                    <input type="tel" class="form-control" id="celular" aria-describedby="celularHelp">
                                    <label>Oficina</label>
                                    <select id="oficina" class="select2" style="width: 100%;">
                                        <?= $listOficina ?>
                                    </select>
                                    <label>Cargo</label>
                                    <input type="text" class="form-control" id="cargo" aria-describedby="cargoHelp">
                                    <label for="imgGobernador">Subir foto</label>
                                    <div class="container-fluid mb-3 d-flex flex-column align-items-center">
                                        <img class="img-fluid" src="<?= $imgFoto ?>" style="width:35%; height: 200px">
                                        <p class="text-center mt-3">Para subir su foto, asegúrese de que tenga el mismo estilo que la imagen referencial.</p>
                                    </div>

                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="foto" onchange="
                                            if (this.files.length > 0) {
                                                document.querySelector('.custom-file-label').innerHTML = this.files[0].name
                                            } else {
                                                document.querySelector('.custom-file-label').innerHTML = 'Seleccione un archivo'
                                            }
                                        ">
                                        <label class="custom-file-label text-left" for="foto" data-browse="Elegir archivo">Elegir archivo</label>
                                    </div>
                                    <label>Cursos</label>
                                    <div class="container-fluid mb-3 d-flex flex-column align-items-center">
                                        <p class="text-center mt-3">Puede seleccionar uno o más cursos.</p>
                                    </div>
                                    <select id="cursos" class="select2" multiple="multiple" data-placeholder="Seleccione un curso" style="width: 100%;">
                                        <?= $listCursos ?>
                                    </select>
                                    <div class="mt-2 mb-2 d-flex justify-content-center">
                                        <button type="button" id="enviar" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="area" >
            <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
            </ul>
    </div >
</body>
<script src="<?= _RTADMINLTE3 . 'plugins/jquery/jquery.min.js' ?>"></script>
<script src="<?= _RTADMINLTE3 . 'plugins/bootstrap/js/bootstrap.bundle.min.js' ?>"></script>
<script src="<?= _RTADMINLTE3 . 'plugins/sweetalert2/sweetalert2.min.js' ?>"></script>
<script src="<?= _RTADMINLTE3 . 'plugins/select2/js/select2.full.min.js' ?>"></script>
<script src="<?= _RTADMINLTE3 . 'dist/js/adminlte.min.js' ?>"></script>
<script src="<?= _ROOT_ASSETS . 'js/registro.js' ?>"></script>

</html>