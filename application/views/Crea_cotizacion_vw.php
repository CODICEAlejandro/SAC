<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Crea_cotizacion_JS.js"></script>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<title>JOBS</title>
</head>
<body>
	<?=$menu; ?>

	<div class="container">
		<div class="row" style="margin-top: 15px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					id = "create-form"
					action = "<?php echo base_url(); ?>index.php/Crea_cotizacion_ctrl/guardarNuevaCotizacion"
					method = "post"
				>
					<div class="form-group">
						<label>Folio</label>
						<input type="text" name="folio" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Cliente</label>
						<select id="cliente" class="form-control">
							<option value="-1">Seleccione un cliente</option>
							<?php foreach($clientes as $cliente){ ?>
							<option value="<?php echo $cliente->id?>">
								<?php echo $cliente->nombre; ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Razon social asociada</label>
						<select name="idRazonSocial" class="form-control">
							<option value="-1">Seleccione una razón social</option>
							<?php foreach($direccionesFiscales as $direccion){ ?>
							<option value="<?php echo $direccion->id?>">
								<?php echo $direccion->razonSocial; ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Responsable</label>
						<select name="idResponsable" class="form-control">
							<option value="-1">Seleccione una opción</option>
							<?php foreach($usuarios as $usuario){ ?>
							<option value="<?php echo $usuario->id?>">
								<?php echo $usuario->nombre; ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Cerrador</label>
						<select name="idCerrador" class="form-control">
							<option value="-1">Seleccione una opción</option>
							<?php foreach($usuarios as $usuario){ ?>
							<option value="<?php echo $usuario->id?>">
								<?php echo $usuario->nombre; ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="form-group">
									<label>Junta de arranque</label>
									<input type="text" name="fechaJuntaArranque" class="datepicker form-control">
									<input type="text" name="fechaJuntaArranqueAlt" class="datepicker form-control">
								</div>
								<div class="form-group">
									<label>Fecha de inicio de proyecto</label>
									<input type="text" name="inicioProyecto" class="datepicker form-control">
									<input type="text" name="inicioProyectoAlt" class="datepicker form-control">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="form-group">
									<label>Notas</label>
									<textarea class="form-control" rows="5" name="nota"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="form-group">
									<label>Fecha de finalización de proyecto</label>
									<input type="text" class="datepicker form-control" name="finProyecto">
									<input type="text" class="datepicker form-control" name="finProyectoAlt">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="form-group">
									<label>Fecha de venta de proyecto</label>
									<input type="text" class="datepicker form-control" name="fechaVenta">
									<input type="text" class="datepicker form-control" name="fechaVentaAlt">
								</div>
							</div>
						</div>
					</div>

					<div class="form-group" id="containerConceptos">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h4>Conceptos</h4>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<button id="btn-agrega-concepto" class="form-control btn btn-warning">Agregar</button>
							</div>
						</div>
					</div>

					<div class="form-group">
						<input type="submit" value="Guardar" class="btn btn-default form-control">
					</div>
				</form>
			</div>			
		</div>
	</div>

	<div id="rowConcepto" class="row rowConcepto" style="border-top: 1px solid #aaa; margin-top: 10px; padding-top: 15px; display: none;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-group" style="margin-bottom: 50px;">
				<button class="btn btn-danger" id="btn-borrar-concepto" style="float: right; margin-left: 15px;">x</button>
				<h4 id="numeroConcepto"  style="float: right;"></h4>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<label>Valor unitario</label>
				<input type="text" name="valorUnitarioConcepto[]" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<label>Importe</label>
				<input type="text" name="importeConcepto[]" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<label>Cantidad</label>
				<input type="number" name="cantidadConcepto[]" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
			<div class="form-group">
				<label>Unidad de medida</label>
				<input type="text" name="unidadMedidaConcepto[]" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<label>Tipo</label>
				<select name="tipoConcepto[]" class="form-control">
					<option value="-1">Seleccione una opción</option>
					<?php foreach($tiposConcepto as $tipo){ ?>
					<option value="<?php echo $tipo->id; ?>"><?php echo $tipo->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
			<div class="form-group" style="display: table; margin: auto;">
				<label>Recurrencia</label>
				<br>
				<input id="recYes" value="1" type="checkbox" name="recurrenciaConcepto[]" style="margin: 10px auto auto; display: table;">
				<input id="recNo" value="0" type="checkbox" name="recurrenciaConcepto[]" style="display: none;" checked>
			</div>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">
			<div class="form-group" id="periodo">
				<label>Periodo</label>
				<select name="periodo[]" class="form-control">
					<option value="-1">Seleccione una opción</option>
					<?php foreach($periodoRecurrencia as $periodo){ ?>
					<option value="<?php echo $periodo->id; ?>"><?php echo $periodo->descripcion; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-group">
				<label>Descripcion</label>
				<input type="text" name="descripcionConcepto[]" class="form-control">
			</div>
			<div class="form-group">
				<label>Referencia</label>
				<input type="text" name="referenciaConcepto[]" class="form-control">
			</div>
			<div class="form-group">
				<label>Nota</label>
				<input type="text" name="notaConcepto[]" class="form-control">
			</div>
		</div>
	</div>
</body>
</html>