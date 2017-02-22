<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/Class/ConceptoCotizacion.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Alta_conceptos_cotizacion.js"></script>
	<title>JOBS</title>

	<style>
		.btn-destroy-fecha {
			cursor: pointer;
		}

		.btn-destroy-concepto {
			cursor: pointer;
		}
	</style>

</head>
<body>
	<?=$menu; ?>

	<form 
		id="main-form"
		method="post"
		action="<?php echo base_url().'index.php/Alta_conceptos_cotizacion_ctrl/guardarCotizacion'; ?>"
	>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Cliente</label>
					<select class="form-control" id="id-cliente">
					<option value="-1">Ninguno</option>
					<?php foreach($clientes as $cliente){ ?>
					<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
					<?php } ?>
					</select>
				</div>

				<div class="form-group">
					<label>Cotización</label>
					<input type="text" name="folio-cotizacion" id="folio-cotizacion" placeholder="Ingrese el folio de la cotización" class="form-control">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Notas</label>
					<textarea name="nota-cotizacion" id="nota-cotizacion" class="form-control" rows="5"></textarea>
				</div>	
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<label>Fecha de la junta de arranque</label>
				<input type="text" class="datepicker form-control" id="fecha_junta_arranque">
				<input type="hidden" name="alt_fecha_junta_arranque" id="alt_fecha_junta_arranque">
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<label>Fecha de venta</label>
				<input type="text" class="datepicker form-control" id="fecha_venta">
				<input type="hidden" name="alt_fecha_venta" id="alt_fecha_venta">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<label>Fecha de inicio del proyecto</label>
				<input type="text" class="datepicker form-control" id="fecha_inicio_proyecto">
				<input type="hidden" name="alt_fecha_inicio_proyecto" id="alt_fecha_inicio_proyecto">
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<label>Fecha de fin del proyecto</label>
				<input type="text" class="datepicker form-control" id="fecha_fin_proyecto">
				<input type="text" name="alt_fecha_fin_proyecto" id="alt_fecha_fin_proyecto">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>Cerrador</label>
				<select class="form-control" id="id_cerrador" name="id_cerrador">
				<option value="-1">Seleccione una opción</option>
				<?php foreach($usuario as $u){ ?>
				<option value="<?php echo $u->id; ?>"><?php echo $u->nombre; ?></option>
				<?php } ?>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>Account Manager</label>
				<select class="form-control" id="id_account_manager" name="id_account_manager">
				<option value="-1">Seleccione una opción</option>
				<?php foreach($account as $a){ ?>
				<option value="<?php echo $a->id; ?>"><?php echo $a->nombre; ?></option>
				<?php } ?>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>Título de referencia para la cotización</label>
				<input type="text" name="titulo_cot" id="titulo_cot" class="form-control">
			</div>
		</div>

		<div style="width: 100%; border-bottom: 2px gray solid; margin-top: 15px; margin-bottom: 15px;"></div>

		<div class="row" id="append-section-concepto"></div>

		<div class="row" style="margin-top: 15px; margin-bottom: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<button class="btn btn-primary form-control" id="btn-add-concepto" style="margin-bottom: 15px;">Agregar concepto</button>
			</div>
		</div>

		<div class="row" style="margin-top: 15px; margin-bottom: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<button class="btn btn-success form-control" id="btn-save-cotizacion" style="margin-bottom: 15px;">Guardar y finalizar</button>
			</div>
		</div>

	</div>

	</form>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clone-section-concepto" id="clone-section-concepto" style="display: none; padding: 15px; border: 2px #aaa dotted; ">
		<div style="margin-top: 15px; margin-bottom: 15px; padding-bottom: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="btn-destroy-concepto" class="btn-destroy-concepto" style="float:right;">
					<span class="glyphicon glyphicon-remove"></span>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>Descripción</label>
				<input type="text" name="descripcion-concepto[]" id="descripcion-concepto" class="form-control">							
			</div>						
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<label>Servicio</label>
				<select class="form-control servicio-concepto" name="servicio-concepto[]" id="servicio-concepto">
					<option>Seleccione una opción</option>
					<?php foreach($tipoConcepto as $t){ ?>
					<option value="<?php echo $t->id; ?>"><?php echo $t->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>						
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<label>Clasificación</label>
				<select class="form-control" name="clasificacion-concepto[]" id="clasificacion-concepto">
					<option>Seleccione una opción</option>
				</select>
			</div>						
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<label>Referencia</label>
				<input type="text" name="referencia-concepto[]" id="referencia-concepto" class="form-control">
			</div>						
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label>Nota</label>
				<input type="text" name="nota-concepto[]" id="nota-concepto" class="form-control">
			</div>						
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<label>IVA</label>
				<select id="iva" class="form-control iva">
					<option value="16">16%</option>
					<option value="0">0%</option>
				</select>
			</div>					
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<label>Importe</label>
				<input type="text" name="importe-concepto[]" id="importe-concepto" class="form-control" value="0" readonly="true">
			</div>						
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<label>Total</label>
				<input type="text" name="total-concepto[]" id="total-concepto" class="form-control" value="0" readonly="true">
			</div>

			<div class="row" id="append-section-fecha-factura"></div>

			<div class="row" style="margin-top: 15px;">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<button class="btn btn-default form-control" id="btn-add-fecha-factura">Agregar fecha de facturación</button>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clone-section-fecha-factura" id="clone-section-fecha-factura" style="display: none; border-top: 2px orange dotted; border-bottom: 2px orange dotted; margin: 15px 0px; padding: 15px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div id="btn-destroy-fecha" class="btn-destroy-fecha" style="float:right; color:orange;">
				<span class="glyphicon glyphicon-remove"></span>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label>Importe</label>
			<input type="text" name="importe-fecha-factura[]" id="importe-fecha-factura" class="importe-fecha-factura form-control">							
		</div>						
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label>Referencia</label>
			<input type="text" name="referencia-fecha-factura[]" id="referencia-fecha-factura" class="form-control">							
		</div>						
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label>Nota</label>
			<input type="text" name="nota-fecha-factura[]" id="nota-fecha-factura" class="form-control">							
		</div>						
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label>Fecha</label>
			<input type="text" name="fecha-factura[]" id="fecha-factura" class="form-control datepicker">							
			<input type="text" name="fecha-factura-alt[]" id="fecha-factura-alt" class="form-control">							
		</div>	
	</div>
</body>
