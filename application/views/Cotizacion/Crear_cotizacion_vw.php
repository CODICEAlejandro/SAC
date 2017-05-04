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
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Cotizacion/Crear_cotizacion_JS.js"></script>
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

				<form enctype="multipart/form-data" id="form-archivo-adjunto">
					<div class="form-group">
						<label for="archivo-adjunto">Archivo adjunto</label>
						<input type="file" name="archivo-adjunto" id="archivo-adjunto" class="form-control">
					</div>
				</form>

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

				<div class="form-group">
					<input type="submit" id="btn-guardar-cotizacion" class="form-control btn btn-default" value="Guardar cotización">
				</div>
			</div>
		</div>
	</div>

	<!-- Clone sections -->
	<div id="clone-sections" style="display: none;">
		
	<!-- Clone section alcance -->
	<div id="clone-section-alcance" class="clone-section-alcance">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-alcance" class="form-control">
				</div>
			</div>
			<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
				<div class="form-group">
					<label>Orden</label>
					<input type="text" id="orden-alcance" class="form-control" disabled>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
				<button class="btn btn-primary" id="btn-up-alcance">
					<span class="glyphicon glyphicon-chevron-up"></span>
				</button>
				<button class="btn btn-info" id="btn-down-alcance">
					<span class="glyphicon glyphicon-chevron-down"></span>
				</button>
			</div>
			<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-primary" id="btn-minus-alcance">-</button>
				</div>
			</div>
			<div class="col-xs-3 col-sm-1 col-md-1 col-lg-1" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-danger" id="btn-delete-alcance">x</button>
				</div>
			</div>

			<div id="minus-section" data-visible=1>

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Servicio (*)</label>
					<select class="form-control" id="id-servicio-alcance">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($servicio_alcance as $s){ ?>
						<option value="<?php echo $s->id; ?>"><?php echo $s->descripcion; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Clasificación (*)</label>
					<select class="form-control" id="id-clasificacion-alcance">
						<option value="-1">Seleccione una opción</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<button class="btn btn-warning form-control" id="btn-agregar-descripcion">Agregar descripción (*)</button>	
				</div>
				<!-- Sección de descriciones del alcance -->
				<div id="append-section-descripcion"></div>	
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Requerimientos (*)</label>
					<textarea class="form-control" rows="5" id="requerimientos-alcance"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Entregables (*)</label>
					<textarea class="form-control" rows="5" id="entregables-alcance"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Fecha de inicio del servicio (*)</label>
					<input type="text" id="fecha-inicio-servicio" class="form-control fecha-inicio-servicio">
					<input type="text" id="fecha-inicio-servicio-alt" class="form-control fecha-inicio-servicio-alt">
				</div>
			</div>

			<!-- Opcionales: si se seleccionó pago recurrente o pago fijo -->
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-recurrentes" id="bloque-pagos-recurrentes"
			style="width: 100%; border-bottom: 2px blue dotted; margin-bottom: 15px;">
				<!-- Pagos recurrentes -->
				<div class="form-group">
					<label>Periodicidad</label>
					<select id="id-periodicidad" class="form-control">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($periodicidad as $p){ ?>
						<option value="<?php echo $p->id ?>"><?php echo $p->clave; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Número de parcialidades</label>
					<input type="number" id="numero-parcialidades" class="form-control">
				</div>
				<div class="form-group">
					<label>Monto de la parcialidad</label>
					<input type="number" id="monto-parcialidad" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-fijos" id="bloque-pagos-fijos">
				<!-- Pagos fijos -->
				<div class="form-group">
					<label>Precio total</label>
					<input type="number" id="precio-total" class="form-control precio-total" value="0">
				</div>
				<div class="form-group">
					<label>Porcentaje de anticipo (De 0 a 100)</label>
					<input type="number" id="porcentaje-anticipo" class="form-control porcentaje-anticipo" value="0">
				</div>
				<div class="form-group">
					<label>Monto de anticipo</label>
					<input type="number" id="monto-anticipo" class="form-control monto-anticipo" value="0">
				</div>
				<div class="form-group">
					<button id="btn-agregar-parcialidad" class="btn btn-primary form-control btn-agregar-parcialidad">Agregar parcialidad</button>
				</div>
				<div id="append-section-parcialidad"></div>
			</div>

			</div>
		</div>
	</div>
	<!-- Fin de clone section alcance -->

	<!-- Clone section descripcion -->
	<div id="clone-section-descripcion" class="clone-section-descripcion">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-descripcion" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn-primary" id="btn-minus-descripcion">-</button>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn-danger" id="btn-delete-descripcion">x</button>
				</div>
			</div>

			<div id="minus-section" data-visible=1>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Descripción (*)</label>
					<textarea id="contenido-descripcion" class="form-control" rows="5"></textarea>
				</div>
			</div>

			</div>
		</div>
	</div>
	<!-- Fin de clone section descripcion -->

	<!-- Clone section parcialidad -->
	<div id="clone-section-parcialidad" class="clone-section-parcialidad">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-danger btn-delete-parcialidad" id="btn-delete-parcialidad">x</button>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Concepto</label>
					<input type="text" id="concepto-parcialidad" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Fecha</label>
					<input type="text" id="fecha-parcialidad" class="form-control fecha-parcialidad">
					<input type="text" id="fecha-parcialidad-alt" class="form-control fecha-parcialidad-alt">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Porcentaje</label>
					<input type="number" id="porcentaje-parcialidad" class="form-control porcentaje-parcialidad" value="0">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Monto</label>
					<input type="number" id="monto-parcialidad" class="form-control monto-parcialidad" value="0">
				</div>
			</div>
		</div>
	</div>
	<!-- Fin clone section parcialidad -->

	</div>
	<!-- Fin de clone sections -->
</body>
