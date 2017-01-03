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
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Facturacion/Control_facturacion_JS.js"></script>
</head>
<body>
	<?=$menu ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form id="main-form" class="form">
					<div class="form-group">
						<label>Cliente</label>
						<select name="cliente" id="cliente" class="form-control">
							<option value="-1">Seleccione una opción</option>
							<?php foreach($clientes as $c){ ?>
							<option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Account manager a cargo</label>
						<select name="account" id="account" class="form-control">
							<option>Seleccione una opción</option>
							<?php foreach($accounts as $c){ ?>
							<option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
							<?php } ?>
						</select>
					</div>

					<span id="append-concepto-section"></span>

					<div class="form-group">
						<button class="form-control btn btn-info" id="btn-add-concepto">Agregar concepto</button>
					</div>

					<div class="form-group">
						<input type="submit" 
							name="submit-main-form" 
							id="submit-main-form" 
							value="Terminar"
							class="form-control btn btn-primary"
						>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="clone-concepto-section" class="concepto-section" style="display: none; border: 3px dotted #aaa; padding: 10px; margin-bottom: 8px;">
		<div class="form-group">
			<label>Concepto</label>
			<input type="text" name="concepto" id="concepto" class="form-control">
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label>Monto</label>
					<input type="text" name="monto" id="monto" class="form-control">
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label>Fecha</label>
					<input type="text" name="fecha" id="fecha" class="form-control datepicker">
					<input type="text" name="fecha-alt" id="fecha-alt">
				</div>
			</div>
		</div>
	</div>
</body>