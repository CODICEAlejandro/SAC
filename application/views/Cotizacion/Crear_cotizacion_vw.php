<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Cotizacions/Crear_cotizacion_JS.js"></script>
</head>
<body>
	<?=$menu ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				<!-- Sección de información general de la cortización -->

				<div class="form-group">
					<label>Cliente (*)</label>
					<select class="form-control" id="id-cliente">
						<option value="-1">Selecciona una opción</option>
						<?php foreach($clientes as $c){ ?>
						<option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Contacto (*)</label>
					<select class="form-control" id="id-contacto">
						<option value="-1">Selecciona una opción</option>
					</select>
				</div>
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-cotizacion" class="form-control">
				</div>
				<div class="form-group">
					<label>Forma de pago (*)</label>
					<select class="form-control" id="id-forma-de-pago">
						<option value="-1">Selecciona una opción</option>
						<?php foreach($forma_pago as $f){ ?>
						<option value="<?php echo $f->id; ?>"><?php echo $f->clave; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="archivo-adjunto">Archivo adjunto</label>
					<input type="file" id="archivo-adjunto" class="form-control">
				</div>

				<div class="form-group">
					<label for="objetivo-cotizacion">Objetivo (*)</label>
					<textarea class="form-control" id="objetivo-cotizacion" rows="5"></textarea>
				</div>
				<div class="form-group">
					<label for="introduccion-cotizacion">Introducción (*)</label>
					<textarea class="form-control" id="introduccion-cotizacion" rows="5"></textarea>
				</div>

				<div class="form-group">
					<label for="nota-cotizacion">Notas</label>
					<textarea class="form-control" id="nota-cotizacion" rows="5"></textarea>
				</div>

				<!-- Sección de alcances -->

				<div class="form-group">
					<button class="form-control btn btn-info" id="btn-agregar-alcance">Agregar alcance</button>
				</div>
				<div id="append-section-alcance"></div>

				<div id="clone-section-alcance" class="clone-section-alcance">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
								<label>Título (*)</label>
								<input type="text" id="titulo-alcance" class="form-control">
							</div>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-1 col-lg-1">
							<div class="form-group">
								<label>Orden</label>
								<input type="text" id="orden-alcance" class="form-control">
							</div>
						</div>
						<div class="col-xs-3 col-sm-2 col-md-3 col-lg-3" style="margin-top: 25px;">
							<div class="form-group">
								<button class="form-control btn btn-primary" id="btn-minus-alcance">-</button>
							</div>
						</div>
						<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
							<div class="form-group">
								<button class="form-control btn btn-danger" id="btn-delete-alcance">x</button>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Servicio (*)</label>
								<select class="form-control" id="id-servicio-alcance">
									<option value="-1">Seleccione una opción</option>
									<?php foreach($servicio_alcance as $s){ ?>
									<option value="<?php echo $s->id; ?>"><?php echo $s->clave; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Clone sections -->
	<div id="clone-sections" style="">
		
	<!-- Clone section alcance -->

	</div>
	<!-- Fin de clone sections -->
</body>
