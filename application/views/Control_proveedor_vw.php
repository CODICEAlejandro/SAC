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

	<script type="text/javascript">
		var pageController = "Control_proveedor_ctrl";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/utilitiesJS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_DireccionFiscal_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_DireccionOperativa_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Banco_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_agenda_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Perfil_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Servicio_JS.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_cliente_Commons_JS.js"></script>

</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="cCliente">Proveedor en edición</label>
					<select id="cCliente" class="form-control">
						<option value="-1">Ninguno</option>
						<?php foreach($proveedores as $proveedor){ ?>
							<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
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

			<div class="row">
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

			<div class="row" style="background: rgb(238, 238, 238) none repeat scroll 0% 0%; border: 2px solid gray;">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<nav class="navbar navbar-default" style="background: none; box-shadow: none; border-color: transparent; margin: 0px;">
					  <div class="container-fluid">
					    <ul class="nav navbar-nav" id="main-menu-cliente">
					      <li>
					      	<a href="#" id="btn-direcciones-fiscales">
					      		<span class="glyphicon glyphicon-home"></span> Direcciones fiscales
					      	</a>
					      </li>
					      <li>
					      	<a href="#" id="btn-direcciones-operativas">
					      		<span class="glyphicon glyphicon-home"></span> Direcciones operativas
					      	</a>
					      </li>
					      <li>
					      	<a href="#" id="btn-bancos">
					      		<span class="glyphicon glyphicon-usd"></span> Bancos
					      	</a>
					      </li>
					      <li>
					      	<a href="#" id="btn-agenda">
					      		<span class="glyphicon glyphicon-phone-alt"></span> Agenda
					      	</a>
					      </li>
					      <li>
					      	<a href="#" id="btn-perfiles">
					      		<span class="glyphicon glyphicon-user"></span> Perfiles
					      	</a>
					      </li>
					      <li>
					      	<a href="#" id="btn-servicios">
					      		<span class="glyphicon glyphicon-th-list"></span> Servicios
					      	</a>
					      </li>
					    </ul>
					  </div>
					</nav>
				</div>
			</div>

			<!-- Inicia sección de información financiera -->
			<?=$form_seccion1; ?>
			<?=$form_agenda; ?>

		</span>
		<!-- FIN ROW CLIENTE EDICIÓN -->

	</div>
</body>
</html>