<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel='icon' type='image/x-icon' href='<?php echo _ROOT_ASSETS . 'images/gorel_favicon.png' ?>'>
	<link href="<?php echo _ROOT_ASSETS_ADMIN . 'css/bootstrap.min.css' ?>" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?php echo _ROOT_ASSETS_ADMIN . 'css/app.css' ?>" rel="stylesheet">
	<link href="<?php echo _ROOT_ASSETS_ADMIN . 'css/icons.css' ?>" rel="stylesheet">
	<link href="<?php echo _ROOT_ASSETS_ADMIN . 'css/styles.css' ?>" rel="stylesheet">
	<title>Administrador Egovt</title>
</head>

<body>
	<div class="wrapper">
		<div class="authentication-header">
			<img src="<?php echo _ROOT_ASSETS . 'images/banners/portada1.jpg' ?>" class="img-fluid oscurecer-imagen">
		</div>
		<div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
					<div class="col mx-auto">

						<div class="card mt-5">
							<div class="card-body">
								<div class="p-4 rounded">
									<div class="my-4 text-center ">
										<img src="<?php echo _ROOT_ASSETS_ADMIN . 'img/vista.gif' ?>" width="110" alt="" />
									</div>
									<div class="text-center">
										<h3 class="">Iniciar Sesión</h3>
									</div>
									<div class="form-body">
										<form class="row g-3" id="myForm">
											<div class="col-12">
												<label for="inputFirstName" class="form-label">Nombre de usuario</label>
												<input type="text" class="form-control" id="inputFirstName" placeholder="Ingrese nombre de usuario...">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Contraseña</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Ingrese contraseña...">
												</div>
											</div>
											<div class="col-12">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
													<label class="form-check-label" for="flexSwitchCheckChecked">Acepta nuestros terminos y condiciones</label>
												</div>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary" id="submitButton"><i class='bx bx-user'></i>Ingresar</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ohsnap">
		<div id="ohsnap"></div>
	</div>
</body>
<script src="<?php echo _ROOT_ASSETS . 'js/ohsnap/ohsnap.js' ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="<?php echo _ROOT_ASSETS_ADMIN . 'js/bootstrap.bundle.min.js' ?>"></script>
<script src="<?php echo _ROOT_ASSETS_ADMIN . 'js/login.js' ?>"></script>
</html>