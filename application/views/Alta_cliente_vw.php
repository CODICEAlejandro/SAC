<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<style type="text/css">
		.dotted-bottom {
			border-bottom: 2px dotted gray;
			padding-top: 15px;
			padding-bottom: 15px;
		}

		.sectionTitle {
			padding-bottom: 15px;
		}
	</style>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/utilitiesJS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_DireccionFiscal_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_DireccionOperativa_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Banco_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Commons_JS.js"></script>

</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="cCliente">Cliente en edición</label>
					<select id="cCliente" class="form-control">
						<option value="-1">Ninguno</option>
						<?php foreach($clientes as $cliente){ ?>
							<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row" id="rowNuevoCliente">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					action = "Alta_cliente_ctrl/nuevoCliente"
					method = "post"
					id = "form_alta"
				>
					<div class="form-group">
						<label for="nombre">Nombre comercial</label>
						<input type="text" name="nombre" placeholder="Nombre comercial" class="form-control" required>
					</div>
					<div class="form-group">
						<input type="submit" value="Crear" class="form-control btn btn-primary">
					</div>
				</form>
			</div>
		</div>


		<!-- Inicia la sección de edición -->
		<span id="rowEdicionCliente">

			<div class="row dotted-bottom">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<form
						action = "Alta_cliente_ctrl/editarCliente"
						method = "post"
						id = "form-edita"
					>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
									<label for="nombre">Nombre comercial</label>
									<input type="text" name="nombre" id="nombre" placeholder="Nombre comercial" class="form-control" required>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
									<label id="labelEstado">Estado: Activo</label>
									<button style="width: 100%;" id="btn-estado" class="btn btn-danger">Inactivar</button>
								</div>
							</div>
						</div>
						
						<input type="hidden" name="id" value="-1">
						<input type="hidden" name="estadoActivo" value="1">

						<div class="form-group">
							<input type="submit" value="Actualizar" class="form-control btn btn-info">
						</div>
					</form>
				</div>
			</div>

			<div class="row" style="border-bottom: 2px dotted gray;">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<nav class="navbar navbar-default" style="background: none; box-shadow: none; border-color: transparent; margin: 0px;">
					  <div class="container-fluid">
					    <ul class="nav navbar-nav" id="main-menu-cliente">
					      <li><a href="#" id="btn-bancos-direcciones">Bancos y direcciones</a></li>
					      <li><a href="#" id="btn-agenda">Agenda</a></li>
					      <li><a href="#" id="btn-perfiles">Perfiles</a></li>
					      <li><a href="#" id="btn-servicios">Servicios</a></li>
					    </ul>
					  </div>
					</nav>
				</div>
			</div>

			<section id="main-info-financiera" style="display: none;">
			<!-- Inicia sección de información financiera -->
			<?=$form_direccion_fiscal; ?>

			</section>

			<!-- Fin de la información financiera -->

			<!-- Inicia agenda -->
			<section id="main-agenda" style="display: none;">
			<?=$form_agenda; ?>
			</section>
			<!-- Fin de la agenda -->

			<!-- Inician los Perfiles -->

			<!-- Fin de los perfiles -->

			<!-- Inician los servicios -->

			<!-- Fin de los servicios -->

		</span>
		<!-- FIN ROW CLIENTE EDICIÓN -->

	</div>
</body>
</html>